<div class="form">
	
	<input id="ContName" type="hidden" value="<?=$data['ContName'];?>"/>
	<input id="LangID" type="hidden" value="<?=$data['bu_lang_lang_id'];?>"/>
	<input id="RowID" type="hidden" value="<?=$data['bu_lang_bu_id'];?>"/>
	<input id="Type" type="hidden" value="<?=$data['Type'];?>"/>
	
	<label>Business Name</label>
	<input size="40" maxlength="45" class ="langFrm" id="bu_lang_title" value="<?=$data['bu_lang_title'];?>" type="text">
	<br />
	<label>Description</label>
	<textarea maxlength="300" rows="6" cols="40" class ="langFrm" id="bu_lang_description"><?=$data['bu_lang_description'];?></textarea>
	
</div>