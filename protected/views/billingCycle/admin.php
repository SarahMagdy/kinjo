<?php
/* @var $this BillingCycleController */
/* @var $model BillingCycle */

$this->breadcrumbs=array(
	'Billing Cycles'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List BillingCycle', 'url'=>array('index')),
	array('label'=>'Create BillingCycle', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#billing-cycle-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Billing Cycles</h1>

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
	'id'=>'billing-cycle-grid',
	'afterAjaxUpdate' => 'function() { AuthLinks(); }',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'bcid',
		// 'bc_duration',
		array('name'=>'bc_duration', 'value'=>array($this,'getDuration')),
		// 'bc_type',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
<script>
	$(document).ready(function(){
		AuthLinks();
	});
</script>