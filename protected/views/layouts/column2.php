<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
		
	   $MenuArr = Login::CreateMenu();
	   //------------------------------
	   $MainMenu = array();	
	 
	   $MainMenu = $MenuArr['Main'];
		
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Store Menu',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$MainMenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
		
		//------------------------------	
		$ExtMenu = array();
	   	
	   	$ExtMenu = $MenuArr['Ext'];
	 
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$ExtMenu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>