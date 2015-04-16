<?php

/***
 * アクセス制限の有効無効
 */
define('COUNTRY_LIMIT_STATUS', 0);

/***
 * アクセス制限対象となるメソッド
 */
define('COUNTRY_LIMIT_MTHOD', 'POST');

/***
 * アクセスを制限(又は許可)
 */
define('COUNTRY_LIMIT_TYPE', 0);

/***
 * アクセスを制限(又は許可)する国(ISO 3166-1 alpha-2)
 */
define('COUNTRY_LIMIT_LIST', '');

/***
 * アクセスを制限(又は許可)するIPアドレス
 */
define('COUNTRY_LIMIT_EXTRA', '');

/***
 * アクセスを制限(又は許可)する国リストの更新間隔(日数)
 */
define('COUNTRY_LIMIT_RENEW', 0);

?>