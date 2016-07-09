<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Kinjo Store</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!-- Bootstrap -->
        <link href="/bootstrap/css/static-bootstrap.css" rel="stylesheet" media="screen">
        <link href="/bootstrap/css/static-bootstrap-responsive.css" rel="stylesheet" media="screen">
        <!-- Stylesheet -->
        <link href="/css/static-style.css" rel="stylesheet" media="screen">     
        <link href="/images/favicon.ico" rel="shortcut icon">
        <!--Jquery Init -->
        <script type="text/javascript" src="/js/static-jquery.js"></script>
        <script src="/bootstrap/js/static-bootstrap.min.js"></script>
        
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>

    <body>
        <!-- 
        ==================================================================
        Header Area
        ==================================================================
        -->
       <?php
        include('header.php');
       ?>
<div class="container auth">
    <h3 class="uppercase normal text-center title">Login & register</h3>
    <div class="row">
        <div class="login span5">
            <div class="row">
                <h4 class="twenty_margin_bottom span5">I am already customer</h4>
            </div>
            <form id="form-login" method="post" action="/index.php/home/Login" class="form-horizontal">
                <div class="element row">
                    <label class="span2">Email</label>
                    <input class="span3" type="text" name="email" />
                </div>
                <div class="element row">
                    <label class="span2">Password</label>
                    <input class="span3" type="password" name="password" />
                </div>
                <div class="element row">
                    <a class="span3 offset2 text-right" href="/home/ForgetPass">Oh, I forgot password</a>
                </div>
                <div class="element row">
                    <div class="span2">
                        <input class="button mustard" type="submit" value="Log In" />
                    </div>
                </div>
            </form>
            <a href="/home/FBLogin">FaceBook Login</a>
        </div>
        
        
        
        <form action="myAction" method="POST">
			<!-- <div class="g-recaptcha" data-sitekey="6LdiGQUTAAAAALot5XwQdfLa-irUAWJE8uUUbuA0"></div> -->
			<div class="g-recaptcha" data-sitekey="6LfU9AUTAAAAAGGGitn3OQuptWzMqLfre373rqY0"></div>
			
	      	<br/>
	      	<input type="submit" value="Submit">
	    </form>
        
        
        
        <div class="register span6 offset1">
            <div class="row">
                <h4 class="twenty_margin_bottom span5 offset1">I am new customer</h4>
            </div>
            <form id="form-register" method="post" action="/index.php/home/RegCust" class="form-horizontal">
                <?php if(isset($error_message)):?>
                	<div class="element row" style="color:#f00;border: 1px solid #ff0000;">
	                    <?php if(!empty($error_message['mail'])):?>
	                    	<?= $error_message['mail'];?>
	                    <?php endif;?>
	                    </br>
	                    <?php if(!empty($error_message['pass'])):?>
	                    	<?= $error_message['pass'];?>
	                    <?php endif;?>
	                </div>
                <?php endif;?>
                <div class="element row">
                    <label class="span2 offset1">Email</label>
                    <input class="span3" type="text" name="email" />
                </div>
                <!-- <div class="element row">
                    <label class="span2 offset1">Confirm Email</label>
                    <input class="span3" type="text" name="confirm_email" />
                </div> -->
                <div class="element row">
                    <label class="span2 offset1">Password</label>
                    <input class="span3" type="password" name="password" />
                </div>
                <div class="element row">
                    <label class="span2 offset1">Confirm Password</label>
                    <input class="span3" type="password" name="confirm_password" />
                </div>
                
                 <div class="element row">
                    <label class="span2 offset1">Gender</label>
                    <input type="radio" name="gender" value="0" checked>Male
					<input type="radio" name="gender" value="1">Female
                </div>
                
                <div class="element row">
                    <label class="span2 offset1">First name</label>
                    <input class="span3" type="text" name="firstname" />
                </div>
                <div class="element row">
                    <label class="span2 offset1">Last name</label>
                    <input class="span3" type="text" name="lastname" />
                </div>
                
                <div class="element row">
                	<label class="span2 offset1">Birth Date</label>
                   
                    <select name="birthD" style="width:60px;">
						<option value=""> Day </option>
						<?php for ($i=1 ; $i<= 31; $i++ ):?>
							<option value="<?= $i;?>"  ><?= $i;?></option>
						<?php endfor;?>
					</select>
                   
                    <select name="birthM" style="width:75px;">
						<option value=""> Month </option>
						<?php for ($i=1 ; $i<= 12; $i++ ):?>
							<option value="<?= $i;?>"  ><?= $i;?></option>
						<?php endfor;?>
					</select>
                    
                    <select name="birthY" style="width:75px;">
						<option value=""> Year </option>
						<?php for ($i= date("Y") ; $i>=1905 ; $i-- ):?>
							<option value="<?= $i;?>"  ><?= $i;?></option>
						<?php endfor;?>
					</select>
                </div>
                
                <!-- <div class="element row">
                    <label class="span2 offset1">Street name</label>
                    <input class="span3" type="text" name="streetname" />
                </div>
                <div class="element row">
                    <label class="span2 offset1">City</label>
                    <input class="span3" type="text" name="city" />
                </div> -->
                <?php $CountrySql = "SELECT * FROM country ";
					  $CountryRes = Yii::app()->db->createCommand($CountrySql)->queryAll();?>
                <div class="element row">
                    <label class="span2 offset1">Country</label>
                    <!-- <input class="span3" type="text" name="country" /> -->
                    <select name="country">
						<option value="">-- Select Country --</option>
						<?php foreach ($CountryRes as $key => $row):?>
							<option value="<?=$row['country_id'];?>"  ><?=$row['name'];?></option>
						<?php endforeach;?>
					</select>
                </div>
                <!-- <div class="element row">
                    <label class="span2 offset1">Phone</label>
                    <input class="span3" type="text" name="phone" />
                </div> -->
                <div class="element row">
                    <div class="span5 offset1 text-right">
                        <input class="button mustard" type="submit" value="Register" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 
==================================================================
Newsletter
==================================================================
-->
<div class="fullwidth clearfix newsletter_cta twenty_margin_top">
    <div class="container">
        <div class="row clearfix">
            <div class="span8">
                <h3 class="pull-left uppercase font-light lightgray">subscribe  to newsletter <span class="mustard">get a 10% discount on 1st purchase</span></h3>
            </div>
            <div class="span4">
                <form class="form-newsletter clearfix">
                    <input type="text" class="input-medium newsletter_input pull-left" placeholder="your email address">
                    <button type="submit" class="newsletter_button">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- 
==================================================================
Footer
==================================================================
-->
       <?php
        include('footer.php');
       ?>
<!-- 
==================================================================
Copyright Information
==================================================================
-->
<!-- Popup -->
<!-- /Popup -->
<!-- Popup 2-->
<!-- /Popup 2-->
<script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
<script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="/js/static-jquery.cookie.js"></script>
<script src="/js/static-script.js" type="text/javascript"></script>

</body>
</html>