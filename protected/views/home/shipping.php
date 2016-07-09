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
    <!-- Breadcrumbs -->
    <div class="row">
        <div class="cart_nav span12">
            <ul class="list row">
                <li class="item span3">
                    <a href="cart.php">Shopping cart</a>
                </li>
                <li class="item span3">
                    <a href="address.php">Delivery address</a>
                </li>
                <li class="item current span3">
                    <a href="shipping.php">Shipping / payment</a>
                </li>
                <li class="item span3">
                    <a href="summary.php">Summary</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs -->

    <!-- Payment -->
    <div class="row payment">
        <!-- <div class="billing_address span5">
            <div class="row">
                <h4 class="font-light twenty_margin_bottom span5">Choose shipping options</h4>
            </div>

            <div class="row">
                <div class="address_box span5 clearfix">
                    <h5 class="main">DHL Standard delivery</h5>
                    <p class="description">The shipment will be delivered in 2-5 working days.</p>
                    <h5 class="main pull-left">price: 2.99€</h5>
                    <button class="button green pull-right">Select</button>
                </div>
            </div>

            <div class="row">
                <div class="address_box span5 clearfix">
                    <h5 class="main">Fedex express delivery</h5>
                    <p class="description">The shipment will be delivered in 1-2 working days.</p>
                    <h5 class="main pull-left">price: 6.99€</h5>
                    <button class="button green pull-right" disabled>Select</button>
                </div>
            </div>

            <div class="row">
                <div class="address_box span5 clearfix">
                    <h5 class="main">postoffice delivery</h5>
                    <p class="description">The shipment will be delivered in 5-7 working days.</p>
                    <h5 class="main pull-left">price: 1.49€</h5>
                    <button class="button green pull-right" disabled>Select</button>
                </div>
            </div>
        </div> -->
        
        
        
        
        
        <div class="delivery_address span5 offset2">
            <div class="row">
                <h4 class="font-light twenty_margin_bottom span5">Choose payment method</h4>
            </div>
            <div class="row">
                <div class="address_box span5">
                    <h5 class="main">creditcard</h5>
                    <p class="description">You  need to enter the essential credit card and payment details online using the ipayment interface. </p>
                    
                    <form id="credit_payment" action="/home/Shipping" method="POST" >
                        
                        
                        <?php 
				        	// foreach ($BuArr as $key => $val) {
							echo '<input type="text" id="shipping_Buid"  name="bu_id" value="'.$BuArr['Buid'].'" />
							      <input type="text" id="shipping_BuName" name="shipping_BuName" value="'. $BuArr['BuName'].'" />
							      <input type="text" id="shipping_BuCurr" name="shipping_BuCurr" value="'. $BuArr['BuCurrCode'].'" />
							      <input type="text" id="shipping_BuVal" name="shipping_BuVal" value="'. $BuArr['BuTotal'].'" />
							      <input type="text" id="ShipAddID"  name="ShipAddID" value="'. $BuArr['ShipAddID'].'" />';		
							// }
				        
				        ?>
	
				        <input type="text" name="id" value="<?= $ordOPen['OrdID']; ?>" />
                        <input type="text" id="kinjo_commission" name="kinjo_comm" value="<?=$kinjo_comm;?>" />
                        
                        
                        <?php 
                        	$us_state_abbrevs_names = array('AL'=>'ALABAMA'   ,   'AK'=>'ALASKA'      ,  'AS'=>'AMERICAN SAMOA',
															'AZ'=>'ARIZONA'   ,   'AR'=>'ARKANSAS'    ,  'CA'=>'CALIFORNIA',
															'CO'=>'COLORADO'  ,   'CT'=>'CONNECTICUT' ,  'DE'=>'DELAWARE',
															'DC'=>'DISTRICT OF COLUMBIA' , 
															'FM'=>'FEDERATED STATES OF MICRONESIA',
															'FL'=>'FLORIDA'   ,   'GA'=>'GEORGIA'     ,  'GU'=>'GUAM GU',
															'HI'=>'HAWAII'    ,   'ID'=>'IDAHO'       ,  'IL'=>'ILLINOIS' , 
															'IN'=>'INDIANA'   ,   'IA'=>'IOWA'        ,  'KS'=>'KANSAS',
															'KY'=>'KENTUCKY'  ,   'LA'=>'LOUISIANA'   ,  'ME'=>'MAINE',
															'MH'=>'MARSHALL ISLANDS'    ,   'MD'=>'MARYLAND',
															'MA'=>'MASSACHUSETTS'       ,    'MI'=>'MICHIGAN',
															'MN'=>'MINNESOTA' ,    'MS'=>'MISSISSIPPI' ,  'MO'=>'MISSOURI',
															'MT'=>'MONTANA'   ,    'NE'=>'NEBRASKA'    ,  'NV'=>'NEVADA',
															'NH'=>'NEW HAMPSHIRE'      ,   'NJ'=>'NEW JERSEY',
															'NM'=>'NEW MEXICO'  ,  'NY'=>'NEW YORK'   ,   'NC'=>'NORTH CAROLINA',
															'ND'=>'NORTH DAKOTA',  'MP'=>'NORTHERN MARIANA ISLANDS',
															'OH'=>'OHIO'        ,  'OK'=>'OKLAHOMA'   ,   'OR'=>'OREGON',
															'PW'=>'PALAU'       ,  'PA'=>'PENNSYLVANIA',  'PR'=>'PUERTO RICO',
															'RI'=>'RHODE ISLAND',  'SC'=>'SOUTH CAROLINA','SD'=>'SOUTH DAKOTA',
															'TN'=>'TENNESSEE'   ,  'TX'=>'TEXAS'         ,'UT'=>'UTAH',
															'VT'=>'VERMONT'     ,  'VI'=>'VIRGIN ISLANDS','VA'=>'VIRGINIA',
															'WA'=>'WASHINGTON'  ,  'WV'=>'WEST VIRGINIA' ,'WI'=>'WISCONSIN',
															'WY'=>'WYOMING'     ,  'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
															'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
															'AP'=>'ARMED FORCES PACIFIC'
													); 
                        	
                        	// echo $form->dropDownList('', 'country_id', $us_state_abbrevs_names, array('prompt' => '- Select Country -')); ?>
                        
                        <select name="State">
                        	<option value=""> Select State</option>
                        	<?php foreach($us_state_abbrevs_names as $k => $r):?>
                        		<option value="<?= $k?>"> <?= $r;?> </option>
                        	<?php endforeach;?>
                        </select>
                        <div class="row element">
                            <label class="span1">Select card</label>
                            <a href="#" class="span3">
                                <img src="/images/credit/visa-green.png" alt="" class="pull-left">
                                <img src="/images/credit/mastercard-grey.png" alt="" class="pull-left">
                            </a>
                        </div>
                        <div class="row element">
                            <label class="span1">Card number</label>
                            <input class="span3" type="text" id="cust_card_number" name="crd_num">  <!-- name="card_number" -->
                        </div>
                        <div class="row element">
                            <label class="span1">Card valid</label>
                            <input class="span1" type="text" id="cust_month" name="exp_month">  <!-- name="month" -->
                            <input class="span2" type="text" id="cust_year" name="exp_year">  <!-- name="year" -->
                        </div>
                        <div class="row element">
                            <label class="span1">CCV</label>
                            <input class="span1" type="text" id="cust_ccv" name="cvv">   <!-- name="ccv" -->
                        </div>
                        
                         <button type="submit" class="button button-fluid mustard">Pay</button>
                    </form>
                </div>

                <!-- <div class="address_box span5">
                    <h5 class="main">Paypal</h5>
                    <p class="description">The procedure prescribed by PayPal is used for payments via PayPal. </p>
                    <form id="paypal_method" action="#">
                        <div class="row element">
                            <label class="span2">Paypal username</label>
                            <input class="span2" type="text" name="card_number">
                        </div>
                    </form>
                </div> -->

            </div>

        </div>
        <div class="span12 actions">
            <div class="row">
                <div class="span3">
                    <a href="/home/Checkout/?Buid=<?= $BuArr['Buid'] ;?>" class="button button-fluid darkgrey">Back to address</a> <!-- address.php -->
                </div>
                <div class="text-right span3 offset6">
                    <!-- <a href="#"id="validate" class="button button-fluid mustard">Proceed to summary</a> -->
                   
                </div>
            </div>
        </div>
    </div>
    <!-- /Payment -->

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



<!-- Popup 
<div class="popup">
    <div class="mask"></div>
    <div class="box span9 clearfix">
        <i class="close">&#9587;</i>
        <figure class="product_fb">
            <img src="images/products/prod-detail.png" alt="">
        </figure>
    </div>
</div>-->
<!-- /Popup -->

<!-- Popup 2-->

<!-- /Popup 2-->

<script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
<script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="/js/static-jquery.cookie.js"></script>
<script src="/js/static-script.js" type="text/javascript"></script>

<!-- <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script> -->
<script>

	// $('#validate').click(function(e){
		// e.preventDefault();
		// // alert($('#shipping_Buid').val());
		// // return;
		 // var args = {
            // sellerId: "901262532",
			// publishableKey: "A72E8DDE-D8B9-4D84-AF5F-B3D546D1589C",
            // ccNo: $('#cust_card_number').val(),
            // cvv: $('#cust_ccv').val(),
            // expMonth: $('#cust_month').val(),
            // expYear: $('#cust_year').val(),
        // };
        // // Make the token request
       // TCO.requestToken(successCallback, errorCallback, args);	
	// });
	
	
	
	// var successCallback = function(data) {     
		// var Token = data.response.token.token;
        // // alert($('#shipping_BuVal').val());
       	// // return; 
        // // var billingAddr = {};
        // // billingAddr["city"]    = "alex";
        // // billingAddr["country"] = "Egy";
        // // billingAddr["zipCode"] = "555";
        // // billingAddr["phone"] = "01205790170";
        // // billingAddr["email"] = "shimaa.mohamed.cs@gmail.com";
        // // billingAddr["cust_name"] = "HOSSAM"; 
        // var data ={
			// Cr_val      : $('#shipping_BuVal').val(),
			// Currency    : $('#shipping_BuCurr').val(),
			// Token       : Token , 
			// kinjo_comm  : $('#kinjo_commission').val(),
			// ShipAddID   : $('#ShipAddID').val(),
			// id          : $('.showCard').html(),
			// bu_id       : $('#shipping_Buid').val()
			// // billingAddr : billingAddr,
		// }; 
		// // $('#shipping_Buid').val()
		// $.post( "/home/Shipping/" , data, function( data ) {
			// // location.reload();
		// });
		// // window.location.href = "/home/";
    // };
    
    // var errorCallback = function(data) {
        // if (data.errorCode === 200) {
        // //    tokenRequest();
     	   // alert('Try Again');
        // } else {
           // alert(data.errorMsg);
        // }
    // };
	
	// $(function() {
        // // Pull in the public encryption key for our environment
		// TCO.loadPubKey('sandbox');
		// return false;
    // });	
	
</script>

</body>
</html>



