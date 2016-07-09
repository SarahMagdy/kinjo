<?php
/* @var $this CatsubController */
/* @var $model Catsub */

$this->breadcrumbs=array(
	'Catsubs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Catsub', 'url'=>array('index')),
	array('label'=>'Create Catsub', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#catsub-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Catsubs</h1>

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

<?php 

$GridArr = array(
		'csid',
		//'parent_id',
		array('name'=>'parent_id' , 'header'=>'Category',
			  'type'=>'raw' , 'value' => '(!empty($data->parent)) ? CHtml::encode($data->parent->title):"--"'),
		//'catsub_buid',
		array('name'=>'catsub_buid' , 'header'=>'Business Unit',
			  'type'=>'raw' , 'value' => '(!empty($data->catsubBu)) ? CHtml::encode($data->catsubBu->title):"--"'),
		'title',
		'desription',
		// 'img_thumb',
		// 'img_url',
		/*
		'created',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	);
	if(Yii::app()->session['User']['UserType']!= 'admin'){
		array_push($GridArr , array('value' => '','header'=>'Languages','type' => 'raw','htmlOptions'=>array('class'=>'LangADD'),
								  'cssClassExpression' => '$data->csid',));
	}
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'catsub-grid',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'function() { AuthLinks(); ReloadJs();}',
	'filter'=>$model,
	'columns'=>$GridArr,
)); ?>
<script>
	$(document).ready(function(){
		ReloadJs();
		AuthLinks();
	});
	
	function ReloadJs(){
		var contname = "'catsub'";
		$.post('/index.php/Common/GetLang',function(data){
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
				$('.items tbody tr').each(function() {
					var RowID = $(this).children('td:first').text();
					for (var key in end_data){
						if(RowID > 0){
							$('.LangADD.'+RowID).append('<a href="#" onclick="OpenDialogFrm('+contname+','+end_data[key]['LangID']+','+RowID+')" class ="LangOpen" contname="catsub" langid="'+end_data[key]['LangID']+'" style="margin:2px;"><img src="/assets/flags/'+end_data[key]['LangC']+'.png"  width="15" height="15"/></a>');	
						}	
					}
				});
			}
		});
	}	

</script>