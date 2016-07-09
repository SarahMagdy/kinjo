<?php
/* @var $this CpanelController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Cpanels',
);
if(Yii::app()->session['User']['UserType']=='owner' || Yii::app()->session['User']['UserType']=='admin'){
	$this->menu=array(
		array('label'=>'Create Cpanel', 'url'=>array('create')),
		array('label'=>'Manage Cpanel', 'url'=>array('admin')),
	);
}
?>

<h1>Cpanels</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
