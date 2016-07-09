<?php
	$RoleID = 0;$UserID = 0;
	if(isset($_SESSION['User'])){
			
		if(isset($_SESSION['User']['UserRoleID']) && !empty($_SESSION['User']['UserRoleID']) && $_SESSION['User']['UserRoleID'] > 0){
			
			$RoleID = $_SESSION['User']['UserRoleID'];
			$UserID = $_SESSION['User']['UserID'];
			$OwnerID = $_SESSION['User']['UserOwnerID'];
		}
		
	}
	$langFile = Yii::app()->session['Language']['LangFile'];//var_dump($langFile);
	//Yii::app()->language = Yii::app()->session['Language']['LangFile'];
	//var_dump(Yii::app()->language);
	//var_dump(Yii::t($langFile, 'BuAccount_pkg_id'));
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
			<li class="hover_menu_mod"><a href="">Reports</a>
				<div id="dropdown_list">
					<ul>
						<li class="subs_inlines"><a href="/index.php/bills/PendingBills">Pending Bills</a></li>
						<li class="subs_inlines"><a href="/index.php/bills/OnSiteCommisionRep">Onsite Commision Report</a></li>
						<li class="subs_inlines"><a href="/index.php/bills/NotifyRep">Notification Report</a></li>
					</ul>
				</div>
			</li>
		<?php endif;?>
		
		<?php if($RoleID == 2):?>
			<li><a href="/index.php/auth/UserHome" class="Owhome"><?=Yii::t($langFile, 'TopMenuHome');?></a></li>
			<li><a class="Owhome" href="/index.php/cpanel/<?=$UserID;?>"><?=Yii::t($langFile, 'TopMenuUserProfile');?></a></li>
			<li><a class="Owhome" href="/index.php/BuAccounts/<?=$OwnerID;?>"><?=Yii::t($langFile, 'TopMenuMyAccount');?></a></li>
			<?php
				if(isset(Yii::app()->session['User'])){
					$Features = Yii::app()->session['User']['Features'];
					if(!empty($Features)){
						for ($i = 0; $i < sizeof($Features['Urls']); $i++) {
							$Col = strtok($Features['Urls'][$i], '/');//var_dump(Yii::t($langFile, 'TopMenu'.$Col));$langFile
							echo '<li><a class="Owhome" href="/index.php/'.$Features['Urls'][$i].'">'.Yii::t($langFile, 'TopMenu'.$Col).'</a></li>';
						}
					}
				}
			?>
		<?php endif;?>
		
		<?php if($RoleID > 2):?>
			<li><a href="/index.php/auth/AdminHome" class="uhome"><?=Yii::t($langFile, 'TopMenuHome');?></a></li>
			<li><a href="/index.php/cpanel/<?=$UserID;?>"><?=Yii::t($langFile, 'TopMenuMyAccount');?></a></li>
		<?php endif;?>
			
	</ul>
<?php endif;?>
<script>
	/*
	$(function() {
		$( "#menu" ).menu();
	});*/
	
	$('.Owhome').click(function(e){
		$.post('/index.php/auth/AjaxRemoveBuid/');
	});
</script>
<script type="text/javascript">

//document.ready(function(){
	$('.hover_menu_mod').hover(function(){
		//$('#dropdown_list').css('display','block');
		$( "#dropdown_list" ).toggle();
	});
//});


</script>
<style>
	.ui-menu li {
	    float: left;
	    list-style: none;
	}
</style> 