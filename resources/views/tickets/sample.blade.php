<html>
	<head>
		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/jquery-ui-1.10.4.min.js"></script>
	</head>
	<form>
		<input type="text" id="q" name="search">
		
	</form>
	
	<script type="text/javascript">
		$(function()
{
	 $( "#q" ).autocomplete({
	  source: "search/autocomplete",
	  minLength: 3,
	  select: function(event, ui) {
	  	$('#q').val(ui.item.value);
	  }
	});
});

$(function() {
            $( "#q" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: "search/autocomplete",
                        dataType: "jsonp",
                        data: {
                            q: request.term
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
            });
        });     
	</script>
</html>