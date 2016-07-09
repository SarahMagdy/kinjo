<?php
/* @var $this BillingCycleController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Billing Cycles',
);

$this->menu=array(
	array('label'=>'Create BillingCycle', 'url'=>array('create')),
	array('label'=>'Manage BillingCycle', 'url'=>array('admin')),
);
?>

<h1>Billing Cycles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
