<?php
/* @var $this OffersController */
/* @var $model Offers */

$this->breadcrumbs=array(
	'Offers'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Offers', 'url'=>array('index')),
	array('label'=>'Create Offers', 'url'=>array('create')),
	array('label'=>'Update Offers', 'url'=>array('update', 'id'=>$model->ofid)),
	array('label'=>'Delete Offers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ofid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Offers', 'url'=>array('admin')),
);
?>

<h1>View Offers #<?php echo $model->ofid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'ofid',
		//'pid',
		  array(
	            'name' => 'pro_name',
	            'type' => 'raw',
	            'value' => CHtml::link(CHtml::encode($model->p->title), Yii::app()->baseUrl.'/index.php/Products/'.$model->pid),
	        ),
		'title',
		'text',
		'discount',
		'active',
		array('name'=>'active' , 'value'=>function($model){
					if($model->active == 1){$act = "Active" ;}else {$act = "Not Active";}
				return $act;
			}
		),
		'from',
		'to',
		'scheduled',
		'created',
	),
)); ?>
<script>
	$(document).ready(function(){
		OpenNotify();
	});
	
	function OpenNotify(){	
		$('.table-striped tbody').append('<tr class="even"><th></th><td><a href="/index.php/offers/OpenNotify/<?php echo $model->ofid?>?type=0">Send Notification</a></td></tr>');
	}
</script>
