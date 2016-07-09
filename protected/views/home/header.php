<div id="header">
            <div class="container boxaly">
                <div class="row">
                    <div class="span3">
                        <div class="logo">
                              <div class="span2 logoalignaly"><a href="/home/stores"><img src="/images/logokinjo.png"></a></div>
                            <h3 class="textalignaly nopadding nomargin autolineheight font-light" ><span class="mustard textalignaly">Kinjo</span> <span class="lightgray textalignaly">Store</span></h3>
                            <h6 class="nomargin autolineheight darkgrey"></h6>
                        </div>
                    </div>
                    <div class="span8 offset1 twenty_margin_top pull-right" id="account_links">
                        <div class="row">
                            <ul class="inline pull-right">
                            	<?php if(isset(Yii::app()->session['Cust']['CustID']) && !empty(Yii::app()->session['Cust']['CustID'])):?>
	                                <li>
	                                    <i class="icon-info-sign ten_padding_right"></i>
                                    	<a href="/home/CustProfile/1">
                                    		<?=Yii::app()->session['Cust']['CustFName'].' '.Yii::app()->session['Cust']['CustLName'];?>
                                    	</a>
	                                </li>
	                                
	                                <li>
	                                    <i class="icon-heart ten_padding_right"></i><a href="/home/CustWishList">Wishlist (1)</a>
	                                </li>
	                                
	                                <li>
	                               		<i class="icon-user ten_padding_right"></i><a href="/home/LogOut">LogOut</a></p>
	                               		<!-- <i class="icon-user ten_padding_right"></i><a href="login.php">Login</a></p> -->  	
	                                </li>
                                <?php if(isset($ordOPen) && !empty($ordOPen)):?>
	                                <li><!-- cart.php -->
	                                    <i class="icon-empty ten_padding_right"></i>
	                                    <a href="#" id="<?=$ordOPen['OrdID'];?>" class="showCard"><?= $ordOPen['OrdID'];?></a>
	                               
	                                </li>
                              	
	                                <li>
	                                    <span class="cart_total">
	                                        <!-- Your Bag -->Cart
	                                    </span>
	                                    <div class="cart_dropdown">
	                                        <div class="box">
	                                            <ul class="list">
	                                            	<?php
	                                            		$path = '/images/upload/products/';
	                                            		foreach ($ordOPen['OrdBuS'] as $key => $row) {
															
															foreach ($row['BuDetails'] as $key2 => $row2) {
																
																$img = substr(strrchr($row2['ProdImg'], '/'), 1);
																
																echo '<li class="item clearfix">
				                                                     <figure class="pull-left">
				                                                        <a href="product.php">
				                                                            <img src="'.$path.'thumbnails/'.$img.'" alt=""/>
				                                                        </a>
				                                                     </figure>
				                                                     <div class="content">
				                                                        <div class="title">
				                                                            '.$row2['ProdName'].' by <a href="category_template_2.html">'.$row2['SubCatName'].'</a>
				                                                        </div>
				                                                        <div class="price">
				                                                           '.$row2['F_Price'].' '.$row['BuCurr'].' <!-- € -->
				                                                        </div>
				                                                     </div>
				                                                 </li>';
															}
															
														}
	                                            	?>
	                                            	
	                                            	
	                                                <!-- <li class="item clearfix">
	                                                    <figure class="pull-left">
	                                                        <a href="product.php">
	                                                            <img src="/images/products/small13.png" alt=""/>
	                                                        </a>
	                                                    </figure>
	                                                    <div class="content">
	                                                        <div class="title">
	                                                            Fresh Shirt-Shortsleeve by <a href="category_template_2.html">Carhartt</a>
	                                                        </div>
	                                                        <div class="price">
	                                                            35.00€
	                                                        </div>
	                                                    </div>
	                                                </li>
	                                                <li class="item clearfix">
	                                                    <figure class="pull-left">
	                                                        <a href="product.php">
	                                                            <img src="/images/products/small14.png" alt=""/>
	                                                        </a>
	                                                    </figure>
	                                                    <div class="content">
	                                                        <div class="title">
	                                                            AV Native American by <a href="category_template_2.html">Vans</a>
	                                                        </div>
	                                                        <div class="price">
	                                                            65.00€
	                                                        </div>
	                                                    </div>
	                                                </li>
	                                                <li class="item clearfix">
	                                                    <figure class="pull-left">
	                                                        <a href="product.php">
	                                                            <img src="/images/products/small15.png" alt=""/>
	                                                        </a>
	                                                    </figure>
	                                                    <div class="content">
	                                                        <div class="title">
	                                                            Coolwood T-shirt by <a href="category_template_2.php">Billabong</a>
	                                                        </div>
	                                                        <div class="price">
	                                                            31.00€
	                                                        </div>
	                                                    </div>
	                                                </li> -->
	                                            </ul>
	                                            <!-- <div class="total_wrapper">
	                                                <div class="total">
	                                                    <div class="uppercase">Subtotal</div>
	                                                    <div class="value">131.00 €</div>
	                                                </div>
	                                                <a href="cart.php" class="button darkgrey">Checkout</a>
	                                            </div> -->
	                                            
	                                        </div>
	                                    </div>
	                                </li>
	                           	 <?php endif;?>
                               <?php elseif(!isset($ordOPen) || empty($ordOPen)):?>
                               	
                               		 <li>	                               
	                               		<i class="icon-user ten_padding_right"></i><a href="/home/Login">Login / Register</a></p>  	
	                                </li>
                               <?php endif;?>
            <!--here i come-->  <li>
                                    <span class="cart_total">
                                        EN
                                    </span>
                                    <div class="cart_dropdown">
                                        <div class="box">
                                            <ul class="list">
                                                <li class="item clearfix">
                                                    
                                                    <div class="content">
                                                        <div class="price">
                                                           <a class="pull-right" href="">AR</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="item clearfix">
                                                    
                                                    <div class="content">
                                                        <div class="price">
                                                            <a class="pull-right" href="#">EN</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="item clearfix">
                                                    
                                                    <div class="content">
                                                        <div class="price">
                                                          <a class="pull-right" href="#">FR</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                           
                                            
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                  
                    <div class="span8 offset1 twenty_margin_top">
                        <div class="row"><img src="/images/2.png"></div>
                    </div>
                    
                  <!--  <div class="span1 lang-select">
                    <!--    <div class="btn-group">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                EUR
                                <span class="caret lightgrey"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>German</li>
                                <li>Spanish</li>
                                <li>Italian</li>
                            </ul>
                        </div>-->
                    <!--     <div class="btn-group">
                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                EN
                                <span class="caret lightgrey"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>US</li>
                                <li>AR</li>
                                <li>FR</li>
                            </ul>
                        </div>
                    </div>-->
                
                </div> 
                <div class="row ten_margin_top">

                    <div style="float:right;" class="span9">
                        <form class="form-search">
                            <input type="text" class="input-medium search_input" id="search_field" style="margin-top:0;" onfocus="if(this.value == '&nbsp;&nbsp;&nbsp;Search...') { this.value = ''; }" onblur="if(this.value == '') { this.value = '&nbsp;&nbsp;&nbsp;Search...'; }" value="&nbsp;&nbsp;&nbsp;Search...">
                            <button type="submit" class="search_button">Search <i class="icon-search icon-white"></i></button>
                        </form>
                    </div>

                    <div style="" class="span3 sectionaly">
                           <select> <!-- id="xxx" -->
                              <option>tech</option>
                              <option>cloths</option>
                              <option>bags</option>
                              <option>house holding</option>
                              <option>reservations</option>
                              <option>cloths</option>
                              <option>cloths</option>
                              <option>cloths</option>
                           </select>
                    </div>

                    
                </div>

                <!-- Expanded Header Menu -->
                <!-- <div class="row">
                    <button type="button" class="btn btn-navbar hidden-desktop" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar menu-toggle fa fa-bars"></span>
                    </button>
                    <nav class="menu main-nav nav-collapse collapse span12">
                        <ul>
                            <li class="has-children">
                                <a href="#">Hot Offer</a>
                                <span class="arrow-down">▼</span>
                                <ul class="span6">
                                    <li class="promo">
                                        <a href="product.php"><img src="/images/menu-promo.png" alt=""/></a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="home.php">stores</a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="home_2.php">Products</a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="category_template_1.html">services</a>
                                    </li>
                                    <li>
                                        <a href="category_template_2.html">news</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Product</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Sweaters</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Hooded Sweater</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Shirts</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Jackets</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Pants</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Headwear</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Accessories</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has-children">
                                <a href="home_2.php">page</a>
                                <span class="arrow-down">▼</span>
                                <ul class="span3">
                          		 <li>
                                        <a href="account.php">Account</a>
                                    </li>
                                    <li>
                                        <a href="address.php">Address</a>
                                    </li>
                                    <li>
                                        <a href="blog_single_2.html">Single Post Full Width</a>
                                    </li>
                                    <li>
                                        <a href="blog_single.html">Single Post</a>
                                    </li>
                                    <li>
                                        <a href="blog.php">Blog</a>
                                    </li>
                                    <li>
                                        <a href="cart.php">Cart</a>
                                    </li>
                                    <li>
                                        <a href="category_template_1.html">Category Template 1</a>
                                    </li>
                                    <li>
                                        <a href="category_template_2.html">Category Template 2</a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="home.html">Header V1</a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="home_2.php">Header V2</a>
                                    </li>
                                    <li>
                                        <a href="home_popup.html">Popup Window</a>
                                    </li>
                                    <li>
                                        <a href="login.html">Login</a>
                                    </li>
                                    <li>
                                        <a href="order_confirmation.html">Order Confirmation</a>
                                    </li>
                                    <li>
                                        <a href="product.html">Product</a>
                                    </li>
                                    <li>
                                        <a href="shipping_information.html">Shipping Information</a>
                                    </li>
                                    <li>
                                        <a href="shipping.php">Shipping</a>
                                    </li>
                                    <li>
                                        <a href="summary.php">Summary</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has-children">
                                <a href="#">Account</a>
                                <span class="arrow-down">▼</span>
                                <ul class="span3">
                                    <li class="font-bold">
                                        <a href="account.php">Account</a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="address.php">Address</a>
                                    </li>
                                    <li>
                                        <a href="cart.php">Cart</a>
                                    </li>
                                    <li>
                                        <a href="login.php">Login</a>
                                    </li>
                                    <li>
                                        <a href="order_confirmation.html">Order Confirmation</a>
                                    </li>
                                    <li>
                                        <a href="shipping_information.html">Shipping Information</a>
                                    </li>
                                    <li>
                                        <a href="summary.php">Summary</a>
                                    </li>
                                    <li>
                                        <a href="shipping.php">Shipping </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="home.html">Home</a>
                                <span class="arrow-down">▼</span>
                            </li>
                            <li>
                                <a href="home_2.html">Header V2</a>
                                <span class="arrow-down">▼</span>
                            </li>
                            <li class="has-children">
                                <a href="#">Shoes</a>
                                <span class="arrow-down">▼</span>
                                <ul class="span6">
                                    <li class="promo">
                                        <a href="product.html"><img src="/images/menu-promo3.png" alt=""/></a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="blog.html">News</a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="category_template_1.html">Sale</a>
                                    </li>
                                    <li class="font-bold">
                                        <a href="category_template_2.html">Bestsellers</a>
                                    </li>
                                    <li>
                                        <a href="product.php">T-Shirt</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Sweaters</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Hooded Sweater</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Shirts</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Jackets</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Pants</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Headwear</a>
                                    </li>
                                    <li>
                                        <a href="product.php">Accessories</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="has-children">
                                <a href="#">Blog</a>
                                <span class="arrow-down">▼</span>
                                <ul class="span3">
                                    <li>
                                        <a href="blog.php">Blog</a>
                                    </li>
                                    <li>
                                        <a href="blog_single_2.html">Single Post Full Width</a>
                                    </li>
                                    <li>
                                        <a href="blog_single.html">Single Post</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="product.php">Product</a>
                                <span class="arrow-down">▼</span>
                            </li>
                            <li>
                                <a href="cart.php">Cart</a>
                                <span class="arrow-down">▼</span>
                            </li>
                            <li class="has-children">
                                <a href="#">Categories</a>
                                <span class="arrow-down">▼</span>
                                <ul class="span3">
                                    <li>
                                        <a href="category_template_1.html">Category Template 1</a>
                                    </li>
                                    <li>
                                        <a href="category_template_2.html">Category Template 2</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div> -->
                <!-- /Expanded Header Menu -->

            </div>

        </div>
        
         
	<link href="/css/sh-jquery-ui.css" rel="stylesheet" media="screen">
    <script src="/js/sh-jquery-ui.js"></script>
    
    <!-- <script type="text/javascript" src="//js.maxmind.com/js/apis/geoip2/v2.1/geoip2.js"></script> -->
    <script>

    	$('.showCard').click(function(e){
    		e.preventDefault();
    		// alert($(this).attr('id'));
    		
    		// var x ={ ordID : $(this).attr('id') };
    		// $.post( "/home/ShowCard/", x , function( data ) {				
			// });
			
			window.location = "/home/ShowCard/";
    		
    	}); 	
    	
    	
    	// ------------------ search the site _ under construction !
    	$("#search_field").autocomplete({
			minLength: 1,
			delay: 500,
			source: function(request, response) {
				// if ( request.term in cache ) {
					// response( cache[ request.term ] );
					// return;
				// }
				
				$.ajax({
					type: "POST",
					url: "/home/autoSearch/", //?serTyp=Pro
					dataType: "text",
					data: request,
					success: function( data ) {
						//cache[ request.term ] = eval(data);
						response( eval(data) );
						// alert(data);
					}
				});
			},
			focus: function(event, ui) {
				return false;
			},
			select: function(event, ui) {

				// $("#fact_id").val(ui.item.id);
				// $("#search").val(ui.item.label);
				// if($("#fact_id").val() != ""){
				if(ui.item.id != ""){
					window.location.href = '/home/ProDetail/'+ui.item.id;
				}
			},
			change: function( event, ui ) {
				if(!ui.item){
					// $("#fact_id").val("");
					$("#search_field").val("");
					
				}
			}
		});
    	
    	
    	
    	
    	
	   // $('#xxx').change(function(e){
	   		// e.preventDefault();
	   		// // alert('hhhh');
	   		// // return;
	    	// $.post("/APIApp/OwnerOrd" , {ow_id  : 1} , function(data){
	    	// });
	   // });
    </script>
        
        
        


