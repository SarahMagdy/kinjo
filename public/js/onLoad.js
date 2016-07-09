 $(document).ready(function(){

	$('.LangOpen').click(function(){
		
		var ContName = $(this).attr('ContName');
		var LangID = $(this).attr('LangID');
		var RowID = $(this).closest('tr').children('td:first').text();
		OpenDialogFrm(ContName,LangID,RowID);
		
	});
	
	var Interval = 1000 * 60 * 5;Interval = 100 * 60;
	
	//setInterval(function(){UserInActive();}, Interval);
});

function UserInActive() {
  
  $.post('/index.php/Auth/AjaxInActive',function(data){
  	
  });
  
}


var Frm;var dialog;

function OpenDialogFrm(ContName,LangID,RowID){

	var d = {
		ContName:ContName,
		LangID:LangID,
		RowID:RowID
	};
	
	$.post('/index.php/Common/OpanLang',d,function(data){
		Frm = data;
		dialog = $('<div>'+Frm+'</div>').dialog({
					autoOpen: false,
					height: 400,
					width: 450,
					modal: true,
					buttons: {
					// "Submit": SubmitDialogFrm,
					// success:function(){
						// dialog.dialog( "close" );
					// },
						Submit:function(){
							SubmitDialogFrm();
							dialog.dialog( "close" );
							$(this).remove();
						},
						Cancel: function() {
							dialog.dialog( "close" );
							$(this).remove();
						}
					},
					close: function() {
						dialog.dialog( "close" );
						$(this).remove();
					}
				});
		
		
		dialog.dialog( "open" );
	});
}

function SubmitDialogFrm(){
	
	//var RowID = $('#RowID').val();
	
	var f_data = {
		
		ContName:$('#ContName').val(),
		LangID:$('#LangID').val(),
		RowID:$('#RowID').val(),
		Type:$('#Type').val(),
		
	};
	
	$('.langFrm').map(function () {
		
		f_data[$(this).attr('id')]= $(this).val();
	});
	
	
	$.post('/index.php/Common/SubmitLang',f_data,function(data){
			
		// dialog.dialog( "close" );
		// dialog.dialog('destroy');
		//$(".LangOpen "+RowID).css("color","#16b51e");

	});	

}



function ConvertLang(link_id ){//,URL
	
	// var full_URL = URL.split('/index.php/')[1];
	// var controller = full_URL.split('/')[0];
	// var action = full_URL.split('/')[1];
	
	var data ={
			link_id : link_id,
			// page_URL : URL,
			// page_controller : controller,
			// page_action : action
		}; 
		
	$.post( "/index.php/Auth/ConvertLang/",data, function( data ) {
		window.location.reload();
		
	});
}

function GetCreditData () {
		
	if($('.CreditCard').val() > 0){
		
		var d ={ CardID:$('.CreditCard').val() };
		
		$.post('/index.php/bills/AjaxGetCreditData',d,function(data){
			var json_data = data.toString();
			if(json_data.length > 10){
				var end_data = $.parseJSON(json_data);
			    // Setup token request arguments
		        var args = {
		            sellerId: "901262532",
       				publishableKey: "A72E8DDE-D8B9-4D84-AF5F-B3D546D1589C",
		            ccNo: end_data['Credit'],
		            cvv: end_data['Cvv'],
		            expMonth: end_data['MonthExp'],
		            expYear: end_data['YearExp']
		        };
		
		        // Make the token request
		       TCO.requestToken(successCallback, errorCallback, args);
		    
		      //alert('Res'+Res);
			}
		});
	
	}else{
		
		NotSavedCredit();
		
	}
	
	
}

function NotSavedCredit(){
	
	alert('Choose Credit Card ');
	$('.CreditCard').focus();
	return 0 ;
}

function ReloadJs(){
	
}

function AuthLinks(){
	$('table.items tbody tr td.button-column a').hide();
	//alert($(location).attr('href'));
	$.post('/index.php/Auth/AuthLinks',function(data){
		var json_data = data.toString();
		var end_data = $.parseJSON(json_data);
		for (var key in end_data){
			//alert(end_data[key]);
			$('table.items tbody tr td.button-column a.'+end_data[key]).show();
		}
	});
}

function AjaxError(Mess){
	alert(Mess);
	
}



