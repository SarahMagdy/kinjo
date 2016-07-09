<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	$model->title=>array('view','id'=>$model->pid),
	'Update',
);

$MenuArr = array(array('label'=>'List Products', 'url'=>array('index')),
				 array('label'=>'Manage Products', 'url'=>array('admin')),
				 array('label'=>'View Products', 'url'=>array('view', 'id'=>$model->pid)),

);

if(Yii::app()->session['User']['UserType']== 'admin'){
	
	array_push($MenuArr,array('label'=>'Create BusinessUnit', 'url'=>array('create')));
}
$this->menu = $MenuArr;
?>

<h1>Update Products <?php echo $model->pid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'CatData'=>$CatData,)); ?>