<?php 	
	$type = isset(Yii::app()->session['User']['UserType'])?Yii::app()->session['User']['UserType']:'';
	if($type == 'owner'){
		$this->breadcrumbs = array(
			'Owner Home',
			'Store '.Yii::app()->session['User']['UserBuid'].' Home',
		);
	}
	if($type == 'data_entry'){
		$this->breadcrumbs = array(
			'Store '.Yii::app()->session['User']['UserBuid'].' Home',
		);
	}
	if(Yii::app()->session['User']['UserType']== 'data_entry'){
	
		$MenuArr = array();
		/*
		array_push($MenuArr,array('label'=>'Store '.Yii::app()->session['User']['UserBuid'].' Data', 'url'=>array('businessUnit/'.Yii::app()->session['User']['UserBuid'])));
				array_push($MenuArr, array('label'=>'Languages Setting', 'url'=>array('setting/OPenSettingLang')));
				array_push($MenuArr, array('label'=>'Messages', 'url'=>array('Messages/admin')));
				array_push($MenuArr, array('label'=>'Send Notifications', 'url'=>array('customers/SendNotify')));
				array_push($MenuArr,array('label'=>'Orders', 'url'=>array('OrdersDetails/customGrid')));*/
		
		array_push($MenuArr,array('label'=>'Products Categories', 'url'=>array('catsub/admin')));
		array_push($MenuArr,array('label'=>'Products Configuration', 'url'=>array('pdConfig/admin')));
		array_push($MenuArr,array('label'=>'Products', 'url'=>array('products/admin')));
		array_push($MenuArr,array('label'=>'Offers', 'url'=>array('offers/admin')));
	
		$this->menu = $MenuArr;
	}
?>

<div>
	<img src="<?= Yii::app()->request->baseUrl."/images/upload/business_unit/". $BU['logo'];?>">
</div>