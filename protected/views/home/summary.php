<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Kinjo Store</title>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">

        <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet" media="screen">

        <!-- Stylesheet -->
        <link href="css/style.css" rel="stylesheet" media="screen">
        
        <link href="images/favicon.ico" rel="shortcut icon">

        <!--Jquery Init -->
        <script type="text/javascript" src="js/jquery.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>

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
    <!-- Breadcrumbs -->
    <div class="row">
        <div class="cart_nav span12">
            <ul class="list row">
                <li class="item span3">
                    <a href="cart.php">Shopping cart</a>
                </li>
                <li class="item span3">
                    <a href="address.php">Delivery address</a>
                </li>
                <li class="item span3">
                    <a href="shipping.php">Shipping / payment</a>
                </li>
                <li class="item span3 current">
                    <a href="summary.php">Summary</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breadcrumbs -->

    <!-- Cart -->
    <div class="cart summary row">

        <div class="address_box span6">
            <div class="row">
                <div class="span3 column">
                    <h5 class="main">Billing address</h5>
                    <p class="description">
                        Kinjo Store<br>
                        Redbull Street 66 <br>
                        909 66 Heineken <br>
                        united kingdom<br>
                    </p>
                </div>
                <div class="span3 column">
                    <h5 class="main">Delivery address</h5>
                    <p class="description">
                        kinjo store <br>
                        Redbull Street 66 <br>
                        909 66 Heineken <br>
                        united kingdom <br>
                    </p>
                </div>
            </div>
            <div class="row action">
                <button class="button darkgrey pull-right">Change</button>
            </div>
        </div>
        <div class="address_box span6">
            <div class="row">
                <div class="span3 column">
                    <h5 class="main">Shipping option</h5>
                    <p class="description">
                        DHL Standard Delivery
                    </p>
                </div>
                <div class="span3 column">
                    <h5 class="main">Payment method</h5>
                    <p class="description">
                        Creditcard <br>
                        1234 567 890/9876 <br>
                        05/15 <br>
                    </p>
                </div>
            </div>
            <div class="row action">
                <button class="button darkgrey pull-right">Change</button>
            </div>
        </div>

        <div class="thead span12">
            <div class="row">
                <div class="span8">
                    <h4 class="title">Product</h4>
                </div>
                <div class="span1 text-center">
                    <h4 class="title">QTY.</h4>
                </div>
                <div class="span3 text-center">
                    <h4 class="title">Price</h4>
                </div>
            </div>
        </div>

        <div class="product span12">
            <div class="row">
                <div class="span8 clearfix">
                    <div class="image">
                        <a href="product.php">
                            <img src="images/products/small1.png" alt=""/>
                        </a>
                    </div>
                    <div class="detail">
                        <p>Eskimo Toast T-shirt by <a href="category_template_2.php">Zara</a></p>
                        <p>Size: M</p>
                        <p>Colour: blue</p>
                    </div>
                </div>
                <div class="quantity span1 text-center">
                    <div class="urbaspin">
                        <label>2</label>
                    </div>
                </div>
                <div class="price span3 text-center pull-right">
                    <button type="button" class="close">×</button>
                    <h5 class="peritem">2x35.00€</h5>
                    <span class="total">70.00€</span>
                </div>
            </div>
        </div>
        <div class="product span12">
            <div class="row">
                <div class="span8 clearfix">
                    <div class="image">
                        <a href="product.php">
                            <img src="images/products/small8.png" alt=""/>
                        </a>
                    </div>
                    <div class="detail">
                        <p>Eskimo Toast T-shirt by <a href="category_template_2.php">Cleptomanicx</a></p>
                        <p>Size: M</p>
                        <p>Colour: blue</p>
                    </div>
                </div>
                <div class="quantity span1 text-center">
                    <div class="urbaspin">
                        <label>1</label>
                    </div>
                </div>
                <div class="price span3 text-center  pull-right">
                    <button type="button" class="close">×</button>
                    <h5 class="peritem">2x35.00€</h5>
                    <span class="total">59.50€</span>
                </div>
            </div>
        </div>
        <div class="product span12">
            <div class="row">
                <div class="span8 clearfix">
                    <div class="image">
                        <a href="product.php">
                            <img src="images/products/small7.png" alt=""/>
                        </a>
                    </div>
                    <div class="detail">
                        <p>Eskimo Toast T-shirt by <a href="category_template_2.php">Cleptomanicx</a></p>
                        <p>Size: M</p>
                        <p>Colour: blue</p>
                    </div>
                </div>
                <div class="quantity span1 text-center">
                    <div class="urbaspin">
                        <label>1</label>
                    </div>
                </div>
                <div class="price span3 text-center  pull-right">
                    <button type="button" class="close">×</button>
                    <h5 class="peritem">2x35.00€</h5>
                    <span class="total">39.50€</span>
                </div>
            </div>
        </div>

        <div class="span12 delivery">
            <div class="row">
                <h4 class="span9">Shipping: DHL Standard Delivery (2-5 working days)</h4>
                <span class="span3 total">2.99€</span>
            </div>
        </div>

        <div class="total span12">
            <div class="row">
                <div class="items text-right span3 offset6">
                    Total price:
                </div>
                <div class="price text-center span3">169€</div>
            </div>
        </div>
        <div class="span12 actions">
            <div class="row">
                <div class="span3">
                    <a href="home.php" class="button darkgrey">back to shipping</a>
                </div>
                <div class="text-right span3 offset6">
                    <a href="order_confirmation.php" class="button mustard">Confirm Order</a>
                </div>
            </div>
        </div>

    </div>
    <!-- /Cart -->

</div>


<!-- 
==================================================================
Newsletter
==================================================================
-->

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



<!-- Popup
<div class="popup">
    <div class="mask"></div>
    <div class="box span9 clearfix">
        <i class="close">&#9587;</i>
        <figure class="product_fb">
            <img src="images/products/prod-detail.png" alt="">
        </figure>
    </div>
</div>
<!-- /Popup -->

<!-- Popup 2-->
<div class="container popup offer">
    <div class="mask"></div>
    <div class="row">
        <div class="box span9 clearfix">
            <i class="close">&#9587;</i>
            <div class="row">
                <figure class="promo span4">
                    <img src="images/subscribe_promo.jpg" alt=""/>
                </figure>
                <div class="content span5">
                    <div class="header">
                        <span class="off font-oswald special-text">20%</span>
                        <span class="uppercase text">one day 
                            <span class="special-text">discount</span> every month
                        </span>
                    </div>
                    <div class="main">
                        <h4 class="uppercase font-light">Subscribe and don’t miss it!</h4>
                        <form>
                            <input type="text" placeholder="Your Email" name="email" class="uppercase"/>
                            <input type="submit" value="Send" class="button mustard"/>
                        </form>
                        <div class="social ten_margin_top">
                            <span class="lbl uppercase">recommend for friends</span>
                            <div class="icons pull-right">
                                <a href="#" class="dib">
                                    <img src="images/social/facebook.png" alt=""/>
                                </a>
                                <a href="#" class="dib">
                                    <img src="images/social/twitter.png" alt=""/>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Popup 2-->

<script src="js/jquery.isotope.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/script.js" type="text/javascript"></script>

</body>
</html>