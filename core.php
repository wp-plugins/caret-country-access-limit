<?php
	define('COUNTRY_LIMIT_CONFIG_DEF', dirname(__FILE__)."/config.default.php");
	define('COUNTRY_LIMIT_CONFIG_MAIN', dirname(__FILE__)."/../../country-limit.config.php");
	define('COUNTRY_LIMIT_SETUP_FILE', dirname(__FILE__)."/setup.php");
	define('COUNTRY_LIMIT_ARIN_FTP', 'ftp://ftp.arin.net/pub/stats/arin/delegated-arin-extended-latest');
	define('COUNTRY_LIMIT_RIPENCC_FTP', 'ftp://ftp.ripe.net/pub/stats/ripencc/delegated-ripencc-extended-latest');
	define('COUNTRY_LIMIT_APNIC_FTP', 'ftp://ftp.apnic.net/pub/stats/apnic/delegated-apnic-extended-latest');
	define('COUNTRY_LIMIT_LACNIC_FTP', 'ftp://ftp.lacnic.net/pub/stats/lacnic/delegated-lacnic-extended-latest');
	define('COUNTRY_LIMIT_AFRINIC_FTP', 'ftp://ftp.afrinic.net/pub/stats/afrinic/delegated-afrinic-extended-latest');
	define('COUNTRY_LIMIT_CIDR_LIST', dirname(__FILE__)."/delegated-extended-latest");
	define('COUNTRY_LIMIT_CIDR_LOCK', dirname(__FILE__)."/delegated-extended-latest.lock");
	define('COUNTRY_LIMIT_BATCH_SCRIPT', dirname(__FILE__)."/batch.php");
	define('COUNTRY_LIMIT_BASE_SCRIPT', dirname(__FILE__)."/base.php");
	define('COUNTRY_LIMIT_HTACCESS_FILE', dirname(__FILE__).'/../../../.htaccess');
	define('COUNTRY_LIMIT_HTACCESS_ORIG', dirname(__FILE__).'/../../../.htaccess_country_limit_org');
	define('COUNTRY_LIMIT_HTACCESS_TEMP', dirname(__FILE__).'/../../../.htaccess_country_limit_tmp');
	define('COUNTRY_LIMIT_CRON', 'countryLimitLoad');
?>