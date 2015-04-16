	<div class="wrap">
		<h2>CaretCountryAccessLimitの設定</h2>
		<?php if (!empty($_POST['country-limit_result'])): ?><h3 id="country-limit_result" style="color:#ff0000;"><?php echo $_POST['country-limit_result']; ?></h3><?php endif; ?>

		<div id="setting-error-settings_updated" class="updated settings-error">
			<?php if (!empty($_POST['country-limit_warning'])): ?>
			<p><strong style="color:#ff0000;">※現在IPアドレスの一覧を取得中です。反映されるまで時間が掛かる場合がありますのでご了承ください</strong></p>
			<?php endif; ?>
			<ul style="list-style-type:decimal;margin-left:16px;font-size:12px;">
			<li style="margin:2px 0;">このプラグインはAPNICなどの機関で公開されているIPアドレスの一覧を取得し、<strong style="color:#0000ff;">「.htaccess」</strong>によるアクセス制限を国単位で行います</li>
			<li style="margin:2px 0;">IPアドレス一覧の自動更新は<strong style="color:#0000ff;">「wp-cron.php」</strong>を使用します。「wp-cron.php」を使用しない場合はcronに<strong style="color:#0000ff;">「php WordPressのroot/wp-content/plugins/CaretCountryAccessLimit/batch.php 1 &gt; /dev/null 2>&amp;1」</strong>を登録することで自動更新が可能です</li>
			<li style="margin:2px 0;">このプラグインはディレクトリ<strong style="color:#0000ff;">「WordPressのroot」「wp-content」「wp-content/plugins/CaretCountryAccessLimit」</strong>に対して書込み権限が必要となります</li>
			<li style="margin:2px 0;">このプラグインをFTPなどでアップロードした場合は<strong style="color:#0000ff;">「wp-content/plugins/CaretCountryAccessLimit/batch.php」</strong>対して実行権限が必要となる場合があります</li>
			<li style="margin:2px 0;">「上記の国からのアクセス時の処理」で<strong style="color:#0000ff;">「許可する」</strong>を選択した場合は<strong style="color:#0000ff;">「プライベートアドレス 」「ループバックアドレス」</strong>が自動的に追加されます</li>
			<li style="margin:2px 0;">「アクセスを拒否(又は許可)する国の2文字の国コード」「上記の国以外でアクセスを拒否(又は許可)するIPアドレス」は<strong style="color:#0000ff;">1件ごとに改行</strong>してください</li>
			<li style="margin:2px 0;">万が一アクセスできなくなった場合は、FTPクライアントなどでWordPressのrootディレクトリに移動し、<strong style="color:#0000ff;">バックアップ「.htaccess_country_limit_org」を「.htaccess」にリネーム</strong>するか<strong style="color:#0000ff;">「.htaccess」を削除</strong>してください</li>
			</ul>
		</div>

		<form method="post" action="options-general.php?page=CaretCountryAccessLimit">
		<input type="hidden" name="country-limit_update" value="1" />

		<table class="widefat">
		<thead>
		<tr>
			<th style="width:22%;border-right:1px solid #dfdfdf;">項目</th>
			<th style="width:78%;">値</th>
		</tr>
		</thead>
		<tbody>
		<tr style="background-color:#f9f9f9;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">アクセス制限機能のON/OFF</th>
			<td style="border-bottom:0;">
				<select name="COUNTRY_LIMIT_STATUS[]" class="postform">
				<option value="0"<?php if ($_POST['COUNTRY_LIMIT_STATUS'][0] == 0): ?> selected="selected"<?php endif; ?>>OFF</option>
				<option value="1"<?php if ($_POST['COUNTRY_LIMIT_STATUS'][0] == 1): ?> selected="selected"<?php endif; ?>>ON</option>
				</select>
			</td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<td style="color:red;border-top:0;"><?php echo $_POST['COUNTRY_LIMIT_STATUS']['error']; ?></td>
		</tr>
		<tr style="background-color:#ececec;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">アクセス制限の対象となるメソッド</th>
			<td style="border-bottom:0;">
				<select name="COUNTRY_LIMIT_MTHOD[]" class="postform">
				<option value="0"<?php if ($_POST['COUNTRY_LIMIT_MTHOD'][0] == 0): ?> selected="selected"<?php endif; ?>>POSTのみ</option>
				<option value="1"<?php if ($_POST['COUNTRY_LIMIT_MTHOD'][0] == 1): ?> selected="selected"<?php endif; ?>>GETのみ</option>
				<option value="2"<?php if ($_POST['COUNTRY_LIMIT_MTHOD'][0] == 2): ?> selected="selected"<?php endif; ?>>両方</option>
				</select>
			</td>
		</tr>
		<tr style="background-color:#ececec;">
			<td style="color:red;border-top:0;"><?php echo $_POST['COUNTRY_LIMIT_MTHOD']['error']; ?></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">アクセスを拒否(又は許可)する国の2文字の国コード<br /><a href="http://ja.wikipedia.org/wiki/ISO_3166-1">(wikipediaの「ISO 3166-1 alpha-2」を参照)</a></th>
			<td style="border-bottom:0;"><textarea name="COUNTRY_LIMIT_LIST[]" rows="5" cols="70" class="search-input"><?php echo htmlspecialchars($_POST['COUNTRY_LIMIT_LIST'][0]); ?></textarea></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<td style="color:red;border-top:0;"><?php echo $_POST['COUNTRY_LIMIT_LIST']['error']; ?></td>
		</tr>
		<tr style="background-color:#ececec;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">上記の国からのアクセス時の処理</th>
			<td style="border-bottom:0;">
				<select name="COUNTRY_LIMIT_TYPE[]" class="postform">
				<option value="0"<?php if ($_POST['COUNTRY_LIMIT_TYPE'][0] == 0): ?> selected="selected"<?php endif; ?>>許可する</option>
				<option value="1"<?php if ($_POST['COUNTRY_LIMIT_TYPE'][0] == 1): ?> selected="selected"<?php endif; ?>>拒否する</option>
				</select>
			</td>
		</tr>
		<tr style="background-color:#ececec;">
			<td style="color:red;border-top:0;"><?php echo $_POST['COUNTRY_LIMIT_TYPE']['error']; ?></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;">上記の国以外でアクセスを拒否(又は許可)するIPアドレス<br />(127.0.0.1、127.0.0.1/32など)</th>
			<td style="border-bottom:0;"><textarea name="COUNTRY_LIMIT_EXTRA[]" rows="5" cols="70" class="search-input"><?php echo htmlspecialchars($_POST['COUNTRY_LIMIT_EXTRA'][0]); ?></textarea></td>
		</tr>
		<tr style="background-color:#f9f9f9;">
			<td style="color:red;border-top:0;"><?php echo $_POST['COUNTRY_LIMIT_EXTRA']['error']; ?></td>
		</tr>
		<tr style="background-color:#ececec;">
			<th rowspan="2" style="border-right:1px solid #dfdfdf;border-bottom:0;">IPアドレス一覧の更新間隔</th>
			<td style="border-bottom:0;">
				<select name="COUNTRY_LIMIT_RENEW[]" class="postform">
				<option value="0"<?php if ($_POST['COUNTRY_LIMIT_RENEW'][0] == 0): ?> selected="selected"<?php endif; ?>>自動更新しない</option>
				<?php foreach (array(3, 7, 14, 30) as $i): ?>
				<option value="<?php echo $i; ?>"<?php if ($_POST['COUNTRY_LIMIT_RENEW'][0] == $i): ?> selected="selected"<?php endif; ?>><?php echo $i; ?>日</option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr style="background-color:#ececec;">
			<td style="color:red;border-top:0;border-bottom:0;"><?php echo $_POST['COUNTRY_LIMIT_RENEW']['error']; ?></td>
		</tr>

		</tbody>
		</table>

		<p><input type="button" class="button" value="　保存　" /></p>

		</form>
	</div>