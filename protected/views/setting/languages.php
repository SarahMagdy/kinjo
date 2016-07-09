<?php
	$this->breadcrumbs=array(
		//'setting'=>array('index'),
		'Languages Setting',
	);

?>

<style>
	ul { list-style-type: none; margin: 0; padding: 0; margin-bottom: 10px; }
	li { margin: 5px; padding: 5px; width: 150px; }
	.active_lang{ background: #99FF66 }
	.deactive_lang{ background: #A9A9A9 }
</style>

<h1>Manage Languages Setting</h1>
<br />

<div id ="FullLangsDiv" style="width:40%;height:350px;float:left;border:2px solid #CCC;">
	<div style="width:94%;padding: 10px;" class="ui-state-default" ><center><b>Languages</b></center></div>
	<ul id ="LangsDiv" class="LangDrop" style="height:100%;">
		<?php if(isset($Data)):?>
			<?php foreach($Data as $key => $row):?>
				<li class="LangDrag ui-state-default" id="ID_<?=$row['lang_id']?>" LangID="<?=$row['lang_id']?>" ActF="0" onclick="funcA('ID_<?=$row['lang_id']?>')">
					<img src="/assets/flags/<?=$row['lang_code']?>.png"  width="30" height="30"/>
					<label><?=$row['lang_name']?></label>
				</li>
			<?php endforeach;?>
		<?php endif;?>
	</ul>
</div>

<div id ="FullBULangsDiv" style="width:40%;height:350px;float:right;border:2px solid #CCC;">
	<div style="width:94%;padding: 10px;" class="ui-state-default"><center><b>Selected Languages</b></center> </div>
	<ul id ="BULangsDiv" class="SLangDrop" style="height:100%;">
		<?php if(isset($BUData)):?>
			<?php foreach($BUData as $key => $row):?>
				<?php $actclass = $row['bu_lang_val']== '1'?'active_lang':'deactive_lang';?>
				<li class="SLangDrag ui-state-default <?=$actclass;?>" id="ID_<?=$row['lang_id']?>" LangID="<?=$row['lang_id']?>" ActF="<?=$row['bu_lang_val']?>" onclick="funcA('ID_<?=$row['lang_id']?>')">
					<img src="/assets/flags/<?=$row['lang_code']?>.png"  width="30" height="30"/>
					<label><?=$row['lang_name']?></label>
				</li>
			<?php endforeach;?>
		<?php endif;?>
	</ul>
</div>

<button id="BtnSave">Save</button>

 <script>
 
	$(function() {

		 $(".LangDrag").draggable({ revert: "invalid" });
		 
		 $(".SLangDrag").draggable({ revert: "invalid" });
		 
		 $("#LangsDiv").droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			accept: ".SLangDrag",
			drop: function( event, ui ) {
				  var offset = $('#LangsDiv').offset();
			      var top = parseInt($(ui.draggable).css('top')) - offset.top;
			      var left = parseInt($(ui.draggable).css('left')) - offset.left;            
			      $(ui.draggable).appendTo("#LangsDiv");
			      $(ui.draggable).css({'top' : top, 'left' : left})
			      $(ui.draggable).animate({
			        top: 0,
			        left: 0           
			      }, 0, function() {
			        $(this).removeClass("SLangDrag").addClass("LangDrag");
			        $(this).removeClass("active_lang");
			        $(this).removeClass("deactive_lang");
			        $(this).attr("ActF","0");
			      });
			}
		}).sortable({
			// items: "li.SLangDrag",
			// sort: function() {
// 				
			// }
		});
		
		$("#BULangsDiv").droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			accept: ".LangDrag",
			drop: function( event, ui ) {
				  var offset = $('#BULangsDiv').offset();
			      var top = parseInt($(ui.draggable).css('top')) - offset.top;
			      var left = parseInt($(ui.draggable).css('left')) - offset.left;            
			      $(ui.draggable).appendTo("#BULangsDiv");
			      $(ui.draggable).css({'top' : top, 'left' : left})
			      $(ui.draggable).animate({
			        top: 0,
			        left: 0           
			      }, 0, function() {
			        $(this).removeClass("LangDrag").addClass("SLangDrag");
			        $(this).addClass("active_lang");
			        $(this).attr("ActF","0");
			        // $(this).addEventListener("click", function(){
					    // //document.getElementById("demo").innerHTML = "Hello World";
					// });
			      });
			}
		}).sortable({
			// items: "li.SLangDrag",
			// sort: function() {
// 				
			// }
		});
		 
	});
	
	function funcA(ID){
		
		if($('#'+ID).hasClass("SLangDrag")){
			
			if($('#'+ID).hasClass("active_lang")){
				$('#'+ID).removeClass("active_lang").addClass("deactive_lang");
				$('#'+ID).attr("ActF","0");
			}else if($('#'+ID).hasClass("deactive_lang")){
				$('#'+ID).removeClass("deactive_lang").addClass("active_lang");
				$('#'+ID).attr("ActF","1");
			}
		}
		
	}
	
	$('#BtnSave').click(function(e){
		
		var LangD = {};
		
		$("ul#BULangsDiv li.SLangDrag").each(function() {
			
			LangD[$(this).attr('LangID')]= $(this).attr('ActF');
		});
				
		$.post('/index.php/setting/SubmitSettingLang',LangD,function(data) {
		  	
		  	if(data){
		  		
		  		if(data == 'save'){
		  			
		  			alert('Languages Setting Saved');
		  		}
		  		if(data == 'nostore'){
		  			
		  		}
		  		if(data == 'login'){
		  			
		  		}
		  		
		  	}
		  	
		});
		
		
	});
	
</script>