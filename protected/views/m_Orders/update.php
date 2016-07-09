<?php
/* @var $this M_OrdersController */
/* @var $model M_Orders */

$this->breadcrumbs=array(
	'M  Orders'=>array('index'),
	$model->ord_id=>array('view','id'=>$model->ord_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List M_Orders', 'url'=>array('index')),
	array('label'=>'Create M_Orders', 'url'=>array('create')),
	array('label'=>'View M_Orders', 'url'=>array('view', 'id'=>$model->ord_id)),
	array('label'=>'Manage M_Orders', 'url'=>array('admin')),
);
?>

<h1>Update M_Orders <?php echo $model->ord_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>