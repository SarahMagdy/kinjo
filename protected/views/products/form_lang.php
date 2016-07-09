<div class="form">
	
	<input id="ContName" type="hidden" value="<?=$data['ContName'];?>"/>
	<input id="LangID" type="hidden" value="<?=$data['p_lang_lang_id'];?>"/>
	<input id="RowID" type="hidden" value="<?=$data['p_lang_pid'];?>"/>
	<input id="Type" type="hidden" value="<?=$data['Type'];?>"/>
	
	<label> Product Name </label>
	<input size="40" maxlength="45" class ="langFrm" id="p_lang_title" value="<?=$data['p_lang_title'];?>" type="text">
	<br />
	<label> Price </label>
	<input size="15" maxlength="15" class ="langFrm" id="p_lang_price" value="<?=$data['p_lang_price'];?>" type="text">
	<br />
	<label>Description</label>
	<textarea maxlength="300" rows="6" cols="40" class ="langFrm" id="p_lang_discription"><?=$data['p_lang_discription'];?></textarea>
	
</div>