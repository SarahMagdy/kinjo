<?php
/* @var $this CustomersController */
/* @var $model Customers */

$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->cid,
);

/*
$this->menu=array(
	array('label'=>'List Customers', 'url'=>array('index')),
	array('label'=>'Create Customers', 'url'=>array('create')),
	array('label'=>'Manage Customers', 'url'=>array('admin')),
);*/

?>
<style>
	.CustContainer { border:2px solid #ccc; width:100%; height: 250px; overflow-y: scroll; }
</style>
<h1>Send Notifications To Customers</h1>

	</br>
	<div class="row">
		<label><b>Message ID</b></label>
		<select id="message_id">
			<option value=""> ------- Select a Message ---- </option>
			<?php foreach($messages AS $Key=>$Row):?>
				<option value="<?=$Row['mid'];?>" txt ="<?=$Row['message'];?>"><?=$Row['message'];?></option>
			<?php endforeach;?>
		</select>
	</div>
	</br>
	
	<label><b>Message Text</b></label>
	</br>
	<textarea id="message_txt" rows="4" cols="70"></textarea>
	
	</br>
	</br>
	<div class="row">
		<label><b>Send To :</b></label>
		<input type="radio" name="chk_notify" value="0" class="RadCust" checked ><b>One Customer</b>
	 	<input type="radio" name="chk_notify" value="1" class="RadCust" ><b>Selected Customers</b>
	 	<input type="radio" name="chk_notify" value="2" class="RadCust" ><b>All Customers</b>
	</div>
	
	</br>
	
	<div id="OneCustDiv">
		<div class="row">
			<label><b>Customer</b></label>
			<select id="cust_id">
				<option> ---- Select a Customer --- </option>
				<?php foreach($CustData AS $Key=>$Row):?>
					<?php if(substr($Row['notify_enable'],0,1) == '0'):?>
						<option value="<?=$Row['cid'];?>"><?=$Row['fname'].' '.$Row['lname'];?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select>
			
		
		</div>
	</div>
	
	<div id="SelectCustDiv" style="display:none;">
		 <div class="CustContainer">
		 	<?php foreach($CustData AS $Key=>$Row):?>
		 		 <?php if(substr($Row['notify_enable'],0,1) == '0'):?>
		 		 	<input type="checkbox" id="<?=$Row['cid'];?>" class="ChkCust"/> <?=$Row['fname'].' '.$Row['lname'];?> <br />
	 			<?php endif;?>
	 		<?php endforeach;?>
		 </div>
	</div>
	
	<div id="AllCustDiv" style="display:none;">
		<div class="row">
			<label><u><b>Filters :</b></u></label></br></br>
			<label><b>Age</b></label>
			<select id="age_sign">
				<option value="=">=</option>
				<option value="<"><</option>
				<option value=">">></option>
			</select>
			<input id="age" style="width: 100px;"/> Years
		</div>
		</br>
		<div class="row">
			<label><b>Gender</b></label>
			<select id="gender">
				<option value=""> Select Gender </option>
				<option value="0">Male</option>
				<option value="1">Female</option>
			</select>
		</div>
	</div>
	
	</br>
	
	<div class="row buttons">
		<button id="BtnSendNotify">Send Notifications</button>
	</div>

<script>
	
	$('.RadCust').click(function() {
		
		$Val = this.value;
		
		if($Val == 0){$('#OneCustDiv').show();$('#SelectCustDiv,#AllCustDiv').hide();}
		if($Val == 1){$('#SelectCustDiv').show();$('#OneCustDiv,#AllCustDiv').hide();}
		if($Val == 2){$('#AllCustDiv').show();$('#SelectCustDiv,#OneCustDiv').hide();}
	  
	});
	
	$('#message_id').change(function() {
		
		if($('#message_id').val()!=''){
			
			$('#message_txt').val($('#message_id option:selected').text());
			
		}else{$('#message_txt').val('');}
	});
	
	$('#BtnSendNotify').click(function(e) {
		
		if($('#message_id').val() > 0){
			
			if($('#message_txt').val() != $('#message_id option:selected').text()){
				
				if(confirm(" This Message will Save As a New Message ")){
				
					SaveNewMess();
					
				}else{
					
					$('#message_id').change();
					
				}
				
			}else{
				
				FunSendNotify();
			}
		
		
		}else{
			
			if($.trim($('#message_txt').val())!=''){
				
				if(confirm(" This Message will Save As a New Message ")){
				
					SaveNewMess();
					
				}else{
					
					$('#message_txt').val('');
				}
				
			}else{
				
				alert('Choose Message');
				$('#message_id').focus();
			}
			
		}
	});
	
	function FunSendNotify(){
		
		$NotifyType = $('input:radio[name = chk_notify]:checked').val();
			
		var DA = {
			
			NotifyType:$NotifyType,
			MessID:$('#message_id').val(),
			MessTXT:$('#message_txt').val(),
		};
		
		if($NotifyType == 0){
			
			if($('#cust_id').val() > 0){
				
				DA.CustID = $('#cust_id').val();
				
			}else{
				
				DA.CustID = 0 ;
			}
		}
		
		if($NotifyType == 1){
			
			var ArrCustIDs =[];
			$('.ChkCust:checked').each(function() {
		        ArrCustIDs.push(this.id);
		    });
		   
		    DA.CustIDs = ArrCustIDs;
		}
		
		if($NotifyType == 2){
			
			var ArrFilters ={};
			
			if ($('#age').val() >= 0 && $('#age').val() != '') {
				
				ArrFilters['age']= $('#age_sign').val()+" "+$('#age').val();
				
			}
			if ($('#gender').val() != '') {
				
				ArrFilters['gender']= $('#gender').val();
			}
			
			DA.Filters = ArrFilters;
		}
		
		$.post('/index.php/customers/ajaxSendNotify',DA,function(data){
			
			if(data){
				
				alert('Sending Notifications Succeed');
				
			}else{
				  
				alert('Sending Notifications Failed');
			}
			
		});
	}
	
	function SaveNewMess () {
		
		var DA = {
	
			MessTXT:$('#message_txt').val()
		};
		
		$.post('/index.php/customers/ajaxSaveNewMess',DA,function(data){
			
			if(data > 0){
				
				$('#message_id').append('<option value="'+data+'" selected >'+$('#message_txt').val()+'</option>');
				FunSendNotify();
				
			}else{
				
				alert(' Try again ');
			}
			
			
		});
		
	}
</script>