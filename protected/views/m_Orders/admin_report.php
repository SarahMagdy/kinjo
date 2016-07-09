<?php
/* @var $this M_OrdersController */
/* @var $model Products */

$this->breadcrumbs=array(
	'Products'=>array('index'),
	// $model->title=>array('view','id'=>$model->pid),
	'Colors Setting',
);

// $this->menu=array(
	// array('label'=>'List Products', 'url'=>array('index')),
	// array('label'=>'Create Products', 'url'=>array('create')),
	// array('label'=>'Update Products', 'url'=>array('update', 'id'=>$model->pid)),
	// array('label'=>'Delete Products', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->pid),'confirm'=>'Are you sure you want to delete this item?')),
	// array('label'=>'View Products', 'url'=>array('view', 'id'=>$model->pid)),
	// array('label'=>'Manage Products', 'url'=>array('admin')),
// );
?>

<h1>Admin Report  #<?php //echo $model->pid; ?></h1>
</br>



<select id='chart_type'>
	<option value="">Select a Chart</option>
	<option value="Month">Month</option>
	<option value="Year">All Years</option>
</select>


<select id='select_year' hidden>
	<option value="">Select a Year</option>
	<!--<option value="ALL">ALL Years</option>-->
	<?php foreach($Years_arr AS $key=>$row):?>
		<option value="<?=$row['created'];?>" ><?=$row['created'];?></option>
	<?php endforeach;?>
</select>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    	google.load("visualization", "1", {packages:["corechart"]});
    	
    	
    	$('#chart_type').change(function(){
    		$("#chart_div").html("");
    		
    		if($('#chart_type').val() == 'Month'){
    			$('#select_year').show();
    		}else{
    			$('#select_year').val('');
    			$('#select_year').hide();
    			DrawChart();
    		}
    		
    		
			
    	});   
	    
	    $('#select_year').change(function(){
	    	// $('#chart_type').change();
	    	DrawChart();
	    });
	    
	    
	    function DrawChart(){
	    	var data = new google.visualization.DataTable();
    		data.addColumn('string', 'Date');
    		data.addColumn('number', 'Orders Total');
			// data.addRow(['01-07-2015' , 66]);
			var x={
				chart_type : $('#chart_type').val(),
				select_year : $('#select_year').val()
			};
			
			$.post( "AjaxGetChartData/", x ,function( return_data ) {
				var json_data = return_data.toString();
				if(json_data.length > 10){
					
					end_data = $.parseJSON(json_data);
					// console.log(end_data);
					
					// var jsonString = ".........."; // json string of array
					// var array  = JSON.parse(json_data);
					// var dataTableData = google.visualization.arrayToDataTable(array);
					
					
					for (var key in end_data){
						var ch_date = end_data[key]['created'];
						// var ch_date = new Date(end_data[key]['created']); 
						// var full_date = ch_date.getDate() +'-'+ (ch_date.getMonth()+1) +'-'+ ch_date.getFullYear();
						var CH_Type = '';						
						var month = new Array();
							month[1] = "January";
							month[2] = "February";
							month[3] = "March";
							month[4] = "April";
							month[5] = "May";
							month[6] = "June";
							month[7] = "July";
							month[8] = "August";
							month[9] = "September";
							month[10] = "October";
							month[11] = "November";
							month[12] = "December";
							CH_Type = month[end_data[key]['created']];
						
						if(ch_date > 2010){
							CH_Type = end_data[key]['created'];
						}
						// console.log(new Date(end_data[key]['created']));
						data.addRow([ CH_Type  ,  Number(end_data[key]['ord_total']) ]);
	
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
	    
	    
	      // google.setOnLoadCallback(drawChart);
	      // function drawChart(data_rows) {
	         // var data = google.visualization.arrayToDataTable([
		        // ['Month', 'Profit'],data_rows
		        // ['Month', 'Profit', { role: 'style' } ],data_rows
		        // ['2010', 10, 'color: gray'],
		        // ['2010', 14, 'color: #76A7FA'],
		        // ['2020', 16, 'opacity: 0.2'],
		        // ['2040', 22, 'stroke-color: #703593; stroke-width: 4; fill-color: #C5A5CF'],
		        // ['2040', 28, 'stroke-color: #871B47; stroke-opacity: 0.6; stroke-width: 8; fill-color: #BC5679; fill-opacity: 0.2']
		      // ]);
	
	        // var options = {
	          // title: 'Company Performance',
	          // vAxis: {title: 'Month',  titleTextStyle: {color: 'red'}}
	        // };
	        // var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
	        // chart.draw(data, options);
	      // }
    </script>


<div id="chart_div" style="width: 900px; height: 500px;"></div>



