
<table> 
	<head>
		<th>Order Detail ID</th>
		<th>Order ID</th>
		<th>Order Type</th>
		<th>Product Name</th>
		<th>Customer</th>
		<!--<th>Quantity</th>
		<th>Price</th>
		<th>Fees</th>
		<th>Discount</th>-->
		<th>Final Price</th>
		<th>Close Date</th>
		<th></th>
	</head>
	<body>
		<?php if(isset($ResData)):?>
			<?php foreach ($ResData as $key => $row):?>
				<tr>
					<td><?=$row["ord_det_id"];?></td>
					<td><?=$row["ordid"];?></td>
					<td><?=$row['ord_type'];?></td>
					<td><a href="/index.php/products/<?=$row["ProID"];?>"><?=$row["ProName"];?></a></td>
					<td><a href="/index.php/customers/<?=$row["custid"];?>"><?=$row["custname"];?></a></td>
					<!--<td><?=$row['qnt'];?></td>
					<td><?=$row['price'];?></td>
					<td><?=$row['fees'];?></td>
					<td><?=$row['disc'];?></td>-->
					<td><?=$row['final_price'];?></td>
					<td><?=$row['CloseDate'];?></td>
					<td> <a href="/index.php/OrdersDetails/<?=$row["ord_det_id"]?>" > <img src="/assets/8626beb4/gridview/view.png"> </a></td>
				</tr>
			<?php endforeach;?>
		<?php endif;?>
	</body>
</table>
<?php // display pagination
	//$this->widget('CLinkPager', array('id'=>'link_pager','pages'=>$pages,));
	$this->widget('CListPager', array('id'=>'list_pager','pages'=>$pages,));
?>
<script>
	
	$('#list_pager').change(function(e){
		
	    e.preventDefault();
		CurrentPage = $('#list_pager option:selected').text();
		
		var d ={
			
			ord_det_id:$('#ord_det_id').val(),
			ord_id:$('#ord_id').val(),
			pid:$('#pid').val(),
			from:$('#from').val(),
			to:$('#to').val(),
			open:'Search',
			page:$('#list_pager option:selected').text()
			
		};
		
		$.post('/index.php/ordersDetails/AjaxCustomGrid',d,function(data){
			
			$('#GridDiv').html(data);
			$("#list_pager").removeAttr("onchange");
			$("#list_pager option").prop("value", "");
			//$("#list_pager option[value = '" + CurrentPage + "'").attr('selected', 'selected');
			$("#list_pager option:contains("+CurrentPage+")").attr('selected', true);
			
		});
		
	});
	
</script>
