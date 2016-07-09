
<h1>Pending Bills</h1>
<br />
<table>
	<thead>
		<tr>
			<th>Owner ID</th>
			<th>Owner Name</th>
			<th>Type</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if($Data['AccData']):?>
			<?php foreach($Data['AccData'] AS $AccKey=>$AccRow):?>
				<tr style="background: none repeat scroll 0 0 #E5F1F4">
					<td><?=$AccRow['AccID']?></td>
					<td><?=$AccRow['AccName']?></td>
					<td><?=$AccRow['AccType']?></td>
					<td><a href="/index.php/bills/paybill?OwnerID=<?=$AccRow['AccID']?>"><img alt="Bill" src="/assets/8626beb4/gridview/icon-dollar-small.png"></a></td>
				</tr>
			<?php endforeach?>
		<?php endif;?>
	</tbody>
</table>
