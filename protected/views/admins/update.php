<?php
/* @var $this AdminsController */
/* @var $model Admins */

$this->breadcrumbs=array(
	'Admins'=>array('index'),
	$model->adid=>array('view','id'=>$model->adid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Admins', 'url'=>array('index')),
	array('label'=>'Create Admins', 'url'=>array('create')),
	array('label'=>'View Admins', 'url'=>array('view', 'id'=>$model->adid)),
	array('label'=>'Manage Admins', 'url'=>array('admin')),
);
?>

<h1>Update Admins <?php echo $model->adid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'dimensions'=>$dimensions)); ?>