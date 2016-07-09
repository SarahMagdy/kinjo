<?php
/* @var $this M_OrdersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'M  Orders',
);

$this->menu=array(
	array('label'=>'Create M_Orders', 'url'=>array('create')),
	array('label'=>'Manage M_Orders', 'url'=>array('admin')),
);
?>

<h1>M  Orders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
