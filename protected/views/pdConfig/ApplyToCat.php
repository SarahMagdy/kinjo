<h1>Apply To Categories</h1>

<?php //var_dump($Data['Cats']);
	//var_dump($Data['Conf']);?>

<div id="Conf" style="border: 1px solid #6E7376">
	<label>Configuration</label>
	<div id="ConfD">
		<?php if(isset($Data['Conf'])):?>
			<input type="checkbox"  class="conf" CType ="par" checked disabled Confid ="<?=$Data['Conf']['ParID'];?>"/><?=$Data['Conf']['ParN'];?><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php foreach($Data['Conf']['Subs'] AS $CoKey=>$CoRow):?>
				
				<?php if($Data['ConfType'] == 'ch'):?> 
					<input type="checkbox" class="conf" CType ="ch" ParID ="<?=$Data['Conf']['ParID'];?>" checked disabled Confid ="<?=$CoRow['SubID'];?>"/><?=$CoRow['SubN'];?>
				<?php else:?>
					<input type="checkbox" class="conf" CType ="ch" ParID ="<?=$Data['Conf']['ParID'];?>" checked Confid ="<?=$CoRow['SubID'];?>"/><?=$CoRow['SubN'];?>
				<?php endif;?>
				
			<?php endforeach;?>
		<?php endif;?>
	</div>
</div>
<br/>
<div id="Cats"style="border: 1px solid #6E7376" >
	<label>Categories</label>
	<div id="CatsD">
		<?php if(isset($Data['Cats'])):?>
			<?php foreach($Data['Cats'] AS $CatKey=>$CatRow):?>
				<input type="checkbox" class="cats" CType="par" Catid="<?=$CatRow['CatID'];?>"/><?=$CatRow['CatN'];?><br />
				<?php if(isset($CatRow['Subs'])):?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php foreach($CatRow['Subs'] AS $SubKey=>$SubRow):?>
						<input type="checkbox" class="cats catsub_<?=$CatRow['CatID'];?>" CType="ch" ParID ="<?=$CatRow['CatID'];?>" Catid="<?=$SubRow['CatID'];?>"/><?=$SubRow['CatN'];?>
					<?php endforeach;?>
					<br />
				<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>
	</div>
</div>
<button id="BtnApply"> Apply </button>
<?//php print_r($Data['Cats']);?>
<script>
	
	$('.conf').click(function(e){
		var ConfType = $(this).attr('CType');
		var Parid = $(this).attr('parid');
		if(ConfType == 'ch'){
			if($('input.conf[ParID='+Parid+']:checked').length == 0){
				alert(' Check at least One Child ');
				$(this).prop('checked', true );
			}
		}
		
	});
	
	$('.cats').click(function(e){
		var CatType = $(this).attr('CType');
		var ParID = $(this).attr('Catid');
		if(CatType == 'par'){
			if($(this).is(":checked")){
				$('.catsub_'+ParID).prop('checked', true );
			} else {
				$('.catsub_'+ParID).prop('checked', false );
			}
		}
		if(CatType == 'ch'){
			var Parid = $(this).attr('parid');
			$('input[Catid='+Parid+']').prop('checked', true );
			if($('input.cats[ParID='+Parid+']:checked').length == 0){
				alert(' Check at least One Child ');
				$(this).prop('checked', true );
			}
		}
	});
	
	var ConfArr = {};var ConfID = 0;
	
	function CollectConf () {
		
		ConfArr = {};var ConfSub = [];
		ConfID = $('.conf[CType=par]').attr('Confid');
		$('.conf[CType=ch]:checked').each(function(){
			ConfSub.push($(this).attr('Confid'));
		});
		ConfArr[ConfID] = ConfSub;
	}
	
	var CatAllArr = {};
	
	function CollectCat () {
		CatAllArr = [];
		$('.cats[CType=par]:checked').each(function(){
			var CatID = $(this).attr('Catid');
			CatAllArr.push(CatID);
			$('.catsub_'+CatID+':checked').each(function(){
				CatAllArr.push($(this).attr('Catid'));	
			});
		});
		
	}

	$('#BtnApply').click(function(e){
		
		CollectConf();
		CollectCat();
		if(CatAllArr.length > 0){
			var d = {
				Conf:ConfArr,
				Cat:CatAllArr,
				ConfID:ConfID
			};
			
			$.post('/index.php/PdConfig/ajaxApplyToCat',d,function(data){
				alert('Configuration Applied Successfully');
				window.location.href = "/pdConfig/Admin";
			});
		}else{
			alert(' No Categories Selected');
		}
	});
	
	
</script>