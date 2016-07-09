
<h1>Reset Password</h1>
<div class="form">
	<form id="ResetPassFrm" action="/index.php/auth/ResetPassword" method="post">
		<label>New Password</label>
		<input name="UsrID" id="UsrID" type="text" value="<?=$UsrID;?>" hidden >
		<input name="password" id="password" class="validate[required]" type="password">
		<br />	
		<label>Confirm Password</label>
		<input name="ConfirmPassword" id="ConfirmPassword" class="validate[required,equals[password]]" type="password">	
		<br />
		<span class="error"><?=$Mess;?></span>
		<br />
		<input name="ResetPassBtn" value="Submit" type="submit">
	</form>	
</div>
<script>
/*
	$(document).ready(function(){
		$("#ResetPassFrm").validationEngine();
	});*/

</script>