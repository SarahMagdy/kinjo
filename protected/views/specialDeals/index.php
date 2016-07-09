<?php
/* @var $this SpecialDealsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Special Deals',
);

$this->menu=array(
	array('label'=>'Create SpecialDeals', 'url'=>array('create')),
	array('label'=>'Manage SpecialDeals', 'url'=>array('admin')),
);
?>

<h1>Special Deals</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
