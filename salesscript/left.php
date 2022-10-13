
<?php 
if(isset($leftCars)):
?>
<nav >
<ul class="left-cars">
	<?php foreach ($leftCars as $leftCar):?>
		<?php if($_SESSION['car_name'] == $leftCar['car']){$redCar = ('style="color:red; font-weight: bold;"');}else{$redCar = ''; }?>
	<li  class="nav-item">
        <a id="<?=$leftCar['id'] ?>" class="nav-link pl-0 car-link" href="" <?=$redCar?>><?=$leftCar['car']?></a>
    </li>
    <?php endforeach;?>
</ul>
</nav>
<?php endif ?>
<script>
$('.car-link').click(function(){
	var car = $(this).text();
	var car_id = $(this).attr('id');
	console.log($(this).attr('id'));
	request = $.ajax({
	    url: "setcarsession.php",
	    type: "post",
	    data:{
	    car_name: car,
	    car_id: car_id
	    }
	});
	console.log(car);
	// callback handler that will be called on success
	request.done(function (response, textStatus, jqXHR){
	    // log a message to the console
	    console.log("Hooray, it worked! " + response);
	    
	});

	// callback handler that will be called on failure
	request.fail(function (jqXHR, textStatus, errorThrown){
	    // log the error to the console
	    console.error(
	        "The following error occured: "+
	        textStatus, errorThrown
	    );
	    });
	});
</script>