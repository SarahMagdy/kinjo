

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

    <!-- 
    ==================================================================
    Featured Banner
    ==================================================================
    -->

    <div class="container">
        <div class="span12 clearfix">

            </div>
        
        <div class="row twenty_margin_top clearfix">
            <div class="span12 clearfix">
                <div class="ux_banner slim hover_zoom">
                    <a href="#">
                        <div class="row">
                            <div class="banner_in banner_padding">
                                <h3 class="black uppercase font-light nomargin nopadding text-center">discover your chance between<span style="color:red;">125</span> store</h3>
                            </div>
                        </div>
                        <div class="banner_bg banner5"></div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row twenty_margin_top clearfix">
            <div class="span12 clearfix">
                <h3 class="heading darkgrey font-light uppercase"><span class="heading_whitebg">Stores <span class="lightgray"></span></span></h3>
            </div>
        </div>
        
        
        
        
        <?php 
        	if(isset($allStores) && !empty($allStores)){
        		$path = '/images/upload/business_unit/';
				
        		echo '<div class="row clearfix">'; //twenty_margin_top
				 
				foreach ($allStores as $key => $row) {
					$img = substr($row['logo_url'], strrpos($row['logo_url'], '/') + 1);
				 
				    echo '<div class="span4 block-fix clearfix">
				                <div class="ux_banner fat hover_zoom">
				                    <div class="row">
				                        <div class="banner_in bottom">
				                            <h3 class="white uppercase font-light nomargin nopadding pull-left">'.$row['title'].'</h3>
				                            <a class="button mustard pull-left" id="" href="/home/GetCat/'.$row['id'].'">Visit Store</a>
				                        </div>
				                    </div>
				                    <div class="banner_bg "><img class="img_aly" src="'.$path.$img.'"></div>
				                </div>
				           </div>';
				}			
							
				echo '</div>';
        	}
        ?>
        
        
        
        <!-- Stores begin here -->
        <!-- <div class="row twenty_margin_top clearfix">
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Zara Loran</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner9.png"></div>
                </div>
            </div>
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Adadis san</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner10.png"></div>
                </div>
            </div>
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Max Carrefour</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner8.png"></div>
                </div>
            </div>
        </div>
        
        <div class="row twenty_margin_top clearfix">
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Zara Loran</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner9.png"></div>
                </div>
            </div>
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Adadis san</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner10.png"></div>
                </div>
            </div>
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Max Carrefour</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner8.png"></div>
                </div>
            </div>
        </div>
        
        <div class="row twenty_margin_top clearfix">
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Zara Loran</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner9.png"></div>
                </div>
            </div>
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Adadis san</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner10.png"></div>
                </div>
            </div>
            <div class="span4 clearfix">
                <div class="ux_banner fat hover_zoom">
                    <div class="row">
                        <div class="banner_in bottom">
                            <h3 class="white uppercase font-light nomargin nopadding pull-left">Max Carrefour</h3>
                            <a class="button mustard pull-left" href="#">Visit Store</a>
                        </div>
                    </div>
                    <div class="banner_bg "><img class="img_aly" src="/images/banner8.png"></div>
                </div>
            </div>    
        </div> -->
        
        <div class="fullwidth clearfix newsletter_cta twenty_margin_top">
            <div class="span4 ">
            </div>
            <div class="span4 ">
                <div class="paginator">
                    <ul>
                        
                        <?php 
                        	if($pagingArr['page'] > 1){
                      			echo '<a href="?page=' . ($pagingArr['page']- 1) . '" class="getPage">Previous</a>';
                        		// echo '<li class="current">
                        				// <!-- <a href="?limit=' . $pagingArr['limit'] . '&page=' . ($pagingArr['page']- 1) . '">&laquo;</a>-->                       				
                        			  // </li>';
                        	}
							
							if ($pagingArr['start'] > 1 ){
						        echo '<li class="current">
						        		<a href="?page=1" >1</a>
						        	  </li>';
						    }
							
							for ( $i = $pagingArr['start'] ; $i <= $pagingArr['end']; $i++){
						        echo '<li>
						        		<a href="?page=' . $i . '" >' . $i . '</a>
						        	  </li>';
						    }
						    
						    if ( $pagingArr['end'] < $pagingArr['last']){
						    
						        echo '<li>
						        		<a href="?page=' . $pagingArr['last'] . '">' . $pagingArr['last'] . '</a>
						        	  </li>';
						    }
							
							if( $pagingArr['page'] != $pagingArr['last'] ){
    							// echo '<li class="disabled">
    									// <a class="getPage" href="?limit=' . $pagingArr['limit'] . '&page=' . ( $pagingArr['page'] + 1 ) . '">&raquo;</a>
    								  // </li>';
								echo '<a href="?page=' . ( $pagingArr['page'] + 1 ) . '"> Next &nbsp;</a>';
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
                        <a href="#">Next &nbsp;</a> -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- 
        ==================================================================
        Big Products Section
        ==================================================================
        -->
          
          

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

    <div class="row twenty_margin_top clearfix">
    
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
<script src="/js/static-script.js" type="text/javascript"></script>

<script type="text/javascript">
	x=document.getElementById('search_field')
	x.onfocus=function(){
	    this.value = "";
	}
	
/*
	$('.getPage').click(function(e){
		e.preventDefault();
		// /home/GetCat
		
		$.get( "/home/actionGetPage", function() {
		  alert( "success" );
		})
		
	});*/

	
</script>
<script type="text/javascript">
// $('.has-children').click(function(){
//    alert("clicked");
// });
 //return false;
// });


</script>


</body>
</html>

