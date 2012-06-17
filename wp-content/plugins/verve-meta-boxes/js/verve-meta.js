jQuery(document).ready(function($){

	$( "form#post" ).attr( "enctype", "multipart/form-data" ).attr( "encoding", "multipart/form-data" );

	$("img.addrow").click(function(event){
		var parent = $(this).parent();
     	parent.clone(true).insertAfter(parent);
	});

	$("img.deleterow").click(function(event){
		var parent = $(this).parent();
     	parent.remove();
	});

	$(".date-pick").datePicker({startDate:'1996-01-01'});

    $(".field_list").sortable({
      handle : '.handle',
      update : function () {
		var data = {
			action: 'verve_process_sortable',
			order: $(this).sortable('serialize')
		};
		jQuery.post(ajaxurl, data, function(response) {
  			$("#info").html(response).fadeOut(3000);
		});
      }
    });

	$("img.deletefile").click(function () {
		var parent 	= jQuery(this).parent();
		var data	= jQuery(this).attr("alt");
		var _wpnonce 	= $("input[name='_wpnonce']").val();
  		
  		$.post(
			ajaxurl, 
			{ action: 'verve_unlink_file', _wpnonce: _wpnonce, data: data },
			function(response){
  				//$("#info").html(response).fadeOut(3000);
				//alert(data.post);
			},
			"json"
		);
		parent.fadeOut("slow");

	});


	$("img.deletefield").click(function (e) {
		
		if( confirm('Are you sure? Field and stored values will be deleted. This cannot be undone.') ) {
	
			var parent 	= jQuery(this).parents("li.field_item");
			var data	= jQuery(this).attr("alt");
			var _wpnonce 	= $("input[name='_wpnonce']").val();
			
			$.post(
				ajaxurl, 
				{ action: 'verve_delete_field', _wpnonce: _wpnonce, data: data },
				function(data){
					alert('field removed.');
				},
				"json"
			);
			parent.fadeOut("slow");
		
		}

	});
	
	$("img.sticky-note").hover(
		function(){
			$(this).next().toggle();
		},
		function(){
			$(this).next().toggle();
		}
	);


	$('.jeditable').editable(ajaxurl, {
		submitdata 	: { action	: "verve_editable" }
	});

});

