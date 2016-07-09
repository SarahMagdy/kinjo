<?php
/* @var $this BuAccountsController */
/* @var $model BuAccounts */

$this->breadcrumbs=array(
	'Bu Accounts'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List BuAccounts', 'url'=>array('index')),
	array('label'=>'Create BuAccounts', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#bu-accounts-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Bu Accounts</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->


<?php 
	
	//$status_val='';
	//if($model->status == 1){$status_val='active';}
	
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bu-accounts-grid',
	'afterAjaxUpdate' => 'function() { AuthLinks(); }',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(//'value'=>$model->accid ,
		array('name'=>'accid' , 'header'=>'Account ID' ,
			  'type'=>'raw' , 'value' => '$data->accid'),
		
		//'lname',
		
		array('name'=>'fname', 'header'=>'Full Name' , 'type'=>'raw' ,
				'value' => 'CHtml::encode($data->fname." ".$data->lname)'),
	
		// array('name'=>'pkg_id' ,'header'=>'Package Name',
			  // 'type'=>'raw' , 'value' => 'CHtml::link(CHtml::encode($data->pkg->title), "/index.php/Packages/$data->pkg_id")'),
			
		// 'special_deal_id',
		array('name'=>'special_deal_id' ,  'header'=>'Special Deal' , 
              'value'=>array($this,'getSpecialDealTitel') ),
		
		//'bill_cycle_id',
		// array('name'=>'bill_cycle_id',
                // 'value'=>'$data->billCycle->duration','header'=>'Bill Cycle Duration'),
		//'country_id',
		'mobile',
		'email',
		//'status',
		array('name'=>'status' , 'value'=> '$data->status==1 ? "Active" : ($data->status==2 ? "disabled" : "--")'),
		// array('name'=>'has_group', 'value'=>'$data->has_group==1 ? "YES" : ($data->has_group==0 ? "NO" : " ")'),
		/*
		'gender',
		'photo',
		'address',
		'city',
		'tel',
		// 'has_group',
		 'bu_acc_TypeID',
		'start_date',
		'status',
		'created',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); 




	/*$this->widget('zii.widgets.grid.CGridView', array(
		        'id'=>'before-tasks-dsm-grid',
		        'dataProvider'=>$model2->search(),
		        'filter'=>$model2,
		        'columns'=>array('pkgid','title',
		                
		                array(
		                        'class'=>'CButtonColumn',
		                ),
		        ),
		));*/


?>
<script>
	$(document).ready(function(){
		AuthLinks();
	});
</script>


