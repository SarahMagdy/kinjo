
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/validationEngine.jquery.css" />
	</head>
	<title>Reset Password</title>
	<body>
		<div style="padding:5%">
			<form id="resetpass-form" action="/index.php/customers/ResetPassword" method="post">
				<label>New Password</label>
				<input name="CustID" id="CustID" type="text" value="<?=$CustID;?>" hidden >
				<input name="password" id="password" class="validate[required]" type="password">
				<br />	
				<label>Confirm Password</label>
				<input name="ConfirmPassword" id="ConfirmPassword" class="validate[required,equals[password]]" type="password">	
				<br />
				<span><?=$Mess;?></span>
				<br />
				<input name="resetpass_btn" value="Submit" type="submit">
			</form>
		</div>
	</body>
	<footer>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.validationEngine.js" charset="utf-8"></script>
		<script>
			$(document).ready(function(){
				$("#resetpass-form").validationEngine();
			});
		</script>
	</footer>
	
</html>

	
		
	