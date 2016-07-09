<?php
/* @var $this BusinessUnitController */
/* @var $model BusinessUnit */

$this->breadcrumbs=array(
	'Business Units'=>array('index'),
	$model->title=>array('view','id'=>$model->buid),
	'Update',
);

$MenuArr = array(array('label'=>'List BusinessUnit', 'url'=>array('index')),
				 array('label'=>'View BusinessUnit', 'url'=>array('view', 'id'=>$model->buid)),
				 array('label'=>'Manage BusinessUnit', 'url'=>array('admin')));

if(Yii::app()->session['User']['UserType']== 'admin'){
	
	array_push($MenuArr,array('label'=>'Create BusinessUnit', 'url'=>array('create')));

}
$this->menu = $MenuArr;
?>

<h1>Update BusinessUnit <?php echo $model->buid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'AccData'=>$AccData,'dimensions'=>$dimensions)); ?>