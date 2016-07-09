<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */

$this->breadcrumbs=array(
	'Orders Details'=>array('index'),
	$model->ord_det_id,
);

$this->menu=array(
	array('label'=>'List OrdersDetails', 'url'=>array('index')),
	array('label'=>'Create OrdersDetails', 'url'=>array('create')),
	array('label'=>'Update OrdersDetails', 'url'=>array('update', 'id'=>$model->ord_det_id)),
	array('label'=>'Delete OrdersDetails', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ord_det_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OrdersDetails', 'url'=>array('admin')),
);
?>

<h1>View OrdersDetails #<?php echo $model->ord_det_id; ?></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ord_det_id',
		'ord_id',
		//'ord_buid',
		 array('name' => 'reserved_bu','value'=>$model->reserved_bu == 1?'Reserved':'Not Reserved'),
		//'reserved_bu',p
		//'pid',
		array(
	            'name' => 'pid',
	            'type' => 'raw',
	            'value' => CHtml::link(CHtml::encode($model->p->title), Yii::app()->baseUrl.'/index.php/Products/'.$model->pid),
	        ),
		'qnt',
		'disc',
		'price',
		'fees',
		'final_price',
		//'convert_price',
		//'dollor_price',
		 array('name' => 'pay_type','value'=>$model->pay_type == 0?'Online':'Onsite'),
		//'pay_type',
		 array('name' => 'cust_billingAddr','value'=>$BillingAddr),
		 array('name' => 'cust_shipAddr','value'=>$ShippingAddr),
		//'cust_billingAddr',
		//'cust_shipAddr',
		 array('name'=>'app_source' , 'value'=>function($model){
					if($model->app_source == 0){$App = "Mobile App" ;}
					if($model->app_source == 1){$App = "Online Site" ;}
					if($model->app_source == 2){$App = "Facebook App" ;}
				return $App;
			}),
		//'app_source',
		'close_date',
		'created',
		 //array('name'=>'Color','value'=> function($data) { return '<div style="background-color:#<?=;width:20px;height:20px;"></div>';}),
		// array('name'=>'Color','value'=>'','cssClass'=>'ColorTd'),
		/*
		 array('name'=>'Conf','type' => 'raw','value'=> function($Conf) {
					 $ConfT = '<table><tbody>';	
						 foreach ($Conf as $key => $row) {
							
							//$ConfT .= '<tr><td>'.$row['ParName'].'</td></tr>';
													   }
					$ConfT .= '</tbody></table>';	
					 return $ConfT;
								   }),*/
		
	),
));?>
<table style="border: 1px solid #ccc;">
	<tbody>
		<tr><td>Color</td><td><div style="background-color:#<?=$Color?> ;width: 70px;height: 20px;"></div></td></tr>
		<tr><td>Conf</td><td>
		<?php
			foreach ($Conf as $key => $row) {
				
				echo '<ul><li>'.$row['ParName'].'</li><li><ul>';
				
					  foreach ($row['Sub'] as $Subkey => $Subrow) {
					  	echo '<li>'.$Subrow['SubName'].'</li>';
					  }
					  
				echo '</ul></li></ul>';
			}
		?>
		</td></tr>
	</tbody>
</table>
<script> 
	//$('.ColorTd td').html('<div style="background-color:#<?=$Color?> ;width: 70px;height: 20px;"></div>');	
</script>

