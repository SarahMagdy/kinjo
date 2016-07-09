<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */
/* @var $form CActiveForm */
?>

<div class="wide form">

	
	<div class="row">
		<label>Order Detail ID</label>
		<input type="text" id="ord_det_id"/>
	</div>

	<div class="row">
		<label>Order ID</label>
		<input type="text" id="ord_id"/>
	</div>

	<div class="row">
		<label>Product Name</label>
		<?php $Products = Products::model()->findAll(array('condition'=>'buid = '.Yii::app()->session['User']['UserBuid'])); ?>
		<select id="pid">
			<option value="">Select a Product</option>
			<?php foreach($Products AS $key=>$row):?>
				<option value="<?=$row['pid']?>"><?=$row['title']?></option>
			<?php endforeach;?>
		</select>
	</div>
	
	<div class="row">
		<label>From</label>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'name'=>'from',
			    'id'=>'from',
			    'options' => array(
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        'yearRange' => '2000:2099',     // range of year
			        'minDate' => '2000-01-01',      // minimum date
			        'maxDate' => '2099-12-31',      // maximum date
			    ),
			));
		?>
	</div>

	<div class="row">
		<label>To</label>
		<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
			    'name'=>'to',
			    'id'=>'to',
			    'options' => array(
			        'dateFormat' => 'yy-mm-dd',     // format of "2012-12-25"
			        'yearRange' => '2000:2099',     // range of year
			        'minDate' => '2000-01-01',      // minimum date
			        'maxDate' => '2099-12-31',      // maximum date
			    ),
			));
		?>
	</div>
	<button id="BtnSearch">Search</button>
	



</div><!-- search-form -->
