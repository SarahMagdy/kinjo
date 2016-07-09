<?php
/* @var $this PackagesController */
/* @var $model Packages */

$this->breadcrumbs=array(
	'Packages'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Packages', 'url'=>array('index')),
	array('label'=>'Create Packages', 'url'=>array('create')),
	array('label'=>'Update Packages', 'url'=>array('update', 'id'=>$model->pkgid)),
	array('label'=>'Delete Packages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->pkgid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Packages', 'url'=>array('admin')),
);
?>

<h1>View Packages #<?php echo $model->pkgid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'pkgid',
		'title',
		'amount',
		'currency',
		'description',
	),
)); ?>
