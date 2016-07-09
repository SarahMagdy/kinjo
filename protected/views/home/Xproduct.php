<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Kinjo store</title>

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



		<script>
			
			$(document).ready(function () {
				$('.colclass').click(function(e){
					e.preventDefault();
					$('.colclass').removeClass('colselected');
					
					$(this).addClass('colselected');
					// alert('dddd');
				});
			});
			
			
			function AddToWishList(){
				var data ={
					pid : <?= $proArr['Result']['Product']['ProID'];?>
				}; 
				
				$.post( "/home/AddWishList/",data, function( data ) {
					// location.reload();
					data = data.trim();
					if(data && data !="")
					{
						var json_data = data.toString();
						if(json_data.length > 10){
							end_data = $.parseJSON(json_data);
							for (var key in end_data){
								alert(end_data[key]['message']);
							}
							
						}
					}else{
						alert('Product Added to your WishList');
					}
					
				});
			}
			
			
			function AddToCard(){
				
/*
				var options = {
					enableHighAccuracy: true,
					timeout: 5000,
					maximumAge: 0
				};
				
				function success(pos) {
					var crd = pos.coords;
					
					// console.log('Your current position is:');
					// console.log('Latitude : ' + crd.latitude);
					// console.log('Longitude: ' + crd.longitude);
					// console.log('More or less ' + crd.accuracy + ' meters.');
					console.log(crd);
					var latitude  = crd.latitude;
					var longitude = crd.longitude;
				
					var data ={
						p_id     : 14 , 
						long  :  longitude , 
						lat   :  latitude ,
						bu_id : 3
					};
					$.post( "/home/AddToCard/",data, function( data ) {
						
						data = data.trim();
						if(data && data !="")
						{
							var json_data = data.toString();
							if(json_data.length > 10){
								end_data = $.parseJSON(json_data);
								for (var key in end_data){
									alert(end_data[key]['message']);
								}
							}
						}else{
							alert('Product Added to your Order');
						}
						
						
					});
				};
				
				function error(err) {
					console.warn('ERROR(' + err.code + '): ' + err.message);
				};
				
				navigator.geolocation.getCurrentPosition(success, error, options);*/

				var onSuccess = function(location){
					// alert("Lookup successful:\n\n" + JSON.stringify(location, undefined, 4));
					
					var json_data = JSON.stringify(location, undefined, 4);
					end_data = $.parseJSON(json_data);
					
					// alert(end_data['country']['iso_code']);
					
					var latitude  = end_data['location']['latitude'];
					var longitude = end_data['location']['longitude'];
					var ordConfArr = new Array();
					// var confArr = new Array();
					var color = "";
					var confString = "";
					
					// alert($("input:radio.conf:checked").val());
					var obj = $("input:radio.conf:checked");
					obj.each(function(entry) {
					    // console.log($(this).val());
					    // alert($(this).val());
					    // confArr.push($(this).val());
					    confString += $(this).val() + ',';
					});
					
					confString = confString.substring(0,confString.length - 1);
					
					ordConfArr.push({"qnt": document.getElementById('quantity').innerHTML ,  "conf":confString , "color":$(".colselected").attr("id")});
					
					// coloString = ''
					// alert(ordConfArr);
					// return;
					var mydata ={
						p_id  : <?= $proArr['Result']['Product']['ProID'];?> , 
						long  :  longitude , 
						lat   :  latitude ,
						bu_id : 3 ,
						iso   : end_data['country']['iso_code'],
						// qnt   : document.getElementById('quantity').innerHTML ,
						// color : $(".colselected").attr("id"), // $('.ordColor').attr('color_id'),
						// conf  : confArr
						Q_Conf : ordConfArr
						
					};
					// alert($('.ordColor').getAttribute('color_id'));
					
					var list = document.getElementsByClassName("ordColor");
					// return;
					if(document.getElementById('quantity').innerHTML > 0){
						$.post( "/home/AddToCard/",mydata, function( data ) {
							
							data = data.trim();
							var jsonData = data.toString();
							
							endData = $.parseJSON(jsonData);
							// alert(endData['order'][0]['ord_id']);return;
							if(endData['error'] && endData['error'] !="")
							{
								alert(endData['error']['message']);
							}else{
								alert('Product Added to your Order');								
								window.location.reload(true);
							}
							
						});
					}else{
						alert('Choose Quantity');
					}
	
				};
				 
				var onError = function(error){
				  alert(
				      "Error:\n\n"
				      + JSON.stringify(error, undefined, 4)
				  );
				};
				 
				geoip2.city(onSuccess, onError);
				
			}
			
		</script>
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
	<?php 
		// $RealAdrr = Globals::ReturnGlobals();
		// $ImgPath = $RealAdrr['ImgPath'] . 'products/';
		$path = '/images/upload/products/';
	?>
    <!-- Product Details -->
    <div class="row product">
        <div class="span6">
            <figure>
                <div class="main">
                    <img src="<?= $path.$proArr['Result']['Product']['ProImgs'][0]['pimg_url'];?>" alt=""/><!-- src="/images/products/1prod-detail.png" -->
                </div>
                <ul class="thumbs">
                	<?php 
                		foreach($proArr['Result']['Product']['ProImgs'] as $key => $row ){
							echo '<li class="item dib">
			                        <a href="#">
			                            <img src="'.$path.$row['pimg_url'].'" alt=""/>
			                        </a>
			                    </li>';
                		}
                	?>
                	
                    <!-- <li class="item dib">
                        <a href="#">
                            <img src="/images/products/1prod-detail.png" alt=""/>
                        </a>
                    </li>
                    <li class="item dib">
                        <a href="#">
                            <img src="/images/products/2prod-detail.png" alt=""/>
                        </a>
                    </li>
                    <li class="item dib">
                        <a href="#">
                            <img src="/images/products/3prod-detail.png" alt=""/>
                        </a>
                    </li> -->
                </ul>
            </figure>
        </div>
        <div class="span6">
            <h3 class="title font-light"><?= $proArr['Result']['Product']['ProTitle']?></h3><!-- KINJO T-shirt -->
            <div class="author">by <a href="#"><?= $proArr['Result']['Product']['SubCatName'];?></a></div><!-- kINJO -->
            <div class="price"><?= $proArr['Result']['Product']['ProPrice'].'$';?></div>
            <p>
               <?= $proArr['Result']['Product']['ProDesc'];?> Data Info .
            </p>
            <ul class="info">
                <li class="item clearfix">
                    <label class="pull-left">Rating</label>
                    <ul class="rating pull-right">
                       
                       <?php $rateClass = "";
                       		for($i=1 ; $i<=5 ; $i++){
                       			if($i<= $proArr['Result']['Product']['ProRate'] ){
	                       			$rateClass = "normal";
								}else{
									$rateClass = "";
								}
								echo '<li class="item '.$rateClass.' dib">
			                          	   <i class="icon"></i>
			                          </li>';
                       		}
                       ?>
                       
                        <!-- <li class="item normal dib">
                            <i class="icon"></i>
                        </li>
                        <li class="item normal dib">
                            <i class="icon"></i>
                        </li>
                        <li class="item normal dib">
                            <i class="icon"></i>
                        </li>
                        <li class="item normal dib">
                            <i class="icon"></i>
                        </li>
                        <li class="item dib">
                            <i class="icon"></i>
                        </li> -->
                    </ul>
                </li>
                <?php if(isset($proArr['Result']['Product']['ProColors']) && !empty($proArr['Result']['Product']['ProColors'])):?>
	                <li class="item clearfix">
	                    <label class="pull-left">Choose colour</label>
	                    <ul class="product_colors pull-right">
	                    	<?php 
	                    		foreach($proArr['Result']['Product']['ProColors'] as $key=> $row){ //onClick="color($this.id);"
	                    			echo '<a href="#" id="'.$row['ColorID'].'" class="colclass" ><li class="item dib" style="background-color:'.$row['ColorCode'].';"></li></a>';
	                    		}
	                    	?>
	                        <!-- <li class="item red dib"></li>
	                        <li class="item blue dib"></li>
	                        <li class="item yellow dib"></li>
	                        <li class="item green dib"></li> -->
	                    </ul>
	                </li>
	            <?php endif;?>
	            
	            
	            
	            <?php
	            	foreach ($proArr['Result']['Product']['ProConfs'] as $key => $val){
						echo '<li class="item clearfix">
			                  		<label class="pull-left">'.$val['Conf'].'</label>
			                    	<ul class="sizes product_sizes pull-right">';
			          
		            	foreach ($val['SubConfig'] as $key2 => $val2) {
							echo '<li class="item dib">
		                            <label>
		                                <input type="radio" name="'.$val['Conf'].'" value="'.$val2['subId'].'" class="conf"/>
		                                <b>'.$val2['SubConf'].'</b>
		                            </label>
		                        </li>';
						}
			            echo '</ul>
                			  </li>';
					}
	            ?>
	            
                <!-- <li class="item clearfix">
                    <label class="pull-left">Choose size</label>
                    <ul class="sizes product_sizes pull-right">
                        <li class="item dib">
                            <label>
                                <input type="radio" name="size"/>
                                <b>XS</b>
                            </label>
                        </li>
                        <li class="item dib">
                            <label>
                                <input type="radio" name="size"/>
                                <b>S</b>
                            </label>
                        </li>
                        <li class="item dib unavailable">
                            <label>
                                <input type="radio" name="size"/>
                                <b>M</b>
                            </label>
                        </li>
                        <li class="item dib">
                            <label>
                                <input type="radio" name="size"/>
                                <b>L</b>
                            </label>
                        </li>
                        <li class="item dib">
                            <label>
                                <input type="radio" name="size"/>
                                <b>XL</b>
                            </label>
                        </li>
                    </ul>
                </li> -->
                
                <li class="item clearfix">
                    <label class="pull-left">Availability</label>
                    <div class="availability pull-right">On stock - Sending within 24 hours</div>
                </li>
                
                <li class="item clearfix">
                    <label class="pull-left">Quantity</label>
                    <div class="quantity pull-right">
                        <div class="urbaspin inline">
                            <i data-arrow="down">-</i>
                            <label id="quantity">1</label>
                            <i data-arrow="up">+</i>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="row actions">
                <div class="span4">
                    <button class="button mustard" onclick="AddToCard()">Add to cart</button>
                </div>
                <div class="span2">
                    <button class="button button-fluid darkgrey wishlist" onclick="AddToWishList()">Add to wishlist</button>
                </div>
            </div>
        </div>

        <div class="span12 detail">
            <div class="row">

                <!-- Description -->
                <div class="description span6">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#details" data-toggle="tab">Detail Info</a>
                        </li>
                        <li class="">
                            <a href="#charts" data-toggle="tab">Chart Sizes</a>
                        </li>
                        <li class="">
                            <a href="#share" data-toggle="tab">Share</a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="details">
                            <p>
                               Data Info Data Info Data Info Data Info Data Info Data Info . 
                                <br><br>Data Info Data Info Data Info Data Info Info Data Info Data Info .
                            </p>
                        </div>
                        <div class="tab-pane fade" id="charts">
                            <p>
                               Data Info Data Info Data Info Data Info Data Info Data Info Data Info .
                                <br><br>Data Info Data Info Data Info Data Info Data Info Data Info .
                            </p>
                        </div>
                        <div class="tab-pane fade" id="share">
                            <p>
                                Data Info Data Info Data Info Data Info Data Info Data Info Data Info .
                                <br><br>Data Info Data Info Data Info Data Info Data Info Data Info Data Info Data Info .
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /Description -->                

                <!-- Reviews -->
                <div class="reviews span6">
                    <h5 class="title">Reviews</h5>
                    <ul class="list">
                        <li class="item row">
                            <div class="author span1">
                                <figure>
                                    <img src="/images/author.png" alt=""/>
                                </figure>
                                <p class="name">Zidan</p>
                            </div>
                            <div class="span5">
                                <p>
                                    Data Info Data Info Data Info Data Info Data Info Data Info Data Info .
                                </p>
                                <span class="date">13/13/2013</span>
                            </div>
                        </li>
                        <li class="item row">
                            <div class="author span1">
                                <figure>
                                    <img src="/images/author.png" alt=""/>
                                </figure>
                                <p class="name">Zidan</p>
                            </div>
                            <div class="span5">
                                <p>
                                     Data Info Data Info Data Info Data Info Data Info Data Info Data Info Data Info Data Info.
                                </p>
                                <span class="date">13/13/2013</span>
                            </div>
                        </li>
                    </ul>
                    <div class="span2 pull-right">
                        <a href="#" class="button darkgrey">Add Review</a>
                    </div>
                </div>
                <!-- /Reviews -->

            </div>
        </div>

    </div>
    <!-- /Product Details -->

    <!-- Recommended Products -->
    <div class="row twenty_margin_top clearfix">
        <div class="span12">
            <h3 class="heading darkgrey font-light uppercase">
                <span class="heading_whitebg">Related Products
                    <span class="lightgray">For You</span>
                </span>
            </h3>
        </div>
    </div>
    <div class="row twenty_margin_top clearfix">
        <div class="span3 product_image clearfix">
            <div class="flip_image">
                <a href="#">
                    <div class="front_image">
                        <img src="/images/products/prod1.png" alt="">
                    </div>
                <!--<div class="back_image">
                        <img src="images/products/prod3.png" alt="">
                        <span>Available Sizes<br>
                            XS S M L XL
                        </span>
                    </div>-->
                </a>
            </div>
            <div class="description clearfix">
                <p class="white nomargin">
                    Authentic Red Unisex <br> by <a href="#">Vans</a>
                </p>
                <span class="price white">
                    109.50€
                </span>
            </div>
        </div>
        <div class="span3 product_image clearfix">
            <div class="flip_image">
                <a href="#">
                    <div class="front_image">
                        <img src="/images/products/prod4.png" alt="">
                    </div>
                <!--    <div class="back_image">
                        <img src="images/products/prod3.png" alt="">
                        <span>Available Sizes<br>
                            XS S M L XL
                        </span>
                    </div>-->
                </a>
            </div>
            <div class="description clearfix">
                <p class="white nomargin">
                    Authentic Red Unisex <br> by <a href="#">Vans</a>
                </p>
                <span class="price white">
                    109.50€
                </span>
            </div>
        </div>
        <div class="span3 product_image clearfix">
            <div class="flip_image">
                <a href="#">
                    <div class="front_image">
                        <img src="/images/products/prod5.png" alt="">
                    </div>
                <!--    <div class="back_image">
                        <img src="images/products/prod3.png" alt="">
                        <span>Available Sizes<br>
                            XS S M L XL
                        </span>
                    </div>-->
                </a>
            </div>
            <div class="description clearfix">
                <p class="white nomargin">
                    Authentic Red Unisex <br> by <a href="#">Vans</a>
                </p>
                <span class="price white">
                    109.50€
                </span>
            </div>
        </div>
        <div class="span3 product_image clearfix">
            <div class="flip_image">
                <a href="#">
                    <div class="front_image">
                        <img src="/images/products/prod6.png" alt="">
                    </div>
                    <div class="back_image">
                        <img src="/images/products/prod3.png" alt="">
                        <span>Available Sizes<br>
                            XS S M L XL
                        </span>
                    </div>
                </a>
            </div>
            <div class="description clearfix clearfix">
                <p class="white nomargin">
                    Authentic Red Unisex <br> by <a href="#">Vans</a>
                </p>
                <span class="price white">
                    109.50€
                </span>
            </div>
        </div>
    </div>
    <!-- /Recommended Products -->

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


<div class="container">
        <div class="row twenty_margin_top clearfix">
            <div class="span4">
                <h4 class="heading darkgrey font-light uppercase twenty_margin_bottom"><span class="heading_whitebg">Top sellers</span></h4>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.php">
                            <img src="/images/products/small1.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Eskimo Toast T-shirt<br/> by <span>Cleptomanicx</span>
                        </a><br/><br/>
                        <span class="price">
                            35.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small4.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Era Black Shoes <br/>by <span>Vans</span>
                        </a><br/><br/>
                        <span class="price">
                            65.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small7.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Corry T-shirt <br/> by <span>Element</span>
                        </a><br/><br/>
                        <span class="price">
                            39.50€
                        </span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <h4 class="heading darkgrey font-light uppercase twenty_margin_bottom"><span class="heading_whitebg">on sale</span></h4>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small2.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Spicoli 4 Shades<br/> by <span>Vans</span>
                        </a><br/><br/>
                        <span class="price">
                            25.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small5.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Side Zip Hoodie <br/>by <span>Chico</span>
                        </a><br/><br/>
                        <span class="price">
                            48.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small8.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Bill Murray Sweatshirt <br/> by <span>Abandon Ship</span>
                        </a><br/><br/>
                        <span class="price">
                            59.50€
                        </span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <h4 class="heading darkgrey font-light uppercase twenty_margin_bottom"><span class="heading_whitebg">Hot offers</span></h4>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small3.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Fresh Shirt-Shortsleeve<br/> by <span>Carhartt</span>
                        </a><br/><br/>
                        <span class="price">
                            35.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small6.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            AV Native American <br/>by <span>Vans</span>
                        </a><br/><br/>
                        <span class="price">
                            65.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="product.html">
                            <img src="/images/products/small9.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="product.html">
                            Coolwood T-shirt <br/> by <span>Billabong</span>
                        </a><br/><br/>
                        <span class="price">
                            31.50€
                        </span>
                    </div>
                </div>
            </div>
        </div>
</div>


<!-- 
==================================================================
Footer
==================================================================
-->



<!-- 
==================================================================
Copyright Information
==================================================================
-->

<?php
	include("footer.php");
?>

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