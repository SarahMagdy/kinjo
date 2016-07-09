<?php
/* @var $this PdConfigController */
/* @var $model PdConfig */

$this->breadcrumbs=array(
	'Pd Configs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PdConfig', 'url'=>array('index')),
	array('label'=>'Create PdConfig', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#pd-config-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Pd Configs</h1>

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
	'id'=>'pd-config-grid',
	'afterAjaxUpdate' => 'function() { AuthLinks(); ReloadJs();}',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'cfg_id',
		//'parent_id',
		'name',
		array('name'=>'parent_id' , 'header'=>'Configuration',
			  'type'=>'raw' , 'value' => '(!empty($data->parent)) ? CHtml::encode($data->parent->name):"--"'),
		
		array('name'=>'conf_chkrad' ,'value' => '(empty($data->parent)) ? $data->conf_chkrad == 0 ?"Checkable":"Radio":"--"'),
		
		'value',
		// 'conf_buid',
		// array('name'=>'conf_buid' , 'header'=>'Business Unit',
			  // 'type'=>'raw' , 'value' => '(!empty($data->confBu)) ? CHtml::encode($data->confBu->title):"--"'),
		
		
		array(
			'class'=>'CButtonColumn',
		),
		array('header'=>'Apply To Categories','type'=>'raw' , 'value' => 'CHtml::link("Apply", "/index.php/PdConfig/ApplyToCat/$data->cfg_id")'),
			  
		array('value' => '','header'=>'Languages','type' => 'raw','htmlOptions'=>array('class'=>'LangADD'),
							  'cssClassExpression' => '$data->cfg_id',),
	),
)); ?>
<script>
	$(document).ready(function(){
		ReloadJs();
		AuthLinks();
	});	
	function ReloadJs(){
		var contname = "'pdConfig'";
		$.post('/index.php/Common/GetLang',function(data){
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
				$('.items tbody tr').each(function() {
					var RowID = $(this).children('td:first').text();
					for (var key in end_data){
						if(RowID > 0){
							$('.LangADD.'+RowID).append('<a href="#" onclick="OpenDialogFrm('+contname+','+end_data[key]['LangID']+','+RowID+')" class ="LangOpen" contname="pdConfig" langid="'+end_data[key]['LangID']+'" style="margin:2px;"><img src="/assets/flags/'+end_data[key]['LangC']+'.png"  width="15" height="15"/></a>');	
						}	
					}
				});
			}
		});
	}
</script>



