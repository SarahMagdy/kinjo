<?php
  
?>

<h1>Onsite Commision Report</h1>
<br />
<label><b> Commision : <?=$DataRep['ONSc']?> %</b></label>
<!--<label style="margin-left: 150px;"><b> Due Date ( Today ) : <?=date('Y-m-d')?></b></label>-->
<br /><br />
<div id="OnSiteTRep">
	<ul>
		<li><a href="#tabs-1">Special Deals</a></li>
		<li><a href="#tabs-2">Packages</a></li>
	</ul>
	<div id="tabs-1">
		<?php $SpTotal = 0 ;?>
		<div id="SpDiv">
			<table id="Sp_T">
				<thead>
					<tr>
						<th> Owner Name </th>
						<th> Special Deal </th>
						<th> Billing Cycle </th>
						<th> LAST Pay Date </th>
						<th> Due Date </th>
						<th> Total Onsite Orders </th>
						<th> Commision </th>
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
								<td><?=$SProw['b_stats_OnSiteOrdTotal']?> $</td>
								<td><?=$SProw['Commision']?> $</td>
								<?php $SpTotal += $SProw['Commision'] ;?>
							</tr>
						<?php endforeach;?>
					<?php endif;?>
				</tbody>
			</table>
			
			<br />
			<label><b>Total Commision : <?=$SpTotal;?> $</b></label>
		</div>
	</div>
	<div id="tabs-2">
		<?php $PkgTotal = 0 ;?>
		<div id="PkgDiv">
			<table id="Pkg_T">
				<thead>
					<tr>
						<th> Bu Name </th>
						<th> Package </th>
						<th> Billing Cycle </th>
						<th> LAST Pay Date </th>
						<th> Due Date </th>
						<th> Total Onsite Orders </th>
						<th> Commision </th>
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
								<td><?=$Pkgrow['b_stats_OnSiteOrdTotal']?> $</td>
								<td><?=$Pkgrow['Commision']?> $</td>
								<?php $PkgTotal += $Pkgrow['Commision'] ;?>
							</tr>
						<?php endforeach;?>
					<?php endif;?>
				</tbody>
			</table>
			<br />
			<label><b>Total Commision : <?=$PkgTotal;?> $</b></label>
		</div>
	</div>
</div>
<br /><br />
<label><b>Total Commision : <?= $SpTotal + $PkgTotal;?> $</b></label>
<script>
	$(function() {
		$("#OnSiteTRep").tabs();
	});
</script>