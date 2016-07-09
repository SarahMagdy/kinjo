<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */

$this->breadcrumbs=array(
	'Orders Details'=>array('index'),
	$model->ord_det_id=>array('view','id'=>$model->ord_det_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OrdersDetails', 'url'=>array('index')),
	array('label'=>'Create OrdersDetails', 'url'=>array('create')),
	array('label'=>'View OrdersDetails', 'url'=>array('view', 'id'=>$model->ord_det_id)),
	array('label'=>'Manage OrdersDetails', 'url'=>array('admin')),
);
?>

<h1>Update OrdersDetails <?php echo $model->ord_det_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>