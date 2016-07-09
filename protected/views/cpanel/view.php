<?php
/* @var $this CpanelController */
/* @var $model Cpanel */

$this->breadcrumbs=array(
	'Cpanels'=>array('index'),
	$model->cp_id,
);

if(Yii::app()->session['User']['UserType']=='owner' || Yii::app()->session['User']['UserType']=='admin'){
	$this->menu=array(
		array('label'=>'List Cpanel', 'url'=>array('index')),
		array('label'=>'Create Cpanel', 'url'=>array('create')),
		array('label'=>'Update Cpanel', 'url'=>array('update', 'id'=>$model->cp_id)),
		array('label'=>'Delete Cpanel', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cp_id),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Manage Cpanel', 'url'=>array('admin')),
		
		array('label'=>'Change Password', 'url'=>array('Changepassword', 'id'=>$model->cp_id)), // ChangePass
		array('label'=>'Reset Password', 'url'=>array('Forgetpassword', 'id'=>$model->cp_id))
	);
}
?>

<h1>View Cpanel #<?php echo $model->cp_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'cp_id',
		// 'buid',
		'username',
		// 'password',
		
		'email',
		'fname',
		'lname',
	     array('name' => 'role_id', 'value' => $model->role->role_name),   
		'created',
		// 'photo',
		array('name'=>'photo' , 'type'=>'image',		
		'value'=>Yii::app()->request->baseUrl.'/../images/upload/cpanel/thumbnails/'.$model->photo ),
		
	),
)); ?>
