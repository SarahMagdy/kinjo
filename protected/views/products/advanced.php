<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->title=>array('view','id'=>$model->pid),
	'Advanced Setting',
);

?>

<h1>Advanced Setting Products #<?php echo $model->pid; ?></h1>

</br>
<?php echo CHtml::link('Add New Configuration','#',array('onclick' => 'FuncConf()')); ?><label style="margin-left: 10px;"><span><b>eg :</b></span> Size , Materials , Extras</label>
<a id="ConfHelp" style="float: right;"><img src="/assets/8626beb4/gridview/question-mark.png" alt="Help" width="30" height="30"></a>
<div id="ConfFrmDiv" style="display: none;background:#ccc;padding: 1%;margin: 20px;">
	<h2>Add New</h2>
	 <input type="radio" name="ParChd" class="ParChd" value="par" checked>Parent
	 <input type="radio" name="ParChd" class="ParChd" value="chd" >Child
	 <br /><br />
	 <div id="ParChdDiv">
		 <label hidden id="lbl_parent">Parent</label>
		 <?php $ParentD = PdConfig::model()->findAll('parent_id IS NULL AND conf_buid ='.Yii::app()->session['User']['UserBuid']);?>
		 <select name="parent_id" id="frm_parent" hidden>
			 <option value="">Select a Parent</option>
			 <?php foreach($ParentD AS $key=>$row):?>
			 	 <option value="<?=$row->cfg_id;?>"><?=$row->name;?></option>
			 <?php endforeach;?>
		 </select>
		 
		<label id="lbl_name">Parent Name</label>
		<input id="frm_name" style="width: 100px;"/>
		
		<label hidden id="lbl_value">Price</label>
		<input id="frm_value" style="width: 100px;" hidden/>
		
		<button id="btn_save">Save</button>
	</div>
</div>

<div id="FrmDiv" style="margin: 20px;">
	<input id="prod_id" type="text" value="<?php echo $model->pid; ?>" hidden />
	 <label><b>Parent</b></label>
	 <?php $ParentD = PdConfig::model()->findAll('parent_id IS NULL AND cfg_id NOT IN (SELECT pdconfv_confid FROM pd_conf_v WHERE pdconfv_pid ='.$model->pid.') AND conf_buid ='.Yii::app()->session['User']['UserBuid']);?>
	 <select name="parent_id" id="parent_id">
		 <option value="">Select a Parent</option>
		 <?php foreach($ParentD AS $key=>$row):?>
		 	 <option value="<?=$row->cfg_id;?>"><?=$row->name;?></option>
		 <?php endforeach;?>
	 </select>
	 
	 <input type="radio" name="chk_rad" value="0" checked ><b>Checkable</b>
	 <input type="radio" name="chk_rad" value="1" ><b>Radio</b>
	 
	 <button id="btn_add"> Apply to Product</button>
</div>

<div id="ConfGridDiv" class="grid-view">
	<table class="items">
		<thead>
			<tr>
				<th style="padding-left: 20px;">Name</th>
				<th>Checkable OR Radio</th>
				<th></th>
				<th colspan="2"></th>
			</tr>
		</thead>
		<tbody>
			nnnnn
			<?php foreach($PdConfG AS $key=>$row):?>
			 	 <tr style="background: none repeat scroll 0 0 #E5F1F4">
			 	 	<td style="padding-left: 20px;"><?=$row['name'];?></td> 
			 	 	<td><?=$row['ChkRad'];?></td>
			 	 	<td colspan="2"></td>
			 	 	<td>
			 	 		<a class="EditChilds" ConfID="<?=$row['Pconfid'];?>" title="Edit" href="#"><img src="/assets/8626beb4/gridview/update.png" alt="Edit"></a>
			 	 		<a class="DetChilds"  ConfID="<?=$row['Pconfid'];?>" title="Details" href="#"><img src="/assets/8626beb4/gridview/view.png" alt="Details"></a>
			 	 		<a class="del_" id_="<?=$row['Pconfid'];?>" ty ="par" title="Delete" href="#"><img src="/assets/8626beb4/gridview/delete.png" alt="Delete"></a>
			 	 	</td>
			 	 	<?php if(isset($row['sub'])):?>	
			 	 		<?php foreach($row['sub'] AS $skey=>$srow):?>
			 	 			 <tr class="sub_<?=$row['Pconfid'];?>" SubConfID="<?=$srow['Sconfid'];?>" style="background: none repeat scroll 0 0 #F8F8F8;display: none;">
			 	 			 	<td colspan="2"></td>
			 	 			 	<td><?=$srow['name']?> :</td>
			 	 			 	<td id="SubVal_<?=$srow['Sconfid'];?>"><?=$srow['value']?></td>
			 	 			 	<td><a class="subdel_" id_="<?=$srow['Sconfid'];?>" ty ="ch" title="Delete" href="#"><img src="/assets/8626beb4/gridview/DeleteRed.png" alt="Delete"></a></td>
			 	 			 </tr>
			 	 		<?php endforeach;?>
				 	 <?php endif; ?>		
			 	 </tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>

<div id="SubDiv" style="display: none;">
	<input type="hidden" value="" id="SubType"/>
	<input type="hidden" value="0" id="PConfID"/>
	<div id="SubItems">
		<table id="SubItemsT">
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>

<div id="ConfHelpDiv" style="display: none;">
	Help
</div>

<script>
	
	var SubArr = []; 
	
	function SubConfFunc (ConfID) {
	  
	  $('#SubItemsT tbody tr').remove();
	  $.post('/index.php/Products/AjaxGetSubConf/'+ConfID,function(data){
	  	
		  	var json_data = data.toString();
			
			var end_data = $.parseJSON(json_data);
			if(end_data.length > 0){
				for (var key in end_data){
					
					if($.inArray(end_data[key]['ConfID'],SubArr) >= 0 ){
						
						 var val = 0;
						 val = $('#SubVal_'+end_data[key]['ConfID']).html();
						 $('#SubItemsT tbody').append("<tr><td><input class= 'SubChk' type='checkbox' id='SubChk_"+end_data[key]['ConfID']+"' SConfId ='"+end_data[key]['ConfID']+"'checked /></td><td>"+end_data[key]['ConfN']+"</td><td><input class= 'SubTxt' id='SubTxt_"+end_data[key]['ConfID']+"' value ='"+val+"' style='width:70px;'/></td></tr>");	
					}else{
						 $('#SubItemsT tbody').append("<tr><td><input class= 'SubChk' type='checkbox' id='SubChk_"+end_data[key]['ConfID']+"' SConfId ='"+end_data[key]['ConfID']+"'/></td><td>"+end_data[key]['ConfN']+"</td><td><input class= 'SubTxt' id='SubTxt_"+end_data[key]['ConfID']+"' value ='"+end_data[key]['ConfV']+"'style='width:70px;'/></td></tr>");
					}
				}
				
				CreateSubDialog();
				
			}else{
				alert('This Configuration does not have Child ,So You Can not apply it ,You should add Child to it First');
			}
		
	  });
	}
	
	function MarkSubConf (ConfID){
		SubArr = [];
		$('tr.sub_'+ConfID).each(function() {
			
			var SubConfID = $(this).attr('SubConfID');
			SubArr.push(SubConfID);
			
		});
	}
	
	function GetNewSubConf (){
		var NSubArr = {};
		$('.SubChk').each(function() {
			var SConfId = $(this).attr('SConfId');
			if($(this).is(':checked')){
				//NSubArr.push(SConfId,$('#SubTxt_'+SConfId).val());
				NSubArr[SConfId]= $('#SubTxt_'+SConfId).val();
			}
		});
		
		return NSubArr;
	}
	
	function CreateSubDialog(){
		 $(function() {
			dialog = $('#SubDiv').dialog({
					autoOpen: false,
					height: 400,
					width: 450,
					modal: true,
					buttons: {
					// "Submit": SubmitDialogFrm,
					// success:function(){
						// dialog.dialog( "close" );
					// },
						Submit:function(){
							SubmitSubConf();
							dialog.dialog( "close" );
							
						},
						Cancel: function() {
							dialog.dialog( "close" );
							
						}
					},
					close: function() {
						dialog.dialog( "close" );
						
					}
				});
			});
			
			dialog.dialog( "open" );
	}
	
	function SubmitSubConf () {
	  
	  if($('.SubChk').is(':checked')){
	  	
	  	 var confID = 0;
	  	 
	  	 confID = $('#PConfID').val();
	  
	 	 var DA ={
			
			SubType:$('#SubType').val(),
			prodID:$('#prod_id').val(),
			confID:confID,
			ChkRad:$('input:radio[name = chk_rad]:checked').val(),
			value:0,
			SubConf:GetNewSubConf(),
		};
			
		$.post('/index.php/products/ajaxaddconf',DA,function(data){
	  			
	  			//if(data){
	  				
	  			//	if(data == 'insert_before'){
	  					
	  			//		alert('This Configration inserted to this product before');
	  			//		$('#parent_id').val('');
	  					
	  			//	}
	  			//}else{
	  				
	  				 location.reload(); 
	  			//}
  		});
  		
	  }else{
	  	alert('Check at Last One ');
	  }
		
	}
	
	$('#btn_add').click(function(e){
		
		if($('#parent_id').val() > 0){
			$('#SubType').val('add');
			$('#PConfID').val($('#parent_id').val());
			SubArr = [];
			SubConfFunc($('#parent_id').val());
			//CreateSubDialog();
		}else{
			alert('Choose Parent');
			$('#parent_id').focus();
		}
		
	});
	
	
	function FuncConf()
	{
		if ($('#ConfFrmDiv').is(":visible")) {
		
			$('#ConfFrmDiv').hide();
		  		
		} else{
		  	
			$('#ConfFrmDiv').show();
		}
	}
	
	$('#btn_save').click(function(e){
		
		var DA ={
			
			parent_id:$('#frm_parent').val(),
			name:$('#frm_name').val(),
			value:$('#frm_value').val(),
		};
		
		$.post('/index.php/products/ajaxnewconf',DA,function(data){
	  			
	  			location.reload();
  		});
  		
     });
     
     $('.del_,.subdel_').click(function(e){

		if(confirm('Are You Sure ')){
			var DA ={
				
				ID:$(this).attr('id_'),
				type:$(this).attr('ty'),
				prodID:$('#prod_id').val(),
			};
			
			$.post('/index.php/products/ajaxdelconf',DA,function(data){
		  			
		  			location.reload();
	  		});
	  	}
  		
     });
     
     $('.DetChilds').click(function(e){
     	
     	var ConfID = $(this).attr('ConfID');
     	if ($('.sub_'+ConfID).is(":visible")) {
		
			$('.sub_'+ConfID).hide();
		  		
		} else{
		  	
			$('.sub_'+ConfID).show();
		}
     	
     });
     
     $('.EditChilds').click(function(e){ 
     	
     	var ConfID = $(this).attr('ConfID');
     	$('#SubType').val('edit');
     	$('#PConfID').val(ConfID);
     	MarkSubConf(ConfID);
     	SubConfFunc(ConfID);
		
     });
    
     $('.ParChd').click(function(e){ 
     	
     	if($("input[name=ParChd]:checked").val() == 'par'){
     		$('#lbl_parent,#frm_parent,#lbl_value,#frm_value').hide();
     		$('#lbl_name').html('Parent Name');
     	}
     	if($("input[name=ParChd]:checked").val() == 'chd'){
     		$('#lbl_parent,#frm_parent,#lbl_value,#frm_value').show();
     		$('#lbl_name').html('Child Name');
     	}
     });
     
     $('#ConfHelp').click(function(e){ 
     	
     	 $(function() {
			dialog = $('#ConfHelpDiv').dialog({
				
					autoOpen: false,
					height: 400,
					width: 450,
					modal: true,
					buttons: {
					// "Submit": SubmitDialogFrm,
					// success:function(){
						// dialog.dialog( "close" );
					// },
					},
					close: function() {
						dialog.dialog( "close" );
						
					}
				});
			});
			
			dialog.dialog( "open" );
			
     });
     
</script>
