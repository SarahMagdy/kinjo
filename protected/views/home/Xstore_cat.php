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
    	
	<?php
		include("header.php");
	?>
	
	
	
	
		<?php 
        	if(isset($P_Arr) && !empty($P_Arr)){
        		$path = '/images/upload/catsub/';
				
        		echo '<div class="row twenty_margin_top clearfix">';
				 
				foreach ($P_Arr as $key => $row) {
					// $img = substr($row['logo_url'], strrpos($row['logo_url'], '/') + 1);
				    echo '<div class="span4 clearfix">
				                <div class="ux_banner fat hover_zoom">
				                    <div class="row">
				                        <div class="banner_in bottom">
				                            <h3 class="white uppercase font-light nomargin nopadding pull-left">'.$row['ParCatName'].'</h3>
				                            <a class="button mustard pull-left" id="" href="#">Visit Store</a>
				                        </div>
				                    </div>
				                    <div class="banner_bg "><img class="img_aly" src="'.$path.$row['ParCatImg'].'"></div>
				                </div>
				           </div>';
				  }	
							
							
				echo '</div>';
        	}
        ?>
	
	
	
	
	
	<?php
		include('footer.php');
	?>
	<script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
	<script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
	<script src="/js/static-jquery.cookie.js"></script>
	<script src="/js/static-script.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		x=document.getElementById('search_field')
		x.onfocus=function(){
		    this.value = "";
		}
	
		
	</script>

	</body>
</html>