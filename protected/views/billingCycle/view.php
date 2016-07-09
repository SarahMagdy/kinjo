<?php
/* @var $this BillingCycleController */
/* @var $model BillingCycle */

$this->breadcrumbs=array(
	'Billing Cycles'=>array('index'),
	$model->bcid,
);

$this->menu=array(
	array('label'=>'List BillingCycle', 'url'=>array('index')),
	array('label'=>'Create BillingCycle', 'url'=>array('create')),
	array('label'=>'Update BillingCycle', 'url'=>array('update', 'id'=>$model->bcid)),
	array('label'=>'Delete BillingCycle', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->bcid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BillingCycle', 'url'=>array('admin')),
);
?>

<h1>View BillingCycle #<?php echo $model->bcid; ?></h1>

<?php 
	$SQL = "SELECT bcid , bc_duration , bc_type
			FROM billing_cycle
			WHERE bcid = " . $model->bcid ;
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
		'bcid',
		// 'bc_duration',
		array('name'=>'bc_duration' , 'type' => 'raw' , 'value' => CHtml::encode($cycle) ),
		
		// 'bc_type',
	),
)); ?>
