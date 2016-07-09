<?php
/* @var $this CreditCardsController */
/* @var $model CreditCards */

$this->breadcrumbs=array(
	'Credit Cards'=>array('index'),
	$model->cr_card_id,
);

$this->menu=array(
	array('label'=>'List CreditCards', 'url'=>array('index')),
	array('label'=>'Create CreditCards', 'url'=>array('create')),
	array('label'=>'Update CreditCards', 'url'=>array('update', 'id'=>$model->cr_card_id)),
	array('label'=>'Delete CreditCards', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cr_card_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CreditCards', 'url'=>array('admin')),
);
?>

<h1>View CreditCards #<?php echo $model->cr_card_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'htmlOptions' => array('class' => 'table table-hover table-striped'),
	'attributes'=>array(
		'cr_card_id',
		// 'cr_card_owner_id',
		array('name'=>'cr_card_owner_id' , 'value'=>$model->crCardOwner->fname.'  '.$model->crCardOwner->lname , 'header'=>'name'),
		
		'cr_card_namecard',
		
		// 'cr_card_credit',
		array('name'=>'cr_card_credit' , 'type' => 'raw',
                'value' => $this->getCreditCard($model,0) ),
		
		// 'cr_card_cvv',
		'cr_card_expirationDate',
		// 'cr_card_rank',
		array('name'=>'cr_card_rank' , 'value'=>function($model){
					if($model->cr_card_rank==1){$x= "Primary Card" ;}elseif ($model->cr_card_rank==2) {
							$x="Secondary Card";
					} //else {$x= "Not stated";}
				return $x;
			}
		),
	),
)); ?>
