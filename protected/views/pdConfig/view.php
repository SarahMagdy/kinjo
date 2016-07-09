<?php
/* @var $this PdConfigController */
/* @var $model PdConfig */

$this->breadcrumbs=array(
	'Pd Configs'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List PdConfig', 'url'=>array('index')),
	array('label'=>'Create PdConfig', 'url'=>array('create')),
	array('label'=>'Update PdConfig', 'url'=>array('update', 'id'=>$model->cfg_id)),
	array('label'=>'Delete PdConfig', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cfg_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PdConfig', 'url'=>array('admin')),
);
?>

<h1>View PdConfig #<?php echo $model->cfg_id; ?></h1>



<?php 

$GridArr = array('cfg_id','name');
if($model->parent != null){
	array_push($GridArr,array('name'=>'parent_id','value'=>(!empty($model->parent)) ? $model->parent->name : '--'  ));
	array_push($GridArr,'value');
}else{
	array_push($GridArr,array('name'=>'conf_chkrad','value'=>($model->conf_chkrad == 0) ? 'Checkable': 'Radio' ));
}

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>$GridArr,
)); ?>
