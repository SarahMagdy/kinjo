<?php
/* @var $this MessagesController */
/* @var $model Messages */

$this->breadcrumbs=array(
	'Messages'=>array('index'),
	$model->mid=>array('view','id'=>$model->mid),
	'Update',
);

$this->menu=array(
	array('label'=>'List Messages', 'url'=>array('index')),
	array('label'=>'Create Messages', 'url'=>array('create')),
	array('label'=>'View Messages', 'url'=>array('view', 'id'=>$model->mid)),
	array('label'=>'Manage Messages', 'url'=>array('admin')),
);
?>

<h1>Update Messages <?php echo $model->mid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>