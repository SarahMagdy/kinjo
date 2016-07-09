<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Products', 'url'=>array('index')),
	array('label'=>'Create Products', 'url'=>array('create')),
	array('label'=>'Update Products', 'url'=>array('update', 'id'=>$model->pid)),
	array('label'=>'Delete Products', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->pid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Products', 'url'=>array('admin')),
);


?>

<h1>View Products #<?php echo $model->pid; ?></h1>
<input type="text" hidden="hidden" id='pro_id' value="<?php echo $model->pid; ?>"/>
<?php $this->widget('zii.widgets.CDetailView', array(
	//$this->widget('application.components.widgets.XDetailView', array(
	//$this->widget('ext.widgets.DetailView4Col', array(
	'data'=>$model ,
	 // 'ItemColumns' => 5,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'pid',
		//'buid',
		// array('name' => 'business_unit_title',
              // 'type' => 'raw',
              // 'value' => (!empty($model->bu)) ? CHtml::link(CHtml::encode($model->bu->title), Yii::app()->baseUrl.'/index.php/businessUnit/'.$model->buid) : 'N/A',
               // ),
		
		//'csid',
		array('name' => 'catsub_title',
              'type' => 'raw',
              'value' => (!empty($model->cs)) ? CHtml::link(CHtml::encode($model->cs->title), Yii::app()->baseUrl.'/index.php/catsub/'.$model->csid) : 'N/A',
               ),
		'sku',
		'title',
		'discription',
		//'price',
		//'instock',
		//'discount',
		//'nfc',
		//'hash',
		//'bookable',
		array('name'=>'bookable' , 'value'=>function($model){
					if($model->bookable==0){$x= "Not Bookable" ;}elseif ($model->bookable==1) {
							$x="Bookable";
						} else {$x= "Not stated";}
				return $x;
			}
		),
		
		// 'qrcode',
		
		array('name'=>'qrcode' , 'type'=>'raw' , 
			  'value'=>CHtml::link(CHtml::encode('View QR-Code') , "http://chart.apis.google.com/chart?chs=250x250&cht=qr&chl=".$model->qrcode , array('target'=>'_blank' , 'alt'=>'Scan Me !')) 
			  // 'value'=>'<a target=_blank href= "#" >
	  			// <img border="0" alt="Scan Me !" src="http://chart.apis.google.com/chart?chs=100x100&cht=qr&chl="'.$model->qrcode.'">
	  			// </a>'
			  ),
		
		// array('name'=>'img' ,'value'=>  CHtml::tag( '<div><div>'.$img_arr.'</div></div>')),
		// 'rating',
	),
)); 

 echo '<div id="DivRat">'; 
 
 $this->widget(
				"CStarRating",array(
				"name"=>"rating".$model->pid,
				"starCount"=>5,
	            "value"=>$model->rating,
	            "minRating"=>1,
	            "maxRating"=>5,
	            "readOnly"=>true)
			);
			
echo '</div>'; 			



//'. CHtml::activeFileField($model,'photo').'
$img_arr = array();
$x = ProductsImgs::model()->findAllByAttributes(array('pid'=>$model->pid));
$count = count ( $x );
	
		echo '<form id="myform" name="myform" action="AjaxUpdateImg" method="POST" enctype="multipart/form-data">'	;
 		echo '<div style="width:100%;"> ';
 		echo '<input hidden type="text" name="proID" value="'.$model->pid.'" id="proID"/>';
 		if(!empty($x)){
 		foreach($x as $image => $pic){
 			
 			
			echo'	<div id="divimg'.$pic->pimgid.'" style="float:left;display: inline-block;margin:2px;">
 				  		<input hidden type="text" name="oldimgName" value="'.$pic->pimg_thumb.'"/>
 				  		<input hidden type="text" name="imgId" value="'.$pic->pimgid.'"/>
 				  		
 				  	    
 				  		<a href="#" id="'.$pic->pimgid.'" picname="'.$pic->pimg_thumb.'" onClick="DeleteImg(this.id);">
 				  	 		<img border="0" alt="W3Schools" src="/assets/8626beb4/gridview/DeleteRed.png" width="10" height="13">
 				  	 	</a>
						</br>
						<a href="/index.php/Common/MyImgsCrop?mName=Products&mID='.$model->pid.'&ProImgID='.$pic->pimgid.'">
							<img src="'.Yii::app()->request->baseUrl.'/../images/upload/products/thumbnails/'.$pic->pimg_thumb.'" alt="" /> 
 						</a>
 					</div>
 				';
			// echo '<input type="file" name="picturess" /> <input type="submit" value="Submit!">';

		}}
		echo '</div>';
		if($count < 5){
			echo '</br></br><div style="float:left;width:100%;">
				  <label>Upload More Images</label></br></br>
				  <input type="file" id="picturess" name="picturess" /> 
				  <input type="submit" value="Upload"></div>';
		}else{
			echo '</br></br><div style="float:left;width:100%;">
					You reached the max number of uploads.
					You can delete images for more uploads!
				  </div>';
		}
		echo '</form>';
		
		
		
		
		
		
	 
?>


<script>

	$('.table-striped tbody').append('<tr class="odd"><th>Rating</th><td>'+ $('#DivRat').html() +'</td></tr>');
	$('.table-striped tbody').append('<tr class="even"><th></th><td><a href="/index.php/products/OpenNotify/<?php echo $model->pid?>?type=0">Send Notification</a></td></tr>');
	$('#DivRat').html('');
	
	function DeleteImg(imgId){
		// var imgName = $("#"+imgId).attr('picname');
		var imgName = document.getElementById(imgId).getAttribute("picname")
		var data ={
			imgId : imgId,
			imgName : imgName,
			proID:$('#proID').val()
		}; 
		
		$.post( "AjaxDeleteImg/",data, function( data ) {
			// $('#divimg'+imgId).hide();
			// $('#divlink'+imgId).hide();
			location.reload();
		});
	}
	
	
	$('#myform').submit(function(eve){
		eve.preventDefault();
		if($('#picturess').val() != ''){
			this.submit();
		}else{
			alert('Please Choose Photo');
			return false;
		}
	});
	
	
	// function UpdateImg(){
		//   var oldimgName = document.getElementById(imgId).getAttribute("picname")
		// var oldimgName ='1419946849-2-cute-animals_1.jpg';
		// var data ={
			// //imgId : imgId,
			// oldimgName : oldimgName,
			// newimgName : $('#Products_img').val(),
			// proID : $('#pro_id').val()
		// };
		
		
		// $.post("AjaxUpdateImg/",data, function( data ) {
			// $('#divimg'+imgId).hide();
			// $('#divlink'+imgId).hide();
			
		// }); 
		
	// }
	
	
	
	
	
</script>



