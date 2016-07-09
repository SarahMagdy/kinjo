<?php

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>
<div class="Centered_box">
<h1 class="h1centered"> User Login</h1>
<div class="form">
	<form action="UserLogin" method="post">
	
		<span class="error"><B><?php echo $error['login'].'<br /><br />';?></B></span>
		<!-- <label>UserName</label> -->
		<input type="text" class="login_input" id = "username" placeholder="Type Username" name = "username"  value = ""/>
		<span class="error">* <?php echo $error['UserName'];?></span>
		<!-- <label>Password</label> -->
		<input type="password" class="login_input" id = "password" name = "password" value = ""/>
		<span class="error">* <?php echo $error['Password'];?></span>
		<div class="margin_5_sep"></div>
		<span><a href="/index.php/auth/ForgetPass">Forget Password</a></span>
		<div class="margin_5_sep"></div>
		<button id="btn_submit" type="submit" class="action-button shadow animate blue">Submit</button>
		<!-- <a href="#" class="action-button shadow animate blue" >login</a> -->
	 </form>
</div>
</div>