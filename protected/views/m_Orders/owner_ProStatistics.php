
<select id='store_type'>
	<option value="">Select a Store</option>
	<?php foreach($bu AS $key=>$row):?>
		<option value="<?=$row['buid'];?>" ><?=$row['title'];?></option>
	<?php endforeach;?>
</select>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    	google.load("visualization", "1", {packages:["corechart"]});
    	
    	
    	$('#store_type').change(function(){
    		$("#chart_div").html("");
    		
    			DrawChart();    		
			
    	});   
	    
	    
	    
	    function DrawChart(){
	    	var data = new google.visualization.DataTable();
    		data.addColumn('string', 'Product');
    		data.addColumn('number', 'Quantity');
    		data.addColumn('number', 'Money Rate');
		
			var x={
				store_type : $('#store_type').val(),
				// select_year : $('#select_year').val()
			};
			
			$.post( "AjaxProStatistics/", x ,function( return_data ) {
				var json_data = return_data.toString();
				if(json_data.length > 10){
					
					end_data = $.parseJSON(json_data);			
					
					for (var key in end_data){
						
						data.addRow([ end_data[key]['item']  ,  Number(end_data[key]['qnt']) , Number(end_data[key]['final_price']) ]);
	
					}
					
					var options = {
			        	title: 'Orders Rate',
			        	vAxis: {title: $('#chart_type').val(),  titleTextStyle: {color: 'red'}}
			        };
			
			        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
			        chart.draw(data, options);
				}
				
			});
	    }
</script>


<div id="chart_div" style="width: 900px; height: 500px;"></div>


