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
        <link href="images/favicon.ico" rel="shortcut icon">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <!--Jquery Init -->
        <script type="text/javascript" src="/js/static-jquery.js"></script>
        <script src="/bootstrap/js/static-bootstrap.min.js"></script>
        <script>
        	$(document).ready(function () {
	        	$('.CustAdd').click(function(e){
	        		e.preventDefault();
	        		
	        		var addID = $(this).attr('addID');
	        		
	        		var xx = { 
						AddrID  : addID,
					}
					$.post( "/home/DefaultAddr/", xx , function( data ){
						// window.location.reload(true);
						
						data = data.trim();
						var jsonData = data.toString();
						endData = $.parseJSON(jsonData);
						// alert (endData['Result']);
						if(endData['Result'] == 'TRUE')
						{
							$('.rmvDefault').hide();
							$('#default_'+addID).show();
						}
						
						
						
					});
	        		
	        	});
	        });
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
        <!-- <div class="container auth">
            <h3 class="uppercase normal text-center title">Contact Us</h3>
            <div class="row">
                <div class="register span10 offset1">
                   
                    <form id="form-register" method="post" action="#" class="form-horizontal">
                        <div class="element row">
                            <label class="span2 offset1">First name</label>
                            <input class="span6" type="text" name="firstname">
                        </div>
                        <div class="element row">
                            <label class="span2 offset1">Email</label>
                            <input class="span6" type="text" name="email" style="cursor: auto; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;">
                        </div>
                        <div class="element row">
                            <label class="span2 offset1">Subject</label>
                            <input class="span6" type="text" name="lastname">
                        </div>
                        <div class="element row">
                            <label class="span2 offset1">message</label>
                            <div class="span6">
                                <textarea rows="9" class="span6" name="message content" placeholder="type in your question"></textarea>
                            </div>
                        </div>
                        <div class="element row">
                            <div class="span8 offset1 text-right">
                                <input class="button span4 mustard" type="submit" value="send message">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div> -->
        <!-- 
            ==================================================================
            Newsletter
            ==================================================================
            -->
    <div class="container">
        <!-- My Account -->
        <div class="row twenty_margin_top clearfix">
            <div class="span3 sidebar filters">
                <div class="filter">
                    <div class="title">
                        <h4 style="font-family: 'Oswald', sans-serif; font-size: 25px; font-weight: 100;">User Preferances</h4>
                    </div>
                    <div class="wrapper">
                        <div class="content">
                            <div class="span2 ten_margin_top">
                                <a class="solid_links" href="/home/CustProfile/1" >profile settings</a>                              
                            </div>
                           	<div class="span2 ten_margin_top">
                                <a class="solid_links" href="/home/CustProfile/2" >Addresses</a>                              
                            </div>
                            <!-- <div class="span2 ten_margin_top">
                                <a class="solid_links" href="#" >Modifey Addressess</a>
                            </div> -->
                            <div class="span2 ten_margin_top">
                                <a class="solid_links" href="/home/OrdHistory" >Order history</a>                              
                            </div>
                            <div class="span2 ten_margin_top">
                                <a class="solid_links resetPass" href="/home/ResetPass" >Reset Password</a>                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="filter">
                <div class="span9 filter">
                    <div class="title ">
                        <h4 class="center_text">User Addresses</h4>
                        <div class="span9 light_seperator"></div>
                        
                        <?php 
                        	foreach($custData['Addr'] as $key=>$row){
                        		
								$default = 'none';
								if($row['AddDefault'] == 'TRUE'){$default = 'inline-block';}
								
                        		echo '<div class="span3 box_adress_aly">
					                    <div class="row title">
					                        <h5 class="uppercase lbl span3">Billing address &nbsp;
					                        <i style="color:green; font-size:20px; display:'.$default.';" class="fa fa-check-circle rmvDefault" id="default_'.$row['AddID'].'"></i></h5>
					                    </div>
					                    <div class="row  item">
					                        <div class="span1 red">
					                            <label>Country</label>
					                        </div>
					                        <span class="span2">'.$row['AddCountry'].'</span>
					                    </div>
					                    
					                    <div class="row item">
					                        <div class="span1 red">
					                            <label>City</label>
					                        </div>
					                        <span class="span2">'.$row['AddCity'].'</span>
					                    </div>
					                    
					                    <div class="row item">
					                        <div class="span1 red">
					                            <label>State / Region</label>
					                        </div>
					                        <span class="span2">'.$row['AddRegion'].'</span>
					                    </div>
					                    
					                    <div class="row item">
					                        <div class="span1 red">
					                            <label>Street</label>
					                        </div>
					                        <span class="span2">'.$row['AddStreet'].'</span>
					                    </div>
					                    
					                    <div class="row item">
					                        <div class="span1 red">
					                            <label>Postal Code</label>
					                        </div>
					                        <span class="span2">'.$row['AddPostal'].'</span>
					                    </div>
					                    <div class="row item pull-left">
					                        <a href="#" class="button pull-right darkgrey span2 CustAdd" addID="'.$row['AddID'].'">Mark as Billing Address</a>
					                    </div>
			            		</div>';
								
								if(($key+1) % 2 == 0){
									echo '<div class="span9 light_seperator"></div>';
								}
                        	}
                        ?>
                        
                        
                        
                        <!-- <div class="span3 box_adress_aly">
		                    <div class="row title">
		                        <h5 class="uppercase lbl span3">Billing address &nbsp;<i style="color:green; font-size:20px" class="fa fa-check-circle"></i></h5>
		                    </div>
		                    <div class="row  item">
		                        <div class="span1 red">
		                            <label>city</label>
		                        </div>
		                        <span class="span2">Aly</span>
		                    </div>
		                    <div class="row item">
		                        <div class="span1 red">
		                            <label>postal</label>
		                        </div>
		                        <span class="span2">Zidan</span>
		                    </div>
		                    <div class="row item">
		                        <div class="span1 red">
		                            <label>Street</label>
		                        </div>
		                        <span class="span2">Alexandria 66</span>
		                    </div>
		                    <div class="row item">
		                        <div class="span1 red">
		                            <label>City</label>
		                        </div>
		                        <span class="span2">909 66 Boxing Ringz</span>
		                    </div>
		                    <div class="row item">
		                        <div class="span1 red">
		                            <label>Country</label>
		                        </div>
		                        <span class="span2">Germany</span>
		                    </div>
		                    <div class="row item pull-left">
		                        <a href="#" class="button pull-right darkgrey span2">Mark as Billing Address</a>
		                    </div>
            		</div> -->
	                <!-- <div class="span3 box_adress_aly">
	                    <div class="row title">
	                        <h5 class="uppercase lbl span3">Billing address</h5>
	                    </div>
	                    <div class="row  item">
	                        <div class="span1 red">
	                            <label>postal</label>
	                        </div>
	                        <span class="span2">12653</span>
	                    </div>
	                    <div class="row item">
	                        <div class="span1 red">
	                            <label>postal</label>
	                        </div>
	                        <span class="span2">Zidan</span>
	                    </div>
	                    <div class="row item">
	                        <div class="span1 red">
	                            <label>Street</label>
	                        </div>
	                        <span class="span2">Alexandria 66</span>
	                    </div>
	                    <div class="row item">
	                        <div class="span1 red">
	                            <label>City</label>
	                        </div>
	                        <span class="span2">909 66 Boxing Ringz</span>
	                    </div>
	                    <div class="row item">
	                        <div class="span1 red">
	                            <label>Country</label>
	                        </div>
	                        <span class="span2">Germany</span>
	                    </div>
	                    <div class="row item pull-left">
	                        <a href="#" class="button pull-right darkgrey span2">Mark as Biling Address</a>
	                    </div>
	                </div> -->
                            <!-- <div class="span9 light_seperator"></div> -->
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- /My Account -->
        </div>
        <div class="fullwidth clearfix newsletter_cta twenty_margin_top">
            <div class="container">
                <div class="row clearfix">
                    <div class="span8">
                        <h3 class="pull-left uppercase font-light lightgray">subscribe to newsletter<span class="mustard">get a 10% discount on 1st purchase</span></h3>
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