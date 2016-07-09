<?php /* @var $this Controller */ 
	$Logo = Yii::app()->request->baseUrl.'/css/logo.png';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<noscript>
	<meta http-equiv="refresh" content="1; URL=http://yourwebsite/yourwarningpage.php" />
</noscript>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tables-bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tables-bootstrap-theme.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/validationEngine.jquery.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/validationEngine.jquery.css" />


	<!--<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fileupload.css">-->
	
	<?php //Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	 
	<!--<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/19785f89/jquery.js"></script>
	
	<link  href="<?php echo Yii::app()->request->baseUrl; ?>/css/cropper.css" rel="stylesheet">-->
	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/onLoad.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validationEngine.js" charset="utf-8"></script>
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<div style="float:right;padding:2% 15%;">
		<?php
				
			if(isset(Yii::app()->session['User'])){
				$UserNameF = isset(Yii::app()->session['User']['UserFname'])?Yii::app()->session['User']['UserFname']:'';
				echo "<div id='WelcomeDiv' style='float:left;margin-right:5px;'> Welcome  ".$UserNameF."</div>";
				echo "<a href='/index.php/auth/Logout'>Logout</a>";
			
				if(isset(Yii::app()->session['User']['Logo'])&& Yii::app()->session['User']['Logo'] != ''&&Yii::app()->session['User']['UserBuid'] > 0){
					
					$Logo =Yii::app()->request->baseUrl."/images/upload/business_unit/Logos/".Yii::app()->session['User']['Logo'];
				}
			
			}else{
					
				echo "<a href='/index.php/auth/UserLogin'>Login</a>";
			}
		?>
		<div style="float:left;">
			
			<!--
			<a href="#" id="ar" onClick="ConvertLang(this.id);"><!-- , '<?= $_SERVER['REQUEST_URI'];?>'
			   <img alt="Arabic" src="/assets/flags/EG.png">
		   </a>
									   <a href="#" id="en" onClick="ConvertLang(this.id);">
			   <img alt="English" src="/assets/flags/US.png">
		   </a>
									   <a href="#" id="fr" onClick="ConvertLang(this.id);">
			   <img alt="French" src="/assets/flags/FR.png">
		   </a>
									   <a href="#" id="it" onClick="ConvertLang(this.id);">
			   <img alt="Italy" src="/assets/flags/IT.png">
		   </a>
									   <a href="#" id="tr" onClick="ConvertLang(this.id);">
			   <img alt="Turkish" src="/assets/flags/TR.png">
		   </a>
									   <a href="#" id="es" onClick="ConvertLang(this.id);">
			   <img alt="Spanish" src="/assets/flags/ES.png">
		   </a>-->
			
	  <!-- 	<?php 
	  	 		$LangData = Yii::app()->db->createCommand("SELECT * FROM languages WHERE active = 1 ")->queryAll();
				if(count($LangData) > 0){
					foreach ($LangData as $key => $row) {
							
						echo '<a href="#" id="'.$row['lang_code'].'" onClick="ConvertLang(this.id);">
						  	 	<img alt="'.$row['lang_name'].'" src="/assets/flags/'.$row['lang_code'].'.png">
						  	  </a>';
						
					}
				}
	  	 	
	  	 	?> -->
	  	 	<div style="">
	  	 	<?php //var_dump(Yii::app()->session['Language']['UserLang']);
	  	 		$LangData = Yii::app()->db->createCommand("SELECT * FROM languages WHERE active = 1 ")->queryAll();
				$UserLang = isset(Yii::app()->session['Language']['UserLang'])?Yii::app()->session['Language']['UserLang']:'';
				echo '<select id = "LangList" name ="select">';
				if(count($LangData) > 0){
					foreach ($LangData as $key => $row) {
						if($row['lang_code'] == $UserLang){
							echo ' <option value="'.$row['lang_code'].'" selected >'.$row['lang_name'].'</option>';
						}else{
							echo ' <option value="'.$row['lang_code'].'">'.$row['lang_name'].'</option>';
						}
					}
				}
	  	 		echo '</select>';
	  	 	?>
	  	 	</div>
	  	 	<!--<div style="">
	  	 		<select name="select">
				  <option value="En">English</option> 
				  <option value="FR" selected>French</option>
				  <option value="IT">Italian</option>
				</select>
	  	 	</div>-->
		</div>
	</div>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
		
	</div><!-- header -->

	<div id="mainmenu">
		<?php include 'menu.php';?>
	</div><!-- mainmenu -->
	
	<!-- submenu -->

	
	
		
	
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		
		<script>
			
			$('div#logo').css('background-image','url(<?=$Logo;?>)');
			
			/*
			$('.Owhome').click(function(e){
				$.post('/index.php/auth/AjaxRemoveBuid/');
			});*/
			
			
			$('#LangList').change(function(e){
				ConvertLang($(this).val());
			});
			
		</script>
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
