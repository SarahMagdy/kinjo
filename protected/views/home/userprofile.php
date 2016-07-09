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
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <!--Jquery Init -->
        <script type="text/javascript" src="/js/static-jquery.js"></script>
        <script src="/bootstrap/js/static-bootstrap.min.js"></script>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<link href="/css/jquery-ui.css" rel="stylesheet" media="screen">
        
        <script type="text/javascript">
        	
        	
        	function xx(){
        		$(".BDate").datepicker({
					changeMonth: true , 
					changeYear: true,
					dateFormat: 'yy-mm-dd'
				});
        	}
        	
        	
        	var x = document.getElementsByClassName('change');
            var y = document.getElementById('act');
            
        	$(document).ready(function(){
				
	            $('#act').click(function(){
           
	                $('.change').toggleClass("hide_toggle");
	                $('.change2').toggleClass("hide_toggle");
	                    
	                if($(this).text() == 'Change'){
	                	$(this).text("save");
	                	xx();
	                }else{
	                	
	                	var data = {
	                		lname   : $('#l_name').val(),
	                		b_date  : $('.BDate').val(),
	                		// country : $('#country').val(),
	                		// email   : $('#email').val(),
	                		gender  : $('#gender').val()
	                	};
	                	$.post( "/home/UpCust/",data, function(data) {
							data = data.trim();
							var jsonData = data.toString();
							endData = $.parseJSON(jsonData);
							if(endData['error'] && endData['error'] !="")
							{
								alert(endData['error']['message']);
							}else{				
								$('#sp_l_name').text(endData['Customer'][0]['lname']);
								$('#sp_gender').text(endData['Customer'][0]['gender']);
								$('#BDate').text(endData['Customer'][0]['birthdate']);
							}
							$('#act').text("Change");
						});
	                	
	                }    
					
	                return false;
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
                                <!-- <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="#" >account settings</a>                              
                                </div> -->
                                <!-- <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="#" >Personal settings</a>                              
                                </div> -->
                                <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="/home/CustProfile/2" >Addresses</a>                              
                                </div>
                                <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="/home/OrdHistory" >Order history</a>                              
                                </div>
                                <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="/home/ResetPass" >Reset Password</a>                              
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
					$path = '/images/upload/customers/';
				?>
                <div class="filter">
                    <div class="span9 filter">
                        <div class="title ">
                            <h4 class="center_text">User Preferances</h4>
                            <div class="span8 light_seperator"></div>
                            <div class="span8">
                                <div class="row  item">
                                    <div class="span2">
                                        <label style="color:red;">Firstname</label>
                                    </div>
                                    <span class="span2"><?= $custData['fname']?></span>
                                   
                                </div>
                                <div class="row item">
                                    <div class="span2">
                                        <label>Surname</label>
                                    </div>
                                    <span id="sp_l_name" class="span2 change2"><?= $custData['lname'];?></span>
                                    <input id="l_name" class="span3 change hide_toggle" type="text" name="">
                                </div>
                                <div class="row item">
                                    <div class="span2">
                                        <label>Birth Date</label>
                                    </div>
                                    <span id="BDate" class="span2 change2"><?= $custData['birthdate']?></span>
                                    <input class="span3 change hide_toggle BDate" type="text" name="">
                                </div>
                                
                          
                                <div class="row item">
                                    <div class="span2">
                                        <label>Gender</label>
                                    </div>
                                    <span id="sp_gender" class="span2 change2"><?= $custData['gender']?></span>
                                    <!-- <input class="span3 change hide_toggle" type="text" name=""> -->
                                    <select class="span3 change hide_toggle" id="gender">
										<option value="">-- Select Country --</option>
										<option value="0"  >Male</option>
										<option value="1"  >Female</option>
									</select>
                                </div>
                                <!-- <div class="row item">
                                    <div class="span2">
                                        <label>billing</label>
                                    </div>
                                    <span class="span2">Master Card</span>
                                </div> -->
                                <div class="span7 ">
                                    <a href="#" id="act" class="button darkgrey span2 pull-right">Change</a>
                                </div>
                            </div>
                            <div class="span8 light_seperator"></div>
                            
                            
                            <form id="custForm" name="custForm" action="/home/CustProfile/1" method="POST" enctype="multipart/form-data">
	                            <div class="span8">
	                                <div class="row  item">
	                                    <div class="span2">
	                                        <label style="color:red;">profile picture</label>
	                                    </div>
	                                    <a href="#"><img class="span2" src="<?= $path.substr(strrchr($custData['image'], '/'), 1)?>"></a>
	                                    <div class="span2" style="display:none;">
	                                        <a href="#"  class="button darkgrey span2 pull-right" >Change</a>
	                                    </div>
	                                    
<input type="file" size="32" name="CustPhoto" value=""/>
<input type="submit" value="update"/>
	                                </div>
	                            </div>
                            </form>
                            
                            
                            <div class="span8 light_seperator"></div>
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