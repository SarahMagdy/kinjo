<?php
	$RoleID = 0;$UserID = 0;
	if(isset($_SESSION['User'])){
			
		if(isset($_SESSION['User']['UserRoleID']) && !empty($_SESSION['User']['UserRoleID']) && $_SESSION['User']['UserRoleID'] > 0){
			
			$RoleID = $_SESSION['User']['UserRoleID'];
			$UserID = $_SESSION['User']['UserID'];
			$OwnerID = $_SESSION['User']['UserOwnerID'];
		}
	}
	//Yii::t($langFile, 'Menu_Products');
?>
<?php if($RoleID > 0):?>
	<ul id="menu" style="width=100%;/*margin:5px 10px 10px 20px;*/">
		<?php if($RoleID == 1):?>
			<li><a href="/index.php/auth/AdminHome" class="Adhome">Home</a></li>
			<li><a href="/index.php/admins/admin">Admin Users</a></li>
			<li><a href="/index.php/Packages/admin">Packages</a></li>
			<li><a href="/index.php/buAccounts/admin">Accounts</a></li>
			<li><a href="/index.php/cpanel/admin">Accounts Users</a></li>
			<li><a href="/index.php/businessUnit/admin">Stores</a></li>
			<li><a href="/index.php/SpecialDeals/admin">Special Deals</a></li>
			<li>Reports
				<ul>
					<li><a href="/index.php/bills/PendingBills">Pending Bills</a></li>
					<li><a href="/index.php/bills/OnSiteCommisionRep">Onsite Commision Report</a></li>
					<li><a href="/index.php/bills/NotifyRep">Notification Report</a></li>
				</ul>
			</li>
		<?php endif;?>
		
		<?php if($RoleID == 2):?>
			<li><a href="/index.php/auth/UserHome" class="Owhome">Home</a></li>
			<li><a class="Owhome" href="/index.php/cpanel/<?=$UserID;?>">User Profile</a></li>
			<li><a class="Owhome" href="/index.php/BuAccounts/<?=$OwnerID;?>">My Account</a></li>
			<?php
				if(isset(Yii::app()->session['User'])){
					$Features = Yii::app()->session['User']['Features'];
					if(!empty($Features)){
						for ($i = 0; $i < sizeof($Features['Urls']); $i++) {
							$Col = strtok($Features['Urls'][$i], '/');
							echo '<li><a class="Owhome" href="/index.php/'.$Features['Urls'][$i].'">'.$Col.'</a></li>';
						}
					}
				}
			?>
		<?php endif;?>
		
		<?php if($RoleID > 2):?>
			<li><a href="/index.php/auth/AdminHome" class="uhome">Home</a></li>
			<li><a href="/index.php/cpanel/<?=$UserID;?>">My Account</a></li>
		<?php endif;?>
			
	</ul>
<?php endif;?>
<script>
	/*
	$(function() {
		$( "#menu" ).menu();
	});*/
	
	$('.Owhome').click(function(e){
		$.post('/index.php/cpanel/AjaxRemoveBuid/');
	});
</script>
<style>
	.ui-menu li {
	    float: left;
	    list-style: none;
	}
</style> 