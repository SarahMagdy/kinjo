<?php


?>

<!--<h1>Admin Annotation Chart</h1>
</br>-->

<select id='select_year'>
	<option value="">Select a Year</option>
	<option value="ALL">ALL Years</option>
	<?php foreach($Years_arr AS $key=>$row):?>
		<option value="<?=$row['created'];?>" ><?=$row['created'];?></option>
	<?php endforeach;?>
</select>


<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['annotationchart']}]}"></script>
<script type='text/javascript'>
	
	google.load('visualization', '1', {'packages':['annotationchart']});
	$('#select_year').change(function(){
	    var data = new google.visualization.DataTable();
	    data.addColumn('date', 'Date');
		data.addColumn('number', 'Rate');
			
		var x={
			chart_year : $('#select_year').val()
			};
		$.post( "AjaxAnnotationChart/", x ,function( return_data ) {
				
			var json_data = return_data.toString();
			if(json_data.length > 10){
					
				end_data = $.parseJSON(json_data);
				for (var key in end_data){
					// var full_date = ch_date.getDate() +'-'+ (ch_date.getMonth()+1) +'-'+ ch_date.getFullYear();				
					var ch_date = new Date(end_data[key]['Y_created'] , end_data[key]['Mon_created']-1 , end_data[key]['D_created'] );
					data.addRow([ ch_date ,  Number(end_data[key]['ord_total']) ]);
				}
					
				var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div'));
				var options = {displayAnnotations: true  };
			    chart.draw(data, options);	
			}
		});
  
  });
</script>

<div id='chart_div' style='width: 900px; height: 500px;'></div>




