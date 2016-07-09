<?php
/* @var $this LoginController */

$this->breadcrumbs=array(
	'Login',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<!--<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
-->

<form method="post" action="/index.php/login/<?=$action;?>" >
	
	<label>User Name</label>
	<input id='username' name="username" type="text" value=''/>
	
	<label>Password</label>
	<input id='pass' name='pass' type='password'value=''/>
	
	
	<button type="submit" id="btn_login">LOGIN</button>
	
</form>