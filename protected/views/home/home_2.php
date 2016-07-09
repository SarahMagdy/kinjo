

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
        <script src="/js/static-jquery.js"></script>
        <script src="/bootstrap/js/static-bootstrap.min.js"></script>

    </head>

    <body>

        <!-- 
        ==================================================================
        Header Area
        ==================================================================
        -->
<?php
include("header.php");
?>

<!-- 
==================================================================
Featured Slider Area
==================================================================
-->

<div id="home">

    <div class="fullwidth section featured_bg1 clearfix">
        <div class="container">
            <div class="row clearfix">
                <div class="span12">
                    <h2 class="uppercase promo text-center">45% Summer  sale  is  here</h2>
                    <h3 class="uppercase promo text-center">for all new summer collections</h3>
                </div>
            </div>
            <div class="row clearfix">
                <div class="span4 clearfix">
                    <div class="featured_category ux_banner hover_zoom">
                        <div class="row">
                            <div class="banner_in uppercase">
                                <a href="#">The best shoes ever</a>
                            </div>
                        </div>
                        <div class="banner_bg category_prod1"></div>
                    </div>
                </div>
                <div class="span4 clearfix">
                    <div class="featured_category ux_banner hover_zoom">
                        <div class="row">
                            <div class="banner_in uppercase">
                                <a href="#">The best shoes ever</a>
                            </div>
                        </div>
                        <div class="banner_bg category_prod2"></div>
                    </div>
                </div>
                <div class="span4 clearfix">
                    <div class="featured_category ux_banner hover_zoom">
                        <div class="row">
                            <div class="banner_in uppercase">
                                <a href="#">The best shoes ever</a>
                            </div>
                        </div>
                        <div class="banner_bg category_prod3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 
    ==================================================================
    Featured Banner
    ==================================================================
    -->


    <div class="container">
        <div class="row twenty_margin_top clearfix">
            <div class="span6 clearfix">
                <div class="ux_banner slim hover_zoom">
                    <div class="row">
                        <div class="banner_in banner_padding">
                            <h3 class="white uppercase font-light nomargin nopadding">Men<br/> Wear</h3>
                            <a class="button mustard" href="#">Shop Now</a>
                        </div>
                    </div>
                    <div class="banner_bg banner1"></div>
                </div>
            </div>
            <div class="span6 clearfix">
                <div class="ux_banner slim hover_zoom">
                    <div class="row">
                        <div class="banner_in banner_padding">
                            <h3 class="white uppercase font-light nomargin nopadding">Women<br/> Wear</h3>
                            <a class="button mustard" href="#">Shop Now</a>
                        </div>
                    </div>
                    <div class="banner_bg banner2"></div>
                </div>
            </div>
        </div>
        <div class="row twenty_margin_top clearfix">
            <div class="span6 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <a href="#">
                        <div class="row">
                            <div class="banner_in banner_padding">
                                <h3 class="white uppercase font-light nomargin nopadding text-center">get new travel bags</h3>
                            </div>
                        </div>
                        <div class="banner_bg banner3"></div>
                    </a>
                </div>
            </div>
            <div class="span6 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in">
                            <div class="description_box">
                                <h4 class="darkgrey uppercase font-light nomargin nopadding">time for skate</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent velit velit, gravida vel felis id, convallis posuer est.</p>
                                <a class="button mustard" href="#">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="banner_bg banner4"></div>
                </div>
            </div>
        </div>
        <div class="row twenty_margin_top clearfix">
            <div class="span12 clearfix">
                <div class="ux_banner slim hover_zoom">
                    <a href="#">
                        <div class="row">
                            <div class="banner_in banner_padding">
                                <h3 class="black uppercase font-light nomargin nopadding text-center">25 % discount for shoes</h3>
                            </div>
                        </div>
                        <div class="banner_bg banner5"></div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row twenty_margin_top clearfix">
            <div class="span8 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">american dream</h3>
                            <a class="button mustard pull-left" href="#">Shop Now</a>
                        </div>
                    </div>
                    <div class="banner_bg banner6"></div>
                </div>
            </div>
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <a href="#">
                        <div class="row">
                            <div class="banner_in banner_padding">
                                <h3 class="white uppercase font-light nomargin nopadding text-center two-lines">get inspired <br/>by our lookbooks</h3>
                            </div>
                        </div>
                        <div class="banner_bg banner7"></div>
                    </a>
                </div>
            </div>
        </div>

        <!-- 
        ==================================================================
        Big Products Section
        ==================================================================
        -->

        <div class="row twenty_margin_top clearfix">
            <div class="span12 clearfix">
                <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Featured products <span class="lightgray">for this week</span></span></h3>
            </div>
        </div>
        
     	<div class="row twenty_margin_top clearfix">
     		
     		<?php 
     			if(!empty($proArr)){
		        	// print_r($proArr);
		        	$path = '/images/upload/products/';
		        	foreach($proArr as $key => $row){
		        		echo'<div class="span3 product_image clearfix">
				         		<div class="flip_image">
				                    <a href="/home/proDetail/'.$row['proID'].'"><!-- href="product.html" -->
				                        <div class="front_image">
				                            <img src="'.$path.$row['pimg_url'].'" alt="" /><!-- src="/images/products/prod13.png" -->
				                        </div>
				                    </a>
				            	</div>
				                <div class="description clearfix">
				                    <p class="white nomargin">
				                        '.$row['title'].'<br/> by <a href="category_template_2.html">'.$row['SubCatName'].'</a>
				                    </p>
				                    <span class="price white">
				                       '.$row['price'].'€
				                    </span>
				                </div>
				        	</div>';
		        	}
		        }
	        ?>
        	
        	<!-- <div class="span3 product_image clearfix">
            	<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod14.png" alt="" />
                        </div>
                    </a>
                </div>
                <div class="description clearfix">
                    <p class="white nomargin">
                        Red Reign Woven Shirt<br/> by <a href="category_template_2.html">HUF</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
        	</div>
        	<div class="span6 product_image clearfix">
            	<div class="flip_image pull-left">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/1.png" alt="" />
                        </div>
                    </a>
                </div>
           		<div class="description pull-left sweaters longsleeves">
               		<h4 class="white font-light nomargin nopadding">Bar Logo T-shirt <br/> <span>by <a href="category_template_2.html">Almost</a></span></h4>
                	<span class="price white">
                   	 65.00€
                	</span>
                	<p class="lightgray nomobile">
                   	 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at velit egestas risus.
                	</p>
                    <p class="lightgray">
                        Choose Size:
                    </p>
                    <ul class="sizes">
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
                        <li class="item dib">
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
               		<a href="product.html" class="button button-fluid mustard">Add To Cart</a>
            	</div>
        	</div>
		</div> -->
        <!-- <div class="row twenty_margin_top clearfix">
        	<div class="span3 product_image clearfix">
            	<div class="flip_image">
                	<a href="product.html">
                    	<div class="front_image">
                        	<img src="/images/products/prod1.png" alt="" />
                    	</div>
                    	
                	</a>
            	</div>
            	<div class="description clearfix">
                	<p class="white nomargin">
                    	Era Black Shoes <br/> by <a href="category_template_2.html">Vans</a>
                	</p>
                	<span class="price white">
                    	109.50€
                	</span>
            	</div>
        	</div>
        	<div class="span3 product_image clearfix">
            	<div class="flip_image">
	                <a href="product.html">
	                    <div class="front_image">
	                        <img src="/images/products/prod4.png" alt="" />
	                    </div>
	                   
	                </a>
	            </div>
	            <div class="description clearfix">
	                <p class="white nomargin">
	                    Birght Eyes T-shirt <br/> by <a href="category_template_2.html">Altamont</a>
	                </p>
	                <span class="price white">
	                    109.50€
	                </span>
	            </div>
        	</div>
        	<div class="span3 product_image clearfix">
	            	<div class="flip_image">
	                <a href="product.html">
	                    <div class="front_image">
	                        <img src="/images/products/prod5.png" alt="" />
	                    </div>
	                   
	                </a>
	            </div>
	            <div class="description clearfix">
	                <p class="white nomargin">
	                    Coolwood T-shirt <br/> by <a href="category_template_2.html">Billabong</a>
	                </p>
	                <span class="price white">
	                    109.50€
	                </span>
	            </div>
            </div>
        	<div class="span3 product_image clearfix">
          		<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod6.png" alt="" />
                        </div>
                       
                    </a>
                </div>
                <div class="description clearfix clearfix">
                    <p class="white nomargin">
                        Fresh Shirt-Shortsleeve<br/> by <a href="category_template_2.html">Carhatt</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
        	</div>
       	</div> -->
          <!-- <div class="row twenty_margin_top clearfix">
            	<div class="span3 product_image clearfix">
                	<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod8.png" alt="" />
                        </div>
                       
                    </a>
                </div>
                <div class="description clearfix">
                    <p class="white nomargin">
                       Corry T-shirt<br/> by <a href="category_template_2.html">Element</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
            	</div>
            	<div class="span6 product_image clearfix">
                	<div class="flip_image pull-left">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/2.png" alt="" />
                        </div>
                       
                    </a>
                </div>
                <div class="description pull-left">
                    <h4 class="white font-light nomargin nopadding">Bar Logo T-shirt <br/> <span>by <a href="category_template_2.html">Almost</a></span></h4>
                    <span class="price white">
                        65.00€
                    </span>
                    <p class="lightgray nomobile">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at velit egestas risus.
                    </p>
                    <p class="lightgray">
                        Choose Size:
                    </p>
                    <ul class="sizes">
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
                        <li class="item dib">
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
                    <a href="product.html" class="button button-fluid mustard">Add To Cart</a>
                </div>
            	</div>
            	<div class="span3 product_image clearfix">
                	<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod15.png" alt="" />
                        </div>
                       
                    </a>
                </div>
                <div class="description clearfix">
                    <p class="white nomargin">
                        Side Zip Hoodie<br/> by <a href="category_template_2.html">Chico</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
            	</div>
      	</div> -->
          <!-- <div class="row twenty_margin_top clearfix">
            	<div class="span3 product_image clearfix">
                	<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod9.png" alt="" />
                        </div>
                     
                    </a>
                </div>
                <div class="description clearfix">
                    <p class="white nomargin">
                       Bill Murray Sweatshirt<br/> by <a href="category_template_2.html">Abandon Ship</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
            	</div>
            	<div class="span3 product_image clearfix">
                	<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod3.png" alt="" />
                        </div>
                      
                    </a>
                </div>
                <div class="description clearfix">
                    <p class="white nomargin">
                        Era Black Shoes<br/> by <a href="category_template_2.html">Vans</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
            	</div>
            	<div class="span3 product_image clearfix">
                	<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod16.png" alt="" />
                        </div>
                      
                    </a>
                </div>
                <div class="description clearfix">
                    <p class="white nomargin">
                        Fresh Shirt-Shortsleeve<br/> by <a href="category_template_2.html">Carhatt</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
            	</div>
            	<div class="span3 product_image clearfix">
                	<div class="flip_image">
                    <a href="product.html">
                        <div class="front_image">
                            <img src="/images/products/prod7.png" alt="" />
                        </div>
                       
                    </a>
                </div>
                <div class="description clearfix">
                    <p class="white nomargin">
                       Eskimo Toast T-shirt<br/> by <a href="category_template_2.html">Cleptomanicx</a>
                    </p>
                    <span class="price white">
                        109.50€
                    </span>
                </div>
            	</div>
        </div> -->
        
        
        
        
        
        <div class="row twenty_margin_top ten_padding_bottom clearfix">
            <div class="span12 clearfix">
                <a class="load_more uppercase lightgray" href="#">
                    <img src="/images/elements/plus.png" alt="" />
                    View more
                </a>
            </div>
        </div>
    </div>

    <!-- 
    ==================================================================
    Newsletter
    ==================================================================
    -->

 <!--   <div class="fullwidth clearfix newsletter_cta twenty_margin_top">
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
    </div>-->

    <!-- 
    ==================================================================
    Small Products 3 Column Section
    ==================================================================
    -->

    <div class="container">
        <div class="row twenty_margin_top clearfix">
            <div class="span4">
                <h4 class="heading darkgrey font-light uppercase twenty_margin_bottom"><span class="heading_whitebg">Top sellers</span></h4>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small1.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            Eskimo Toast T-shirt<br/> by <span>Cleptomanicx</span>
                        </a><br/><br/>
                        <span class="price">
                            35.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small4.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            Era Black Shoes <br/>by <span>Vans</span>
                        </a><br/><br/>
                        <span class="price">
                            65.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small7.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
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
                        <a href="#">
                            <img src="/images/products/small2.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            Spicoli 4 Shades<br/> by <span>Vans</span>
                        </a><br/><br/>
                        <span class="price">
                            25.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small5.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            Side Zip Hoodie <br/>by <span>Chico</span>
                        </a><br/><br/>
                        <span class="price">
                            48.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small8.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            Bill Murray Sweatshirt <br/> by <span>Abandon Ship</span>
                        </a><br/><br/>
                        <span class="price">
                            59.50€
                        </span>
                    </div>
                </div>
            </div>
            <div class="span4">
                <h4 class="heading darkgrey font-light uppercase twenty_margin_bottom"><span class="heading_whitebg">new stuff</span></h4>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small3.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            Fresh Shirt-Shortsleeve<br/> by <span>Carhartt</span>
                        </a><br/><br/>
                        <span class="price">
                            35.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small6.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            AV Native American <br/>by <span>Vans</span>
                        </a><br/><br/>
                        <span class="price">
                            65.00€
                        </span>
                    </div>
                </div>
                <div class="product_row clearfix">
                    <div class="image">
                        <a href="#">
                            <img src="/images/products/small9.png" alt="" />
                        </a>
                    </div>
                    <div class="detail">
                        <a class="darkgrey" href="#">
                            Coolwood T-shirt <br/> by <span>Billabong</span>
                        </a><br/><br/>
                        <span class="price">
                            31.50€
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <!-- 
        ==================================================================
        Low In Stock Section
        ==================================================================
        -->

        <div class="row twenty_margin_top clearfix">
            <div class="span12">
                <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Last Minute <span class="lightgray">Super Sale</span></span></h3>
            </div>
        </div>

        <div class="row twenty_margin_top clearfix">
            <div class="span4 product_detailed">
                <div class="row">
                    <div class="span4 product_image">
                        <div class="image flip_image">
                            <a href="#">
                                <div class="front_image">
                                    <img src="/images/products/prod10.png" alt="" />
                                </div>
                                <!--<div class="back_image">
                                    <img src="images/products/prod3.png" alt="" />
                                    <span>Available Sizes<br/>
                                        XS S M L XL
                                    </span>
                                </div>-->
                            </a>
                        </div>
                        <div class="image_ux">
                            <div class="section">
                                Size<br/>
                                <select>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="section">
                                Quantity<br/>
                                <select>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="section">
                                <a class="cart uppercase" href="#">
                                    Add <br/>to cart
                                </a>
                            </div>
                            <div class="section nomobile">
                                <a class="lightgray wishlist" href="#">
                                    Wishlist
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Authentic Red Unisex Shoes<br/> by <a href="#">Vans</a>
                            </p>
                            <span class="price white">
                                109.50€
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span4 product_detailed">
                <div class="row">
                    <div class="span4 product_image">
                        <div class="image flip_image">
                            <a href="#">
                                <div class="front_image">
                                    <img src="/images/products/prod11.png" alt="" />
                                </div>
                            <!--<div class="back_image">
                                    <img src="images/products/prod3.png" alt="" />
                                    <span>Available Sizes<br/>
                                        XS S M L XL
                                    </span>
                                </div>-->
                            </a>
                        </div>
                        <div class="image_ux">
                            <div class="section">
                                Size<br/>
                                <select>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="section">
                                Quantity<br/>
                                <select>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="section">
                                <a class="cart uppercase" href="#">
                                    Add <br/>to cart
                                </a>
                            </div>
                            <div class="section nomobile">
                                <a class="lightgray wishlist" href="#">
                                    Wishlist
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Authentic Red Unisex Shoes<br/> by <a href="#">Vans</a>
                            </p>
                            <span class="price white">
                                109.50€
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span4 product_detailed">
                <div class="row">
                    <div class="span4 product_image">
                        <div class="image flip_image">
                            <a href="#">
                                <div class="front_image">
                                    <img src="/images/products/prod12.png" alt="" />
                                </div>
                            <!--<div class="back_image">
                                    <img src="images/products/prod3.png" alt="" />
                                    <span>Available Sizes<br/>
                                        XS S M L XL
                                    </span>
                                </div>-->
                            </a>
                        </div>
                        <div class="image_ux">
                            <div class="section">
                                Size<br/>
                                <select>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="section">
                                Quantity<br/>
                                <select>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <div class="section">
                                <a class="cart uppercase" href="#">
                                    Add <br/>to cart
                                </a>
                            </div>
                            <div class="section nomobile">
                                <a class="lightgray wishlist" href="#">
                                    Wishlist
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="span4">
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Authentic Red Unisex Shoes<br/> by <a href="#">Vans</a>
                            </p>
                            <span class="price white">
                                109.50€
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        ==================================================================
        Blog | Instagram
        ==================================================================
        -->

        <div class="row twenty_margin_top clearfix">
            <div class="span6 home_blog">
                <div class="row clearfix">
                    <div class="span6">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Blog <span class="lightgray">By Our Team </span></span></h3>
                    </div>
                </div>
                <div class="row twenty_margin_top clearfix">
                    <div class="span6">
                        <div class="image">
                            <a href="#">
                                <img src="/images/blog1.png" alt="" />
                            </a>
                        </div>
                        <div class="excerpt">
                            <a class="darkgrey uppercase nomargin title" href="#">How to choose shoes</a>
                            <p class="darkgrey nomargin nopadding">Lorem ipsum dolor sit amet, consectetur adipi elit. Ut vel lacus justo. Nullam feugiat velit euismod auctor volutpat. In congue, libero.</p>
                            <a href="#" class="uppercase">Read more</a>
                        </div>
                    </div>
                </div>
                <div class="row twenty_margin_top clearfix">
                    <div class="span6">
                        <div class="image">
                            <a href="#">
                                <img src="/images/blog1.png" alt="" />
                            </a>
                        </div>
                        <div class="excerpt">
                            <a class="darkgrey uppercase nomargin title" href="#">How to choose shoes pt.2</a>
                            <p class="darkgrey nomargin nopadding">Lorem ipsum dolor sit amet, consectetur adipi elit. Ut vel lacus justo. Nullam feugiat velit euismod auctor volutpat. In congue, libero.</p>
                            <a href="#" class="uppercase">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span6 instagram">
                <div class="row clearfix">
                    <div class="span6">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Instagram <span class="lightgray">Photos </span></span></h3>
                    </div>
                </div>
                <div class="row twenty_margin_top clearfix">
                    <div class="span6">
                        <div class="image">
                            <a href="#">
                                <img src="/images/instagram/inst1.png" alt="" />
                            </a>
                        </div>
                        <div class="image">
                            <a href="#">
                                <img src="/images/instagram/inst2.png" alt="" />
                            </a>
                        </div>
                        <div class="image">
                            <a href="#">
                                <img src="/images/instagram/inst3.png" alt="" />
                            </a>
                        </div>
                        <div class="image last">
                            <a href="#">
                                <img src="/images/instagram/inst4.png" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row twenty_margin_top clearfix">
                    <div class="span6">
                        <div class="image">
                            <a href="#">
                                <img src="/images/instagram/inst5.png" alt="" />
                            </a>
                        </div>
                        <div class="image">
                            <a href="#">
                                <img src="/images/instagram/inst6.png" alt="" />
                            </a>
                        </div>
                        <div class="image">
                            <a href="#">
                                <img src="/images/instagram/inst7.png" alt="" />
                            </a>
                        </div>
                        <div class="image nomargin">
                            <a href="#">
                                <img src="/images/instagram/inst8.png" alt="" />
                            </a>
                        </div>
                    </div>
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
<?php
include('footer.php');
?>

<!-- /Popup 2-->

<script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
<script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="/js/static-jquery.cookie.js"></script>
<!--<script src="js/script.js" type="text/javascript"></script>-->
<script type="text/javascript">
x=document.getElementById('search_field')
x.onfocus=function(){
    this.value = "";
}
</script>

</body>
</html>

