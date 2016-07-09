<?php
/* @var $this CreditCardsController */
/* @var $model CreditCards */

$this->breadcrumbs=array(
	'Credit Cards'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List CreditCards', 'url'=>array('index')),
	array('label'=>'Create CreditCards', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#credit-cards-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Credit Cards</h1>

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
	'id'=>'credit-cards-grid',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'function() { AuthLinks(); ReloadJs();}',
	'filter'=>$model,
	'columns'=>array(
		'cr_card_id',
		// 'cr_card_owner_id',
		array('name'=>'cr_card_owner_id', 'type'=>'raw' ,
				'value' => 'CHtml::encode($data->crCardOwner->fname." ".$data->crCardOwner->lname)'),
		
		'cr_card_namecard',
		
		// 'cr_card_credit',
		array('name'=>'cr_card_credit' ,'header'=>'Credit Card',
			  'type'=>'raw' ,
			  'value' => array($this,'getCreditCard')  ),
		
		// 'cr_card_cvv',
		'cr_card_expirationDate',
		'cr_card_rank',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>


<script>

	$(document).ready(function(){
		ReloadJs();
		AuthLinks();
	});
	
	function ReloadJs(){
		$('.items tbody tr').each(function() {
			var RowID = $(this).children('td:first').text();
			$(this).find("td.button-column").append('<a class="addvalue" title="Colors" href="/index.php/CreditCards/ajaxAddValue/'+RowID+'"><img src="/assets/8626beb4/gridview/advanced.png" alt="Advanced Setting" width="15" height="15"></a>');	
		});
	}			
	
</script>




