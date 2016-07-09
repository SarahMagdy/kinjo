<?php
/* @var $this CpanelController */
/* @var $model Cpanel */

$this->breadcrumbs=array(
	'Cpanels'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Cpanel', 'url'=>array('index')),
	array('label'=>'Create Cpanel', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cpanel-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Cpanels</h1>

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
	'id'=>'cpanel-grid',
	'dataProvider'=>$model->search(),
	'afterAjaxUpdate' => 'function() { AuthLinks();}',
	'filter'=>$model,
	'columns'=>array(
		'cp_id',
		// 'buid',
	    array('name'=>'fname' , 'header'=>'Name', 'value' => 'CHtml::encode($data->fname." ".$data->lname)'),
	    'username',
		// 'password',
		//'photo',
		'email',
		/*
		'role_id',
				array('name'=>'role_id' , 'header'=>'Roles',
					  'type'=>'raw' , 'value' => '(!empty($data->role)) ? CHtml::link(CHtml::encode($data->role->role_name), "/index.php/roles/$data->role_id"): "N/A"'),*/
		
		 array('name'=>'role_id' , 'header'=>'Roles', 'value' => 'CHtml::encode($data->role->role_name)'),
		/*
		'fname',
		'lname',
		'created',
		*/
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