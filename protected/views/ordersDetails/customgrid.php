<?php
/* @var $this OrdersDetailsController */
/* @var $model OrdersDetails */

$this->breadcrumbs=array(
	'Orders Details'=>array('index'),
	'Manage',
);

$this->menu=array(
	//array('label'=>'List OrdersDetails', 'url'=>array('index')),
	//array('label'=>'Create OrdersDetails', 'url'=>array('create')),
	array('label'=>'View Orders', 'url'=>array('CustomView')),
	array('label'=>'Close Orders', 'url'=>array('CloseOrder')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#orders-details-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Orders Details</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('customsearch'); ?>
</div><!-- search-form -->

<div id="GridDiv">
	
	
</div>

<script>

	var CurrentPage = 1;
	
	var d ={
			open:'Grid',
			page:1
		};
		$.post('/index.php/ordersDetails/AjaxCustomGrid',d,function(data){
			
			$('#GridDiv').html(data);
			$("#list_pager").removeAttr("onchange");
			$("#list_pager option").prop("value", "");
			//$("#list_pager option[value = '1']").attr('selected', 'selected');
			$("#list_pager option:contains(1)").attr('selected', true);
			
		});
	
	$('#BtnSearch').click(function(e){
		
		var d = {
			ord_det_id:$('#ord_det_id').val(),
			ord_id:$('#ord_id').val(),
			pid:$('#pid').val(),
			from:$('#from').val(),
			to:$('#to').val(),
			open:'Search',
			page:1
		};
		
		$.post('/index.php/ordersDetails/AjaxCustomGrid',d,function(data){
			
			$('#GridDiv').html(data);
			$("#list_pager").removeAttr("onchange");
			$("#list_pager option").prop("value", "");
			//$("#list_pager option[value = '1']").attr('selected', 'selected');
			$("#list_pager option:contains(1)").attr('selected', true);
		});
	});
	
</script>

