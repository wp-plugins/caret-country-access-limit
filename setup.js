jQuery(function(){
	jQuery('#country-limit_result').fadeOut(3000);

	jQuery('.button').click(function()
	{
		var list = jQuery('textarea[name="COUNTRY_LIMIT_LIST[]"]').val().split("\n");
		var is_jp = false;
		for (key in list) {
			if (list[key].toUpperCase() === "JP") {
				is_jp = true;
				break;
			}
		}

		var type = jQuery('select[name="COUNTRY_LIMIT_TYPE[]"]').val();

		if (type == 0 && is_jp === false) {
			if (!window.confirm('許可する対象国に日本が入っていません。このまま登録してよろしいですか？')) {
				return false;
			}
		} else if (type == 1 && is_jp === true) {
			if (!window.confirm('拒否する対象国に日本が入っています。このまま登録してよろしいですか？')) {
				return false;
			}
		}

		jQuery('form').submit();
	});
});
