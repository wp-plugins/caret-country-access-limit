<?php

define('COUNTRY_LIMIT_CONFIG_MAIN', WP_CONTENT_DIR."/country-limit.config.php");

if (!defined('WP_UNINSTALL_PLUGIN')) exit;

@unlink(COUNTRY_LIMIT_CONFIG_MAIN);

?>