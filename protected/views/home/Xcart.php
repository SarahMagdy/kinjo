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



<div class="container">
    <!-- Breadcrumbs -->
    <div class="row">
        <div class="cart_nav span12">
            <ul class="list row">
                <li class="item span3 current">
                    <a href="cart.php">Shopping cart</a>
                </li>
                <li class="item span3">
                    <a href="address.php">Delivery address</a>
                </li>
                <li class="item span3">
                    <a href="shipping.php">Shipping / payment</a>
                </li>
                <li class="item span3">
                    <a href="summary.php">Summary</a>
                </li>
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
                <div class="span1 text-center">
                    <h4 class="title">QTY.</h4>
                </div>
                <div class="span3 text-center">
                    <h4 class="title">Price</h4>
                </div>
            </div>
        </div>
        
        
         <?php if(!empty($ordOPen)){
         	$path = '/images/upload/products/';
       			foreach($ordOPen['OrdBuS'] as $key => $row){
       				foreach ($row['BuDetails'] as $key2 => $row2) {
       				
       					$img = substr(strrchr($row2['ProdImg'], '/'), 1);
       					
       					// echo '<pre/>'; 
       					// print_r($img);
	       				echo '<div class="product span12" id="'.$row2['ID'].'">
						            <div class="row">
						                <div class="span8 clearfix">
						                    <div class="image">
						                        <a href="product.php">
						                            <img src="'.$path.$img.'" alt=""/>
						                        </a>
						                    </div>
						                    <div class="detail">
						                        <p>'.$row2['ProdName'].' by <a href="category_template_2.php">'.$row2['SubCatName'].'</a></p>
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
						                    <button type="button" DetId="'.$row2['ID'].'" class="close cardRmvCls">×</button>
						                    <h5 class="peritem">'.$row2['Qnt'].'x'.$row2['Price'].'€</h5>
						                    <span class="total">70.00€</span>
						                </div>
						            </div>
						        </div>';
					}
       			}
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

        <div class="total span12">
            <div class="row">
                <div class="items text-right span3 offset6">
                    4 items in total price
                </div>
                <div class="price text-center span3">169€</div>
            </div>
        </div>
        <div class="span12 actions">
            <div class="row">
                <div class="span3">
                    <a href="home.php" class="button darkgrey">back to shopping</a>
                </div>
                <div class="text-right span3 offset6">
                    <a href="address.php" class="button button-fluid mustard">Procced to address</a>
                </div>
            </div>
        </div>

    </div>
    <!-- /Cart -->

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
<script>
	$('.cardRmvCls').click(function(e){
		e.preventDefault();
		var DetId = $(this).attr('DetId') ;
		// alert($('.showCard').html());
		// return;
		if (confirm("Remove From Card ?")) {
			var xx ={
				c_id : DetId , 
				id   : $('.showCard').html()
			};
			
			$.post( "/home/RmvFromOrder/", xx , function( data ) {
								
				data = data.trim();
				var jsonData = data.toString();
				
				endData = $.parseJSON(jsonData);
			
				if(endData['error'] && endData['error'] !="")
				{
					alert(endData['error']['message']);
				}else{						
					// window.location.reload(true);
					$( "#"+DetId ).remove();
				}
				
			});
			
		}
	});
</script>
</body>
</html>







