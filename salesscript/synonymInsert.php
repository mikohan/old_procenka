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
$syns = $obj->getSyn();

//p($cars);

// p($obj->getSyn('колес'));
//p($obj->getSearchQuery('шин', 'ALLCARS'));
?>

<div class="container-fluid">
<div class="row">
        		<div class="col-md text-center">
        			<h4>Синонимы</h4>
        		</div>
        	</div>
	<div class="row">
		<div class="col-md-1">
    	</div>
    	<div class="col-md-6 mt-3">
    		<table class="table table-bordered">
    			<thead>
    			<tr>
    				<th>ID</th>
    				<th>Ключевые слова</th>
    				<th>Соответсвующие запчасти</th>
    				<th>Стоп слова</th>
    				<th>Удалить</th>
    			</tr>
    			</thead>
    			<tbody>
            		<?php foreach($syns as $syn): ?>
            		<tr>
            			<td><?=$syn['slang_id'] ?></td>
            			<td><span class="edit_area1" id="<?=$syn['slang_id'] ?>"><?=$syn['slang_name'] ?></span></td>
            			<td><span class="edit_area2" id="<?=$syn['orig_id'] ?>"><?=$syn['orig_name'] ?></span></td>
            			<td><span class="edit_area3" id="<?=$syn['orig_id'] ?>"><?=$syn['stop_words'] ?></span></td>
            			<td class="text-center trash-tree2"><a href="newSyn.php?action=delete&syn_id=<?=$syn['slang_id'] ?>"><i class="fa fa-trash"></i></a></td>
            		</tr>
            		<?php endforeach ?>
    			</tbody>
    		</table>
    	</div>
    	<div class="col-md-4">
    		<div class="row">
		<div class="col-md mt-2 mb-2">
		<label>Добавить новый элемент</label>
    		<form method="post" action="newSyn.php">
              <div class="form-row">
                <div class="col">
                  <textarea type="text" class="form-control" placeholder="Синоним - сленг" name="slang_name"></textarea>
                </div>
                <div class="col">
                  <textarea type="text" class="form-control" placeholder="Название запчасти" name="orig_name"></textarea>
                </div>
                <div class="col">
                  <textarea type="text" class="form-control" placeholder="Стоп слова" name="stop_words"></textarea>
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
   
    $('.edit_area1').editable('saveSyns.php', {
        type      : 'textarea',
        name      : 'keywords',
        submit    : 'OK',
        tooltip   : 'Кликни для редактирования...',
        rows    : 4,
        cols    : 20
    });
    $('.edit_area2').editable('saveSyns.php', {
        type      : 'textarea',
        name      : 'origwords',
        submit    : 'OK',
        tooltip   : 'Кликни для редактирования...',
        rows    : 4,
        cols    : 20
    });
    $('.edit_area3').editable('saveSyns.php', {
        type      : 'textarea',
        name      : 'stopwords',
        submit    : 'OK',
        tooltip   : 'Кликни для редактирования...',
        rows    : 4,
        cols    : 20,
        placeholder: 'Stop Words'
    });
});
</script>




