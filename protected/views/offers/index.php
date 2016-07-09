<?php
/* @var $this OffersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Offers',
);

$this->menu=array(
	array('label'=>'Create Offers', 'url'=>array('create')),
	array('label'=>'Manage Offers', 'url'=>array('admin')),
);
?>

<h1>Offers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
