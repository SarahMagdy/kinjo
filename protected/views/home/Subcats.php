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
        <link href="images/favicon.ico" rel="shortcut icon">
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
        <div id="header_filter">
            <div class="container">
	          	<?php 
	          		if(isset($SubCatRes) && !empty($SubCatRes)){
	          			echo '<div class="row ten_margin_top">
			                    <div class="span12">
			                        <ul id="category-filter" class="inline">
			                        	<li><a href="/home/SubCatPro/?catID='.$_GET['catID'].'" data-filter="*" class="selected"> All</a></li>';
			                        
						foreach ($SubCatRes as $key => $val) {
							echo '<li class="inline-block">
									<a href="/home/SubCatPro/?catID='.$val['ParCatID'].'&subID='.$val['SubCatID'].'" > '.$val['SubCatName'].'</a>
								  </li>';
						}
									
						echo '		</ul>
		                    	</div>
		                	</div>';
	          		}
	          	?>
                <!-- <div class="row ten_margin_top">
                    <div class="span12">
                        <ul id="category-filter" class="inline">
                            <li><a href="#" data-filter="*" class="selected"> All</a></li>
                            <li class="inline-block"><a href="#" data-filter=".shoes"> Shoes</a></li>
                            <li class="inline-block"><a href="#" data-filter=".tshirts"> T-Shirts</a></li>
                            <li class="inline-block"><a href="#" data-filter=".longsleeves"> Long-Sleeves</a></li>
                            <li class="inline-block"><a href="#" data-filter=".sweaters"> Sweaters</a></li>
                            <li class="inline-block"><a href="#" data-filter=".hoodies"> Hoodies</a></li>
                            <li class="inline-block"><a href="#" data-filter=".shirts"> Shirts</a></li>
                            <li class="inline-block"><a href="#" data-filter=".jackets"> Jackets</a></li>
                            <li class="inline-block"><a href="#" data-filter=".pants"> Pants</a></li>
                            <li class="inline-block"><a href="#" data-filter=".headwear"> Headwear</a></li>
                            <li class="inline-block"><a href="#" data-filter=".accessories"> Accessories</a></li>
                        </ul>
                    </div>
                </div> -->
            	<!-- <div class="row">
                    <div class="span12">
                        
                    </div>
                    
                    <div class="span12"><button class="apply button darkgrey pull-right ">Apply Filters</button></div>
                    <div class="span12" style="height:20px;"></div>
                </div> -->
            </div>
        </div>
        </div>
        <div id="home">
        
        
        <div class="container">
           
           
            <div class="container">
                
	                <div class="row twenty_margin_top clearfix">
	                    <div class="span12">
	                        <h3 class="heading darkgrey font-light uppercase">
	                        	<?php 
	                        		if(isset($SubCatRes) && !empty($SubCatRes)){
	                        			echo '<span class="heading_whitebg">Sub <span class="lightgray">categories</span></span>';
	                        		}else{
	                        			echo '<span class="heading_whitebg">Products</span>';
	                        		}
	                        	?>
	                        	
	                        </h3>
	                    </div>
	                </div>
                <div class="row twenty_margin_top clearfix">
                	
                	<?php 
	                	if(isset($prodArr)){
	                		
							if (array_key_exists("error" , $prodArr)){
								
								echo '<div>'.$prodArr['error']['message'].'</div>';
								
							}else{	
		                		$path = '/images/upload/products/';
		                		
		                		foreach($prodArr as $key => $row ){
		                			$byCat = '';
		                			if(!empty($row['SubCatName'])){
		                				$byCat = $row['SubCatName'];
		                			}else{
		                				$byCat = $row['BUName'];
		                			}
									
									$img = 'default.jpg';	
									if(!empty($row['ProdImg'])){
										$img = substr($row['ProdImg'][0]['img'] , strrpos( $row['ProdImg'][0]['img'] , '/') + 1);
									}						
		                			
									
		                			echo '<div class="span3 product_image clearfix">
					                        <div class="flip_image">
					                            <a href="/home/ProDetail/'.$row['ProdID'].'">
					                                <div class="front_image">
					                                    <img src="'.$path.$img.'" alt="">
					                                </div>
					                            </a>
					                        </div>
					                        <div class="description clearfix">
					                            <p class="white nomargin">
					                                '.$row['ProdName'].' <br> 
					                                by <a href="category_template_2.html">'.$byCat.'</a>
					                            </p>
					                            <span class="price white">
					                            '.$row['ProdPrice'].'  €
					                            </span>
					                        </div>
					                    </div>';
					        	}
	                		}
	                	}
                	?>
                </div>
                
                <!-- <div class="row twenty_margin_top clearfix">
                    <div class="span3 product_image clearfix">
                        <div class="flip_image">
                            <a href="product.html">
                                <div class="front_image">
                                    <img src="/images/products/prod1.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Era Black Shoes <br> by <a href="category_template_2.html">Vans</a>
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
                                    <img src="/images/products/prod4.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Birght Eyes T-shirt <br> by <a href="category_template_2.html">Altamont</a>
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
                                    <img src="/images/products/prod5.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Coolwood T-shirt <br> by <a href="category_template_2.html">Billabong</a>
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
                                    <img src="/images/products/prod6.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix clearfix">
                            <p class="white nomargin">
                                Fresh Shirt-Shortsleeve<br> by <a href="category_template_2.html">Carhatt</a>
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
                                    <img src="/images/products/prod1.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Era Black Shoes <br> by <a href="category_template_2.html">Vans</a>
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
                                    <img src="/images/products/prod4.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Birght Eyes T-shirt <br> by <a href="category_template_2.html">Altamont</a>
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
                                    <img src="/images/products/prod5.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix">
                            <p class="white nomargin">
                                Coolwood T-shirt <br> by <a href="category_template_2.html">Billabong</a>
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
                                    <img src="/images/products/prod6.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="description clearfix clearfix">
                            <p class="white nomargin">
                                Fresh Shirt-Shortsleeve<br> by <a href="category_template_2.html">Carhatt</a>
                            </p>
                            <span class="price white">
                            109.50€
                            </span>
                        </div>
                    </div>
                </div> -->
                
                
                
                <div class="fullwidth clearfix newsletter_cta twenty_margin_top">
                    <div class="span4 ">
                    </div>
                    <div class="span4 ">
                    	
                    	<?php
                    		if(isset($pagingArr) && !empty($pagingArr)){
								
								$paramSubID = '';  $paramCatID = ''; 	$paramBUID = '';
								
								if(isset($_GET['subID'])){
									$paramSubID = '&subID='.$_GET['subID'];
								}
								if(isset($_GET['catID'])){
									$paramCatID = '&catID='.$_GET['catID'];
								}
                    			if(isset($_GET['BUID'])){
                    				$paramBUID = '&BUID='.$_GET['BUID'];
                    			}
								
								if($pagingArr['start'] != $pagingArr['end']){
								
	                    			echo '<div class="paginator">
	                            			<ul>';
	                    			if($pagingArr['page'] > 1){
		                      			echo '<a href="?'.$paramBUID.$paramCatID.$paramSubID.'&limit=4&page=' . ($pagingArr['page']- 1) . '" class="getPage">Previous</a>';
		                        	}
								
									if ($pagingArr['start'] > 1 ){
								        echo '<li class="current">
								        		<a href="?'.$paramBUID.$paramCatID.$paramSubID.'&limit=4&page=1">1</a>
								        	  </li>';
								    }
								
									for ( $i = $pagingArr['start'] ; $i <= $pagingArr['end']; $i++){
								        echo '<li>
								        		<a href="?'.$paramBUID.$paramCatID.$paramSubID.'&limit=4&page=' . $i . '">' . $i . '</a>
								        	  </li>';
								    }
							    
								    if ( $pagingArr['end'] < $pagingArr['last']){
								        echo '<li>
								        		<a href="?'.$paramBUID.$paramCatID.$paramSubID.'&limit=4&page=' . $pagingArr['last'] . '">' . $pagingArr['last'] . '</a>
								        	  </li>';
								    }
								
									if( $pagingArr['page'] != $pagingArr['last'] ){
										echo '<a href="?'.$paramBUID.$paramCatID.$paramSubID.'&limit=4&page=' . ( $pagingArr['page'] + 1 ) . '"> Next &nbsp;</a>';
									}
	                    			
	                    			echo '	</ul>
	                        			</div>';
								}
							}
                    	?>
                    	
                        <!-- <div class="paginator">
                            <ul>
                                <a href="#">Previous</a>
                                <li class="current">
                                    <a href="#">1</a>
                                </li>
                                <li>
                                    <a href="#">2</a>
                                </li>
                                <li>
                                    <a href="#">3</a>
                                </li>
                                <li>
                                    <a href="#">4</a>
                                </li>
                                <a href="#">Next &nbsp;</a>
                            </ul>
                        </div> -->
                    </div>
                </div>
                
                <div class="row twenty_margin_top clearfix">
                </div>
            </div>
        </div>
        <!--==================================================================
            Footer
            ==================================================================-->
        <?php
            include('footer.php');
            ?>
        <!-- /Popup 2-->
        <script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
        <script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
        <script src="/js/static-jquery.cookie.js"></script>
        <!-- <script src="/js/script.js" type="text/javascript"></script> -->
        <script type="text/javascript">
            x=document.getElementById('search_field')
            x.onfocus=function(){
                this.value = "";
            }
        </script>
        <script type="text/javascript">
            // $(function() {
            // "use strict";
            // var e = $("#isotope");
            // e.isotope({
            // itemSelector: ".product_image"
            // });
            // $(".dd-menu-trigger").click(function() {
            // $(this).toggleClass("indropdown")
            // });
            // $("#category-filter a").click(function() {
            // var t = $(this).attr("data-filter");
            // e.isotope({
                // filter: t
            // });
            // return false
            // });
            // var t = $("#category-filter"),
            // n = t.find("a");
            // n.click(function() {
            // var e = $(this);
            // if (e.hasClass("selected")) {
                // return false
            // }
            // var t = e.parents("#category-filter");
            // t.find(".selected").removeClass("selected");
            // e.addClass("selected")
            // });
            // $("#filter_one").click(function() {
            // $("#filter_one_toggle").toggle()
            // });
            // $("#filter_two").click(function() {
            // $("#filter_two_toggle").toggle()
            // });
            // $("#filter_three").click(function() {
            // $("#filter_three_toggle").toggle()
            // });
            // $("#filter_four").click(function() {
            // $("#filter_four_toggle").toggle()
            // });
            // $("#filter_five").click(function() {
            // $("#filter_five_toggle").toggle()
            // });
            // $(".urbaspin").urbaspin({
            // callback: function(e) {}
            // });
            // $("fieldset.delivery_address input[type=checkbox]").change(function() {
            // $this = $(this);
            // var e = $this.closest("fieldset.delivery_address").find("input");
            // var t = $this.is(":checked") ? e.attr("readonly", "readonly") : e.removeAttr("readonly")
            // });
            // var r = $(".price-range-value");
            // var i = r.attr("data-currency");
            // var s = $("#price-range");
            // s.slider({
            // range: true,
            // min: 0,
            // max: 500,
            // values: [75, 300],
            // slide: function(e, t) {
                // r.text(i + t.values[0] + " - " + i + t.values[1])
            // }
            // });
            // r.text(i + s.slider("values", 0) + " - " + i + s.slider("values", 1));
            // $(".filter .content").urbascroll();
            // var o = $(".popup");
            // var u = o.find("i.close");
            // u.click(function() {
            // popup_close($(this).parents(".popup"))
            // });
            // $.each(o, function(e, t) {
            // if ($(t).hasClass("active")) {
                // popup_open($(t))
            // }
            // });
            // var a = $(".product .main img");
            // var f = a.attr("src");
            // $(".product .thumbs .item").hover(function() {
            // var e = $(this).find("img").attr("src");
            // a.attr("src", e)
            // });
            // $(".product figure").mouseleave(function() {
            // a.attr("src", f)
            // });
            // a.click(function() {
            // var e = $(this).attr("src");
            // $(".popup figure.product_fb img").attr("src", e);
            // popup_open(o)
            // });
            // vmiddle($(".product .quantity"));
            // same_height($(".cart.summary .address_box"));
            // $(".cart_total").click(function(e) {
            // e.stopPropagation();
            // $(".cart_dropdown").toggleClass("active")
            // });
            // $("body").click(function() {
            // var e = $(".cart_dropdown");
            // if (e.hasClass("active")) {
                // e.removeClass("active")
            // }
            // })
            // });
            // var popup_open = function(e) {
            // var t = e.find(".box"),
            // n = t.height() / 3,
            // r = t.width() / 2;
            // console.log(t, n, r);
            // t.css({
            // "margin-top": n,
            // "margin-left": -r
            // });
            // e.addClass("active")
            // };
            // var popup_close = function(e) {
            // e.toggleClass("active")
            // };
            // var vmiddle = function(e) {
            // var t = (e.parent().height() - e.height()) / 2;
            // e.css("padding-top", t)
            // };
            // var same_height = function(e) {
            // var t = 0;
            // $.each(e, function(e, n) {
            // t = $(n).height() > t ? $(n).height() : t
            // });
            // e.height(t)
            // };
            // (function(e) {
            // e.fn.urbaspin = function(t) {
            // $this = e(this);
            // $arrow = $this.find("i");
            // var n = 0;
            // $arrow.click(function() {
                // $label = e(this).parent().find("label");
                // var r = parseInt($label.html()),
                    // i = e(this).attr("data-arrow");
                // n = i === "up" ? r + 1 : r !== 0 ? r - 1 : 0;
                // $label.html(n);
                // if (typeof t.callback === "function") {
                    // t.callback.call(this, n)
                // }
            // })
            // }
            // })(jQuery);
            // (function(e) {
            // e.fn.urbascroll = function(t) {
            // function n(t) {
                // $handle = e(t.helper.get(0));
                // $content = $handle.closest(".content");
                // $parent = $content.parent();
                // var n = $handle.position().top,
                    // r = $content.outerHeight(true),
                    // i = $parent.height();
                // $content.css({
                    // "margin-top": -(n * (r / i) - 1)
                // })
            // }
            // this.each(function() {
                // $this = e(this);
                // $parent = $this.parent();
                // var t = $this.outerHeight(true),
                    // n = $parent.height(),
                    // r = n,
                    // i = n / (t / n);
                // if (t > n) {
                    // $handle = e('<div class="handle">').css({
                        // height: i
                    // });
                    // $this.append(e('<div class="urbascroll">').css({
                        // height: r
                    // }).append($handle))
                // }
            // });
            // e(".filter .handle").draggable({
                // axis: "y",
                // containment: "parent",
                // drag: function(e, t) {
                    // n(t)
                // }
            // });
            // }
            // })(jQuery);
            // (function(e) {
            // if (typeof e.cookie("urbanix_popup") === "undefined") {
            // setTimeout(function() {
                // e.cookie("urbanix_popup", "seen", {
                    // expires: 1,
                    // path: "/"
                // });
                // popup_open(e(".popup.offer"));
                // e(document).click(function(t) {
                    // if (e(t.target).hasClass("mask")) {
                        // popup_close(e(".popup.offer"))
                    // }
                // })
            // }, 5e3)
            // }
            // })(jQuery);
            // jQuery(document).ready(function(e) {
            // e(".main-nav ul li:has(ul)").addClass("has-dropdown");
            // e("li.has-dropdown").click(function() {
            // e(this).toggleClass("active")
            // })
            // })
        </script>
    </body>
</html>