<?php
/* @var $this CreditCardsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Credit Cards',
);

$this->menu=array(
	array('label'=>'Create CreditCards', 'url'=>array('create')),
	array('label'=>'Manage CreditCards', 'url'=>array('admin')),
);
?>

<h1>Credit Cards</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
