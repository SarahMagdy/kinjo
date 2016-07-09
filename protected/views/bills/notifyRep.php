<?php
  
?>

<h1>Notification Report</h1>
<br />
<div id="NotifyTRep">
	<ul>
		<li><a href="#tabs-1">Special Deals</a></li>
		<li><a href="#tabs-2">Packages</a></li>
	</ul>
	<div id="tabs-1">
		<div id="SpDiv">
			<table id="Sp_T">
				<thead>
					<tr>
						<th> Owner Name </th>
						<th> Special Deal </th>
						<th> Billing Cycle </th>
						<th> LAST Pay Date </th>
						<th> Due Date </th>
						<th> Notification Count </th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($DataRep['SPDataRep'])):?>
						<?php foreach($DataRep['SPDataRep'] AS $SPkey=>$SProw):?>
							<tr>
								<td><?=$SProw['b_stats_BuName']?></td>
								<td><?=$SProw['b_stats_PkgN']?></td>
								<td><?=$SProw['Duration']?></td>
								<td><?=$SProw['b_stats_bill_LASTDate']?></td>
								<td><?=$SProw['b_stats_DueDate']?></td>
								<td><?=$SProw['b_stats_GCMCount']?></td>
							</tr>
						<?php endforeach;?>
					<?php endif;?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="tabs-2">
		<div id="PkgDiv">
			<table id="Pkg_T">
				<thead>
					<tr>
						<th> Bu Name </th>
						<th> Package </th>
						<th> Billing Cycle </th>
						<th> LAST Pay Date </th>
						<th> Due Date </th>
						<th> Notification Count </th>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($DataRep['PkgDataRep'])):?>
						<?php foreach($DataRep['PkgDataRep'] AS $Pkgkey=>$Pkgrow):?>
							<tr>
								<td><?=$Pkgrow['b_stats_BuName']?></td>
								<td><?=$Pkgrow['b_stats_PkgN']?></td>
								<td><?=$Pkgrow['Duration']?></td>
								<td><?=$Pkgrow['b_stats_bill_LASTDate']?></td>
								<td><?=$Pkgrow['b_stats_DueDate']?></td>
								<td><?=$Pkgrow['b_stats_GCMCount']?></td>
							</tr>
						<?php endforeach;?>
					<?php endif;?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(function() {
		$("#NotifyTRep").tabs();
	});
</script>