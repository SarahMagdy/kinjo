<?php
/* @var $this BillsController */
/* @var $model Bills */

$this->breadcrumbs=array(
	'Bills'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Bills', 'url'=>array('index')),
	array('label'=>'Create Bills', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#bills-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Bills</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bills-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'bill_id',
		'bill_owner_id',
		'bill_due_date',
		'bill_pay_date',
		'bill_amount',
		'bill_disc',
		/*
		'bill_currency_id',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
