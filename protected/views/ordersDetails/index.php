<?php
/* @var $this OrdersDetailsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Orders Details',
);

$this->menu=array(
	//array('label'=>'Create OrdersDetails', 'url'=>array('create')),
	array('label'=>'Manage OrdersDetails', 'url'=>array('customGrid')),
);
?>

<h1>Orders Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
