<?php
/* @var $this RoomCustomersController */
/* @var $model RoomCustomers */
/* @var $form CActiveForm */
?>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customers-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'Cust_id',
		'business_id',
		'checkin_date',
                'checkout_date',
                
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>