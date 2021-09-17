function loopdata_table(){
	$.ajax({
				 type:'GET',
				 url: url,
				 data: data,
				 datatype: 'json',
				 success:function(data){
					 $('#result').html(data.html);
					 $('#table_style').html(data.html_style);
				 }
			 });
		 }
$(document).ready(function(){
	$('#startbtn').on('click',function(){
		const  music = document.getElementById("music");
		music.play();
	});
		var title = document.title;
		function changeTitle(number) {
				var number = number
				var newTitle = title;
				if (number > '0') {
					var newTitle = '(' + number + ') ' + title;
				}
				document.title = newTitle;
		}
		var loaddata_table_all = function loopdata_table(){
			$.ajax({
						 type:'GET',
						 url: url,
						 data: data,
						 datatype: 'json',
						 success:function(data){
							 $('#result').html(data.html);
							 $('#table_style').html(data.html_style);
							 changeTitle(data.number);
							 if (data.newrepair) {
								 $('#startbtn').trigger('click');
									  Swal.fire({
											icon : 'error',
									    title: '!! มีรายการแจ้งซ่อมรอยืนยัน !!',
											showCloseButton: false,
            					showCancelButton: false,
											showconfirmButton: true,
										  confirmButtonText: 'ตกลง',
									  }).then((result) => {
										  if (result.isConfirmed) {
										    $.ajax({
													type:'GET',
							 						 url: url_confirmpd,
							 						 data: {STATUS:'1'
												 					,UNID:data.UNID},
							 						 datatype: 'json',
												});
										  }
										})
							 }
						 }
					 });
				 }
	setInterval(loaddata_table_all,30000);
	loopdata_table();
});
