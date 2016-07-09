<?php
/* @var $this PackagesController */
/* @var $model Packages */

$this->breadcrumbs=array(
	'Packages'=>array('index'),
	$model->title=>array('view','id'=>$model->pkgid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Packages', 'url'=>array('index')),
	array('label'=>'Create Packages', 'url'=>array('create')),
	array('label'=>'View Packages', 'url'=>array('view', 'id'=>$model->pkgid)),
	array('label'=>'Manage Packages', 'url'=>array('admin')),
);
?>

<h1>Update Packages <?php echo $model->pkgid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>