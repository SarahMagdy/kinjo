<?php
/* @var $this businessUnnitController */
/* @var $model BusinessUnnit */

// $this->breadcrumbs=array(
	// 'Orders Details'=>array('index'),
	// 'Close Orders',
// );

// $this->menu=array(
	// array('label'=>'View Orders', 'url'=>array('CustomView')),
	// array('label'=>'Orders Grid', 'url'=>array('CustomGrid')),
// );
?>

<h1>Reservations</h1>

<?php 
	// echo '<pre/>';
	// print_r($Data);
?>
<style type="text/css">	
	
	/*
	table, th, td {
		   border: 1px solid black; 
		   padding: 4px; 
		}*/
	
	
	/*div.horizontal {
	    width: 100%;
	    height: 400px;
	    overflow: auto;
	}*/
	
	/*
	.table {
			display: table;
			table-layout: fixed;
			width: 100%;
		}*/
	
	
	div.wrapper {
	  width: 500px;
	  height: 500px;
	  overflow: auto;
	}
	
</style>

<label>Date : </label>
<input type="text" id="ResDate" value=""/>
<br />
<!-- <input type="text" id="basicExample" value=""/> -->

	<?php 
		// echo '<pre/>';
		// print_r($Data['Res']['units']);
		// var_dump($Data['Res']['units']);
	?>


<div class="wrapper" style="width:100%;">
</div>


<script type="text/javascript">

	$("#ResDate ").datepicker({
		changeMonth: true , 
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		// timeFormat:  "HH:mm",
		onSelect: function(dateStr) {
			// alert('You chose ' + dateStr);
			// alert($('#ResDate').val());
			
			if($('#ResDate').val() != ''){
		    	var p = {
			    		RDate : $('#ResDate').val(),
			    		// DB_ID  : $('#delivery_boy').val()
			    	};
			    $.post('/index.php/BusinessUnit/GetAvUnitsByD',p,function(data){
					// alert(data);
					$( ".wrapper" ).empty();
					$( ".wrapper" ).append( data );
					xx();
					// location.reload();
				});
		    }else{
		    	alert('Please Choose Specifi Day .');
		    }
			
		}
	});
	// $(document).ready(function(){			
		// $('#basicExample').timepicker();
		
		function xx(){
			$("#Restimes tr").each(function (){
				
				$('td', this).each(function (){
				    	
			    	if($(this).hasClass('From')){
			    		if($(this).hasClass('td-start')){
			    			// $(this).css("border", "1px solid black");
			    			$(this).css("border-left" , "thick double #0000FF");
			    		}
			    		// else{
			    			$(this).css("background-color", "yellow");
			    		// }
						
			    		
			    		if( $(this).closest('td').next().hasClass('To') ){
			    			
			    			if($(this).closest('td').next().hasClass('td-end')){
				    			// $(this).closest('td').next().css("border", "1px solid black");
				    			$(this).closest('td').next().css("border-right", "thick double #0000FF");
				    		}
			    			$(this).closest('td').next().css("background-color", "yellow");
			    			
			    		}else{
			    			
			    			$(this).closest('td').next().addClass('From');
			    			
			    		}
			    	}	
				});
			});
		} 

	// });
	
	
</script>







