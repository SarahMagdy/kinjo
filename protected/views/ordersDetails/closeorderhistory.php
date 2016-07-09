<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */

$this->breadcrumbs=array(
	'Orders Details'=>array('index'),
	'Close Orders History',
);

?>

<?php //var_dump($Res);?>

<select id="user">
	<option value=""> -- Choose Delivery Boy -- </option>
	<option value="<?=$Res['OData']['cp_id']?>" <?php if($Res['cp_id'] == $Res['OData']['cp_id']):?>  selected <?php endif;?>  > <?=$Res['OData']['fname'].' '.$Res['OData']['lname']?> </option>
	<?php foreach($Res['DData'] AS $key=>$row):?>
		<option value="<?=$row['cp_id']?>" <?php if($Res['cp_id'] == $row['cp_id']):?>  selected <?php endif;?> > <?=$row['fname'].' '.$row['lname']?> </option>
	<?php endforeach;?>
</select>

<br/><br/>


<table>
	<thead>
		<th>Order ID</th>
		<th>Customer Name</th>
		<th>Total</th>
		<!-- <th>App Type</th> -->
		<th>Close Date</th>
		<th>User Name</th>
	</thead>
	<tbody>
		
		<?php if(isset($Res['history'])):?>
			<?php foreach($Res['history'] AS $key=>$row):?>
				<tr style="background: none repeat scroll 0 0 #E5F1F4">
					<td><?=$row['ord_bu_total_ord_id']?></td>
					<td><?=$row['CustName']?></td>
					<td><?=$row['ord_bu_total_total']?></td>
					<td><?=$row['ord_bu_total_close_date']?></td>
					<td><?=$row['cpanel_name']?></td>
					
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		
	</tbody>
</table>



<script>
	$('#user').change(function(e){
		e.preventDefault();
		
		location.href = "/index.php/ordersDetails/OrdAssignsHistory?cp_id="+$('#user').val();
		// $.get('/index.php/ordersDetails/OrdAssignsHistory?cpanelID=1',function(data){
			// location.reload();
		// }); 
	});
</script>



