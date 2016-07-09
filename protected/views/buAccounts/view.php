<?php
/* @var $this BuAccountsController */
/* @var $model BuAccounts */

$this->breadcrumbs=array(
	'Bu Accounts'=>array('index'),
	$model->accid,
);

$this->menu=array(
	array('label'=>'List BuAccounts', 'url'=>array('index')),
	array('label'=>'Create BuAccounts', 'url'=>array('create')),
	array('label'=>'Update BuAccounts', 'url'=>array('update', 'id'=>$model->accid)),
	array('label'=>'Delete BuAccounts', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->accid),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BuAccounts', 'url'=>array('admin')),
);
?>

<h1>View BuAccounts #<?php echo $model->accid; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'accid',
		//'pkg_id',
		// array('name' => 'title' , 'type' => 'raw', 'value' => CHtml::link(CHtml::encode($model->pkg->title), Yii::app()->baseUrl.'/index.php/Packages/'.$model->pkg_id),
               // ),

		// 'special_deal_id',
		array('name'=>'special_deal_id' , 'type' => 'raw',
                // 'value' => CHtml::link(CHtml::encode($model->specialDeal->sp_d_title), Yii::app()->baseUrl.'/index.php/SpecialDeals/'.$model->special_deal_id),
			 	'value'=>function($model){							
						$SP_deal = Yii::app()->db->createCommand("SELECT sp_d_title FROM special_deals
						  					   WHERE sp_d_id =".$model->special_deal_id)->queryRow();
						return $SP_deal['sp_d_title'];
					}
			 ),
		
		'fname',
		'lname',
		//'country_id',
		array('name'=>'country_id' , 'value'=>$model->country->name , 'header'=>'name'),
		'gender',
		
		'address',
		'city',
		'mobile',
		'tel',
		'email',
		//'has_group',
		// array('name'=>'has_group' , 'value'=>$model->has_group==0 ? "NO" : ($model->has_group==1 ? "YES" : "Not stated")),
		// 'bu_acc_TypeID',
		array('name' => 'bu_acc_TypeID',
              'type' => 'raw',
              'value' => (!empty($model->buAccType)) ? CHtml::encode($model->buAccType->bu_acc_type_name) : 'N/A',
               ),
		
		
		'start_date',
		//'status',
		array('name'=>'status' , 'value'=>function($model){
					if($model->status==1){$x= "Active" ;}elseif ($model->status==2) {
							$x="disabled";
						} else {$x= "Not stated";}
				return $x;
			}
		),
		'created',
		// 'photo',
		array('name'=>'photo' , 'type'=>'image',		
		'value'=>Yii::app()->request->baseUrl.'/../images/upload/bu_accounts/thumbnails/'.$model->photo , 
			//CHtml::image(Yii::app()->request->baseUrl.'/../images/upload/bu_accounts/thumbnails/'.$model->photo , "photo" ,array('style'=>'float:right;'))
	     						 ),
	),
)); 


?>

<!--<select id='Pchart_type'>
	<option value="">Select a Chart</option>
	<option value="Month">Month</option>
	<option value="Year">Year</option>
</select>-->

<script type="text/javascript">
	// $('#Pchart_type').live('change',function(){
		
		// var order2 = {order:'{"bu_id":"3","id":"","qnt":"1","AppSource":"0","p_id":"12","cust_id":"1"}'}
			// $.post('/index.php/API/AddToOrder',order2,function(data){	
				// alert(data);
			// });
		
	// });
</script>


