<?php
/* @var $this CatsubController */
/* @var $model Catsub */

$this->breadcrumbs=array(
	'Catsubs'=>array('index'),
	$model->title=>array('view','id'=>$model->csid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Catsub', 'url'=>array('index')),
	array('label'=>'Create Catsub', 'url'=>array('create')),
	array('label'=>'View Catsub', 'url'=>array('view', 'id'=>$model->csid)),
	array('label'=>'Manage Catsub', 'url'=>array('admin')),
);
?>

<h1>Update Catsub <?php echo $model->csid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'type'=>$type,'dimensions'=>$dimensions,)); ?>