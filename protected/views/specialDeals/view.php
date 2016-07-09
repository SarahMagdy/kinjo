<?php
/* @var $this SpecialDealsController */
/* @var $model SpecialDeals */

$this->breadcrumbs=array(
	'Special Deals'=>array('index'),
	$model->sp_d_id,
);

$this->menu=array(
	array('label'=>'List SpecialDeals', 'url'=>array('index')),
	array('label'=>'Create SpecialDeals', 'url'=>array('create')),
	array('label'=>'Update SpecialDeals', 'url'=>array('update', 'id'=>$model->sp_d_id)),
	array('label'=>'Delete SpecialDeals', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->sp_d_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SpecialDeals', 'url'=>array('admin')),
);
?>

<h1>View SpecialDeals #<?php echo $model->sp_d_id; ?></h1>

<?php 
	$SQL = "SELECT bcid , bc_duration , bc_type
			FROM billing_cycle
			WHERE bcid = " . $model->sp_d_bill_cycle_id ;
 	$result = Yii::app()->db-> createCommand($SQL) -> queryRow();
	$cycle = "";
	if($result['bc_type'] == 0){
		$cycle = $result['bc_duration'] .' Days';
		
	}elseif($result['bc_type'] == 1){
		$cycle = $result['bc_duration'] .' Months';
	}elseif($result['bc_type'] == 2){
		$cycle = $result['bc_duration'] .' Years';
	}
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'sp_d_id',
		// 'sp_d_bill_cycle_id',
		array('name'=>'sp_d_bill_cycle_id' , 'type' => 'raw',
                'value' => CHtml::link(CHtml::encode($cycle), Yii::app()->baseUrl.'/index.php/BillingCycle/'.$model->sp_d_bill_cycle_id),
			 ),
		
		'sp_d_title',
		'sp_d_amount',
		'sp_d_currency',
		'sp_d_description',
	),
)); ?>
