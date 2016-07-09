<?php
/* @var $this BillsController */
/* @var $model Bills */

$this->breadcrumbs=array(
	'Bills'=>array('index'),
	$model->bill_id,
);

$this->menu=array(
	array('label'=>'List Bills', 'url'=>array('index')),
	array('label'=>'Create Bills', 'url'=>array('create')),
	array('label'=>'Update Bills', 'url'=>array('update', 'id'=>$model->bill_id)),
	array('label'=>'Delete Bills', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->bill_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Bills', 'url'=>array('admin')),
);
?>

<h1>View Bills #<?php echo $model->bill_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'bill_id',
		'bill_owner_id',
		'bill_due_date',
		'bill_pay_date',
		'bill_amount',
		'bill_disc',
		'bill_currency_id',
	),
)); ?>
