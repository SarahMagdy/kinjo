<?php 
	//print_r(Yii::app()->session['User']);
/*
	$RoleID = isset(Yii::app()->session['User']['UserRoleID'])?Yii::app()->session['User']['UserRoleID']:'';
	if($RoleID > 2){
		$MenuArr = array();
		
		array_push($MenuArr,array('label'=>'Products Categories', 'url'=>array('catsub/admin')));
		array_push($MenuArr,array('label'=>'Products Configuration', 'url'=>array('pdConfig/admin')));
		array_push($MenuArr,array('label'=>'Products', 'url'=>array('products/admin')));
		array_push($MenuArr,array('label'=>'Offers', 'url'=>array('offers/admin')));
	
		if($RoleID == 3){
			array_push($MenuArr,array('label'=>'Orders', 'url'=>array('OrdersDetails/customGrid')));
		}
		
		$this->menu = $MenuArr;
	}*/

?>
<div>
	<?php if(isset($BU['logo'])):?>
		<img src="<?= Yii::app()->request->baseUrl."/images/upload/business_unit/". $BU['logo'];?>">
	<?php endif;?>
</div>
<!--$('div#logo').css('background-image','url(<?= Yii::app()->request->baseUrl."/images/upload/business_unit/". $BU['logo'];?>)');-->
<script>
	
</script>