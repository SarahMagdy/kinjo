<?php
/* @var $this BuAccountsController */
/* @var $model BuAccounts */

$this->breadcrumbs=array(
	'Bu Accounts'=>array('index'),
	$model->accid=>array('view','id'=>$model->accid),
	'Update',
);

$this->menu=array(
	array('label'=>'List BuAccounts', 'url'=>array('index')),
	array('label'=>'Create BuAccounts', 'url'=>array('create')),
	array('label'=>'View BuAccounts', 'url'=>array('view', 'id'=>$model->accid)),
	array('label'=>'Manage BuAccounts', 'url'=>array('admin')),
);
?>

<h1>Update BuAccounts <?php echo $model->accid; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model  ,  'dimensions'=>$dimensions)); ?>
