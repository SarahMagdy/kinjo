<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>join Kinjo</title>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/bootstrap.css" />
  <!--<link href="./css/bootstrap.css" rel="stylesheet">-->
  <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
  <style type="text/css">
  /* Prevent Page-Jumping */
  .wizard fieldset {
    display:none;
  }
  .wizard fieldset:first-of-type {
    display:block;
  }
  .main-header{
    width:30%;
    margin: 20px auto;
    font-family: 'Lobster', cursive;
    color: rgb(66, 66, 66);
  }
  .center-text{
    text-align: center;
     font-size: 55px;
     color: ;
     text-shadow: 3.019px 2.624px 1px rgba(0, 0, 0, 0.294);
 /* -moz-transform: matrix( 1.91186454011206,0,0,1.91186454011206,0,0);
    -webkit-transform: matrix( 1.91186454011206,0,0,1.91186454011206,0,0);
    -ms-transform: matrix( 1.91186454011206,0,0,1.91186454011206,0,0);*/
  }

  #googleMapDiv {
  	 visibility:hidden;
     position: absolute;
     left: 0px;
     top: 0px;
     width:100%;
     height:100%;
     text-align:center;
     z-index: 1000;
     /*background-color:rgba(192,192,192,0.8);*/
     padding-top: 3%;
     padding-bottom: 3%;
     font-size: 100%;
     -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      background-color: rgba(255,236,208,0.8);
  /*background-image: linear-gradient(90deg, transparent 100%, white 100%), linear-gradient(45deg, rgba(255, 255, 255, .3) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .3) 50%, rgba(255, 255, 255, .3) 75%, transparent 75%, transparent);*/
      background-size: 100% 100%, 100px 100px;
  }	

	#googleMapDiv div#googleMap {
      width:70%;
      height:65%;
      margin: auto;
      /*border: 10px solid #DDDDDD;*/
	}
	
	#MapLbl {
     	  width: 31%;
		  float: left;
		  text-align: center;
		  position: relative;
		  left: 140px;
		background-image: -webkit-linear-gradient( 90deg, rgb(234,234,234) 0%, rgb(246,246,246) 99%);
		  display: block;
	}
	#MapA {
      width:25%; 
      float: left;
	}
	
	.ReqInput {
		border : 2px solid #FF0000;
	}
	
	.NoReqInput {
		border : 1px solid #cccccc;
	}
  </style>
</head>
<body>    
  <div class="container">
    <div id ="content">
      <form id="BuFrm" class="wizard form-horizontal" method="post" action="SubmitClient" enctype="multipart/form-data">
        <div class="main-header">
          <h2 class="center-text">join Kinjo</h2>
        </div>
        <fieldset Num = "F_1" class="F_1">
          <legend >Personal Info</legend>
            <div class="control-group">
              <label class="control-label" for="Name">Name</label>
              <div class="controls">
                <input type="text" class="ChkReq1" id="FName" name="FName" placeholder="First Name"   >
                <input type="text" class="ChkReq1" id="LName" name="LName" placeholder="Last Name"   >
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="Email">E-Mail</label>
              <div class="controls">
                <input type="email" class="ChkReq1" id="Email" name="Email" placeholder="E-Mail"   >
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="Country">Country</label>
              <div class="controls">
                <select id="Country" class="ChkReq1" name="Country"   >
                	<option value="">--Country--</option>
                	<?php foreach($Data['Country'] AS $CKey=>$CRow):?>
                		<option value="<?=$CRow['country_id'];?>"><?=$CRow['name'];?></option>
                	<?php endforeach;?>
                	<option></option>
                </select>
              </div>
            </div>
         
            <div class="control-group">
              <label class="control-label" for="Mobile">Mobile</label>
              <div class="controls">
                  <input class="ChkReq1" name="Mobile" type="text" id="Mobile" placeholder="Mobile"   >
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="Gender">Gender</label>
              <div class="controls">
                <input type="radio" class="ChkReq1" id="GenderM" name="Gender" value="0" checked >Male
				<input type="radio" class="ChkReq1" id="GenderF" name="Gender" value="1" >Female
              </div>
            </div>
         
            <div class="form-actions">
              <!--<button type="submit" name="cancel" value="cancel" class="btn">Cancel</button>
              <button class="btn btn-primary next" onclick="ValidateFunc(1);">Next</button>-->
              <!--<button class="btn btn-primary B-next" clNum="1">Next</button>-->
              <a class="btn btn-primary B-next" clNum="1">Next</a>
            </div>
            
          </fieldset>

          <fieldset Num = "F_2" class="F_2">
            <legend>Business Info</legend>
            <div class="control-group">
              <label class="control-label" for="BuName" >Business Name</label>
              <div class="controls">
                <input type="text" class="ChkReq2" id="BuName" name="BuName" placeholder="Business Name"   >
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="BuType">Business Type</label>
              <div class="controls">
                <select id="BuType" class="ChkReq2" name="BuType"   x-moz-errormessage="Business Type">
                	<option value="">--Business Type--</option>
                	<?php foreach($Data['Types'] AS $TKey=>$TRow):?>
                		<option value="<?=$TRow['type_id'];?>"><?=$TRow['type_name'];?></option>
                	<?php endforeach;?>
                </select>
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="BuLogo">Business Logo</label>
              <div class="controls">
                <input type="file" class="ChkReq2" value="Business Logo" id="BuLogo" name="BuLogo"   >
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="BuDesc">Description</label>
              <div class="controls">
                <textarea id="BuDesc" name="BuDesc" placeholder="Description" ></textarea>
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="BuLoc">Location</label>
              <div class="controls">
                <!--<div id="googleMap" style="width:80%;height:300px;"></div>-->
                <input type="text" class="ChkReq2" id="BuLat" name="BuLat" placeholder="Latitude" readonly  >
                <input type="text" class="ChkReq2" id="BuLong" name="BuLong" placeholder="Longitude" readonly  >
                <button id="LocBtn" class="btn" >Select</button>
               </div>
            </div>
            <div class="form-actions">
              <!--
              <button class="previous btn">Previous</button>
              <button class="btn btn-primary B-next" clNum="2">Next</button>-->
          
              <a class="previous btn">Previous</a>
              <a class="btn btn-primary B-next" clNum="2">Next</a>
              
            </div>
            
          </fieldset>

          <fieldset Num = "F_3" class="F_3" >
            <legend >Cpanel Credentials</legend>
           
            <div class="control-group">
              <label class="control-label" for="UserName">User Name</label>
              <div class="controls">
                <input type="text" class="ChkReq3" id="UserName" name="UserName" placeholder="User Name"   >
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="Password">Password</label>
              <div class="controls">
                <input type="password" class="ChkReq3" id="Password" name="Password" placeholder="Password"   >
              </div>
            </div>
            
            <div class="form-actions">
              <!--
              <button class="previous btn">Previous</button>
              <button type="submit" class="btn btn-primary B-next" clNum="3">Submit</button>-->
              <a class="previous btn">Previous</a>
              <a type="submit" class="btn btn-primary B-next" clNum="3">Submit</a>
              
            </div>
          </fieldset>
	 </form>
    </div>
    <div class=""></div>
	<!--<button class="button" value="Show map">Show map</button>
	<div id="googlemap">
	    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2624.9983685732213!2d2.29432085!3d48.85824149999999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2sgoogle+map+paris+eiffel+tower!5e0!3m2!1sfr!2sbe!4v1387990714927" width="800" height="600" frameborder="0" style="border:0"></iframe>
	</div>-->
  </div>
  
  <div id="googleMapDiv">
  	 <label id="MapLbl"> Select From Map </label>
 	 <a href="#" id = "MapA" onclick="CloseMap();"><img src="/assets/8626beb4/gridview/DeleteRed.png" alt="Delete"></a>
  	 <div id="googleMap"></div>
  </div>
	
 <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js/"></script>-->
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/bootstrap.wizard.js"></script>
  <script src="http://maps.googleapis.com/maps/api/js"></script>
  <!--<script type="text/javascript" src="./js/bootstrap.min.js"></script>
  <script type="text/javascript" src="./js/bootstrap.wizard.js"></script>-->
  <script type="text/javascript">
   

    $(document).ready(function() {
      
      
     /*
      $('fieldset:first').data('validation',function($fieldset, callback) {
             var $name = $('input[name=name]', $fieldset);
             if ($.trim($name.val()) == "" || $.trim($name.val()).length < 4 || $.trim($name.val()).length > 30) {
               if ($name.closest('.control-group').hasClass('error')) return false;
               $name.closest('.control-group').addClass('error');
               var $appendTo = $name.parent();
               if ($appendTo.is('.input-append')) $appendTo = $appendTo.parent();
               $('<span class="help-inline">The name is to short. At least 4 characters, maximum 30.</span>').appendTo($appendTo);
               return callback(false);
             }
             $name.closest('.control-group').removeClass('error');
             $('.help-inline', $name.parent()).remove();
             callback(true);
           });*/
     
      
      $('.wizard').wizard();
 
    });
      $('.B-next').click(function(e){
    	e.preventDefault();
    	var clNum = $(this).attr('clNum');
    	ValidateFunc(clNum);
    });
    
   
    $('#LocBtn').click(function(e){
    	e.preventDefault();
    	$('#googleMapDiv').css('visibility','visible');
    	//$("#googleMapDiv").slideToggle("slow");
    	//$('#overlay').show();
    	 
    	 
    	//$("#googleMap").slideToggle("slow");

    });
    
    $(document).keyup(function(e) {

	  if (e.keyCode == 27) {$('#googleMapDiv').css('visibility','hidden');}
	
	});
    
    //$('#CloseMap').click(function(e){$('#googleMapDiv').css('visibility','hidden');});
    function CloseMap(){$('#googleMapDiv').css('visibility','hidden');}
    
   var myCenter = new google.maps.LatLng(34.82641, 32.22986);
   var zoom = 2;
   var marker;
	  
    function initialize()
	{
		var mapProp = {
		  center:myCenter,
		  zoom:zoom,
		  mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		
	   map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
	   marker = new google.maps.Marker({
		  map:map,
		  draggable:true,
		  animation: google.maps.Animation.DROP,
		  position: myCenter
	   });
	   google.maps.event.addListener(marker, 'click', function(event) {
	     toggleBounce(event.latLng);
	   });
	   
	}
	
	function toggleBounce(location) {

		  if (marker.getAnimation() != null) {
		    marker.setAnimation(null);
		  } else {
		    marker.setAnimation(google.maps.Animation.BOUNCE);
		    placeMarker(location);
		  }
	}
	
	function placeMarker(location) {
	
	 $('#BuLat').val(location.lat());
	 $('#BuLong').val(location.lng());
   }
   
   google.maps.event.addDomListener(window, 'load', initialize);


	function ValidateFunc(FNum)
	{	
		var Result = true;
		
		$('.ChkReq'+FNum).each(function() {
			
			$(this).css('border','1px solid #cccccc');
			
		    var id = this.id;
		    
		    var TagName = $('#'+id).prop('tagName');
		    if($('#'+id).is("input") && $('#'+id).attr('type') == 'file' ){
		    	if($('#'+id).val().length == 0){
	    			$(this).css('border','2px solid #FF0000');
	    			Result = false;
	    		}
		    } else {
		    	if($('#'+id).val() == ""){
		    		$(this).css('border','2px solid #FF0000');
					Result = false;
				}
		    }
		});
		if(Result == true){
			if(FNum == 3){
				$('#BuFrm').submit();
			}
			$('fieldset.F_'+FNum).css('display','none');
			FNum++;
			$('fieldset.F_'+FNum).css('display','block');
			var Li_In = $('li.active').index();
			$('ul li:eq('+Li_In+')').removeClass('active');
			Li_In++;
			$('ul li:eq('+Li_In+')').addClass('active');
			
		}
		
	}

  </script>
</body>
</html>
