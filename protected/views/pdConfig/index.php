<?php
/* @var $this PdConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pd Configs',
);

$this->menu=array(
	array('label'=>'Create PdConfig', 'url'=>array('create')),
	array('label'=>'Manage PdConfig', 'url'=>array('admin')),
);
?>

<h1>Pd Configs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
