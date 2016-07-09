


<h1>Forget Password</h1>
<div class="form">
	<form action="ForgetPass" method="post">
		<span class="error" style="color:red;">
			<?php if (array_key_exists("error",$Res)){
				echo $Res['error']['message'];
				}elseif (array_key_exists("Result",$Res)){
					if($Res['Result'] == 'TRUE'){
						echo 'Mail has been sent to your E-mail';
					}elseif($Res['Result'] == 'FALSE'){
						echo 'Please Try Again';
					}
				}
			?>
		</span>
		<div class="margin_5_sep"></div>
		<label>E-Mail</label>
		<input type="text" id = "email" placeholder="E-Mail" name = "email"  value = ""/>
		<button id="btn_submit" type="submit">Submit</button>
	 </form>
</div>