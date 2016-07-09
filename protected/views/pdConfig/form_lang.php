<div class="form">
	
	<input id="ContName" type="hidden" value="<?=$data['ContName'];?>"/>
	<input id="LangID" type="hidden" value="<?=$data['conf_lang_lang_id'];?>"/>
	<input id="RowID" type="hidden" value="<?=$data['conf_lang_conf_id'];?>"/>
	<input id="Type" type="hidden" value="<?=$data['Type'];?>"/>
	
	<label> Name </label>
	<input size="40" maxlength="45" class ="langFrm" id="conf_lang_name" value="<?=$data['conf_lang_name'];?>" type="text">
	<br />
	<label> Value </label>
	<input size="40" maxlength="45" class ="langFrm" id="conf_lang_value" value="<?=$data['conf_lang_value'];?>" type="text">
	
</div>