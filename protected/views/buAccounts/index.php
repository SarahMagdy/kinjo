<?php
/* @var $this BuAccountsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Bu Accounts',
);

$this->menu=array(
	array('label'=>'Create BuAccounts', 'url'=>array('create')),
	array('label'=>'Manage BuAccounts', 'url'=>array('admin')),
);
?>

<h1>Bu Accounts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
