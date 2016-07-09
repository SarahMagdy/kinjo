

<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">-->
<!--<link rel="stylesheet" href="/css/style.css">-->
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<!--<link rel="stylesheet" href="/css/jquery.fileupload.css">-->



<?php
/* @var $this BuAccountsController */
/* @var $model BuAccounts */

$this->breadcrumbs=array(
	'Bu Accounts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BuAccounts', 'url'=>array('index')),
	array('label'=>'Manage BuAccounts', 'url'=>array('admin')),
);
?>

<h1>Create BuAccounts</h1>

<?php 
	//$cs = Yii::app()->clientScript;
	//$cs->scriptMap = array(
	//'jquery.js' => Yii::app()->request->baseUrl.'/js/jquery.js',
	//'jquery.yii.js' => Yii::app()->request->baseUrl.'/js/jquery.min.js',
	//);
	//$cs->registerCoreScript('jquery');
	//$cs->registerCoreScript('jquery.ui');

	
	//$baseUrl = $_SERVER['SERVER_NAME']; 
	//$cs = Yii::app()->getClientScript();
	//$cs->registerScriptFile('/js/main.js');
	//$cs->registerCssFile($baseUrl.'/css/yourcss.css');

?>



<?php $this->renderPartial('_form', array('model'=>$model )); ?>







