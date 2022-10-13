<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(3000);
require 'tpl/header.php';
?>
<div class="spacer-60"></div>
<div class="container-fluid">
<div class="row">

<?php
require 'tpl/left.php';
?>
<main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">

            <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Email address</label>
                            <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                    </div>
                    <div class="form-group">
                    <label for="exampleFormControlSelect1">Example select</label>
                        <select class="form-control sel" id="sel1" >
                            <option></option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group">
                    <label for="exampleFormControlSelect1">Example select</label>
                        <select class="form-control sel" id="sel2">
                            <option></option>
                            <option>one</option>
                            <option>two</option>
                            <option>three</option>
                            <option>four</option>
                            <option>five</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="amount">Емкость</label>
                        <div id="slider-range"></div>
                        <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                    </div>
            
            
                                             <script>
                                                $(document).ready(function() {
                                                    $("#slider-range").slider({
                                                        range: true,
                                                        min: 0, 
                                                        max: 300,
                                                        values: [ 0, 300 ],
                                                        slide: function( event, ui ) {
                                                        $( "#amount" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                                                        console.log(ui.values[ 0 ] + " - " + ui.values[ 1 ]);
                                                        }
                                                        });
                                                        
                                                        $( "#amount" ).val($( "#slider-range" ).slider( "values", 0 ) +
                                                        " - " + $( "#slider-range" ).slider( "values", 1 ) );
                                                        });
                                            </script>
            
            
            

<script>
	$(document).ready(function() {
	  
		$("#sel1").change(function() {
			var value = $(this).val();
			if(value != ''){
			console.log(value);
			$.ajax({
				url : 'response.php?action='+value+'&andrey=vasya',
				success : function(data) {
					$('.results').html(data);
				}
			});
			}
			
		});
		
	}); 
</script>

<button id="btn">Send an HTTP GET request to a page and get the result back</button>
<div class="results"></div>

</main>
<?php
require 'tpl/footer.php';
?>