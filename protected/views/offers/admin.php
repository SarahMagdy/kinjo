<?php
/* @var $this OffersController */
/* @var $model Offers */

$this->breadcrumbs=array(
	'Offers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Offers', 'url'=>array('index')),
	array('label'=>'Create Offers', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#offers-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Offers</h1>

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
	'id'=>'offers-grid',
	'afterAjaxUpdate' => 'function() { AuthLinks(); ReloadJs();}',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ofid',
		//'pid',
		array('name'=>'pid' , 'header'=>'Product',
			  'type'=>'raw' , 'value' => '(!empty($data->p)) ? CHtml::link(CHtml::encode($data->p->title), "/index.php/products/$data->pid"): "N/A"'),
		
		'title',
		'text',
		//'discount',
		array('name'=>'discount' , 'header'=>'discount',
			  'type'=>'raw' , 'value' => 'CHtml::encode($data->discount." %")'),
		// 'from',
		array('name'=>'from' , 
			  'type'=>'raw' , 'value' => 'CHtml::encode($data->from)'),
		'to',
		
		// 'active',
		array('name'=>'active', 'value'=>'$data->active==0 ? "Not Active" : ($data->active==1 ? "Active" : " ")'),
		/*
		
		'scheduled',
		'created',
		*/
		array(
			'class'=>'CButtonColumn',
		),
		array('value' => '','header'=>'Languages','type' => 'raw','htmlOptions'=>array('class'=>'LangADD'),
							  'cssClassExpression' => '$data->ofid',),
	),
)); ?>

<script>
	$(document).ready(function(){
		ReloadJs();
		AuthLinks();
	});	
	function ReloadJs(){
		var contname = "'offers'";
		$.post('/index.php/Common/GetLang',function(data){
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
				$('.items tbody tr').each(function() {
					var RowID = $(this).children('td:first').text();
					for (var key in end_data){
						if(RowID > 0){
							$('.LangADD.'+RowID).append('<a href="#" onclick="OpenDialogFrm('+contname+','+end_data[key]['LangID']+','+RowID+')" class ="LangOpen" contname="offers" langid="'+end_data[key]['LangID']+'" style="margin:2px;"><img src="/assets/flags/'+end_data[key]['LangC']+'.png"  width="15" height="15"/></a>');	
						}	
					}
				});
			}
		});
	}
</script>