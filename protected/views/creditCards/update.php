<?php
/* @var $this CreditCardsController */
/* @var $model CreditCards */

$this->breadcrumbs=array(
	'Credit Cards'=>array('index'),
	$model->cr_card_id=>array('view','id'=>$model->cr_card_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CreditCards', 'url'=>array('index')),
	array('label'=>'Create CreditCards', 'url'=>array('create')),
	array('label'=>'View CreditCards', 'url'=>array('view', 'id'=>$model->cr_card_id)),
	array('label'=>'Manage CreditCards', 'url'=>array('admin')),
);
?>

<h1>Update CreditCards <?php echo $model->cr_card_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>