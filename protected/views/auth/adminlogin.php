<?php

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>
<h1>Admin Login</h1>
<div class="form">
	<form action="AdminLogin" method="post">
		
		<span class="error"><B><?php echo $error['login'].'<br /><br />';?></B></span>
		<label>UserName</label>
		<input type="text" id = "username" name = "username"  value = ""/>
		<span class="error">* <?php echo $error['UserName'];?></span>
		<label>Password</label>
		<input type="password" id = "password" name = "password" value = ""/>
		<span class="error">* <?php echo $error['Password'];?></span>
		<br />
		<button id="btn_submit" type="submit">Submit</button>
		
	 </form>
</div>
