<?php
/* @var $this CpanelController */
/* @var $model Cpanel */

$this->breadcrumbs=array(
	'Cpanels'=>array('index'),
	$model->cp_id=>array('view','id'=>$model->cp_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Cpanel', 'url'=>array('index')),
	array('label'=>'Create Cpanel', 'url'=>array('create')),
	array('label'=>'View Cpanel', 'url'=>array('view', 'id'=>$model->cp_id)),
	array('label'=>'Manage Cpanel', 'url'=>array('admin')),
);
?>

<h1>Update Cpanel <?php echo $model->cp_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'dimensions'=>$dimensions,)); ?>