<?php
/* @var $this SpecialDealsController */
/* @var $model SpecialDeals */

$this->breadcrumbs=array(
	'Special Deals'=>array('index'),
	$model->sp_d_id=>array('view','id'=>$model->sp_d_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SpecialDeals', 'url'=>array('index')),
	array('label'=>'Create SpecialDeals', 'url'=>array('create')),
	array('label'=>'View SpecialDeals', 'url'=>array('view', 'id'=>$model->sp_d_id)),
	array('label'=>'Manage SpecialDeals', 'url'=>array('admin')),
);
?>

<h1>Update SpecialDeals <?php echo $model->sp_d_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>