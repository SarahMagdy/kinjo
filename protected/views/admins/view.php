<?php
/* @var $this AdminsController */
/* @var $model Admins */

$this->breadcrumbs=array(
	'Admins'=>array('index'),
	$model->adid,
);

$this->menu=array(
	array('label'=>'List Admins', 'url'=>array('index')),
	array('label'=>'Create Admins', 'url'=>array('create')),
	array('label'=>'Update Admins', 'url'=>array('update', 'id'=>$model->adid)),
	array('label'=>'Delete Admins', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->adid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Admins', 'url'=>array('admin')),
);
?>

<h1>View Admins #<?php echo $model->adid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'adid',
		'fname',
		'lname',
		'username',
		//'password',
		//'photo',
		 array('name' => 'photo','type'=>'Image','value'=>Yii::app()->baseUrl.'/images/upload/admins/thumbnails/'.$model->photo)
		// 'status',
		// 'level',
		// 'email',
		// 'created',
	),
)); ?>
