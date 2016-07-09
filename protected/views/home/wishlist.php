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


<?php if(!empty($WishList['Products'])): ?>
	<div class="container">
	    <!-- Breadcrumbs -->
	    <div class="row">
	        <div class="cart_nav span12">
	            <ul class="list row">
	                <li class="item span3 current">
	                    <a href="cart.php">Wish List</a><!-- Shopping cart -->
	                </li>
	                <!-- <li class="item span3">
	                    <a href="address.php">Delivery address</a>
	                </li>
	                <li class="item span3">
	                    <a href="shipping.php">Shipping / payment</a>
	                </li>
	                <li class="item span3">
	                    <a href="summary.php">Summary</a>
	                </li> -->
	            </ul>
	        </div>
	    </div>
	    <!-- /Breadcrumbs -->
	
	    <!-- Cart -->
	    <div class="cart row">
	
	        <div class="thead span12">
	            <div class="row">
	                <div class="span8">
	                    <h4 class="title">Product</h4>
	                </div>
	                <!-- <div class="span1 text-center">
	                    <h4 class="title">QTY.</h4>
	                </div> -->
	                <div class="span3 text-center">
	                    <h4 class="title">Price</h4>
	                </div>
	            </div>
	        </div>
	        <?php
				$path = '/images/upload/products/';
			?>
	        <?php foreach($WishList['Products'] as $key => $row){
	        		$img = substr(strrchr($row['ProImg'][0]['img'], '/'), 1);
	        		echo '<div class="product span12" id="'.$row['ProID'].'">
					            <div class="row">
					                <div class="span8 clearfix">
					                    <div class="image">
					                        <a href="/home/ProDetail/'.$row['ProID'].'">
					                            <img src="'.$path.$img.'" alt=""/>
					                        </a>
					                    </div>
					                    <div class="detail">
					                        <p>'.$row['ProTitle'].'</p>
					                        <!--<p> Size: M</p>-->
					                        <ul class="rating pull-right">';
									
									$rateClass = "";
		                       		for($i=1 ; $i<=5 ; $i++){
		                       			if($i<= $row['ProRate'] ){
			                       			$rateClass = "normal";
										}else{
											$rateClass = "";
										}
										echo '<li class="item '.$rateClass.' dib">
					                          	   <i class="icon"></i>
					                          </li>';
		                       		}
		                   
											
											
					  echo '				</ul>
					                    </div>
					                </div>
					             
					                <div class="price span3 text-center pull-right">
					                    <button type="button" rmvProId="'.$row['ProID'].'" class="close rmvCls">×</button>
					                  
	                 				   <a href="#" pro_id="'.$row['ProID'].'" class="button addCardCls" >Add to Cart</a>
	               
					                    <span class="total">'.$row['ProPrice'].'€</span>
					                </div>
					            </div>
					        </div>';
				
	        	}
	        ?>
	        
	
	        <!-- <div class="product span12">
	            <div class="row">
	                <div class="span8 clearfix">
	                    <div class="image">
	                        <a href="product.php">
	                            <img src="/images/products/small1.png" alt=""/>
	                        </a>
	                    </div>
	                    <div class="detail">
	                        <p>Eskimo Toast T-shirt by <a href="category_template_2.php">Cleptomanicx</a></p>
	                        <p>Size: M</p>
	                        <p>Colour: blue</p>
	                    </div>
	                </div>
	                <div class="quantity span1 text-center">
	                    <div class="urbaspin">
	                        <i data-arrow="up">&#9650;</i>
	                        <label>2</label>
	                        <i data-arrow="down">&#9660;</i>
	                    </div>
	                </div>
	                <div class="price span3 text-center pull-right">
	                    <button type="button" class="close">×</button>
	                    <h5 class="peritem">2x35.00€</h5>
	                    <span class="total">70.00€</span>
	                </div>
	            </div>
	        </div>
	        <div class="product span12">
	            <div class="row">
	                <div class="span8 clearfix">
	                    <div class="image">
	                        <a href="product.php">
	                            <img src="/images/products/small8.png" alt=""/>
	                        </a>
	                    </div>
	                    <div class="detail">
	                        <p>Eskimo Toast T-shirt by <a href="category_template_2.php">Cleptomanicx</a></p>
	                        <p>Size: M</p>
	                        <p>Colour: blue</p>
	                    </div>
	                </div>
	                <div class="quantity span1 text-center">
	                    <div class="urbaspin">
	                        <i data-arrow="up">&#9650;</i>
	                        <label>1</label>
	                        <i data-arrow="down">&#9660;</i>
	                    </div>
	                </div>
	                <div class="price span3 text-center pull-right">
	                    <button type="button" class="close">×</button>
	                    <h5 class="peritem">2x35.00€</h5>
	                    <span class="total">59.50€</span>
	                </div>
	            </div>
	        </div>
	        <div class="product span12">
	            <div class="row">
	                <div class="span8 clearfix">
	                    <div class="image">
	                        <a href="product.php">
	                            <img src="/images/products/small7.png" alt=""/>
	                        </a>
	                    </div>
	                    <div class="detail">
	                        <p>Eskimo Toast T-shirt by <a href="category_template_2.php">Cleptomanicx</a></p>
	                        <p>Size: M</p>
	                        <p>Colour: blue</p>
	                    </div>
	                </div>
	                <div class="quantity span1 text-center">
	                    <div class="urbaspin">
	                        <i data-arrow="up">&#9650;</i>
	                        <label>1</label>
	                        <i data-arrow="down">&#9660;</i>
	                    </div>
	                </div>
	                <div class="price span3 text-center pull-right">
	                    <button type="button" class="close">×</button>
	                    <h5 class="peritem">2x35.00€</h5>
	                    <span class="total">39.50€</span>
	                </div>
	            </div>
	        </div> -->
	
	        <!-- <div class="total span12">
	            <div class="row">
	                <div class="items text-right span3 offset6">
	                    4 items in total price
	                </div>
	                <div class="price text-center span3">169€</div>
	            </div>
	        </div> -->
	        <!-- <div class="span12 actions">
	            <div class="row">
	                <div class="span3">
	                    <a href="home.php" class="button darkgrey">back to shopping</a>
	                </div>
	                <div class="text-right span3 offset6">
	                    <a href="address.php" class="button button-fluid mustard">Procced to address</a>
	                </div>
	            </div>
	        </div> -->
	
	    </div>
	    <!-- /Cart -->
	
	</div>
<?php else:?>
	<div>Your WishList is Empty</div>
<?php endif;?>
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
<script>
	
	// function W_AddToCard($id)
	// {
		// alert($id);
	// }
	
	$('.addCardCls').click(function(e){
		e.preventDefault();
		var p_id = $(this).attr('pro_id');
		window.location.href = "/home/ProDetail/"+p_id;
		
		// alert(p_id);
		// var onSuccess = function(location){
			
			// var location_json = JSON.stringify(location, undefined, 4);
			// locationData = $.parseJSON(location_json);
			
			// var latitude  = locationData['location']['latitude'];
			// var longitude = locationData['location']['longitude'];
			// alert($(this).attr('pro_id'));return;
			// var ordConfArr = new Array();
		
			// ordConfArr.push({"qnt": 2 ,  "conf":'1,2' , "color":2});
			// var mydata ={
				// p_id   : p_id, 
				// long   : longitude , 
				// lat    : latitude ,
				// bu_id  : 3 ,
				// iso    : locationData['country']['iso_code'],
				// Q_Conf : ordConfArr	
			// };
			
			// $.post( "/home/AddToCard/",mydata, function( data ) {		
				// data = data.trim();
				// var jsonData = data.toString();
				// endData = $.parseJSON(jsonData);
				// if(endData['error'] && endData['error'] !="")
				// {
					// alert(endData['error']['message']);
				// }else{
					// alert('Product Added to your Order');
				// }
			// });
			
		// };
		
		// var onError = function(error){
			// alert( "Error:\n\n" + JSON.stringify(error, undefined, 4));
		// };
		
		// geoip2.city(onSuccess, onError);
		
	});
	
	
	
	$('.rmvCls').click(function(eve){
		eve.preventDefault();
		// alert($(this).attr('rmvProId'));
		var proID = $(this).attr('rmvProId') ;
		if (confirm("Remove From WishList ?")) {
       
			var xx ={
				pid  : proID
			};
			
			$.post( "/home/RmvFromWishList/", xx , function( data ) {
								
				data = data.trim();
				var jsonData = data.toString();
				
				endData = $.parseJSON(jsonData);
			
				if(endData['error'] && endData['error'] !="")
				{
					alert(endData['error']['message']);
				}else{
					// alert('Product Removed');								
					// window.location.reload(true);
					$( "#"+proID ).remove();
				}
				
			});
		}
	});

</script>
</body>
</html>

