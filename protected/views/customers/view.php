<?php
/* @var $this CustomersController */
/* @var $model Customers */

$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->cid,
);

$this->menu=array(
	array('label'=>'List Customers', 'url'=>array('index')),
	array('label'=>'Create Customers', 'url'=>array('create')),
	array('label'=>'Update Customers', 'url'=>array('update', 'id'=>$model->cid)),
	array('label'=>'Delete Customers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Customers', 'url'=>array('admin')),
);
?>

<h1>View Customers #<?php echo $model->cid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'cid',
		'fname',
		'lname',
		'email',
		// 'password',
		// 'gender',
		array('name'=>'gender' , 'value'=>$model->gender==0 ? "Male" : "Female"),
		'birthdate',
		// 'country_id',
                 'phone',
		
		array('name' => 'country_id',
              'type' => 'raw',
              'value' => CHtml::encode($model->country->name),
              ),
		
		// 'social_id',
		// 'google_id',
		// 'fav_id',
		// 'status',
		'created',
                
	),
)); ?>
