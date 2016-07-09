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

<div class="container">
    <!-- Breadcrumbs -->
    <div class="row" >
        <div class="cart_nav span12" >
  
        </div>
    </div>
    <!-- /Breadcrumbs -->

    <!-- Cart -->
    <div class="cart row">

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

        <?php if(!empty($ordOPen)){
    		$path = '/images/upload/products/';
			foreach($ordOPen['OrdBuS'] as $key => $row){
				echo '<input type="hidden" id="bureservd_'.$row['Buid'].'" value="'.$row['IsReserved'].'">';
				// $items = count($row['BuDetails']);
				$items = 0;
					
				foreach ($row['BuDetails'] as $key2 => $row2) {
					$img = substr(strrchr($row2['ProdImg'], '/'), 1);
					$items += $row2['Qnt'];
						
					echo '<div class="product span12" id="'.$row2['ID'].'">
				            <div class="row">
				                <div class="span8 clearfix">
				                    <div class="image">
				                        <a href="/home/ProDetail/'.$row2['ProdID'].'">
				                            <img src="'.$path.$img.'" alt=""/>
				                        </a>
				                    </div>
				                    <div class="detail">
				                       <p>'.$row2['ProdName'];
										   
					if(isset($row2['SubCatName']) && !empty($row2['SubCatName']) ){
						echo ' by <a href="/home/SubCatPro/?catID='.$row2['ParCatID'].'">'.$row2['SubCatName'].'</a>';
					}
							
					echo '</p>';
								
					echo '<ul class="info" id="dubs">';
					if(isset($row2['ProdColor']) && !empty($row2['ProdColor'])){
						echo '<li class="clearfix"><!-- class ="item"-->
							 	<label class="pull-left">Colour</label>
								<ul class="product_colors pull-right">';
						foreach ($row2['ProdColor'] as $key3 => $row3) {
							$colClass = ""; $style="";
						   	if($row3['IS_Apply'] == 'TRUE'){
						   		// echo '<p>Colour : '. $row3['ColorName'].'</p>';
						   		$colClass = "colselected_".$row2['ID'];
						   		// $style = " border-style: dotted";
						   	}
							echo '<a href="#" id="'.$row3['ColorID'].'" class="colclass '.$colClass.'" det_id="'.$row2['ID'].'">
                					<li class="item dib" style="background-color:'.$row3['ColorCode'].';">
                					</li> </a>';
					   }

						echo '</ul>
				 		</li>';
						
					}
					 			   
				   foreach ($row2['ProdConf'] as $key4 => $row4) {
						echo '<li class="clearfix">
		                  		<label class="pull-left">'.$row4['Conf'].'</label>
		                    	<ul class="sizes product_sizes pull-right">';
				   	  // if (in_array("TRUE", $row4['SubConfig'][$key4] )) {
						 // echo '<p>'.$row4['Conf'].' : ';
					  // }
					  foreach ($row4['SubConfig'] as $key5 => $row5) {
					  	$type = 'radio'; $confCHK="";
						if($row4['Check'] == 'TRUE'){
							$type = 'checkbox';
						}
						  
						if($row5['IS_Apply'] == 'TRUE'){
					   		// echo $row5['SubConf'].'&nbsp &nbsp';
					   		$confCHK = "checked";
					   	}
						echo '<li class="item dib">
	                            <label>
	                                <input type="'.$type.'" name="'.$row4['Conf'].'" value="'.$row5['subId'].'" class="conf_'.$row2['ID'].'" '.$confCHK.'/>
	                                <b>'.$row5['SubConf'].'</b>
	                            </label>
	                        </li>';	 
					  }
					  // if (in_array("TRUE", $row4['SubConfig'][$key4] )) {
						// echo '</p>';
					  // }
					  
					  echo '</ul>
		                	 </li>';  
				  }
				echo '</ul>';
										   
		         echo '   	</div>
		               	 </div>
		                	<div class="quantity span1 text-center">
			                    <div class="urbaspin">
			                        <i data-arrow="up" class="ord_qnt">&#9650;</i>
			                        <label id="qnt_lbl_'.$row2['ID'].'"> '.$row2['Qnt'].' </label>
			                        <i data-arrow="down" class="ord_qnt">&#9660;</i>
			                    </div>
		                	</div>
			                <div class="price span3 text-center pull-right">
			                	
			                    <button type="button" bu_id="'.$row['Buid'].'" DetId="'.$row2['ID'].'" class="close cardRmvCls">×</button>
			                    <h5 class="peritem">'.$row2['Qnt'].' x '.$row2['Price'].' '.$row['BuCurr'].'</h5>
			                    <span class="total">'.$row2['F_Price'].' '.$row['BuCurr'].'</span>
			                
			                	<button type="button" proID="'.$row2['ProdID'].'" id="'.$row2['ID'].'" class="editOrd"  bu_id="'.$row['Buid'].'"> Change </button>
								
			                </div>
		            	</div>
		        	</div>';
				}
				
				
				$CloseBTN = '';
				if($row['PayType'] == 0)
				{	
					$CloseBTN = '<a href="/home/Checkout/?Buid='.$row['Buid'].'" class="button button-fluid mustard checkout" bu_id="'.$row['Buid'].'" paytype="'.$row['PayType'].'">Checkout</a>';
					
				}elseif($row['PayType'] == 1){
						
					$CloseBTN = '<a href="#" id="closeBu" class="button button-fluid mustard checkout" bu_id="'.$row['Buid'].'" paytype="'.$row['PayType'].'">Close</a>';
				
				}elseif($row['PayType'] == 2){
					
					$CloseBTN = '<a href="#" id="closeBu" class="button button-fluid mustard checkout" bu_id="'.$row['Buid'].'" paytype="'.$row['PayType'].'" > Close </a>
								OR
								 <a href="/home/Checkout/?Buid='.$row['Buid'].'" class="button button-fluid mustard checkout" bu_id="'.$row['Buid'].'" paytype="'.$row['PayType'].'" >Checkout</a>';
				}
							
				
				echo ' <div class="total span12">
					            <div class="row">
					                <div class="items text-left span2 offset7">
					                    '.$row['BuName'].' '.$items.' items
					                </div>
					                <div class="price text-center span3">'.$row['BuTotal'].' '.$row['BuCurr'].'</div>
					            </div>
								<div class="row" style="margin-top: 15px;">
					                <div class="text-right span4 offset7">
					                '.$CloseBTN.'
					                </div>
					            </div>
					            <a href="#" class="RmvBu" bu_id="'.$row['Buid'].'">X</a>
					        </div>';
				
							
							
				
			}
        }
        ?>
        
        <!-- <div class="product span12">
            <div class="row">
                <div class="span8 clearfix">
                    <div class="image">
                        <a href="product.php">
                            <img src="/images/products/small7.png" alt=""/>
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
                        <i data-arrow="up">&#9650;</i>
                        <label>1</label>
                        <i data-arrow="down">&#9660;</i>
                    </div>
                </div>
                <div class="price span3 text-center pull-right">
                    <button type="button" class="close">×</button>
                    <h5 class="peritem">2x35.00€</h5>
                    <span class="total">39.50€</span>
                </div>
            </div>
        </div> -->
        
        <!-- <div class="total span12">
            <div class="row">
                <div class="items text-left span2 offset7">
                    Zara stores 5 items
                </div>
                <div class="price text-center span3">169€</div>
            </div>
			<div class="row" style="margin-top: 15px;">
                
                <div class="text-right span4 offset7">
                    <a href="address.php" class="button button-fluid mustard">Checkout</a>
                </div>
            </div>
        </div> -->
        
        
        
        <!-- <div class="span12 actions">
            <div class="row">
                <div class="span3">
                </div>
            </div>
        </div> -->

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
</div>-->
<!-- /Popup -->

<!-- Popup 2-->

<!-- /Popup 2-->



<script src="/js/static-jquery.isotope.min.js" type="text/javascript"></script>
<script src="/js/static-jquery-ui-custom.min.js" type="text/javascript"></script>
<script src="/js/static-jquery.cookie.js"></script>
<script src="/js/static-script.js" type="text/javascript"></script>

<script>
	$('.cardRmvCls').click(function(e){
		e.preventDefault();
		var DetId = $(this).attr('DetId') ;
		var buID = $(this).attr('bu_id');
		if($('#bureservd_'+buID).val() == 'false'){
			if (confirm("Remove From Card ?")) {
				var xx = {
					c_id : DetId ,                  // Detail ID
					id   : $('.showCard').html()    // Order ID
				};
				
				$.post( "/home/RmvFromOrder/", xx , function( data ){
									
					data = data.trim();
					var jsonData = data.toString();
					
					endData = $.parseJSON(jsonData);
				
					if(endData['error'] && endData['error'] !="")
					{
						alert(endData['error']['message']);
					}else{						
						window.location.reload(true);
						// $( "#"+DetId ).remove();
					}
				});
			}
		}else{
			alert('You cannot Delete from this store , Complete Your Payment ');
		}
	});
	
	
	$('.RmvBu').click(function(eve){
		eve.preventDefault();
		var buID = $(this).attr('bu_id');
		if($('#bureservd_'+buID).val() == 'false'){
			if (confirm("Remove This Store ?")) {
				var p = {
					bu_id : $(this).attr('bu_id') ,
					id    : $('.showCard').html()
				};
				
				$.post( "/home/RmvBuFromOrder/", p , function( data ){
					window.location.reload(true);
				});
			}
		}else{
			alert('You cannot Delete from this store , Complete Your Payment ');
		}
	});
	
	
	$(document).ready(function () {
		$('.colclass').click(function(e){
			var det_id = $(this).attr('det_id');

			e.preventDefault();
			$('.colclass').removeClass('colselected_'+det_id);
			
			$(this).addClass('colselected_'+det_id);
		});
	});
	
	
	$('.editOrd').click(function(e){
		e.preventDefault();
		var buID = $(this).attr('bu_id');
		
		if($('#bureservd_'+buID).val() == 'false'){
			var confString = "";		
			var det_id = $(this).attr('id');
			var p_id   = $(this).attr('proID');
			
			$(".conf_"+det_id+":checked").each(function() {
				confString += this.value + ',';
			});
			confString = confString.substring(0,confString.length - 1);
			
			var xx = { 
				qnt   : $('#qnt_lbl_'+det_id).html(),
				c_id  : det_id ,
				p_id  : p_id , 
				conf  : confString,
				id    : $('.showCard').html(),
				color : $(".colselected_"+det_id).attr("id"),
				bu_id : buID
			};
			
			$.post( "/home/EditOrder/", xx , function( data ){
				window.location.reload(true);
			});
		}else{
			alert('You cannot Edit to this store , Complete Your Payment ');
		}
	});
	
	
	$('.checkout').click(function(e){
		// e.preventDefault();
		var href = this.href;
		var buID = $(this).attr('bu_id');
		
		if($('#bureservd_'+buID).val() == 'false'){
			// if($(this).attr('paytype') == 2){
				// alert($(this).attr('paytype'));
			// }else if($(this).attr('paytype') == 0){
				// alert($(this).attr('paytype'));
			// }else{
				// alert($(this).attr('paytype'));
			// }
		}else{
			alert('You Already Did Your checkOut');
			return false;
		}
		
		
			// CreateSubDialog();
		    
	});
	
	$('#closeBu').click(function(e){
		e.preventDefault();
		
		var buID = $(this).attr('bu_id');
		
		var xx = { 
			pay_type : 1,
			id       : $('.showCard').html(),
			bu_id    : buID
		};
		
		$.post( "/home/Shipping/", xx , function( data ){
			// window.location.reload(true);
			$('#bureservd_'+buID).val('true');
		});
		
	});
	
	// function CreateSubDialog(){
		 // $(function() {
			// dialog = $('#dialogDiv').dialog({
					// autoOpen: false,
					// height: 400,
					// width: 450,
					// modal: true,
					// buttons: {
					// // "Submit": SubmitDialogFrm,
					// // success:function(){
						// // dialog.dialog( "close" );
					// // },
						// Submit:function(){
							// dialog.dialog( "close" );
						// },
						// Cancel: function() {
							// dialog.dialog( "close" );
						// }
					// },
					// close: function() {
						// dialog.dialog( "close" );
					// }
				// });
			// });
			// dialog.dialog( "open" );
	// }
	
</script>

</body>
</html>


