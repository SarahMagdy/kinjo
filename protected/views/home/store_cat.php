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
                        <h2 class="uppercase promo text-center"><?= $P_Arr['BuName'];?></h2>
                        <h3 class="uppercase promo text-center"><?= $P_Arr['BuDescription'];?></h3>
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
         
            <div class="container">
            	
            	<?php 
		        	if(isset($P_Arr['cat']) && !empty($P_Arr['cat'])){
		        		$path = '/images/upload/catsub/';					 
						 
						echo '<div class="row twenty_margin_top clearfix">';
						 
						foreach ($P_Arr['cat'] as $key => $row) {
							// $img = substr($row['logo_url'], strrpos($row['logo_url'], '/') + 1);

							echo '<div class="span6 clearfix">
			                        <h3 class="heading darkgrey font-light uppercase">
			                        	<span class="heading_whitebg">'.$row['ParCatName'].' <span class="lightgray">By '.$P_Arr['BuName'].'</span></span>
			                        </h3>
			                        <div class="ux_banner fat hover_zoom">
			                            <div class="row">
			                                <div class="banner_in">
			                                    <div class="description_box">
			                                        <h4 class="darkgrey uppercase font-light nomargin nopadding">'.$row['ParCatName'].'</h4>
			                                        <p>'.$row['ParCatDesc'].'</p>
			                                        <a class="banner_in_aly button mustard" href="/home/SubCatPro/?catID='.$row['ParCatID'].'">Browse</a>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="banner_bg">
			                                <img class="img_aly" src="'.$path.$row['ParCatImg'].'">
			                            </div>
			                        </div>
			                    </div>';
						 }	
									
						echo '</div>';
		        	}
		        ?>
            	
            	
                <!-- <div class="row twenty_margin_top clearfix">
                    <div class="span6 clearfix">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Shoes <span class="lightgray">By Zara</span></span></h3>
                        <div class="ux_banner fat hover_zoom">
                            <div class="row">
                                <div class="banner_in">
                                    <div class="description_box">
                                        <h4 class="darkgrey uppercase font-light nomargin nopadding">Shoes</h4>
                                        <p>here you will find every shoes available in our stores.</p>
                                        <a class="banner_in_aly button mustard" href="#">Browse</a>
                                    </div>
                                </div>
                            </div>
                            <div class="banner_bg">
                                <img class="img_aly" src="/images/banner4new.png">
                            </div>
                        </div>
                    </div>
                    <div class="span6 clearfix">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Shoes <span class="lightgray">By Zara</span></span></h3>
                        <div class="ux_banner fat hover_zoom">
                            <div class="row">
                                <div class="banner_in">
                                    <div class="description_box">
                                        <h4 class="darkgrey uppercase font-light nomargin nopadding">Shoes</h4>
                                        <p>here you will find every shoes available in our stores.</p>
                                        <a class="banner_in_aly button mustard" href="#">Browse</a>
                                    </div>
                                </div>
                            </div>
                            <div class="banner_bg">
                                <img class="img_aly" src="/images/banner4new.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row twenty_margin_top clearfix">
                    <div class="span6 clearfix">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Shoes <span class="lightgray">By Zara</span></span></h3>
                        <div class="ux_banner fat hover_zoom">
                            <div class="row">
                                <div class="banner_in">
                                    <div class="description_box">
                                        <h4 class="darkgrey uppercase font-light nomargin nopadding">Shoes</h4>
                                        <p>here you will find every shoes available in our stores.</p>
                                        <a class="banner_in_aly button mustard" href="#">Browse</a>
                                    </div>
                                </div>
                            </div>
                            <div class="banner_bg">
                                <img class="img_aly" src="/images/banner4new.png">
                            </div>
                        </div>
                    </div>
                    <div class="span6 clearfix">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Shoes <span class="lightgray">By Zara</span></span></h3>
                        <div class="ux_banner fat hover_zoom">
                            <div class="row">
                                <div class="banner_in">
                                    <div class="description_box">
                                        <h4 class="darkgrey uppercase font-light nomargin nopadding">Shoes</h4>
                                        <p>here you will find every shoes available in our stores.</p>
                                        <a class="banner_in_aly button mustard" href="#">Browse</a>
                                    </div>
                                </div>
                            </div>
                            <div class="banner_bg">
                                <img class="img_aly" src="/images/banner4new.png">
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="row twenty_margin_top clearfix">
                    <div class="span6 clearfix">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Shoes <span class="lightgray">By Zara</span></span></h3>
                        <div class="ux_banner fat hover_zoom">
                            <div class="row">
                                <div class="banner_in">
                                    <div class="description_box">
                                        <h4 class="darkgrey uppercase font-light nomargin nopadding">Shoes</h4>
                                        <p>here you will find every shoes available in our stores.</p>
                                        <a class="banner_in_aly button mustard" href="#">Browse</a>
                                    </div>
                                </div>
                            </div>
                            <div class="banner_bg">
                                <img class="img_aly" src="/images/banner4new.png">
                            </div>
                        </div>
                    </div>
                    <div class="span6 clearfix">
                        <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Shoes <span class="lightgray">By Zara</span></span></h3>
                        <div class="ux_banner fat hover_zoom">
                            <div class="row">
                                <div class="banner_in">
                                    <div class="description_box">
                                        <h4 class="darkgrey uppercase font-light nomargin nopadding">Shoes</h4>
                                        <p>here you will find every shoes available in our stores.</p>
                                        <a class="banner_in_aly button mustard" href="#">Browse</a>
                                    </div>
                                </div>
                            </div>
                            <div class="banner_bg">
                                <img class="img_aly" src="/images/banner4new.png">
                            </div>
                        </div>
                    </div>
                </div> -->
               
               
                <div class="fullwidth clearfix newsletter_cta twenty_margin_top">
                    <div class="span4 ">
                    </div>
                    <div class="span4 ">
                                <?php 
		                        	if(isset($pagingArr) && !empty($pagingArr)){
		                        		
										if($pagingArr['start'] != $pagingArr['end']){
											
											echo '<div class="paginator">
	                            				<ul>';
											
				                        	if($pagingArr['page'] > 1){
				                      			echo '<a href="?limit=4&page=' . ($pagingArr['page']- 1) . '" class="getPage">Previous</a>';
				                        	}
										
											if ($pagingArr['start'] > 1 ){
										        echo '<li class="current">
										        		<a href="?limit=4&page=1">1</a>
										        	  </li>';
										    }
										
											for ( $i = $pagingArr['start'] ; $i <= $pagingArr['end']; $i++){
										        echo '<li>
										        		<a href="?limit=4&page=' . $i . '">' . $i . '</a>
										        	  </li>';
										    }
									    
										    if ( $pagingArr['end'] < $pagingArr['last']){
										        echo '<li>
										        		<a href="?limit=4&page=' . $pagingArr['last'] . '">' . $pagingArr['last'] . '</a>
										        	  </li>';
										    }
									
											if( $pagingArr['page'] != $pagingArr['last'] ){
												echo '<a href="?limit=4&page=' . ( $pagingArr['page'] + 1 ) . '"> Next &nbsp;</a>';
											}
										echo '	</ul>
	                        			</div>';
										}
									}
		                        ?>
                               
                               
                               
                                <!-- <a href="#">Previous</a>
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
                        </div>-->
                    </div>
                </div>
                <div class="row twenty_margin_top clearfix">
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