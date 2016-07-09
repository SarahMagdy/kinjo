<?php
/* @var $this CatsubController */
/* @var $model Catsub */

$this->breadcrumbs=array(
	'Catsubs'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Catsub', 'url'=>array('index')),
	array('label'=>'Create Catsub', 'url'=>array('create')),
	array('label'=>'Update Catsub', 'url'=>array('update', 'id'=>$model->csid)),
	array('label'=>'Delete Catsub', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->csid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Catsub', 'url'=>array('admin')),
);
?>

<h1>View Catsub #<?php echo $model->csid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'csid',
		//'parent_id',
		array('name'=>'parent_id',
                'value'=>(!empty($model->parent)) ? $model->parent->title : '--' ,'header'=>'Category' ),
		//'catsub_buid',
		array('name'=>'business_unit',
                'value'=>(!empty($model->catsubBu)) ? $model->catsubBu->title : '--' ,'header'=>'Business Unit' ),
                
		'title',
		'desription',
		//'img_thumb',
		//'img_url',
		'created',
		array('name'=>'img_thumb' , 'type'=>'image',		
		'value'=>Yii::app()->request->baseUrl.'/../images/upload/catsub/thumbnails/'.$model->img_thumb)
	),
)); ?>
