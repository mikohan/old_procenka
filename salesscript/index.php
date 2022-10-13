<?php
session_start();
include 'header.php';
require_once __DIR__ . '/lib/ModelGet.php';
if(!isset($_SESSION['car_name']) OR empty($_SESSION['car_name'])){
    $_SESSION['car_name'] = 'porter';
}
$car_name = $_SESSION['car_name'];
$obj = new ModelGet();
$leftCars = $obj->getAllCars();
// p($obj->getSyn('колес'));
//p($obj->getSearchQuery('шин', 'ALLCARS'));
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
      		<?php include_once 'left.php';?>
      		<?php include_once 'right.php';?>
    	</div>
		<div class="col col-md-4">
		<div class="top-row">Мы находимся в машине: <span class="show_car_name_session"><?=$car_name?></span><span class="marg-20"></span><span>Найдено: <span id="countItems"></span></span></div>
			<form action="" style="width: 100%" >
				<input class="form form-control" id="search_input" type="text" class="input_search"
					placeholder="&#128270; Поиск">
			</form>
				<div class="div_search">
					<table class="table table-striped table-hover search-table" >
    					<tbody id="search_res_table">
    					</tbody>
					</table>
				</div>
		</div>
		<div class="col-md-4">
		<div class="div_search2">
		<div style="overflow-x:auto;">
					<table class="table table-striped table-hover search-table mt-5 sup-table-ajax" >
    					<tbody id="search_res_table2">
    					</tbody>
					</table>
					</div>
				</div>
		</div>
		<div class="col-md-3">
			<div class="mt-5" >
				<div id="situations" ></div>
				<div class="testdiv"></div>
			</div>
		</div>
	</div>
</div>
<!-- Плавающее окно с кликнутыми запчастями  -->
<!-- <div id="dialog" title="На проценку">
  <table  class="float_window table table-striped table-hover search-table">
  <tbody class="float-window-body"></tbody>
  </table>
</div> -->



<?php include_once 'footer.php';?>
<script src="/js/index_search.js"></script>
