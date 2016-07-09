<?php
/* @var $this PdConfigController */
/* @var $model PdConfig */

$this->breadcrumbs=array(
	'Pd Configs'=>array('index'),
	$model->name=>array('view','id'=>$model->cfg_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PdConfig', 'url'=>array('index')),
	array('label'=>'Create PdConfig', 'url'=>array('create')),
	array('label'=>'View PdConfig', 'url'=>array('view', 'id'=>$model->cfg_id)),
	array('label'=>'Manage PdConfig', 'url'=>array('admin')),
);
?>

<h1>Update PdConfig <?php echo $model->cfg_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>