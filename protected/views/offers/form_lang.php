<div class="form">
	
	<input id="ContName" type="hidden" value="<?=$data['ContName'];?>"/>
	<input id="LangID" type="hidden" value="<?=$data['offer_lang_lang_id'];?>"/>
	<input id="RowID" type="hidden" value="<?=$data['offer_lang_offer_id'];?>"/>
	<input id="Type" type="hidden" value="<?=$data['Type'];?>"/>
	
	<label>Title</label>
	<input size="40" maxlength="45" class ="langFrm" id="offer_lang_title" value="<?=$data['offer_lang_title'];?>" type="text">
	<br />
	<label>Text</label>
	<textarea maxlength="200" rows="6" cols="40" class ="langFrm" id="offer_lang_text"><?=$data['offer_lang_text'];?></textarea>
	<br />
	
	<label>Discount</label>
	<input size="6" maxlength="6" class ="langFrm" id="offer_lang_discount" value="<?=$data['offer_lang_discount'];?>" type="text">
	
</div>