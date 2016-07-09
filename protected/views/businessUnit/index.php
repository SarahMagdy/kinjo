<?php
/* @var $this BusinessUnitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Business Units',
);

$MenuArr = array(array('label'=>'Manage BusinessUnit', 'url'=>array('admin')));
if(Yii::app()->session['User']['UserType']== 'admin'){
	
	array_push($MenuArr,array('label'=>'Create BusinessUnit', 'url'=>array('create')));
}
$this->menu = $MenuArr;
?>

<h1>Business Units</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
