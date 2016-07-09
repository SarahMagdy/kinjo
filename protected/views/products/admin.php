<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Products', 'url'=>array('index')),
	array('label'=>'Create Products', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#products-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Products</h1>

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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'products-grid',
	'afterAjaxUpdate' => 'function() { AuthLinks(); ReloadJs();}',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'pid',
		//'buid',
		//array('name'=>'buid' , 'header'=>'Business',
		//	  'type'=>'raw' , 'value' => '(!empty($data->bu)) ? CHtml::link(CHtml::encode($data->bu->title), "/index.php/businessUnit/$data->buid") :"N/A"'),
		//'csid',
		array('name'=>'csid' , 'header'=>'Category',
			  'type'=>'raw' , 'value' => '(!empty($data->cs)) ? CHtml::link(CHtml::encode($data->cs->title), "/index.php/catsub/$data->csid"): "N/A"'),
			  
		
		//'sku',
		'title',
		//'discription',
		'barcode',
      	 array('name'=>'rating' ,'type' => 'raw',
			 'value'=>'$this->grid->controller->widget("CStarRating",
	            array("starCount"=>"5",
	            "minRating"=>"1",
	            "maxRating"=>"5",
	            "name"=>"rating".$data->pid,
				"value"=>$data->rating,
				"readOnly"=>true,),true)',  ),
		//'bookable',		
		//array('name'=>'bookable', 'value'=>'$data->bookable==0 ? "Not Bookable" : ($data->bookable==1 ? "Bookable" : " ")'),
		//'created',
		
		
		/*
		'price',
		'instock',
		'discount',
		'qrcode',
		'nfc',
		'hash',
		
		*/
		array(
			'class'=>'CButtonColumn',
		),
		array('value' => '','header'=>'Languages','type' => 'raw','htmlOptions'=>array('class'=>'LangADD'),
							  'cssClassExpression' => '$data->pid',),
		
	    // array(
	    	// 'class' => 'CButtonColumn',
            // 'viewButtonImageUrl' => Yii::app()->baseUrl . '/assets/8626beb4/gridview/' . 'view.png',
            // 'updateButtonImageUrl' => Yii::app()->baseUrl . '/assets/8626beb4/gridview/' . 'update.png',
            // 'deleteButtonImageUrl' => Yii::app()->baseUrl . '/assets/8626beb4/gridview/' . 'delete.png',
            // 'advancedButtonImageUrl' => Yii::app()->baseUrl . '/assets/8626beb4/gridview/' . 'delete.png',
         // ),
	     
	),
)); ?>

<script>

	$(document).ready(function(){
		ReloadJs();
		AuthLinks();
	});	
	function ReloadJs(){
		var contname = "'products'";
		$.post('/index.php/Common/GetLang',function(data){
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
				$('.items tbody tr').each(function() {
					var RowID = $(this).children('td:first').text();
					for (var key in end_data){
						if(RowID > 0){
							$('.LangADD.'+RowID).append('<a href="#" onclick="OpenDialogFrm('+contname+','+end_data[key]['LangID']+','+RowID+')" class ="LangOpen" contname="products" langid="'+end_data[key]['LangID']+'" style="margin:2px;"><img src="/assets/flags/'+end_data[key]['LangC']+'.png"  width="15" height="15"/></a>');	
						}
					}
					$(this).find("td.button-column").append('<a class="advanced" title="Advanced Setting" href="/index.php/products/advanced/'+RowID+'"><img src="/assets/8626beb4/gridview/advanced.png" alt="Advanced Setting"></a>');
					$(this).find("td.button-column").append('<a class="color" title="Colors" href="/index.php/products/Color/'+RowID+'"><img src="/assets/8626beb4/gridview/color_picker_icon.png" alt="colors" width="15" height="15"></a>');	
					jQuery('#rating'+RowID+' > input').rating({'readOnly':true});
					
				});
			}else{
				$('.items tbody tr').each(function() {
					var RowID = $(this).children('td:first').text();
					$(this).find("td.button-column").append('<a class="advanced" title="Advanced Setting" href="/index.php/products/advanced/'+RowID+'"><img src="/assets/8626beb4/gridview/advanced.png" alt="Advanced Setting"></a>');
					$(this).find("td.button-column").append('<a class="color" title="Colors" href="/index.php/products/Color/'+RowID+'"><img src="/assets/8626beb4/gridview/color_picker_icon.png" alt="colors" width="15" height="15"></a>');	
					jQuery('#rating'+RowID+' > input').rating({'readOnly':true});
				});
				
			}
		});
	}
	
</script>




