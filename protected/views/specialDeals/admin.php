<?php
/* @var $this SpecialDealsController */
/* @var $model SpecialDeals */

$this->breadcrumbs=array(
	'Special Deals'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SpecialDeals', 'url'=>array('index')),
	array('label'=>'Create SpecialDeals', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#special-deals-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Special Deals</h1>

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
	'id'=>'special-deals-grid',
	'afterAjaxUpdate' => 'function() { AuthLinks();}',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'sp_d_id',
		// 'sp_d_bill_cycle_id',
		array('name'=>'sp_d_bill_cycle_id' ,'header'=>'Billing Cycle',
			  'type'=>'raw' , 
			  // 'value' => 'CHtml::link(CHtml::encode($data->spDBillCycle->bc_year), "/index.php/BillingCycle/$data->sp_d_bill_cycle_id")'
			  'value' => array($this,'getSpecialDeal')  ),
		'sp_d_title',
		'sp_d_amount',
		'sp_d_currency',
		'sp_d_description',
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