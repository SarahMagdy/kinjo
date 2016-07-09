<?php
/* @var $this BillsController */
/* @var $model Bills */

$this->breadcrumbs=array(
	'Bills'=>array('index'),
	$model->bill_id=>array('view','id'=>$model->bill_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Bills', 'url'=>array('index')),
	array('label'=>'Create Bills', 'url'=>array('create')),
	array('label'=>'View Bills', 'url'=>array('view', 'id'=>$model->bill_id)),
	array('label'=>'Manage Bills', 'url'=>array('admin')),
);
?>

<h1>Update Bills <?php echo $model->bill_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>