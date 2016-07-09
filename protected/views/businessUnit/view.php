<?php
/* @var $this BusinessUnitController */
/* @var $model BusinessUnit */

	$this->breadcrumbs=array(
		'Business Units'=>array('index'),
		$model->title,
	);
	
	$MenuArr = array(array('label'=>'List BusinessUnit', 'url'=>array('index')),
					 array('label'=>'Manage BusinessUnit', 'url'=>array('admin')),
					 array('label'=>'Update BusinessUnit', 'url'=>array('update', 'id'=>$model->buid)),
	
	);
	
	if(Yii::app()->session['User']['UserType']== 'admin'){
		
		array_push($MenuArr,array('label'=>'Create BusinessUnit', 'url'=>array('create')),
							array('label'=>'Delete BusinessUnit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->buid),'confirm'=>'Are you sure you want to delete this item?'))
		);
	}
	
	if(Yii::app()->session['User']['UserType']!= 'data_entry'){
			
		$this->menu = $MenuArr;
	}
	// $this->menu=array(
// 		
		// array('label'=>'Create BusinessUnit', 'url'=>array('create')),
		// array('label'=>'Update BusinessUnit', 'url'=>array('update', 'id'=>$model->buid)),
		// array('label'=>'Delete BusinessUnit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->buid),'confirm'=>'Are you sure you want to delete this item?')),
		// array('label'=>'Manage BusinessUnit', 'url'=>array('admin')),
	// );

?>

<h1>View BusinessUnit #<?php echo $model->buid; ?></h1>

<?php 
	
	$GridArr = array('buid',
					 'title',
					 'currency_code',
					 'long',
					 'lat',
					 'description',
					  array('name'=>'active' , 'value'=> $model->active == 0 ? "Active" : "Not Active"),
					 'created',
	 				  array('name' => 'logo','type'=>'Image','value'=>Yii::app()->baseUrl.'/images/upload/business_unit/thumbnails/'.$model->logo),
	 				   array('name' => 'cpanel_logo','type'=>'Image','value'=>Yii::app()->baseUrl.'/images/upload/business_unit/Logos/'.$model->cpanel_logo),
					  array('name' => 'urlid','type'=>'Image','value'=>Yii::app()->baseUrl.'/images/upload/business_unit/icons/'.$model->urlid));

	if($model->pkg_id > 0){
		$Pkg =	Yii::app()->db->createCommand("SELECT title FROM packages WHERE pkgid = ".$model->pkg_id)->queryRow();
		array_push($GridArr,  array(
            'name' => 'pkg_id',
            'type' => 'raw',
            'value' => $Pkg['title'] ,
        ));	
	}
	if(Yii::app()->session['User']['UserType']== 'admin'){
			
		array_push($GridArr,  array(
	            'name' => 'accid',
	            'type' => 'raw',
	            'value' => Yii::app()->session['User']['UserType']=='owner' ? 
	            			CHtml::link(CHtml::encode($model->acc->fname." ".$model->acc->lname), Yii::app()->baseUrl.'/index.php/buAccounts/'.$model->accid) : $model->acc->fname." ".$model->acc->lname,
	        ));	
	}
	
	$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>$GridArr,
)); 

 echo '<div id="DivRat">'; 
 
 $this->widget(
				"CStarRating",array(
				"name"=>"rating".$model->buid,
				"starCount"=>5,
	            "value"=>$model->rating,
	            "minRating"=>1,
	            "maxRating"=>5,
	            "readOnly"=>true)
			);
			
echo '</div>'; 	

?>
<script>
	$('.detail-view tbody').append('<tr class="odd"><th>Rating</th><td>'+ $('#DivRat').html() +'</td></tr>');
	$('#DivRat').html('');
</script>
<!--
'attributes'=>array(
		'buid',
		 array(
	            'name' => 'accid',
	            'type' => 'raw',
	            'value' => Yii::app()->session['User']['UserType']=='owner' ? 
	            			CHtml::link(CHtml::encode($model->acc->fname." ".$model->acc->lname), Yii::app()->baseUrl.'/index.php/buAccounts/'.$model->accid) : $model->acc->fname." ".$model->acc->lname,
	        ),
		'title',
		'long',
		'lat',
		 // array('name' => 'membership','value'=>function($model){
	     	// if($model->membership == 3){$membership = 'membership_3';}
			// elseif ($model->membership == 2){$membership = 'membershipl_2';}
			// else{$membership = 'membership_1';}
			 // return $membership;
	     // },),
		//'urlid',
		'description',
		 // array('name' => 'type','value'=>function($model){
	     	// if($model->type == 3){$type = 'Type_3';}
			// elseif ($model->type == 2){$type = 'Type_2';}
			// else{$type = 'Type_1';}
			 // return $type;
	     // },),
		//'site',
		// 'statid',
		//'apiKey',
		//'rating',
		'created',
		 array('name' => 'logo','type'=>'Image','value'=>Yii::app()->baseUrl.'/images/upload/business_unit/thumbnails/'.$model->logo),
	),-->
