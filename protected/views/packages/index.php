<?php
/* @var $this PackagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Packages',
);

$this->menu=array(
	array('label'=>'Create Packages', 'url'=>array('create')),
	array('label'=>'Manage Packages', 'url'=>array('admin')),
);
?>

<h1>Packages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
