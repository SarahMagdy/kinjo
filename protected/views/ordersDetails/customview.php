<?php

$this->breadcrumbs=array(
	'Orders Details'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Orders Grid', 'url'=>array('CustomGrid')),
	array('label'=>'Close Orders', 'url'=>array('CloseOrder')),
);

?>
<table> 
	<head>
		<th>Order ID</th>
		<th colspan="3">Customer</th>
		<th>Opened or Closed</th>
		<th>Order Total</th>
		<th></th>
	</head>
	<body>
		<?php if(isset($ResData)):?>
			<?php foreach ($ResData as $key => $row):?>
				<tr>
					<td><?=$row["ordid"];?></td>
					<td colspan="3"><?=$row['custname'];?></td>
					<td><?=$row['ord_type'];?></td>
					<td><?=$row['ord_total'];?></td>
					<td> <a href="#" class="detail" ordid="<?=$row['ordid'];?>" id="detail_<?=$row['ordid'];?>"> <img src="/assets/8626beb4/gridview/detail-icons.png" alt="Details" height="20" width="20"> </a></td>
				</tr>
					<?php if(isset($row['details'])):?>
							<tr class="tr_<?=$row['ordid'];?>" style="display:none;">
								<th>ID</th>
								<th>Product</th>
								<th>Quantity</th>
								<th>Discount</th>
								<th>Price</th>
								<th>Fees</th>
								<th>Final Price</th>
							</tr>
						<?php foreach ($row['details'] as $dkey => $drow):?>
							<tr class="tr_<?=$row['ordid'];?>" style="display:none;">
								<td><?=$drow['detail_id'];?></td>
								<td><?=$drow['item'];?></td>
								<td><?=$drow['qnt'];?></td>
								<td><?=$drow['disc'];?></td>
								<td><?=$drow['price'];?></td>
								<td><?=$drow['fees'];?></td>
								<td><?=$drow['final_price'];?></td>
							</tr>
						<?php endforeach;?>	
					<?php endif;?>
				
			<?php endforeach;?>
		<?php endif;?>
	</body>
</table>

<?php // display pagination
	$this->widget('CLinkPager', array('pages'=>$pages,));
	$this->widget('CListPager', array('pages'=>$pages,));
?>

<script>

	$('.detail').click(function(e){
		
		var OrdID = $(this).attr('ordid');
		
		if ($('.tr_'+OrdID).is(":visible")) {
		
			$('.tr_'+OrdID).hide();
		  		
		} else{
		  	
			$('.tr_'+OrdID).show();
		}
		
	});
	
	
</script>