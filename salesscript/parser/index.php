<?php
include __DIR__ . '/../header.php';
//require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
//require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
//require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
require_once 'ParserClass.php';

if(isset($_GET['cat'])){

$cat = $_GET['cat'];
$obj = new ParserClass;
//Парсим запкиа
$data = [];
$zk = $obj->searchZapkia($cat);
$data[] = $zk;
//p($zk);

//Парсим ЛидерЗапавто
$lz = $obj->searchLider($cat);
$data[] = $lz;
//p($lz);

//Парсим Глобал

$g = $obj->searchGlobal($cat);
//p($g);
$data[] = $g;
//p($data);
}
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
      		<?php include_once __DIR__ . '/../left_short.php';?>
    	</div>
		<div class="col col-md-8">
				<div class="div_search">
				<h3>Проценка у конкурентов</h3>
					<table class="table table-striped table-hover search-table" >
						<thead>
							<tr>
        						<th>Название</th>
        						<th>Номер</th>
        						<th id="sup">Поставщик<small> <i class="fa fa-arrows-v" aria-hidden="true"></i></small></th>
        						<th id="price">Цена<small> <i class="fa fa-arrows-v" aria-hidden="true"></i></small></th>
    						</tr>
    					</thead>
    					<tbody id="search_res_table">
    					<?php foreach($data as $dat): ?>
    						<?php foreach($dat as $d): ?>
    						<tr>
    							<td style="width: 35%"><?=@$d[0] ?></td>
    							<td style="width: 15%"><?=@$d[1]?></td>
    							<td style="width: 35%"><?=@$d[3] ?></td>
    							<td style="width: 10%; font-weight: bold;"><?=@$d[2]?></td>
    						</tr>
    						<?php endforeach ?>
    					<?php endforeach ?>
    					</tbody>
					</table>
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
<script type="text/javascript" src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script>
	
	<script type="text/javascript">
	 var table = $('table');
	    
	    $('#price, #sup')
	        .wrapInner('<span title="sort this column"/>')
	        .each(function(){
	            
	            var th = $(this),
	                thIndex = th.index(),
	                inverse = false;
	            
	            th.click(function(){
	                
	                table.find('td').filter(function(){
	                    
	                    return $(this).index() === thIndex;
	                    
	                }).sortElements(function(a, b){
	                    
	                    return $.text([a]) > $.text([b]) ?
	                        inverse ? -1 : 1
	                        : inverse ? 1 : -1;
	                    
	                }, function(){
	                    
	                    // parentNode is the element we want to move
	                    return this.parentNode; 
	                    
	                });
	                
	                inverse = !inverse;
	                    
	            });
	                
	        });
	</script>

<?php include_once __DIR__ . '/../footer.php';?>