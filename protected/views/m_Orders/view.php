<?php
/* @var $this M_OrdersController */
/* @var $model M_Orders */

$this->breadcrumbs=array(
	'M  Orders'=>array('index'),
	$model->ord_id,
);

$this->menu=array(
	array('label'=>'List M_Orders', 'url'=>array('index')),
	array('label'=>'Create M_Orders', 'url'=>array('create')),
	array('label'=>'Update M_Orders', 'url'=>array('update', 'id'=>$model->ord_id)),
	array('label'=>'Delete M_Orders', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ord_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage M_Orders', 'url'=>array('admin')),
);
?>

<h1>View M_Orders #<?php echo $model->ord_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ord_id',
		'cid',
		'status',
		'created',
		'app_type',
		'ord_total',
	),
)); ?>
<!--<img src="https://chart.googleapis.com/chart?chs=250x100&amp;chd=t:60,40&amp;cht=p3&amp;chl=Hello|World" />-->

<!--<form action='https://chart.googleapis.com/chart' method='POST'>
  <input type="hidden" name="cht" value="lc"  />
  <input type="hidden" name="chtt" value="This is | my chart"  />
  <input type='hidden' name='chs' value='600x200' />
  <input type="hidden" name="chxt" value="x,y" />
  <input type='hidden' name='chd' value='t:40,20,50,20,100'/>
  <input type="submit"  />
</form>-->







