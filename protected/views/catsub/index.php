<?php
/* @var $this CatsubController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Catsubs',
);

$this->menu=array(
	array('label'=>'Create Catsub', 'url'=>array('create')),
	array('label'=>'Manage Catsub', 'url'=>array('admin')),
);
?>

<h1>Catsubs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
