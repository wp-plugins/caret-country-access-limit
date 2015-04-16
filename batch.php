<?php

	include_once(dirname(__FILE__)."/core.php");
	include_once(file_exists(COUNTRY_LIMIT_CONFIG_MAIN) ? COUNTRY_LIMIT_CONFIG_MAIN : COUNTRY_LIMIT_CONFIG_DEF);
	include_once(COUNTRY_LIMIT_BASE_SCRIPT);

	$base_obj = & new BaseClass();

	if ($argv[1] != 0 && $argv[1] != 1) die();

	if ($argv[1] == 1) {
		$fp = fopen(COUNTRY_LIMIT_CIDR_LOCK, "w") or die("Can't open ".COUNTRY_LIMIT_CIDR_LOCK);

		$mask_bits = array(
			'256'=>'24','512'=>'23','1024'=>'22','2048'=>'21','4096'=>'20',
			'8192'=>'19','16384'=>'18','32768'=>'17','65536'=>'16','131072'=>'15',
			'262144'=>'14','524288'=>'13','1048576'=>'12','2097152'=>'11','4194304'=>'10',
			'8388608'=>'9','16777216'=>'8','33554432'=>'7','67108864'=>'6','134217728'=>'5',
			'268435456'=>'4','536870912'=>'3','1073741824'=>'2','2147483648'=>'1'
		);

		$context = stream_context_create(array(
			'http' => array(
				'method' => 'GET',
				'timeout' => 30,
			)
		));

		$cidr_line = null;

		foreach (array(COUNTRY_LIMIT_ARIN_FTP, COUNTRY_LIMIT_RIPENCC_FTP, COUNTRY_LIMIT_APNIC_FTP, COUNTRY_LIMIT_LACNIC_FTP, COUNTRY_LIMIT_AFRINIC_FTP) as $val) {
			$data = @file($val, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES, $context);

			foreach ($data as $val2) {
				$expr1 = '/^[^\|]+\|([a-z]{2})\|ipv4\|([\d\.]+)\|(\d+)\|\d+\|.+/i';
				$expr2 = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\/\d+$/';

				if (@preg_match($expr1, $val2, $match)) {
					if (!empty($match[1]) && !empty($match[2]) && !empty($match[3]) && !empty($mask_bits[$match[3]])) {
						$cidr = $match[2] . '/' . $mask_bits[$match[3]];
						if (@preg_match($expr2, $cidr)) $cidr_line .= "{$match[1]}\t{$cidr}".PHP_EOL;
					}
				}
			}

			unset($data);
		}

		rewind($fp);
		fwrite($fp, $cidr_line, strlen($cidr_line));
		fclose($fp);

		if (!$base_obj->fileRename(COUNTRY_LIMIT_CIDR_LOCK, COUNTRY_LIMIT_CIDR_LIST)) {
			@unlink(COUNTRY_LIMIT_CIDR_LOCK);
			die("Can't rename ".COUNTRY_LIMIT_CIDR_LOCK." to ".COUNTRY_LIMIT_CIDR_LIST);
		}

		@chmod(COUNTRY_LIMIT_CIDR_LIST, 0666);
	}

	$cidr_list = array();

	$fp = fopen(COUNTRY_LIMIT_CIDR_LIST, "r")
			or die("Can't open ".COUNTRY_LIMIT_CIDR_LIST);

	while ($line = fgets($fp)) {
		$line = trim($line);
		list($code, $cidr) = $base_obj->strToArray($line, "\t");

		foreach ($base_obj->strToArray(COUNTRY_LIMIT_LIST) as $val) {
			if ($code === $val) {
				$cidr_list[] = $cidr;
			}
		}
	}

	fclose($fp);

	if (count($cidr_list) > 0) {
		$new_line = null;

		$fp = fopen(COUNTRY_LIMIT_HTACCESS_TEMP, "w")
			or die("Can't open ".COUNTRY_LIMIT_HTACCESS_TEMP);

		if ($base_obj->isExists(COUNTRY_LIMIT_HTACCESS_FILE)) {
			$fp2 = fopen(COUNTRY_LIMIT_HTACCESS_FILE, "r")
				or die("Can't open ".COUNTRY_LIMIT_HTACCESS_FILE);

			$mode = 0;
			while ($line = fgets($fp2)) {
				$line = trim($line);

				if ($mode == 0) {
					if ($line === '#COUNTRY_LIMIT_____#') {
						$mode = 1;
					} else {
						$new_line .= $line.PHP_EOL;
					}
				} else if ($mode == 1) {
					$new_line .= $base_obj->makeCidrList($cidr_list, COUNTRY_LIMIT_TYPE, COUNTRY_LIMIT_MTHOD, COUNTRY_LIMIT_EXTRA);
					$mode = 2;
				} else if ($mode == 2) {
					if ($line === '#_____COUNTRY_LIMIT#') $mode = 3;
				} else if ($mode == 3) {
					$new_line .= $line.PHP_EOL;
				}
			}

			fclose($fp2);

			if ($mode == 0) {
				$new_line .= $base_obj->makeCidrList($cidr_list, COUNTRY_LIMIT_TYPE, COUNTRY_LIMIT_MTHOD, COUNTRY_LIMIT_EXTRA);
			}

			if (!$base_obj->isExists(COUNTRY_LIMIT_HTACCESS_ORIG)) {
				if (!$base_obj->fileCopy(COUNTRY_LIMIT_HTACCESS_FILE, COUNTRY_LIMIT_HTACCESS_ORIG)) {
					die("Can't copy {$htaccess} to ".COUNTRY_LIMIT_HTACCESS_ORIG);
				}
			}
		} else {
			$new_line .= $base_obj->makeCidrList($cidr_list, COUNTRY_LIMIT_TYPE, COUNTRY_LIMIT_MTHOD, COUNTRY_LIMIT_EXTRA);
		}

		rewind($fp);
		fwrite($fp, $new_line, strlen($new_line));
		fclose($fp);

		if (!$base_obj->fileRename(COUNTRY_LIMIT_HTACCESS_TEMP, COUNTRY_LIMIT_HTACCESS_FILE)) {
			die("Can't rename ".COUNTRY_LIMIT_HTACCESS_TEMP." to ".COUNTRY_LIMIT_HTACCESS_FILE);
		}

		unset($cidr_list);
	}

?>