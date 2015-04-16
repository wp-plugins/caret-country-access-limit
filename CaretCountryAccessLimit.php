<?php
/*
Plugin Name: Caret Country Access Limit
Plugin URI: http://www.ca-ret.co.jp/WordPress/
Description: APNICなどの機関で公開されているIPアドレスの一覧を自動取得し、.htaccessによるアクセス制限を国単位で行います。
Author: Caret Inc.
Version: 1.0.1
Author URI: http://www.ca-ret.co.jp/
License: GPL2
*/

/*	@2015 caret
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once(dirname(__FILE__)."/core.php");

add_action('admin_menu', 'countryLimitSetupLoad');
add_filter('plugin_action_links', 'countryLimitAddLink', 10, 2);
add_action('admin_head', 'countryLimitAddJs', 100);
add_action(COUNTRY_LIMIT_CRON, COUNTRY_LIMIT_CRON);
register_deactivation_hook(__FILE__, 'countryLimitDisable');
register_activation_hook(__FILE__, 'countryLimitEnable');

function countryLimitSetupLoad()
{
	if ((file_exists(COUNTRY_LIMIT_CONFIG_MAIN) || file_exists(COUNTRY_LIMIT_CONFIG_DEF)) && file_exists(COUNTRY_LIMIT_SETUP_FILE)) {
		$country_limit_obj = & new countryLimitSetupClass();
		add_submenu_page('options-general.php', 'CaretCountryAccessLimitの設定', 'CaretCountryAccessLimitの設定', 8, 'CaretCountryAccessLimit', array($country_limit_obj, 'start'));
	}
}

function countryLimitAddLink($links, $file)
{
	if ($file === plugin_basename(__FILE__)) {
		$settings_link = '<a href="options-general.php?page=CaretCountryAccessLimit">設定</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

function countryLimitAddJs()
{
	if ($_GET['page'] === "CaretCountryAccessLimit") {
		wp_enqueue_script('CaretCountryAccessLimitJs', plugin_dir_url(__FILE__) . 'setup.js');
	}
}

class countryLimitSetupClass
{
	var $error = 0;
	var $base_obj;

	function start()
	{
		include_once(COUNTRY_LIMIT_BASE_SCRIPT);

		$this->base_obj = & new BaseClass();

		$is_update = false;
		if (isset($_POST['country-limit_update'])) {
			$this->setupSave();
			$is_update = true;
		}

		if (!$this->error) {
			$this->setupSetDefine();
			if ($is_update) countryLimitLoad(false);
		}

		if ($this->base_obj->isExists(COUNTRY_LIMIT_CIDR_LOCK)) {
			$_POST['country-limit_warning'] = 1;
		}

		include_once(COUNTRY_LIMIT_SETUP_FILE);
	}

	function setupSave()
	{
		$config_tmp = dirname(__FILE__)."/_config.php";

		$this->setupValid();

		if (!$this->error) {
			$line = "<?php".PHP_EOL;

			foreach ((array)$_POST as $key=>$val) {
				if (preg_match("/^COUNTRY_LIMIT_(.+)$/", $key)) {
					$val[0] = sanitize_text_field($val[0]);
					$val[0] = str_replace(array("'", ","), array("\\'", "\\,"), $val[0]);

					switch (true) {
						case preg_match("/^COUNTRY_LIMIT_STATUS$/", $key):
						case preg_match("/^COUNTRY_LIMIT_TYPE$/", $key):
						case preg_match("/^COUNTRY_LIMIT_RENEW$/", $key):
							$line .= "define('{$key}', {$val[0]});".PHP_EOL;
							break;
						case preg_match("/^COUNTRY_LIMIT_MTHOD$/", $key):
							$line .= "define('COUNTRY_LIMIT_MTHOD', '".($val[0] == 0 ? 'POST' : ($val[0] == 1 ? 'GET' : 'POST,GET'))."');".PHP_EOL;
							break;
						case preg_match("/^COUNTRY_LIMIT_LIST$/", $key):
						case preg_match("/^COUNTRY_LIMIT_EXTRA$/", $key):
							$line .= "define('{$key}', '" . $this->base_obj->setupSreplace($val[0]) . "');".PHP_EOL;
							break;
						default:
							break;
					}
				}
			}

			$line .= "?>";

			$fp = fopen($config_tmp,"w")
					or wp_die("Can't open ${config_tmp}");

			rewind($fp);
			fwrite($fp, $line, strlen($line));
			fclose($fp);

			rename($config_tmp, COUNTRY_LIMIT_CONFIG_MAIN)
				or wp_die("Can't rename ${config_tmp} to ".COUNTRY_LIMIT_CONFIG_MAIN);

			$_POST['country-limit_result'] = "保存しました";
		} else {
			$_POST['country-limit_result'] = "設定に誤りがあります";
		}
	}

	function setupValid()
	{
		if (!$this->base_obj->isInt($_POST['COUNTRY_LIMIT_STATUS'][0]) || $_POST['COUNTRY_LIMIT_STATUS'][0] < 0 || $_POST['COUNTRY_LIMIT_STATUS'][0] > 1) {
			$_POST['COUNTRY_LIMIT_STATUS']['error'] = "選択してください";
			$this->error = 1;
		}

		if (!$this->base_obj->isInt($_POST['COUNTRY_LIMIT_MTHOD'][0]) || $_POST['COUNTRY_LIMIT_MTHOD'][0] < 0 || $_POST['COUNTRY_LIMIT_MTHOD'][0] > 2) {
			$_POST['COUNTRY_LIMIT_MTHOD']['error'] = "選択してください";
			$this->error = 1;
		}

		if (!$this->base_obj->isInt($_POST['COUNTRY_LIMIT_TYPE'][0]) || $_POST['COUNTRY_LIMIT_TYPE'][0] < 0 || $_POST['COUNTRY_LIMIT_TYPE'][0] > 1) {
			$_POST['COUNTRY_LIMIT_TYPE']['error'] = "選択してください";
			$this->error = 1;
		}

		if (!$this->base_obj->isNotNull($_POST['COUNTRY_LIMIT_LIST'][0])) {
			$_POST['COUNTRY_LIMIT_LIST']['error'] = "値を入力してください";
			$this->error = 1;
		} else {
			foreach (preg_split("/[\s]+/", $_POST['COUNTRY_LIMIT_LIST'][0]) as $val) {
				if (!$this->base_obj->isNotNull($val)) continue;
				if (!preg_match("/^[a-z]{2}$/i", $val)) {
					$_POST['COUNTRY_LIMIT_LIST']['error'] = "値を正しく入力してください";
					$this->error = 1;
					break;
				}
			}
		}

		$expr1 = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
		$expr2 = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/([0-9]|[1-2][0-9]|3[0-2]))$/';

		foreach ((array)preg_split("/[\s]+/", $_POST['COUNTRY_LIMIT_EXTRA'][0]) as $val) {
			if (!$this->base_obj->isNotNull($val)) continue;
			if (!preg_match($expr1, $val) && !preg_match($expr2, $val)) {
				$_POST['COUNTRY_LIMIT_EXTRA']['error'] = "値を正しく入力してください";
				$this->error = 1;
				break;
			}
		}

		if (!$this->base_obj->isInt($_POST['COUNTRY_LIMIT_RENEW'][0]) || $_POST['COUNTRY_LIMIT_RENEW'][0] < 0 || $_POST['COUNTRY_LIMIT_RENEW'][0] > 30) {
			$_POST['COUNTRY_LIMIT_RENEW']['error'] = "選択してください";
			$this->error = 1;
		}
	}

	function setupSetDefine()
	{
		include_once(file_exists(COUNTRY_LIMIT_CONFIG_MAIN) ? COUNTRY_LIMIT_CONFIG_MAIN : COUNTRY_LIMIT_CONFIG_DEF);

		$_POST['COUNTRY_LIMIT_STATUS'][0] = COUNTRY_LIMIT_STATUS;
		$_POST['COUNTRY_LIMIT_MTHOD'][0] = (COUNTRY_LIMIT_MTHOD === 'POST' ? 0 : (COUNTRY_LIMIT_MTHOD === 'GET' ? 1 : 2));
		$_POST['COUNTRY_LIMIT_TYPE'][0] = COUNTRY_LIMIT_TYPE;
		$_POST['COUNTRY_LIMIT_LIST'][0] = $this->base_obj->setupReplace(COUNTRY_LIMIT_LIST, array(","), array("\r"));
		$_POST['COUNTRY_LIMIT_EXTRA'][0] = $this->base_obj->setupReplace(COUNTRY_LIMIT_EXTRA, array(","), array("\r"));
		$_POST['COUNTRY_LIMIT_RENEW'][0] = COUNTRY_LIMIT_RENEW;
	}
}

function countryLimitDisable()
{
	include_once(file_exists(COUNTRY_LIMIT_CONFIG_MAIN) ? COUNTRY_LIMIT_CONFIG_MAIN : COUNTRY_LIMIT_CONFIG_DEF);

	$country_limit_obj = & new countryLimitClass();
	$country_limit_obj->disable(true);
}

function countryLimitEnable()
{
	countryLimitLoad(false);
}

function countryLimitLoad($force_update = true)
{
	include_once(file_exists(COUNTRY_LIMIT_CONFIG_MAIN) ? COUNTRY_LIMIT_CONFIG_MAIN : COUNTRY_LIMIT_CONFIG_DEF);

	$country_limit_obj = & new countryLimitClass();

	if (COUNTRY_LIMIT_STATUS == 1) {
		if (COUNTRY_LIMIT_RENEW > 0) {
			$country_limit_obj->enable($force_update, true);
		} else {
			$country_limit_obj->enable($force_update, false);
		}
	} else {
		$country_limit_obj->disable(true);
	}
}

class countryLimitClass
{
	var $base_obj;

	function __construct()
	{
		include_once(COUNTRY_LIMIT_BASE_SCRIPT);

		$this->base_obj = & new BaseClass();
	}

	function enable($force_update = true, $auto_update = false)
	{
		if (wp_next_scheduled(COUNTRY_LIMIT_CRON)) $this->disable(false);

		if ($auto_update) wp_schedule_single_event(time() + (86400*COUNTRY_LIMIT_RENEW), COUNTRY_LIMIT_CRON);

		if ($force_update || !$this->base_obj->isExists(COUNTRY_LIMIT_CIDR_LIST)) {
			$this->getList(1);
		} else {
			$this->getList(0);
		}
	}

	function disable($repair_file = false)
	{
		wp_clear_scheduled_hook(COUNTRY_LIMIT_CRON);
		if ($repair_file) $this->repair();
	}

	function repair()
	{
		if ($this->base_obj->isExists(COUNTRY_LIMIT_HTACCESS_ORIG)) {
			if (!$this->base_obj->fileRename(COUNTRY_LIMIT_HTACCESS_ORIG, COUNTRY_LIMIT_HTACCESS_FILE)) {
				wp_die("Can't rename ".COUNTRY_LIMIT_HTACCESS_ORIG." to ".COUNTRY_LIMIT_HTACCESS_FILE);
			}
		}
	}

	function getList($renew_only = 0)
	{
		@chmod(COUNTRY_LIMIT_BATCH_SCRIPT, 0755);
		@exec("nohup php -c '' '".COUNTRY_LIMIT_BATCH_SCRIPT."' '".$renew_only."' > /dev/null &");
	}
}

?>