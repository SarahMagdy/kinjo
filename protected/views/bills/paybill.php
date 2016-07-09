<?php
/* @var $this BillsController */
/* @var $model Bills */

$this->breadcrumbs=array(
	'Bills'=>array('index'),
	'Pay Bills',
);

$this->menu=array(
	//array('label'=>'List Bills', 'url'=>array('index')),
);
?>
<style>
	.ToDay{color: green;}
	.NoPay{}
	tr.NoPay td button{display: none;}
	.GracePeriod{color: red;}
	.DelayFees{color: black;}
	
</style>

<h1>Pay Bills</h1>
<div id="Bill">
	<label><B>Owner ID : <span id="OwnerID"><?= $Data['Owner']['accid']?></span></B></label>
	<!--<input id="OwnerID" value="<?= $Data['Owner']['accid']?>" disabled style="width:170px;" />-->
	<label style="margin-left: 150px;"><B>Owner Name : <span id="OwnerName"><?= $Data['Owner']['fname'].' '.$Data['Owner']['lname']?></span></B></label>
	<!--<label>Owner Name</label>
	<input id="OwnerName" value="<?= $Data['Owner']['fname'].' '.$Data['Owner']['lname']?>" disabled style="width:200px;"/>-->
	<br /><br />
	<label id="currencyL"><B>Currency</B></label>                      
	<select id='currency'>
		<option value="">-- Currency --</option>
		<?php foreach($Data['CurrData'] AS $key=>$row):?>
			 <option value="<?=$row['currency_code']?>" <?php if($Data['Owner']['currency_code'] == $row['currency_code']):?>Selected<?php endif;?>><?php echo $row['currency_code'].' - '.$row['currency_name']; ?></option>
		<?php endforeach;?>
	</select>
	<?php if($Data['RoleID'] == '2'):?>
		<label><B>Credit Card</B></label>                      
		<select id='CreditCard' class='CreditCard'>
			<option value="">-- Credit Card --</option>
			<?php if(isset($Data['CrData'])):?>
				<?php foreach($Data['CrData'] AS $key=>$row):?>
					 <option value="<?=$row['CrID']?>"><?php echo $row['CrN']; ?></option>
				<?php endforeach;?>
			<?php endif;?>
		</select>
	<?php endif;?>
	<br /><br />
	<input id="SP_ID" value="<?=$Data['Owner']['special_deal_id']?>" hidden/>
	<input id="Curr_Code" value="<?=$Data['Owner']['currency_code']?>" hidden/>
	<input id="RoleID" value="<?=$Data['RoleID']?>" hidden/>
	<div>
		<!-- BU          -->
		<?php if($Data['Owner']['special_deal_id'] == 0):?>
			 <label><B>Store Packages</B></label>
			<br /><br />
			 <?php if(isset($Data['BuData'])):?>
				<div id="BuDiv" class="grid-view">
					<table id="BuData" class="items"> 
						<thead>
							<tr>
								<th>Store Name</th>
								<th>Package</th>
								<th>Amount</th>
								<th>Commision</th>
								<th>Extra Fees</th>
								<th>Delay Fees</th>
								<th colspan="2"><center>Total</center></th>
								<th>Due Date</th>
								<!--<th>Payment Date</th>-->
								<th></th>
								<th></th>
							</tr>
							<tr>
								<th colspan="6"></th>
								<th><center>USD</center></th>
								<th><center><span id="th_curr"><?=$Data['Owner']['currency_code']?></span></center></th>
								<th colspan="3"></th>
							</tr>
						</thead>	
						 <tbody>
						 	<?php foreach($Data['BuData'] AS $key=>$row):?>
						 		<tr class="<?=$row["class"];?>" style="background: none repeat scroll 0 0 #E5F1F4" id="<?=$row['BU_ID']?>">
						 			<td><a href="/index.php/businessUnit/<?=$row["BU_ID"];?>"><?=$row["BU_T"];?></a></td>
						 			<td><a href="/index.php/packages/<?=$row["Pkg_ID"];?>"><?=$row["Pkg_T"];?></a></td>
						 			<td class="am" amount="<?=$row['Pkg_Am']?>"><?=$row['Pkg_Am']?> $</td>
						 			<td id="co_<?=$row['BU_ID']?>" co="<?=$row['Commision']?> "><?=$row['Commision']?>  $</td>
						 			<td id="fe_<?=$row['BU_ID']?>" Fees="<?=$row['ExtraFees']?> "><?=$row['ExtraFees']?>  $</td>
						 			<td id="de_<?=$row['BU_ID']?>" delay="<?=$row["DelayFees"];?>" ><?=$row['DelayFees']?> $</td>
						 			<td id="dto_<?=$row['BU_ID']?>" dtotal="<?=$row['$Total']?>"><?=$row['$Total']?> $</td>
						 			<td id="to_<?=$row['BU_ID']?>" ctotal="<?=$row['CTotal']?>"><?=$row['CTotal'].' '.$Data['Owner']['currrency_symbol']?></td>
						 			<td id="due_<?=$row['BU_ID']?>" due="<?=$row["DueDate"];?>"><?=$row["DueDate"];?></td>
						 			<td><button id="Btn_<?=$row["BU_ID"];?>" BUID="<?=$row["BU_ID"];?>" class="BuPay">Pay</button></td>
						 			<td><?=$row["class"];?></td>
						 		</tr>
						 	<?php endforeach;?>
						 </tbody>
					</table>
				</div>	
			 <?php endif;?>
		<!-- SP          -->
		<?php else:?>
			 <label><B>Special Deal</B></label>
			 <br/>
			 <?php if(isset($Data['SpData'])):?>
			 	<div style="background: none repeat scroll 0 0 #E5F1F4;padding: 10px;">
			 		<b>Special Deal Title :</b><span><?=$Data['SpData']['sp_d_title']?></span><br />
			 		<b>Billing Cycle:</b><span> <?=$Data['SpData']['Bill_C']?></span><br />
			 		<b>Status :</b><span> <?=$Data['SpData']['class']?></span><br />
			 		<b>Due Date :</b><span id="DueDate" class="<?=$Data['SpData']['class']?>"> <?=$Data['SpData']['DueDate']?></span><br />
			 		<!--<b>Payment Date :</b><span id="PaymentDate"> <?php echo date('Y-m-d')?></span><br />-->
			 		<b>Special Deal Description :</b><span> <?=$Data['SpData']['sp_d_description']?></span><br />
			 		<br />
			 		<b>Special Deal Amount :</b><span> <?=$Data['SpData']['sp_d_amount']?></span> $<br />
			 		<b>Extra Fees : </b><span id="Sp_extrafees"> <?=$Data['SpData']['ExtraFees']?> </span> $<br />
			 		<b>Delay Fees : </b><span id="Sp_delayfees"> <?=$Data['SpData']['DelayFees']?> </span> $<br />
			 		<b>OnSite Commision : </b><span id="Sp_OnSiteCommision"> <?=$Data['SpData']['OnSiteCommision']?> </span> $<br />
			 		<br />
			 		<b>Total : </b><span id="Sp_curramount_dollor"> <?=$Data['SpData']['dollor_total']?> </span> $<br />
			 		<b>Total : </b><span id="Sp_curramount"> <?=$Data['TotalBill']?> </span> <span id="Sp_curr_s"><?=$Data['Owner']['currrency_symbol']?></span><br />
			 	</div>
			 <?php else:?>
			 	
			 	<br /><center><div style="background: none repeat scroll 0 0 #E5F1F4;padding: 10px;"><B>No Bill</B></div></center><br />
			 	
			 <?php endif;?>
		<?php endif;?>
	</div>	

	<?php if($Data['RoleID'] == '1'):?>
		<label><B>Notes</B></label><br />
		<textarea id="notes" cols="100" rows="4"></textarea>
	<?php else:?>
		<input id="notes" hidden/>
	<?php endif;?>
	<br />
	<!--<label>Amount</label><input id="Amount" value="<?=$Data['TotalBill']?>" disabled style="width:175px;" />-->
	<label><B> Amount : <span id="Amount"><?= $Data['TotalBill']?></span> <span id="Amount_s"><?=$Data['Owner']['currrency_symbol']?></span></B></label>
	<br /><br />
	<!--<label>Discount</label>
	<input id="Discount" value="<?=$Data['Disc']?>" disabled style="width:175px;" />-->
	<label><B> Discount : <span id="Discount"><?= $Data['Disc']?></span> %</B></label>
	<br /><br />
	<!--<label>Final Total</label>
	<input id="finalTotal" value="<?=$Data['finalTotal']?>" disabled style="width:175px;" />-->
	<label><B> Final Total : <span id="finalTotal"><?= $Data['finalTotal']?></span> <span id="finalTotal_s"><?=$Data['Owner']['currrency_symbol']?></span></B></label>
	<br /><br />
	<label><B> Final Total by Dollor: <span id="dfinalTotal"><?= $Data['dfinalTotal']?></span> $</span></B></label>
	<br /><br />
	<button id="btn_bill"><B>Submit And Pay Bill</B></button>
</div>

<div id="CreditChD" title="Choose Credit" style="display: none;">
	Choose Credit Card Or Pay On The Fly
</div>

<div id="CreditFrmD" style="display: none;">
	<form>
        <label>
            <span>Card Number</span>
        </label>
        <br />
        <input id="ccNo" type="text" size="20" value="" /><!-- autocomplete="off" -->
  		<br />
        <label>
            <span>Expiration Date</span>
        </label>
        <br />
        <input type="text" size="2" id="expMonth" />
        <span> / </span>
        <input type="text" size="4" id="expYear" />
    	<br />
        <label>
            <span>CVV</span>
        </label>
        <br />
        <input id="cvv" size="4" type="text" value="" autocomplete="off"  />
	</form>
	
</div>


<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script>
	var PType = '';
	
	$(document).ready(function() {
		
		ClearCreditFrm();
		
		if($('#finalTotal').html() <= 0){$('#currency,#currencyL').hide();}
		
		if($('#SP_ID').val() == 0){
			
			if($('tr').hasClass('ToDay')||$('tr').hasClass('GracePeriod')||$('tr').hasClass('DelayFees')){
			
				$('#btn_bill').show();
				
			}else{
				
				$('#btn_bill').hide();
			}
		}
		if($('#SP_ID').val() > 0){
			
			if($('#DueDate').hasClass('ToDay')||$('#DueDate').hasClass('GracePeriod')||$('#DueDate').hasClass('DelayFees')){
			
				$('#btn_bill').show();
				
			}else{
				
				$('#btn_bill').hide();
			}
		}
	
	});
	
	$('#currency').change(function(e){
		
		if($('#currency').val()!= ''){
		
			if($('#currency').val()!= $('#Curr_Code').val()){
				if($('#SP_ID').val() == 0){
					$('.BuPay,#btn_bill').attr('disabled',true);
					BuFunc();
				}
				if($('#SP_ID').val() > 0){
					$('#btn_bill').attr('disabled',true);
					SpFunc();
				}
				
			}
		}else{
			$('#currency').val($('#Curr_Code').val());
		}
		
	});
	
	function BuFunc() {
	  
	  	var DaArr = {};
		$('#BuData tbody tr').each(function() {
			var BUID = $(this).attr('id');
			DaArr[BUID]= $('td#dto_'+BUID).attr('dtotal');
		});
		var Da = {
			
			DaArr:DaArr,
			Frm :'USD',
			to :$('#currency').val()
		};
		
		$.post('/index.php/bills/ajaxChBuBillCurr',Da,function(data){
			$('#Curr_Code').val($('#currency').val());
			var Total = 0;
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
				for (var key in end_data){
					$('td#to_'+end_data[key]['BUID']).attr('ctotal',end_data[key]['NewTotal']);
					$('td#to_'+end_data[key]['BUID']).html(end_data[key]['NewTotal']+' '+end_data[key]['Symbol']);
					
					if($('tr#'+end_data[key]['BUID']).hasClass('ToDay')||$('tr#'+end_data[key]['BUID']).hasClass('GracePeriod')||$('tr#'+end_data[key]['BUID']).hasClass('DelayFees')){
						Total = Total + parseFloat(end_data[key]['NewTotal']);
					}
					
					$('#Amount_s,#finalTotal_s').html(end_data[key]['Symbol']);
				}
				$('#th_curr').html($('#currency').val());
				$('#Amount').html(Total.toFixed(2));
				var Disc = ((Total * parseFloat($('#Discount').html()))/100).toFixed(2);
				$('#finalTotal').html((Total - Disc ).toFixed(2));
				$('.BuPay,#btn_bill').attr('disabled',false);
				
			}
			
		});
	}
	
	function SpFunc () {
	  
		/*
		$.get('/index.php/API/GetConvertCurrency?Frm='+$('#Curr_Code').val()+'&&To='+$('#currency').val()+'&&Val='+$('#Sp_curramount').html(),function(data){
					$('#Curr_Code').val($('#currency').val());
					$('#Sp_currcode').html($('#Curr_Code').val());
					var json_data = data.toString();
					var end_data = $.parseJSON(json_data);
					var Total = 0;
					Total = parseFloat(end_data['ValTo']);
					Total = Math.round(Total * 100) / 100;
					$('#Sp_curramount').html(Total);
					$('#Amount').val(Total);
					$('#finalTotal').val(Total - parseFloat($('#Discount').val()));
				});*/
		
		
		var Da = {
			
			Amount:$('#Sp_curramount_dollor').html(),
			//Frm :$('#Curr_Code').val(),
			Frm :'USD',
			to :$('#currency').val()
		};
		
		$.post('/index.php/bills/AjaxChSpBillCurr',Da,function(data){
			
			$('#Curr_Code').val($('#currency').val());
			$('#Sp_currcode').html($('#Curr_Code').val());
			var json_data = data.toString();
			var end_data = $.parseJSON(json_data);
			var Total = 0;
			Total = parseFloat(end_data['CurrVal']);
			Total = Math.round(Total * 100) / 100;
			$('#Sp_curramount').html(Total);
			$('#Sp_curr_s,#Amount_s,#finalTotal_s').html(end_data['Symbol']);
			$('#Amount').html(Total);
			$('#finalTotal').html(Total - parseFloat($('#Discount').html()));
			$('#btn_bill').attr('disabled',false);
			
		});
		
	}
	
	$('#btn_bill').click(function(e){
		
		if($('#SP_ID').val() == 0){
			
			PType = 'SubmitBuBill';
			//SubmitBuBill();
		}
		if($('#SP_ID').val() > 0){
			
			PType = 'SubmitSpBill';
			//SubmitSpBill();
		}
		
		if($('#RoleID').val() == '1'){
			if(PType == 'SubmitBuBill'){
				SubmitBuBill(0);
			}
			if(PType == 'SubmitSpBill'){
				SubmitSpBill(0);
			}
		}
		if($('#RoleID').val() == '2'){
			GetCreditData();
		}
		
	});
	
	$('.BuPay').click(function(e) {
		
		PType = 'SubmitBuPay';
		
		$('.BuPay').removeClass('clicked');
		
		$(this).addClass('clicked');
		
		if($('#RoleID').val() == '1'){
			SubmitBuPay(0);
		}
		if($('#RoleID').val() == '2'){
			GetCreditData();
		}
		
		/*
		if(confirm('Are You Sure')){
					
					var BUID = $(this).attr('BUID');
					
					var Da = {
						Type:0,
						OwnerID:$('#OwnerID').val(),
						BUSPID:BUID,
						currency:$('#currency').val(),
						RowTotal:$('td#to_'+BUID).attr('amount'),
						Disc:0,
						DueDate:$('td#due_'+BUID).attr('due'),
						ExtraFees:$('td#fe_'+BUID).attr('Fees'),
						DelayFees:$('td#de_'+BUID).attr('delay')
					};
										  $.post('/index.php/bills/AjaxSubmitPayBill',Da,function(data){
						
						location.reload();
					});
			
				}*/
		
		//else{
			
		//}	
	});
	
	function SubmitBuPay ($Token) {
	  
	   var BUID = $('button.clicked').attr('BUID');
		var Da = {
			Type:0,
			OwnerID:$('#OwnerID').html(),
			BUSPID:BUID,
			currency:$('#currency').val(),
			RowTotal:$('td#to_'+BUID).attr('ctotal'),
			dRowTotal:$('td#dto_'+BUID).attr('dtotal'),
			Disc:0,
			DueDate:$('td#due_'+BUID).attr('due'),
			ExtraFees:$('td#fe_'+BUID).attr('Fees'),
			DelayFees:$('td#de_'+BUID).attr('delay'),
			Commision:$('td#co_'+BUID).attr('co'),
			Token:$Token,
			Notes:$('#notes').val() 
		};
		$.post('/index.php/bills/AjaxSubmitPayBill',Da,function(data){
			
			//location.reload();
			data = data.trim();
			if(data == 'TRUE'){
				
				alert('Payment succeeded');
				location.reload();
				
			}else{
				
				alert(data);
			}
		});
	}
	
	function SubmitBuBill ($Token) {
	  
	   var Arrpay = [];
	   
	   $('#BuData tr').each(function () {
	   		
	   		if($(this).hasClass('ToDay')||$(this).hasClass('GracePeriod')||$(this).hasClass('DelayFees')){
		   	
		   		var SArrpay = {};
		   		var BUID = $(this).attr('id');
				SArrpay['BUSPID'] = BUID;
				SArrpay['RowTotal'] = $('td#to_'+BUID).attr('ctotal');
				SArrpay['DueDate'] = $('td#due_'+BUID).attr('due');
				SArrpay['ExtraFees'] = $('td#fe_'+BUID).attr('Fees');
				SArrpay['DelayFees'] = $('td#de_'+BUID).attr('delay');
				SArrpay['Commision'] = $('td#co_'+BUID).attr('co');
				/*
				   SArrpay.push({
					  // BUSPID:BUID,
					   currency:$('#currency').val(),
					   RowTotal:$('td#to_'+BUID).attr('amount'),
					   DueDate:$('td#due_'+BUID).attr('due'),
					   ExtraFees:$('td#fe_'+BUID).attr('Fees'),
					   DelayFees:$('td#de_'+BUID).attr('delay')
				   });*/
				Arrpay.push(SArrpay);
		     }
	   });
	   
	   var Da = {
	   		Type:0,
			OwnerID:$('#OwnerID').html(),
	   		Arrpay:Arrpay,
	   		Disc:$('#Discount').html(),
	   		TotalBill:$('#finalTotal').html(),
	   		dTotalBill:$('#dfinalTotal').html(),
	   		currency:$('#currency').val(),
	   		Token:$Token,
	   		Notes:$('#notes').val()
	   };
	  
	  	$.post('/index.php/bills/AjaxSubmitPayBillBUAll',Da,function(data){
				
			//location.reload();
			data = data.trim();
			if(data == 'TRUE'){
				
				alert('Payment succeeded');
				location.reload();
				
			}else{
				
				alert(data);
			}
		});
	}
	
	function SubmitSpBill ($Token){
	
		if(confirm('Are You Sure')){
			
			var Da = {
				Type:1,
				OwnerID:$('#OwnerID').html(),
				BUSPID:$('#SP_ID').val(),
				currency:$('#currency').val(),
				RowTotal:$('#finalTotal').html(),
				dRowTotal:$('#dfinalTotal').html(),
				Disc:$('#Discount').html(),
				DueDate:$('#DueDate').html(),
				ExtraFees:$('#Sp_extrafees').html(),
				DelayFees:$('#Sp_delayfees').html(),
				Commision:$('#Sp_OnSiteCommision').html(),
				Token:$Token,
				Notes:$('#notes').val() 
			};
			
		 	$.post('/index.php/bills/AjaxSubmitPayBill',Da,function(data){
				
				//location.reload();
				data = data.trim();
				if(data == 'TRUE'){
				
					alert('Payment succeeded');
					location.reload();
					
				}else{
					
					alert(data);
				}
			});
		}
	}
	
	/*
	function GetCreditData () {
			
			if($('.CreditCard').val() > 0){
				
				var d ={ CardID:$('.CreditCard').val() };
				
				$.post('/index.php/bills/AjaxGetCreditData',d,function(data){
					var json_data = data.toString();
					if(json_data.length > 10){
						var end_data = $.parseJSON(json_data);
						// Setup token request arguments
						var args = {
							sellerId: "901262532",
							   publishableKey: "A72E8DDE-D8B9-4D84-AF5F-B3D546D1589C",
							ccNo: end_data['Credit'],
							cvv: end_data['Cvv'],
							expMonth: end_data['MonthExp'],
							expYear: end_data['YearExp']
						};
										 // Make the token request
					   TCO.requestToken(successCallback, errorCallback, args);
										   //alert('Res'+Res);
					}
				});
			
			}else{
				
				alert('Choose Credit Card ');
				$('.CreditCard').focus();
				return 0 ;
			}
			
			
		}*/
	
	// Called when token created successfully.
   function NotSavedCredit(){
   	
		$( "#CreditChD" ).dialog({
			 closeOnEscape: false,
			 buttons: {
			 "Choose": function() {
				$( this ).dialog( "close" );
				$('.CreditCard').focus();
			 },
			 "On The Fly": function() {
				OnTheFlyCredit();
				$( this ).dialog( "close" );
			 }
			}
		});
		$("#CreditChD").siblings('div.ui-dialog-titlebar').remove();
	
   }
     
    function OnTheFlyCredit(){
    	
    	$( '#CreditFrmD' ).dialog({
			
			 buttons: {
			 "Cancel": function() {
				ClearCreditFrm();
				$( this ).dialog( "close" );
			 },
			 "Submit": function() {
				var args = {
						sellerId: "901262532",
					    publishableKey: "A72E8DDE-D8B9-4D84-AF5F-B3D546D1589C",
						ccNo: $('#ccNo').val(),
						cvv: $('#cvv').val(),
						expMonth: $('#expMonth').val(),
						expYear: $('#expYear').val()
					};
								 // Make the token request
				   TCO.requestToken(successCallback, errorCallback, args);
				   ClearCreditFrm();
				   $( this ).dialog( "close" );
			 }
			}
		});
    }
    
    var successCallback = function(data) {
        //var myForm = document.getElementById('myCCForm');

        // Set the token as the value for the token input
        //myForm.token.value = data.response.token.token;
		
        // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
        // myForm.submit();
        
        var Token = data.response.token.token;
        if(Token != ''){
	        if(PType == 'SubmitBuBill'){SubmitBuBill(Token);}
	        if(PType == 'SubmitSpBill'){SubmitSpBill(Token);}
	        if(PType == 'SubmitBuPay'){SubmitBuPay(Token);}
	    }
    };

    // Called when token creation fails.
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
		//alert('on load');
       // $("#myCCForm").submit(function(e) {
            // Call our token request function
          //  tokenRequest();

            // Prevent form from submitting
            return false;
        //});
    });
    
    function ClearCreditFrm(){
    	$('#ccNo,#cvv,#expMonth,#expYear').val('');
    }
</script>