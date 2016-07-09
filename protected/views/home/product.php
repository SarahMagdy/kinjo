<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Kinjo store</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!-- Bootstrap -->
		<!-- the styles for the lozenge theme -->
		
		<link href="/bootstrap/css/static-bootstrap.css" rel="stylesheet" media="screen">
		<link href="/bootstrap/css/static-bootstrap-responsive.css" rel="stylesheet" media="screen">
		<!-- Stylesheet -->
		<link href="/css/static-style.css" rel="stylesheet" media="screen">
		<link href="/images/favicon.ico" rel="shortcut icon">
		<style type="text/css" id="page-css">
			/* Styles specific to this particular page */
			.scroll-pane,
			.scroll-pane-arrows
			{
			width: 100%;
			height: 200px;
			overflow: auto;
			}
			.horizontal-only
			{
			height: auto;
			max-height: 200px;
			}
		</style>
		<!--Jquery Init -->
		<script type="text/javascript" src="/js/static-jquery.js"></script>
		<script src="/bootstrap/js/static-bootstrap.min.js"></script>
		<!-- <script type="text/javascript" src="/js/jquery.jscrollpane.min.js"></script> -->
		<!-- <script type="text/javascript" src="/js/jquery.mousewheel.js"></script> -->
		<!-- <script type="text/javascript" src="/js/mwheelIntent.js"></script> -->
		<script type="text/javascript" id="sourcecode">
			
			function DrawRate(){
				$('.rating').children('li').remove();
				var rateClass = "";
				for(var i=1 ; i<= 5 ; i++){
					
					if(i<= endData['rate'] ){
                    	rateClass = "normal";
					}else{
						rateClass = "";
					}
					
					$('.pro_rating').append('<li class="item ' + rateClass + ' dib" >  <i class="icon"></i> </li>');
					
					
					$('.cust_rating').append('<li class="item ' + rateClass + ' dib" > <a id="li_' + i + '" href="#" class="ProRate" li_count="'+ i + '"> <i class="icon"></i> </a></li>');  
				}
			}
			
			
			$(document).ready(function () {
				$('.colclass').click(function(e){
					e.preventDefault();
					$('.colclass').removeClass('colselected');
					
					$(this).addClass('colselected');
					// alert('dddd');
				});
				
				// $('.ProRate').on('click',function(e){
				// });
				
			});
			
			
			$(document).on('click', "a.ProRate", function(e) {
				
				e.preventDefault();
				
				$(".cust_rating li").removeClass("normal");
				
				for(var i=1 ; i<= $(this).attr('li_count') ; i++){
					$('#li_'+i).parents('li').addClass('normal');
				}
				
				var data = {
					rate : $(this).attr('li_count'),
					pid  : <?= $proArr['Result']['Product']['ProID'];?>
				};

				$.post( "/home/AddProRate/",data, function(data) {
					data = data.trim();
					var jsonData = data.toString();
					endData = $.parseJSON(jsonData);
					
					if(endData['error'] && endData['error'] !="")
					{
						alert(endData['error']['message']);
					}else{				
						// window.location.reload(true);
						DrawRate();
					}		
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
							// for (var key in end_data){
								// alert(end_data[key]['message']);
								if(end_data['error']['code'] == '201'){
									window.location.href = "/home/Login";
								}else{
									alert(end_data['error']['message']);
								}
								
							// }
							
						}
					}else{
						alert('Product Added to your WishList');
					}
					
				});
			}
			
			
			function AddToCard(){
				if($('#BuReservd').val() == 'false'){
					var ordConfArr = new Array();
					var color = "";
					var confString = "";
					// var obj = $("input:radio.conf:checked");
					// obj.each(function(entry) {
					    // console.log($(this).val());
					    // alert($(this).val());
					    // confString += $(this).val() + ',';
					// });
					
					// $('input.conf[type=checkbox]').each(function () {
						// confString += (this.checked ? $(this).val()+ ',' : ""); 
					// });
					
					$(".conf:checked").each(function() {
						confString += this.value + ',';
					});
				
				
					confString = confString.substring(0,confString.length - 1);
					// alert(confString);
					// return;
									
					ordConfArr.push({"qnt": document.getElementById('quantity').innerHTML ,  "conf":confString , "color":$(".colselected").attr("id")});
					
					var mydata ={
						p_id  : <?= $proArr['Result']['Product']['ProID'];?> , 
						bu_id : 3 ,
						Q_Conf : ordConfArr
					};
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
								if(endData['error']['code'] == '201'){
									window.location.href = "/home/Login";
								}else{
									alert(endData['error']['message']);
								}
							}else{
								alert('Product Added to your Order');								
								window.location.reload(true);
							}
							
						});
					}else{
						alert('Choose Quantity');
					}
				}else{
					alert('You Cannot add any Product From this Store until You finish Your Payment');
				}
			}
			
			
			
				
			
			
		</script>
		<script src="/js/jquery.js" type="text/javascript" charset="utf-8"></script>
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
			<input type="hidden" id="BuReservd" value="<?= $proArr['Result']['Store']['IsReserved'];?>"/>
			<!-- Product Details -->
			<div class="row product">
				<div class="span6">
					<figure>
						<div class="main">
							<img src="<?= $path.$proArr['Result']['Product']['ProImgs'][0]['pimg_url'];?>" alt=""/>
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
								<img src="/images/products/3prod-detail.png" alt=""/>
								</a>
							</li> -->
							
						</ul>
					</figure>
				</div>
				<div class="span6">
					<h3 class="title font-light"><?= $proArr['Result']['Product']['ProTitle']?></h3><!-- KINJO T-shirt -->
					<div class="author">by <a href="#"><?= $proArr['Result']['Product']['SubCatName'];?></a></div><!-- kINJO -->
					<div class="price"><?= $proArr['Result']['Product']['ProPrice'].'$';?></div> <!-- 325.00€GP -->
					
					<?php 
						if(!empty($proArr['Result']['Product']['ProDesc'])){
							echo '<p>'.$proArr['Result']['Product']['ProDesc'].'</p>';
						}
					?> 
					
					<ul class="info" id="dubsorig">
						<li class="item clearfix">
							<label class="pull-left">Rating</label>
							<ul class="rating pull-right pro_rating">
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
					                          
					                      // echo '<li class="item '.$rateClass.' dib" >
					                          	   // <a id="li_'.$i.'" href="#" class="ProRate" li_count="'.$i.'">
					                          	   		// <i class="icon"></i>
					                          	   // </a>
					                          	// </li>';
		                       		}
		                       ?>
								
								<!-- <li class="item normal dib">
										<a href="#"><i class="icon"></i></a>
									</li>
									<li class="item normal dib">
										<a href="#"><i class="icon"></i></a>
									</li>
									<li class="item normal dib">
										<a href="#"><i class="icon"></i></a>
									</li>
									<li class="item dib">
										<i class="icon"></i></a>
									</li> -->
							</ul>
						</li>
						
						
						
						<?php if(isset(Yii::app()->session['Cust']['CustID']) && !empty(Yii::app()->session['Cust']['CustID'])):?>
							<li class="item clearfix">
								<label class="pull-left">Your Rating</label>
								<ul class="rating pull-right cust_rating">
								<?php $rateClass = "";
		                       		for($i=1 ; $i<=5 ; $i++){
		                       			if($i<= $proArr['Result']['Product']['ProRate'] ){
			                       			$rateClass = "normal";
										}else{
											$rateClass = "";
										}
							                          
				                      echo '<li class="item '.$rateClass.' dib" >
				                          	   <a id="li_'.$i.'" href="#" class="ProRate" li_count="'.$i.'">
				                          	   		<i class="icon"></i>
				                          	   </a>
				                          	</li>';
		                       		}
		                       	?>
								</ul>
							</li>
						<?php endif;?>
						
						
						
						
						<li class="item clearfix">
							<label class="pull-left">Availability</label>
							<div class="availability pull-right">On stock - Sending within 24 hours</div>
						</li>
					</ul>
					<ul class="info" id="dubs">
						
						 <?php if(isset($proArr['Result']['Product']['ProColors']) && !empty($proArr['Result']['Product']['ProColors'])):?>
						 	<li class="item clearfix">
							 	<label class="pull-left">Choose colour</label>
								<ul class="product_colors pull-right">
									
									<?php 
			                    		foreach($proArr['Result']['Product']['ProColors'] as $key=> $row){ //onClick="color($this.id);"
			                    			echo '<a href="#" id="'.$row['ColorID'].'" class="colclass" >
			                    					<li class="item dib" style="background-color:'.$row['ColorCode'].';">
			                    					</li> </a>';
			                    		}
			                    	?>
							 	</ul>
						 	</li>
						 <?php endif;?>
						
						<!-- <li class="item clearfix">
							<label class="pull-left">Choose colour</label>
							<ul class="product_colors pull-right">
								<a href="#">
									<li class="item red dib"></li>
								</a>
								<a href="#">
									<li class="item blue dib"></li>
								</a>
								<a href="#">
									<li class="item yellow dib"></li>
								</a>
								<a href="#">
									<li class="item green dib"></li>
								</a>
							</ul>
						</li> -->
						
					
						<?php
			            	foreach ($proArr['Result']['Product']['ProConfs'] as $key => $val){
								echo '<li class="item clearfix">
					                  		<label class="pull-left">'.$val['Conf'].'</label>
					                    	<ul class="sizes product_sizes pull-right">';
					          
				            	foreach ($val['SubConfig'] as $key2 => $val2) {
									$type = 'radio';
									if($val['Check'] == 'TRUE'){
										 $type = 'checkbox';
									}	
										
									echo '<li class="item dib">
				                            <label>
				                                <input type="'.$type.'" name="'.$val['Conf'].'" value="'.$val2['subId'].'" class="conf"/>
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
							<label class="pull-left">Quantity</label>
							<div class="quantity pull-right">
								<div class="urbaspin inline">
									<i data-arrow="down">-</i>
									<label id="quantity" >1</label>
									<i data-arrow="up">+</i>
								</div>
							</div>
						</li>
					
					</ul>
					<div class="row actions">
						<div class="span4">
							<button rel="prettyPhoto" class="button mustard" onclick="AddToCard()">Add to cart</button>
						</div>
						<div class="span2">
							<button class="button button-fluid darkgrey wishlist" onclick="AddToWishList()">Add to wishlist</button>
						</div>
					</div>

					<div class="row twenty_margin_top clearfix"></div>
					<!-- <div id="btndub1" class="row actions">
						<div class="span6">
							<button style="display:block; " class="button mustard" onclick="dublication()">+</button>
						</div>
					</div> -->
				</div>
			</div>
			<!-- /Product Details -->
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
		
		<div class="row twenty_margin_top clearfix"></div>
		
		<?php
			include("footer.php");
		?>
		
		<script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
		<script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
		<script src="/js/static-jquery.cookie.js"></script>
		<script src="/js/static-script.js" type="text/javascript"></script>
		<script type="text/javascript">
			var i = 0 
			var x = document.getElementById('dubs');
			var e = document.getElementById('btndub1');
			
			function dublication(){
			       var y = x.cloneNode(true);
			       y.id  = "dublicated" + i++;
			       var d = x.parentNode.appendChild(y);
			       var newbutton = x.createElement('div');
			       newbutton.className = "span6";
			       newbutton.innerHTML = '<button class="button mustard" onclick="dublication()">+</button>' ; 
			       var addonbtn = x.parentNode.appendChild(y);
			}
		</script>
		<script type="text/javascript">
			// $("#bzoom").zoom({
			    // zoom_area_width: 300,
			    // autoplay_interval :3000,
			    // small_thumbs : 4,
			    // autoplay : false
			// });
		</script>
		<!-- <script type="text/javascript" charset="utf-8">
			  // $(document).ready(function(){
				// $("a[rel^='prettyPhoto']").prettyPhoto();
			  // });
		</script> -->
	</body>
</html>