<?php
/* @var $this BusinessUnitController */
/* @var $model BusinessUnit */

$this->breadcrumbs=array(
	'Business Units'=>array('index'),
	'Manage',
);

$MenuArr = array(array('label'=>'List BusinessUnit', 'url'=>array('index')));

if(Yii::app()->session['User']['UserType']== 'admin'){
	
	array_push($MenuArr,array('label'=>'Create BusinessUnit', 'url'=>array('create')));
}
$this->menu = $MenuArr;

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#business-unit-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Business Units</h1>

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

	$GridArr = array('buid',
					 'title',
					// 'long',
					// 'lat',
					 'currency_code',
					  array('name'=>'active' , 'value'=> '$data->active == 0 ? "Active" : "Not Active"'),
                    );
							
	$BtnArr = array(
	    	'class' => 'CButtonColumn',
            'template'=>'{view} {update}',
         );
	
	if(Yii::app()->session['User']['UserType']== 'admin'){
			
		array_push($GridArr, array('name'=>'accid',
		        'value' => 'CHtml::link(CHtml::encode(!isset($data->acc)?"":$data->acc->fname." ".$data->acc->lname), "/index.php/buAccounts/$data->accid")',
		'type'=>'raw'));	
		
		$BtnArr = array(
	    	'class' => 'CButtonColumn',
            'template'=>'{view} {update} {delete}',
         );
		
		
	}
	
	array_push($GridArr,array( 'name'=>'rating' ,'type' => 'raw',
							   'value'=>'$this->grid->controller->widget("CStarRating",
					            array("starCount"=>"5",
					            "minRating"=>"1",
					            "maxRating"=>"5",
					            "name"=>"rating".$data->buid,
								"value"=>$data->rating,
								"readOnly"=>true,),true)',  ));
	
	//array_push($GridArr,array('value' => '','header'=>'','type' => 'raw','htmlOptions'=>array('class'=>'contact_us'),
	//						  'cssClassExpression' => '$data->buid',));
							  
	array_push($GridArr,$BtnArr);
	
	if(Yii::app()->session['User']['UserType']!= 'admin'){
		
		array_push($GridArr,array('value' => '','header'=>'Languages','type' => 'raw','htmlOptions'=>array('class'=>'LangADD'),
							  'cssClassExpression' => '$data->buid',));
							  
	}
	
	
	/*
	array_push($GridArr, array('value' => 'CHtml::link("عربى")','type'=>'raw','header'=>'Languages',
								   'cssClassExpression' => '$data->buid',
								   'htmlOptions'=>array('class'=>'LangOpen','ContName'=>'businessUnit','LangID'=>'1')));
		*/
	
							   
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'business-unit-grid',
		'afterAjaxUpdate' => 'function() { AuthLinks(); ReloadJs();}',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'columns'=>$GridArr,
		)
	); 
?>


<script>

	$(document).ready(function(){
		ReloadJs();
		AuthLinks();
	});
	
function ReloadJs(){
		var contname = "'businessUnit'";
		$.post('/index.php/businessUnit/GetStoresLangs',function(data){
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
				for (var key in end_data){
					for (var skey in end_data[key]){
						if($('.LangADD.'+key).length){
							$('.LangADD.'+key).append('<a href="#" onclick="OpenDialogFrm('+contname+','+end_data[key][skey]['LangID']+','+key+')" class ="LangOpen" contname="businessUnit" langid="'+end_data[key][skey]['LangID']+'" style="margin:2px;"><img src="/assets/flags/'+end_data[key][skey]['LangC']+'.png"  width="15" height="15"/></a>');
							
						}	
					}
				}
			}
		});
		$('.items tbody tr').each(function() {
			var RowID = $(this).children('td:first').text();
			$(this).find("td.button-column").append('<a class="contactus" title="Advanced Setting" href="/index.php/BusinessUnit/ContactUS/'+RowID+'"><img src="/assets/8626beb4/gridview/contact.png" width = "25" height="25" alt="Contact Us"></a>');
			jQuery('#rating'+RowID+' > input').rating({'readOnly':true});
		});	
	}
</script>
	
	<!--
	'columns'=>array(
			'buid',
			 array('name'=>'accid',
					'value' => 'CHtml::link(CHtml::encode($data->acc->fname." ".$data->acc->lname), "/index.php/buAccounts/$data->accid")',
			'type'=>'raw'),
			'title',
			//'long',
			//'lat',
			 // array('name' => 'membership','value'=>function($data){
				 // if($data->membership == 3){$membership = 'membership_3';}
				// elseif ($data->membership == 2){$membership = 'membership_2';}
				// else{$membership = 'membership_1';}
				 // return $membership;
			 // },),
			 // array('name' => 'type','value'=>'$data->type == 3 ? "type_3" : ($data->type == 2 ? "type_2" : "type_1")'),
			/*
			'logo',
			'urlid',
			'description',
			'type',
			'site',
			'statid',
			'apiKey',
			'rating',
			'created',
			*/
			array(
				'class'=>'CButtonColumn',
			),
		),-->
	