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
                                    <a class="solid_links" href="#" >profile settings</a>                              
                                </div>
                                <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="#" >account settings</a>                              
                                </div>
                                <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="#" >Personal settings</a>                              
                                </div>
                                <div class="span2 ten_margin_top">
                                    <a class="solid_links" href="#" >Order history</a>                              
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter">
                    <div class="span9 filter">
                        <div class="title ">
                            <h4 class="center_text">Order History</h4>
                            <div class="span9 light_seperator"></div>
                            <div class="span9 order_history">
                                <table class="table table-striped table-collapse">
                                    <thead>
                                        <tr>
                                            <th class="span3">Order no.</th>
                                            <th class="span2">Order created</th>
                                            <th class="span2">Order shipped</th>
                                            <th class="span2">Quantaty</th>
                                            <th class="span2">Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="#">T-shirt Body</a>
                                            </td>
                                            <td>
                                                <span>20/6/2013</span>
                                            </td>
                                            <td>
                                                <span>21/6/2013</span>
                                            </td>
                                            <td>
                                                <span>1</span>
                                            </td>
                                            <td>
                                                <span>Paypal</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#">Lino pants</a>
                                            </td>
                                            <td>
                                                <span>28/6/2013</span>
                                            </td>
                                            <td>
                                                <span>30/6/2013</span>
                                            </td>
                                            <td>
                                                <span>3</span>
                                            </td>
                                            <td>
                                                <span>Creditcard</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="#">hoodie T-Shirts</a>
                                            </td>
                                            <td>
                                                <span>19/7/2013</span>
                                            </td>
                                            <td>
                                                <span>Not shipped</span>
                                            </td>
                                            <td>
                                                <span>1</span>
                                            </td>
                                            <td>
                                                <span>Creditcard</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="span9 light_seperator"></div>
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