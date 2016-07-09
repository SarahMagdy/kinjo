


<select id='Pchart_type'>
	<option value="">Select a Chart</option>
	<option value="Month">Month</option>
	<option value="Year">Year</option>
</select>


<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	
	// google.setOnLoadCallback(drawChart);
	// function drawChart() {
		
		
	$('#Pchart_type').change(function(){
		$("#piechart_3d").html("");
    	var data = new google.visualization.DataTable();
    		
    	if($('#Pchart_type').val() == 'Month'){data.addColumn('string', 'Month');}
    	else if($('#Pchart_type').val() == 'Year'){data.addColumn('string', 'Year');}
    		
    	data.addColumn('number', 'Orders Total');
			
		var x={
			chart_type : $('#Pchart_type').val()
		};
			
		$.post( "AjaxPieChart/", x ,function( return_data ) {
			var json_data = return_data.toString();
			if(json_data.length > 10){
				end_data = $.parseJSON(json_data);
				for (var key in end_data){
					var type = '';
					if($('#Pchart_type').val() == 'Year'){
						type = end_data[key]['Y_created'];
					}else if($('#Pchart_type').val() == 'Month'){
														
						var month = new Array();
							month[1] = "January";   month[2] = "February";
							month[3] = "March";     month[4] = "April";
							month[5] = "May";       month[6] = "June";
							month[7] = "July";      month[8] = "August";
							month[9] = "September"; month[10] = "October";
							month[11] = "November"; month[12] = "December";
							type = month[end_data[key]['Mon_created']];
					}
						
					data.addRow([  type ,  Number(end_data[key]['ord_total']) ]);
				}
					
				var options = {
					title: 'Monitor Orders',
					is3D: true,
				};
			
				var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
				chart.draw(data, options);	
					
			}			
		});
		
			// var order2 = {order:'{"ord_id":"15"}'};
			// var order2 = {order:'{"bu_id":"3","id":"","qnt":"1","AppSource":"0","p_id":"11","cust_id":"1"}'}
			// $.post('/index.php/API/AddToOrder',order2,function(data){	
				// alert(data);
			// });
	});
		
		
    	// var data = google.visualization.arrayToDataTable([
      				// ['Task', 'Hours per Day'],
      				// ['Work',     11],
      				// ['Eat',      2],
      				// ['Commute',  2],
      				// ['Watch TV', 2],
      				// ['Sleep',    7]
    			// ]);
		// }
</script>



<div id="piechart_3d" style="width: 900px; height: 500px;"></div>




