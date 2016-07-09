

<form name="BuAccountform" id="BuAccountform" method="POST" enctype="multipart/form-data">
	
	<input hidden type="text" name="modelID" value="<?php echo $modelArr['modelID']; //$model->accid ;?>" id="modelID"/>
	
	
	<div class="container" style="float:left;width:50%">
		<?php //echo '<pre>';print_r($_SERVER); ?>
		<img src='//<?= $modelArr['uploaddir'].$modelArr['imgName'];?>'></img>
	</div>

	<div id="img_show" style="float:right;" class="img-preview img-preview-sm">
		
	</div>

 
   <div>
   		<button title="Zoom In" type="button" id='zoom_in' >Zoom In</button>
    	<button title="Zoom Out" type="button" id='zoom_out' >Zoom Out</button>
   		<button title="rotate left" type="button" id='rotate_left' >Rotate Left</button>
   		<button title="rotate right" type="button" id='rotate_right' >Rotate Right</button>
   		<input type="file" name="inputImage" accept="image/*" id="inputImagee" > </input><!--   -->
   		
   		
   		</br>
   		<!--<button title="set Dimensions" type="button" id='Dimensions' >Dimensions</button>-->
   		
   		<button id="submitImg" name="submitImg" type="submit"  style="height:26px;background-color: #E5F1F4;">Save Image</button>
   </div>

</form>

	<!--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/cropper.js"></script>
	<link  href="<?php echo Yii::app()->request->baseUrl; ?>/css/cropper.css" rel="stylesheet">
	<link  href="<?php echo Yii::app()->request->baseUrl; ?>/css/docs.css" rel="stylesheet">

<script>
	
	
	var $image = $(".container img");
		//1.618,//
		$image.cropper({
			aspectRatio: 16 / 9, 
			zoomable:true,
			// dragmove:function(){
				
			// },
			modal: false,
			// data: {
			    // x: 480,
			    // y: 60,
			    // width: 640,
			    // height: 360
			  // },
			preview: ".img-preview",
			done: function(data) {//alert(data.y);
				// Output the result data for cropping image.
				// $dataX.val(Math.round(data.x));
				// $dataY.val(Math.round(data.y));
				// $dataHeight.val(Math.round(data.height));
				// $dataWidth.val(Math.round(data.width));
			}
		});
		
		
		$('#zoom_in').click(function(){
			$image.cropper("zoom",0.1);	
		});
		
		$('#zoom_out').click(function(){
			$(".container > img").cropper("zoom", -0.1);
		});
		
		
		$('#rotate_left').click(function(){
			$(".container > img").cropper("rotate", 90);
		});
		
		$('#rotate_right').click(function(){
			$(".container > img").cropper("rotate", -90);
		});

	
	var $inputImage =  $("#inputImagee");//$( "input[name='inputImage']" );//

    if (window.FileReader) {
		$inputImage.change(function() {
			var fileReader = new FileReader(),
		    	files = this.files,
		    	file;
		
		    if (!files.length) {
		      return;
		    }
		
		    file = files[0];
		
		    if (/^image\/\w+$/.test(file.type)) {
		 		fileReader.readAsDataURL(file);
		  		fileReader.onload = function () {
		    		$image.cropper("reset", true).cropper("replace", this.result);
					// $inputImage.val("");
		  		};
			} else {
		  		showMessage("Please choose an image file.");
		    }
		});
	} else {
		$inputImage.addClass("hide");
    }
	
	
	
	
	
	// $(document).ready(function(){
	// });	
	
	
	// $('#Dimensions').change(function(){
		// $image.cropper("setData", {width: 580, height: 470});
		
		// $image.cropper("disable");
		
		// $('.img-preview img').css("width", $('#Dimensions').val().split('x')[0]);
        // $('.img-preview img').css("height", $('#Dimensions').val().split('x')[1]);
        
        // $('.container img').css("width", $('#Dimensions').val().split('x')[0]);
        // $('.container img').css("height", $('#Dimensions').val().split('x')[1]);
        
        // $('.container span').removeClass('cropper-dashed');
        // $('.container span').removeClass('cropper-viewer');
        // $('.container span').removeClass('line-e');
        // $('.container span').removeClass('cropper-line'); 
        // $('.container span').removeClass('cropper-point');
        // $('.container span').removeClass('point-w');
        // $('.container span').removeClass('cropper-face');
        // $('.container div').removeClass('cropper-dragger');
        
        // $( "div" ).remove( ".cropper-dragger" );
	// });
	
	
	$('#submitImg').click(function(e){
		e.preventDefault();
		// $(".img-preview img").attr('id' , 'cropped_img');
		// document.getElementsByName('elem name');
		// var canvas = document.getElementById('cropped_img');		
		var My_imageName = '';
		if($('#inputImagee').val() == ''){
			My_imageName = '<?php echo substr( $modelArr['imgName'] , strpos($modelArr['imgName'], "-") + 1);	?>';
		}else{
			My_imageName = $('#inputImagee').val();
		}
		
		
		var dataURL = $image.cropper("getDataURL", "image/jpeg");
		params = {
			My_image : dataURL,
			My_imageName : My_imageName, // $('#inputImagee').val(),
			// My_imageWidth :	$('#Dimensions').val().split('x')[0],
			// My_imageHeight : $('#Dimensions').val().split('x')[1]
		};

		//  buAccounts
		$.post( "/index.php/Common/MyImgsCrop?mName="+'<?=$modelArr['modelName'];?>'+"&mID="+$("#modelID").val() +"&ProImgID=" + '<?=$modelArr['ProImgID'];?>' , params , function( data ) {
			location.reload();
		});
		
		// $( "#BuAccountform" ).submit();
	});
	
	
</script>






