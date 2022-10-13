<?php
session_start();
include 'header.php';
require_once __DIR__ . '/lib/ModelInsert.php';
if(!isset($_SESSION['car_name']) OR empty($_SESSION['car_name'])){
    $_SESSION['car_name'] = 'porter';
}
$car_name = $_SESSION['car_name'];
$obj = new ModelInsert;
$cars = $obj -> getAllCars();
// Выбираем все синонимы из связанных таблиц
$cases = $obj->getCases();

//p($cases);

// p($obj->getSyn('колес'));
//p($obj->getSearchQuery('шин', 'ALLCARS'));
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md text-center">
			<h4>Ситуации</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-1">
      		 
    	</div>
    	<div class="col-md-5 mt-3 case-table">
    		<table class="table table-bordered">
    			<thead>
    			<tr>
    				<th>ID</th>
    				<th>Ключевые слова</th>
    				<th>Ситуации</th>
    				<th>Машина</th>
    				<th>Удалить</th>
    			</tr>
    			</thead>
    			<tbody>
            		<?php foreach($cases as $case): ?>
            		
            		<?php
            		//p($case);
            		$carString = '';
            		for($i = 0; $i < count($case['cars']); ++$i):
            		$carName = $case['cars'][$i]['car'];
            		$carId = $case['cars'][$i]['car_id'];
            		  $carString .= $carId . ', ';
            		
            		endfor;
            		$carString = rtrim($carString, ', ');
            		?>
            		
            		<tr>
            			<td><a href="editTree.php?id=<?=$case['case']['id'] ?>"><?=$case['case']['id'] ?></a></td>
            			<td><span class="edit_area1" id="<?=$case['case']['id'] ?>"><?=$case['case']['ang_name'] ?></span></td>
            			<td><span class="edit_area2" id="<?=$case['case']['id'] ?>"><?=$case['case']['situation_name'] ?></span></td>
            			<td><span class="edit_area3" id="<?=$case['case']['id'] ?>"><?=$carString ?></span></td>
            			<td class="text-center trash-tree2"><a href="saveCase.php?action=delete&case_id=<?=$case['case']['id'] ?>"><i class="fa fa-trash"></i></a></td>
            		</tr>
            		<?php endforeach ?>
    			</tbody>
    		</table>
    	</div>
    	<div class="col-md-1 cars-table">
    		<table class="table table-bordered">
    			<thead>
    				<tr>
    					<th>ID</th>
    					<th>Машина</th>
    				</tr>
    			</thead>
    			<tbody>
    			<?php foreach($cars as $car): ?>
    				<tr>
    					<td><?=$car['id'] ?></td>
    					<td><?=$car['car'] ?></td>
    				</tr>
    			<?php endforeach ?>
    			</tbody>
    			
    		</table>
    	</div>
    	<div class="col-md-4">
    		<div class="row">
		<div class="col-md mt-2 mb-2 ml-2">
		<label>Добавить новый элемент</label>
    		<form method="post" action="newCase.php">
              <div class="form-row">
                <div class="col">
                  <textarea type="text" class="form-control" placeholder="Ключевые слова" name="keys"></textarea>
                </div>
                <div class="col">
                  <textarea type="text" class="form-control" placeholder="Название ситуации" name="case"></textarea>
                </div>
                <div class="col">
                  <textarea type="text" class="form-control" placeholder="Машины" name="cars"></textarea>
                </div>
              </div>
              <button class="btn btn-outline-success mt-2">Сохранить</button>
            </form>
        </div>
	</div>
    	</div>
		<div class="col-md-1"><?php include_once 'right.php';?></div>
	</div>
</div>

<div id="dialog" title="На проценку">
  <table  class="float_window table table-striped table-hover search-table">
  <tbody class="float-window-body"></tbody>
  </table>
</div>



<?php include_once 'footer.php';?>
<script src="/js/jquery.jeditable.js"></script>
<script type="text/javascript">
$(document).ready(function() {
   
    $('.edit_area1').editable('saveCase.php', {
        type      : 'textarea',
        name      : 'keywords',
        submit    : 'OK',
        tooltip   : 'Кликни для редактирования...',
        rows    : 4,
        cols    : 20
    });
    $('.edit_area2').editable('saveCase.php', {
        type      : 'textarea',
        name      : 'cases',
        submit    : 'OK',
        tooltip   : 'Кликни для редактирования...',
        rows    : 4,
        cols    : 20
    });
    $('.edit_area3').editable('saveCase.php', {
        type      : 'textarea',
        name      : 'cars',
        submit    : 'OK',
        tooltip   : 'Кликни для редактирования...',
        rows    : 4,
        cols    : 20,
        placeholder: 'Stop Words'
    });
});
</script>




