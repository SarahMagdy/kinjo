<?php
/* @var $this M_OrdersController */
/* @var $model M_Orders */

$this->breadcrumbs=array(
	'M  Orders'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List M_Orders', 'url'=>array('index')),
	array('label'=>'Create M_Orders', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#m--orders-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage M  Orders</h1>

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
	'id'=>'m--orders-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ord_id',
		'cid',
		'status',
		'created',
		'app_type',
		'ord_total',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
