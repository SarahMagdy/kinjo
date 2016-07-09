<div class="form">
	
	<input id="ContName" type="hidden" value="<?=$data['ContName'];?>"/>
	<input id="LangID" type="hidden" value="<?=$data['mess_lang_lang_id'];?>"/>
	<input id="RowID" type="hidden" value="<?=$data['mess_lang_mess_id'];?>"/>
	<input id="Type" type="hidden" value="<?=$data['Type'];?>"/>
	
	
	<label>Message</label>
	<textarea maxlength="300" rows="6" cols="40" class ="langFrm" id="mess_lang_mess"><?=$data['mess_lang_mess'];?></textarea>
	
</div>