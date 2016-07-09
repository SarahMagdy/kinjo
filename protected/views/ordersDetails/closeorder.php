<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */

$this->breadcrumbs=array(
	'Orders Details'=>array('index'),
	'Close Orders',
);

$this->menu=array(
	//array('label'=>'List OrdersDetails', 'url'=>array('index')),
	//array('label'=>'Create OrdersDetails', 'url'=>array('create')),
	array('label'=>'View Orders', 'url'=>array('CustomView')),
	array('label'=>'Orders Grid', 'url'=>array('CustomGrid')),
);
?>

<h1>Close Orders</h1>

<table>
	<thead>
		<th>Order ID</th>
		<th>Customer Name</th>
		<th>Total</th>
		<th>App Type</th>
		<th></th>
		<th>Select</th>
		<th>Delivery Boy</th>
	</thead>
	<tbody>
		<?php if(isset($Data['OData'])):?>
			<?php foreach($Data['OData'] AS $key=>$row):?>
				<tr style="background: none repeat scroll 0 0 #E5F1F4">
					<td><?=$row['OrdID']?></td>
					<td><?=$row['CustName']?></td>
					<td><?=$row['BuTotal'].' '.$row['CurrS']?></td>
					<td><?=$row['AppType']?></td>
					<td><a href="#" class="close_ord" OrdID = "<?=$row['OrdID']?>"> <img src="/assets/8626beb4/gridview/Lock_icon.png" alt="Close Order" height="20" width="20"> </a></td>
					<td style="text-align:center;">
						<?php $checked = ''; $DBCls = 'chk_DB';
							if($row['has_DB'] == 'TRUE'){
								$checked = 'checked';
								$DBCls = '';
							}
						 ?>
						<input type="checkbox" class="CHK_Box <?=$DBCls;?>" value="<?=$row['OrdID']?>" ordAssignID="<?=$row['ord_assign_id']?>" <?=$checked;?> >
					</td>
					<td><?=$row['DB_name'];?></td>
  						
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>

<br/>

<select id="delivery_boy">
	<option value=""> -- Choose Delivery Boy -- </option>
	<?php foreach($Data['DData'] AS $key=>$row):?>
		<option value="<?=$row['cp_id']?>"><?=$row['fname'].' '.$row['lname']?></option>
	<?php endforeach;?>
</select>

<button id="btn_assign">Assign</button>


<script>
	$('.close_ord').click(function(){
		
		if(confirm('Are You Sure , You Want Close This Order ')){
		
			var OrdID = {OrdID:$(this).attr('OrdID')};
			
			$.post('/index.php/ordersDetails/AjaxCloseOrder',OrdID,function(data){
				
				data = data.trim();
				
				if(data == 'True'){
					
					alert('Order Closed');
					location.reload();
				
				}else{
					
					alert('Try Again');
				}
			});
		}
	});
	
	$('#delivery_boy').change(function(e){
		e.preventDefault();
		if($('#delivery_boy').val() != ''){
			$('#btn_assign').text('Assign To '+ $("#delivery_boy option:selected").text());
		}else{
			$('#btn_assign').text('Assign');
		}		
	});
	
	$('#btn_assign').click(function(eve){
		eve.preventDefault();
		
		var chk_orders = [];
		$(".chk_DB:checked").each(function() {
		    chk_orders.push(this.value);
		});
		
		if (typeof chk_orders !== 'undefined' && chk_orders.length > 0) {
		    // the array is defined and has at least one element
		    if($('#delivery_boy').val() != ''){
		    	var p = {
			    		ordArr : chk_orders,
			    		DB_ID  : $('#delivery_boy').val()
			    	};
			    $.post('/index.php/ordersDetails/AjaxAssignToDB',p,function(data){
					location.reload();
				});
		    }else{
		    	alert('Please Choose Delivery Boy !');
		    }
		    
		}else{
			alert('Please Choose Order To be Assigned');
		}
		
	});
	
	
	$(".CHK_Box").on('change',function()
	{
		if(!$(this).is(':checked')){
			var p = { ord_assign_id : $(this).attr("ordAssignID") };
			$.post('/index.php/ordersDetails/AjaxDeleteDBAssign',p,function(data){
				location.reload();
			}); 
		}
	}); 


	
</script>


