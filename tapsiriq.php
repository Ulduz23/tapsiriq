<?php
session_start();
include"headertapsiriq.php";

echo '<div class="container">';


$yaranma=date('Y-m-d H:i:s');


?>

 
<div id="result"><img style="width:150px; height:150px;" class="rounded mx-auto d-block" src="https://static.wixstatic.com/media/d8ccb2_3b8cfa5f14d642d7a30863c8d9253ac8~mv2.gif/v1/fill/w_256,h_256,al_c/d8ccb2_3b8cfa5f14d642d7a30863c8d9253ac8~mv2.gif"></div>

</div>


<script>

		
$(document).on('click','.secsil',function(){
        let form = $('#tsecsil')[0]
        let data = new FormData(form)
     
        $.ajax({
            type: "POST",
			url: "tapmain.php?t=tapshiriq",
            data: data,
            processData: false,
            contentType: false,            
            success: function (response) {
                $('#result').html(response)
            },
        })
    });


$(document).ready(function()
{
	$("#search").keyup(function()
	{
		let input = $(this).val();

		if(input !=" "){
		  $.ajax({
			method:'POST',
			url: "tapmain.php?t=tapshiriq",
			data:{input:input},
			success:function(response){
				$('#result').html(response)
				}
			});
		}
		
	});
});	

$(document).on('click','.edit',function(){
	let id = $(this).attr('id')

	$.ajax({
		method:'POST',
		url: "tapmain.php?t=tapshiriq",
		data:{edit_id:id},
		success:function (response) {
			$('#result').html('<img style="width:75px; height:75px;" class="rounded mx-auto d-block" src="https://acegif.com/wp-content/uploads/loading-1.gif">')
			$('#result').html(response)
			
		}
	})
})


$(document).on('click','.update',function(){
        let form = $('#tapupdate')[0];
        let data = new FormData(form);
        $.ajax({
            type: "POST",
			url: "tapmain.php?t=tapshiriq",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
				$('#cedvel').html('<img style="width:75px; height:75px;" class="rounded mx-auto d-block" src="https://acegif.com/wp-content/uploads/loading-1.gif">')
				$('#result').html(response)
            }
        })
    })


$(document).on('click','.delete',function(){
	let id = $(this).attr('id')
	if(confirm('Melumati silmeye eminsinizmi?'))

	$.ajax({
		method:'POST',
		url: "tapmain.php?t=tapshiriq",
		data:{delete_id:id},
		success:function (response) {
			$('#result').html(response)
			
		}
	})
})



$(document).on('click','.insert',function(){
        let form = $('#tapinsert')[0];
        let data = new FormData(form);
        $.ajax({
            type: "POST",
            url: "tapmain.php?t=tapshiriq",
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
				$('#result').html(response)
            }
        })
    })

	
	$.ajax({
		type:'GET',
		url: "tapmain.php?t=tapshiriq",
		dataType:'html',
		success:function (response) {
			$('#result').html(response)
			
		}
	})






</script>


