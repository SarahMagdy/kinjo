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




<div class="container checkout">
	<?php if($cust['CustAddr'] == TRUE):?>
    <!-- Breadcrumbs -->
    <div class="row">
        <div class="cart_nav span12">
            <ul class="list row">
                <li class="item span3">
                    <a href="/home/ShowCard">Shopping cart</a>
                </li>
                <li class="item span3 current">
                    <a href="#">Delivery address</a>
                </li>
                <li class="item span3">
                    <a href="#">Shipping / payment</a> <!-- shipping.php -->
                </li>
                <!-- <li class="item span3">
                    <a href="summary.php">Summary</a>
                </li> -->
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs -->

    <!-- Address & Shipping -->
    <div class="row address">
        <form id="checkout_address" method="post" action="#" class="form-horizontal">
        	
            <fieldset class="billing_address span5">
                <div class="row">
                    <h4 class="font-light twenty_margin_bottom span5">Billing address</h4>
                </div>
                <?php 
                	foreach ($cust['Addr'] as $key => $val) {
                		if($val['AddDefault'] == 'TRUE'){
	                		echo '<div class="element row">
				                    <label class="span2">Country</label>
				                    <input class="span3" type="text" name="country" id="cust_country" value="'.$val['AddCountry'].'">
				                </div>
				                <div class="element row">
				                    <label class="span2">City</label>
				                    <input class="span3" type="text" name="city" id="cust_city" value="'.$val['AddCity'].'">
				                </div>
				                <div class="element row">
				                    <label class="span2">State / Region</label>
				                    <input class="span3" type="text" name="region" id="cust_region" value="'.$val['AddRegion'].'">
				                </div>
				                <div class="element row">
				                    <label class="span2">Street name</label>
				                    <input class="span3" type="text" name="streetname" id="cust_street" value="'.$val['AddStreet'].'">
				                </div>
				                <div class="element row">
				                    <label class="span2">Postal Code</label>
				                    <input class="span3" type="text" name="postalCode" id="cust_postalCode" value="'.$val['AddPostal'].'">
				                </div>';
						}
                	}
                ?>
               			
		                
		          
	                
            </fieldset>
            <fieldset class="delivery_address span5 offset2">
                <div class="row">
                    <h4 class="font-light twenty_margin_bottom span3">Delivery address</h4>
                    <!-- <div class="span2 ten_margin_top">
                        <label class="custom_checkbox delivery">
                            <input type="checkbox" name="delivery" id="custAddr" />
                            <b class="cb"></b>
                        </label>
                        <span>Same as billing</span>
                    </div> -->
                </div>
                
                <input type="hidden" name="custAddID" id="custAddID" value="" />
	        	<input type="hidden" name="Buid" id="Buid" value="<?=$cust['Buid'];?>" />
	        	<?php 
	            	foreach ($cust['Addr'] as $key => $val) {
	            		// if($val['AddDefault'] == 'FALSE'){
	            			echo '<div id="'.$val['AddID'].'" class="custAddDiv" style="border:thin solid black;width:30%;display:inline-block;margin-right:4px;">
								 	 <p><B>'.$val['AddCountry'].' , </B>  '.$val['AddCity'].'  '.$val['AddRegion'].'  '.
								 	 		 $val['AddStreet'].'</p>
								  </div>';
	            		// }
						
					}
	            ?>
                
              
                <?php $CountrySql = "SELECT * FROM country ";
					  $CountryRes = Yii::app()->db->createCommand($CountrySql)->queryAll();?>
                
                <div class="element row">
                    <label class="span2">Country</label>
                    <!-- <input class="span3" type="text" name="del_country" id="del_country"> -->
                     <select name="ship_country" id="ship_country">
						<option value="">-- Select Country --</option>
						<?php foreach ($CountryRes as $key => $row):?>
							<option value="<?=$row['country_id'];?>"  ><?=$row['name'];?></option>
						<?php endforeach;?>
					</select>
                </div>
                <div class="element row">
                    <label class="span2">City</label>
                    <input class="span3" type="text" name="ship_city" id="ship_city">
                </div>
                <div class="element row">
                    <label class="span2">State / Region</label>
                    <input class="span3" type="text" name="ship_region" id="ship_region">
                </div>
                <div class="element row">
                    <label class="span2">Street name</label>
                    <input class="span3" type="text" name="ship_stname" id="ship_stname">
                </div>
                
                <div class="element row">
                    <label class="span2">Postal/Zip Code</label>
                    <input class="span3" type="text" name="ship_postalCode" id="ship_postalCode">
                </div>
                
              
            </fieldset>
            <!-- <div class="span12">
                <div class="element row">
                    <label class="span2">Order Comment</label>
                    <textarea class="span10" rows="5" name="message"></textarea>
                </div>
            </div> -->
            <div class="span12 actions">
                <div class="row">
                    <div class="span3">
                        <a href="/home/ShowCard" class="button button-fluid darkgrey">Back to cart</a>
                    </div>
                    <div class="text-right span3 offset6">
                       	  
                        <button href="#" type="submit" class="button button-fluid mustard">Proceed to shipping</button> <!-- shipping.php -->
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php else:?>
    	<div>
    		Please Complete Your Profile Data in order To Complete Your Order .
    	</div>
    <?php endif;?>
    <!-- /Address & Shipping -->

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

<!-- Popup 2-->
<div class="container popup offer">
    <div class="mask"></div>
    <div class="row">
        <div class="box span9 clearfix">
            <i class="close">&#9587;</i>
            <div class="row">
                <figure class="promo span4">
                    <img src="/images/subscribe_promo.jpg" alt=""/>
                </figure>
                <div class="content span5">
                    <div class="header">
                        <span class="off font-oswald special-text">20%</span>
                        <span class="uppercase text">one day 
                            <span class="special-text">discount</span> every month
                        </span>
                    </div>
                    <div class="main">
                        <h4 class="uppercase font-light">Subscribe and don’t miss it!</h4>
                        <form>
                            <input type="text" placeholder="Your Email" name="email" class="uppercase"/>
                            <input type="submit" value="Send" class="button mustard"/>
                        </form>
                        <div class="social ten_margin_top">
                            <span class="lbl uppercase">recommend for friends</span>
                            <div class="icons pull-right">
                                <a href="#" class="dib">
                                    <img src="/images/social/facebook.png" alt=""/>
                                </a>
                                <a href="#" class="dib">
                                    <img src="/images/social/twitter.png" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Popup 2-->

<script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
<script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="/js/static-jquery.cookie.js"></script>
<script src="/js/static-script.js" type="text/javascript"></script>
<script>
	$('#custAddr').click(function(e){
		// alert($(this).attr('readonly'));
		if($("#custAddr").is(':checked')){
			$('#del_fname').val($('#cust_fname').val());
			$('#del_lname').val($('#cust_lname').val());
			$('#del_stname').val($('#cust_street').val());
			$('#del_city').val($('#cust_city').val());
			$('#del_country').val($('#cust_country').val());
			$('#del_phone').val($('#cust_phone').val());
			
		}else{
			$('#del_stname').val('');
			$('#del_city').val('');
			$('#del_country').val('');
			$('#del_phone').val('');
		}
	});
	
	
	$('.custAddDiv').click(function(e){
		e.preventDefault();
		if ( $(this).hasClass( "AddSelected" ) ) {
			$(this).removeClass('AddSelected');
			$('.custAddDiv').css('border-color', '#000000');
			$('.custAddDiv').css('border-width','1px');
			$('#custAddID').val('');
		}else{
			$('.custAddDiv').removeClass('AddSelected');
			$('.custAddDiv').css('border-color', '#000000');
			$('.custAddDiv').css('border-width','1px');
			
			$(this).addClass('AddSelected');
			$('#custAddID').val($(this).attr('id'));
			$(this).css('border-color', '#FF00FF');
			$(this).css('border-width','3px');
		}
		
	});
	
	$('#checkout_address').submit(function(e){
		e.preventDefault();
		if($('#custAddID').val() != '' || $('#ship_country').val()){
			this.submit();
		}else{
			alert('Please Enter Shipping Address');
		}
		
	});
	
</script>
</body>
</html>



