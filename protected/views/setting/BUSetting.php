<!-- <div>
	This Is BU Setting.
</div> -->

<html>
    <head>
        
        
    </head>
    <header>
        <style>
           #jumbotron
            {
                width: 200px;
                background-color:rgba(0,0,255,0.3);
            }
            </style>
        
    </header>
    
    
</html>




 <div class="col-lg-12"> 
            <div class="container">


<form method="POST" class="form-horizontal">

	<input hidden type="text" name="frm_status" id="frm_statusID" value="<?= $BUSett['frm_status'];?>"/>
	
	<div class="form-group">

            <label class="col-sm-2 control-label" style="color: #0000ff"><b>General Notify Customer</b></label></br></br>
        <div class="col-sm-10">

	<input type="checkbox" name="onLineCHK" id="onLineCHKID" value="0"> On Line <br>
        </div>
        </div>
	<input type="text" hidden name="onLine" id="onLineID" value="0"/>
	
	<input type="checkbox" name="onSiteCHK" id="onSiteCHKID" value="1" checked> On Site <br>
	<input type="text" hidden name="onSite" id="onSiteID" value="0"/> <br>
	
	
	<?php // if(Yii::app()->session['User']['UserType'] == 'admin' ):?>
        <label class="col-sm-2 control-label" style="color:#0000ff"><b>General Notify Customer : </b></label></br></br>
		<div class="form-group" id="jumbotron">
			<input type="radio" name="general_notify" class="sub_Loc" value="0" checked> All Subscriped Cusromers<br>
			<input type="radio" name="general_notify" class="sub_Loc" value="1"> All Subscriped by Location<br>
			<input type="radio" name="general_notify" class="sub_Loc" value="2"> All Customers via Location <br>
		</div>
        </br></br>
	<?php // endif;?>
	
	<div id="diameterDiv" hidden>
            <label style="color:#0000ff"><b>Diameter : </b></label></br</br>
		<input type="text" name="diameter" id="diameterID" value="0"/>
	</div>
	<br>
	
	<div style="float:left;width:100%;"><br>
            <input type="submit" value="Success"  class="btn btn-success" style="margin-left: 141px; margin-top: 71px;">
	</div>
</form> 
            </div>
 </div>



<script>
	
	
	$( document ).ready(function() {
		<?php if($BUSett['onLine'] == 1):?>
			$('#onLineCHKID').attr('checked', true);
			$("#onLineCHKID").val('1');
			$('#onLineID').val('1');
		<?php endif;?>
		
		
		<?php if($BUSett['onSite'] == 1):?>
			$('#onSiteCHKID').attr('checked', true);
			$("#onSiteCHKID").val('1');
			$("#onSiteID").val('1');
		<?php else: ?>
			$('#onSiteCHKID').attr('checked', false);
		<?php endif;?>
		
		if($('#frm_statusID').val() == 'edit'){
			$("input[name=general_notify][value=" + <?= $BUSett['general_notify']?> + "]").attr('checked', 'checked');
			$('input[name=general_notify]').click();
			$('#diameterID').val(<?= $BUSett['diameter']; ?>);
			
		}
		
	});
	
	$('input[name=general_notify]').click(function(){
	    if($("input[type='radio'][name='general_notify']:checked").val() == 1 || $("input[type='radio'][name='general_notify']:checked").val() == 2){
	    	$('#diameterDiv').attr('hidden' , false);
	    }else{
	    	$('#diameterDiv').attr('hidden' , true);
	    	if($('#frm_statusID').val() == 'edit'){
	    		$('#diameterID').val(<?= $BUSett['diameter']; ?>);
	    	}else{
	    		$('#diameterID').val(0);
	    	}
	    }
	   
	});
	
	
	
	$('#onLineCHKID').change(function() {
		if ($(this).is(':checked')) {
			$('#onLineCHKID').val('1');
			$('#onLineID').val('1');
			// $("input[type='radio'][name='general_notify']:checked").val('1');
   		} else {
   			$('#onLineCHKID').val('0');
   			$('#onLineID').val('0');
   			// $("input[type='radio'][name='general_notify']:checked").val('0');
   		}
	});
	
	
	$('#onSiteCHKID').change(function() {
		if ($(this).is(':checked')) {
			$('#onSiteCHKID').val('1');
			$("#onSiteID").val('1');
			// $("input[type='radio'][name='general_notify']:checked").val('1');
   		} else {
   			$('#onSiteCHKID').val('0');
   			$("#onSiteID").val('0');
   			// $("input[type='radio'][name='general_notify']:checked").val('0');
   		}
	});
	
</script>