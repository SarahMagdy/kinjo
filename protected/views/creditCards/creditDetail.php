<?php
/* @var $this CreditCardsController */
/* @var $model CreditCards */

$this->breadcrumbs=array(
	'Credit Cards'=>array('index'),
	$model->cr_card_id=>array('view','id'=>$model->cr_card_id),
	'Credit Card Details',
);

$this->menu=array(
	array('label'=>'List CreditCards', 'url'=>array('index')),
	array('label'=>'Create CreditCards', 'url'=>array('create')),
	array('label'=>'View CreditCards', 'url'=>array('view', 'id'=>$model->cr_card_id)),
	array('label'=>'Manage CreditCards', 'url'=>array('admin')),
);
?>


<h1>Credit Card Action  #<?php echo $model->cr_card_id; ?></h1>
</br>

	<input hidden type="text" name="CrCardID" value="<?php echo $model->cr_card_id;?>" id="CrCardID" class="CreditCard"/>
	
	<label><b>Currency</b></label>
	<select id='currency'>
		<option value="">-- Currency --</option>
		<?php foreach($Data['CurrData'] AS $key=>$row):?>
			 <option value="<?=$row['currency_code']?>" ><?php echo $row['currency_code'].' - '.$row['currency_name']; ?></option>
		<?php endforeach;?>
	</select>
	
	</br></br>
	<label><b>Amount &nbsp </b></label>
	<!-- <select id='Cr_action' name="Cr_action" required>
		<option value=""> + / -</option>
		<option value="+" > + </option>
		<option value="-" > - </option>
	</select> -->

	<input id="Cr_val" name="Cr_val" type="text" value="" />
	
	
	
	
	<button id="submitCrVal" name="submitCrVal" style="height:26px;background-color: #E5F1F4;alignment-baseline: central;">Transfere</button>
	


	</br> </br>
	
	<div class="grid-view">
		<table class="items">
			<thead>
				<tr>
					<!--<th style="width: 10%;"></th>-->
					<th>ID</th>
					<th>Credit Vlaue</th>
					<!-- <th>Credit Type</th> -->
					<th>Date</th>
				</tr>
			</thead>
			
			<tbody>
				<?php foreach($DetailArr AS $key=>$row):?>
					<tr style="background: none repeat scroll 0 0 #E5F1F4">
						<!--<td style="padding-left: 5%;"><a class="del_color" id="<?=$row['cr_d_id'];?>" title="Delete" href="#"><img src="/assets/8626beb4/gridview/DeleteRed.png" alt="Delete"></a>
				 		</td>-->
						<td><?php echo $row['cr_d_id']?></td>
						<td><?php echo $row['cr_d_val']?></td>
						<!-- <td><?php echo $row['cr_d_type']?></td> -->
						<td><?php echo $row['cr_d_date']?></td>
					</tr>
				<?php endforeach;?>
			</tbody>
			<tfoot>
				<tr>
					
					<td><b>Total</b></td>
					<!-- <td></td> -->
					
					<td colspan="2" style="color:red;text-align:center" ><b><?= $total; ?></b></td>
				</tr>
			</tfoot>
		</table>
		
		
	</div>
	


<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script>
	
	$('#submitCrVal').click(function(e){
		
		GetCreditData();
		//----------------- ON Success (successCallback) -----
		
	});
	
	
	var successCallback = function(data) {     
        var Token = data.response.token.token;
        // alert(Token);
        
        
        var data ={
			Cr_val   : $('#Cr_val').val(),
			CrCardID : $('#CrCardID').val(),
			currency : $('#currency').val(),
			Token    : Token
		}; 
		if($('#Cr_val').val() != '' && $('#Cr_val').val() > 0){
			$.post( "/index.php/CreditCards/ajaxAddValue/"+$('#CrCardID').val() , data, function( data ) {
				// location.reload();
			});
		}else{
			alert('Please Insert Correct value');
			$('#Cr_val').focus();
		}
        
    };
	
	var errorCallback = function(data) {
        if (data.errorCode === 200) {
        //    tokenRequest();
     	   alert('Try Again');
        } else {
           alert(data.errorMsg);
        }
       
    };
	
	$(function() {
        // Pull in the public encryption key for our environment
		TCO.loadPubKey('sandbox');
		return false;
    });	
	
	
		
</script>








