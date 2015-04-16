=== Plugin Name ===
Contributors: Caret Inc.
Tags: Caret, SPAM, Security, admin
Requires at least: 3.0.0
Tested up to: 4.1.1
Stable tag: 1.0.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

国単位アクセス制限プラグイン - Caret Country Access Limit

== Description ==

### APNICなどの機関で公開されているIPアドレスの一覧を自動取得し、.htaccessによるアクセス制限を国単位で行います。
紹介ページ
http://www.ca-ret.co.jp/?p=1172

アクセス元の国を制限することにより、総当たり攻撃などの防止策になります。
よろしければお試しください。

== Installation ==

### ■注意事項
* IPアドレス一覧の自動更新は、「wp-cron.php」を使用します。「wp-cron.php」を使用しない場合は、cronに「php WordPressのroot/wp-content/plugins/CaretCountryAccessLimit/batch.php 1 > /dev/null 2>&1」を登録することで自動更新が可能です。
* このプラグインは、WordPressルートディレクトリ、「wp-content」、「wp-content/plugins/CaretCountryAccessLimit」に対して書込み権限が必要となります。
* このプラグインをFTPなどでアップロードした場合は、「wp-content/plugins/CaretCountryAccessLimit/batch.php」対して実行権限が必要となる場合があります。
* 「上記の国からのアクセス時の処理」で「許可する」を選択した場合、「プライベートアドレス 」「ループバックアドレス」が自動的に追加されます。
* 「アクセスを拒否(又は許可)する国の2文字の国コード」「上記の国以外でアクセスを拒否(又は許可)するIPアドレス」は、1件ごとに改行をしてください。
* 万が一アクセスできなくなった場合は、FTPクライアントなどでWordPressのルートディレクトリに移動し、htaccessのバックアップ「.htaccess_country_limit_org」を「.htaccess」にリネームするか「.htaccess」を削除してください。

### ■プラグインの有効化
管理ページ＞プラグイン＞CaretCountryAccessLimitを有効にしてください。

### ■設定
管理ページ＞設定＞CaretCountryAccessLimitの設定にて下記を設定してください。

####1.アクセス制限機能のON/OFF
* このプラグインによるアクセス制限を一時的に無効にしたい場合は「OFF」に設定します。

####2.アクセス制限の対象となるメソッド
* 制限したいリクエストメソッド「POSTのみ」「GETのみ」「両方」の中から選択します。

####3.アクセスを拒否(又は許可)する国の2文字の国コード
* 制限対象の2文字の国コード(ISO 3166-1 alpha-2)を入力します。
* 参考URL：http://ja.wikipedia.org/wiki/ISO_3166-1

####4.上記の国からのアクセス時の処理
* 3で入力した国からアクセスを「拒否する」「許可する」から選択します。
* 「許可する」を選択した場合、「プライベートアドレス 」「ループバックアドレス」が自動的に追加されます。

####5.上記の国以外でアクセスを拒否(又は許可)するIPアドレス
* 3以外で別途制限したいIPアドレスを入力します。
* 例）127.0.0.1、127.0.0.1/32など

####6.IPアドレス一覧の更新間隔
* IPアドレス一覧を自動更新する間隔を「3日」「7日」「14日」「30日」から選択します。

== Screenshots ==

1. 設定画面

== Changelog ==
= 1.0.0 =
* 初版リリース

== Upgrade Notice ==
####none


== Arbitrary section ==

###■免責事項
本プラグインは無料でご利用いただけますが、ご自身の責任においてご利用ください。
利用の結果生じた損害について、一切責任を負いません。予めご了承ください。

お問い合わせ、ご意見、ご要望、不具合等は、以下お問い合わせフォームよりご連絡ください。
http://www.ca-ret.co.jp/contact

####■関連事項
* ISO 3166-1 wikipedia

> http://ja.wikipedia.org/wiki/ISO_3166-1

* IPアドレスの管理について

> https://www.nic.ad.jp/ja/ip/admin.html

このプラグインは、国別のIPアドレスの取得の際に、インターネットレジストリを使用しています。
This plugin uses the internet registery in order to validate IP addresses.

* Regional Internet Registry(RIR) database

> ftp://ftp.arin.net/pub/stats/arin/delegated-arin-extended-latest
> ftp://ftp.ripe.net/pub/stats/ripencc/delegated-ripencc-extended-latest
> ftp://ftp.apnic.net/pub/stats/apnic/delegated-apnic-extended-latest
> ftp://ftp.lacnic.net/pub/stats/lacnic/delegated-lacnic-extended-latest
> ftp://ftp.afrinic.net/pub/stats/afrinic/delegated-afrinic-extended-latest
