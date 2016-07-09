<style type="text/css">
	.InputTxt{
		width:60px;
	}
</style>
<div>
	<h1>Tolal Tables</h1>
</div>
<a href="#" id="a-add-t">Add New Table</a>
</br></br>
<div>
	<table>
		<thead>
			<tr>
				<th colspan="2"><center>Table Serial</center></th>
				<th>Number OF Chairs</th>
				<th colspan="2"><center>QRCode</center></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($TablesData)):?>
				<?php foreach($TablesData AS $Key=>$Row):?>
					<tr id = "TR_<?=$Row['bu_table_id']?>" Serial = "<?=$Row['bu_table_serial']?>" T_id ="<?=$Row['bu_table_id']?>">
						<td class="td-<?=$Row['bu_table_id']?>" case="display" ><span><?=$Row['bu_table_serial']?></span></td>
						<td><a href="#" class="a-serial a-<?=$Row['bu_table_id']?>">
							<img src="/assets/8626beb4/gridview/update.png"> </a>
						</td>
						<td class="td-num_chairs"><span><?=$Row['bu_table_num_chairs']?></span></td>
						<td class="td-QRCode">
							<img src="http://chart.apis.google.com/chart?chs=75x75&cht=qr&chl=<?=$Row['bu_table_qrcode']?>" alt="QRcode" width="75" height="75"/>
						</td>
						<td class="td-QRCodeLink">
							<a href="http://chart.apis.google.com/chart?chs=250x250&cht=qr&chl=<?=$Row['bu_table_qrcode']?>" alt="Scan Me !" target="_blank" class="a-v-qr"><img src="/assets/8626beb4/gridview/view.png"></a>
							<!--<a href="#" class="a-d-qr" qr="<?=$Row['bu_table_qrcode']?>"><img src="/assets/8626beb4/gridview/download.png"></a>-->
							<!--<pre><?=urlencode("qr=".$Row['bu_table_qrcode']."&serial=".$Row['bu_table_serial'])?></pre>-->
							<a href="<?php echo "/index.php/businessUnit/downLoadQrCode?".urlencode("qr=".$Row['bu_table_qrcode']."&serial=".$Row['bu_table_serial'])?>"><img src="/assets/8626beb4/gridview/download.png"></a>
							<a href="#" class="a-r-qr" qr="<?=$Row['bu_table_qrcode']?>"><img src="/assets/8626beb4/gridview/refersh.png"></a>
						</td>
						<td><a href="#" class="a-del-t" t_id="<?=$Row['bu_table_id']?>"><img src="/assets/8626beb4/gridview/delete.png"></a></td>
					</tr>	
				<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table>
</div>
<div id="AddDiv" style="display: none;">
	<form>
		<label> Table Serial</label></br>
		<input id="serial_txt" value=""/></br>
		
		<label> Number OF Chairs</label></br>
		<input id="Chair_txt" value=""/>
	</form>
</div>
<script>
	//---------------------------Serial
	
	$('.a-serial').click(function(e){

		var T_id = $(this).closest('tr').attr('T_id');
		var Serial = $('#TR_'+T_id).attr('Serial');
		var Case = $("td.td-"+ T_id +"").attr('case');
		
		if(Case == 'display'){
			
			$("td.td-"+ T_id +"").html('<input class="InputTxt" id="Txt-'+T_id+'" value="'+Serial+'"/>');
			$("a.a-"+ T_id +"").html('<img src="/assets/8626beb4/gridview/save.png">');
			$("td.td-"+ T_id +"").attr('case','edit');
		}
		if(Case == 'edit'){
			
			if(Serial != $("#Txt-"+ T_id).val()){
				//------Save New Serial
				
				Serial = $("#Txt-"+ T_id).val();
				
				var d = {
					Serial:Serial,
					T_id:T_id
				};
				$.post('/index.php/BusinessUnit/EditSerial',d,function(data){
					
					if(data == 'True'){
						$('#TR_'+T_id).attr('Serial',Serial);
						$("td.td-"+ T_id +"").html('<span>'+Serial+'</span>');
						$("a.a-"+ T_id +"").html('<img src="/assets/8626beb4/gridview/update.png">');
						$("td.td-"+ T_id +"").attr('case','display');
					}
				});
			
			} else {
				
				$("td.td-"+ T_id +"").html('<span>'+Serial+'</span>');
				$("a.a-"+ T_id +"").html('<img src="/assets/8626beb4/gridview/update.png">');
				$("td.td-"+ T_id +"").attr('case','display');
			}	
		}
		
	});
	
	//---------------------------QRCode
	$('.a-r-qr').click(function(e){
		
		var QR = {QRCode : $(this).attr('qr')}
		$.post('/index.php/BusinessUnit/ReCreateQrCode',QR,function(data){
			if(data == 'True'){
				alert('QrCode Re-Generate Successfully');
				location.reload();
			}
		});
	});
	//------------
	$('.a-d-qr_').click(function(e){
		
		var Serial = $(this).closest('tr').attr('Serial');
		var QR = {
					QRCode : $(this).attr('qr'),
					Serial:Serial
				};
		$.post('/index.php/BusinessUnit/DownLoadQrCode',QR,function(data){
			if(data == 'True'){
				alert('QrCode DownLoaded Successfully');
				
			}
		});
	});
	//---------------------------Delete Table
	$('.a-del-t').click(function(e){
		
		if(confirm('Are You Sure ?')){
			var d = {t_id : $(this).attr('t_id')};
			$.post('/index.php/BusinessUnit/DeleteTable',d,function(data){
				location.reload();
			});
		}
	});
	
	 $('#a-add-t').click(function(e){ 
     	
     	 $(function() {
			dialog = $('#AddDiv').dialog({
				
					autoOpen: false,
					height: 400,
					width: 450,
					modal: true,
					buttons: {
						Submit:function(){
							SubmitTableFrm();
							dialog.dialog( "close" );
							
						},
						Cancel: function() {
							dialog.dialog( "close" );
							
						}
					},
					close: function() {
						dialog.dialog( "close" );
						
					}
				});
			});
			
			dialog.dialog( "open" );
			
     });
     
     function SubmitTableFrm () {
     	
     	var d ={
     		Serial :$('#serial_txt').val(),
     		Chairs :$('#Chair_txt').val()
     	}
     	$.post('/index.php/BusinessUnit/AddTable',d,function(data){
			if(data > 0){
				location.reload();
			}
		});
     }
     
</script>