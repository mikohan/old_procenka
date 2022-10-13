<?php
session_start();
if(!isset($_SESSION['name'])){
    header("location: login.php");
}
if($_SESSION['type'] == 'manager'){
    require 'tpl/manager_header.php';
}elseif($_SESSION['type'] == 'admin'){
    require 'tpl/header.php';
}
?>


<?php
//error_reporting(E_ALL); 
//ini_set("display_errors", 1);

require 'SearchClasses/SearchClass.php';
// Делаем выборку из общей таблицы прайсов

?>

    <h1>Здесь будет таблица проценки</h1>
    
    <div class="container-fluid">
      <div class="row">
        

        <main class="col-sm-12 ml-sm-auto col-md-12 pt-3" role="main">
          <?php
          
          $object = new SearchClass;
//$object->p($_GET);
$object->table_name = 'ang_prices_all';
//$object->table_nameSlow = 'ang_prices_all_no_beginning';
//$object->search = $_GET['search'];
if(isset($_GET['search'])){
        
    $search = trim($_GET['search']);
    $dataAngara = $object->getSearchAngara($search);
    $data = $object->getSearch($search);
    //p($data);
    //$data2 = $object->getSearchSlow($search);
    //$data = array_merge($data,$data2);
    $croses = $object->getCroses($search);
    foreach($croses as $ck=>$cv){
        if(!isset($cv['partnumber'])){
             continue;
        }
        $data3[] = $object->getSearchCross($cv['partnumber']);
        //p($cv);
    }
    //p($data3)
    if(isset($data3)){
    foreach($data3 as $d3k=>$d3v){
        $data = array_merge($data, $data3[$d3k]);
    }
    }
    
    foreach($data as $keystrip => $valuestrip){
        if(isset($valuestrip['price'])){
        $valuestrip['price'] = str_replace("'", "", $valuestrip['price']);
        }
        if(isset($data[$keystrip]['price'])){
        $data[$keystrip]['price'] = $valuestrip['price'];
        //p($data[$keystrip]);
        }
    }
//     usort($data, function($a, $b) {
//     //return (strtotime($a['price_load_date']) < strtotime($b['price_load_date'])?1:-1);
//     //return ($a['price'] - $b['price']);
//       return $a['weight'] < $b['weight']?1:-1;
// });
 
//Сортирую массив по весу поставщика и по цене
    foreach ($data as $key => $row) {
        @$price[$key]  = $row['price'];
        @$weigt[$key] = $row['weight'];
    }
    array_multisort($weigt, SORT_DESC, $price, SORT_ASC, $data);
    
    
//p($croses);
}else{
    $data = [];
}

$microtime_key = key($data);
if(isset($data['microtime'])){
    $microtime = $data['microtime'];
}else{
    $microtime = 0;
}
$suppliers = $object->getAllSuppliers();
$rows = $object->getAllRows();
          
          ?>
          <div class="row">
              <div class="col-md-12">
                  <span class="badge badge-info badge-margin">В базе - <?=$suppliers?> поставщиков </span><span class="badge badge-info"> <?=number_format($rows)?> позиций прайса</span>
              </div>
          </div>
          <div class="row big-form">
              <div class="col-lg-2 col-md-2 col-sm-2">
                  <?php if(!empty($dataAngara[0]['microtime'])):?>
                <h6><small class="blue">Запрос Ангара</small> <span class="badge badge-success"><?=$dataAngara[0]['microtime'];?> сек</span></h6>
                    <?php endif;?>
                    <?php if(!empty($microtime)):?>
                <h6><small class="blue">Запрос Постав</small> <span class="badge badge-success"><?=$microtime;?> сек</span></h6>
                <?php endif;?> 
              </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                <form class="well" method="get" action = "">
                    <div class="input-group">
                        <input name="search" type="text" class="form-control input-lg" id="pwd" placeholder="Поиск по номеру, названию запчасти..." value="<?php if(!empty($_GET['search'])): echo $_GET['search']; endif; ?>">
                        <span class="input-group-btn">
                            <button  type="submit" class="btn btn-outline-primary input-lg">
                                ПОИСК
                            </button> </span>
                    </div>
                    <!-- <span class="help-block">Введите номер запчасти или название...</span> -->
                </form>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                  
              </div>
            </div><!-- end row -->
          
          <?php if(!empty($dataAngara[1])):?>
          
          <div class="table-responsive">
            <table class="table table-striped angara-table-striped small-text">
              <thead>
                <tr>
                  <th>Номер оригинал</th>
                  <th>Номер аналог</th>
                  <th>Название</th>
                  <th>Цена</th>
                  <th>Наличие</th>
                  <th>Бренд</th>
                  <th>Поставщик</th>
                  <th>Доп информация</th>
                  <th>Дата обновления</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($dataAngara as $kAngara=>$datAngara):?>
                      <?php if($kAngara == 0){
                          continue; 
                      }?>
                <tr>
                  <td style="width: 10%" class="bold"><?=$datAngara['cat']?></td>
                  <td style="width: 10%"></td>
                  <td style="width: 30%"><?=$datAngara['ang_name']?></td>
                  <td style="width: 5%" class="bold green"><?=$datAngara['price']?></td>
                  <td style="width: 5%"><?=$datAngara['nal']?></td>
                  <td style="width: 10%"></td>
                  <td style="width: 10%">Angara</td>
                  <td style="width: 15%"></td>
                  <td style="width: 5%"></td>
                </tr>
                
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
          <?php else:?>
              <span class="badge badge-danger">Нет на складе Ангара</span>
          <?php endif; ?>
          
          <div>У поставщиков</div>
          
          <div class="table-responsive">
            <table class="table table-striped small-text sup-table">
              <thead>
                <tr>
                  <th>Номер оригинал</th>
                  <th>Номер аналог</th>
                  <th style="whidth: 400px;">Название</th>
                  <th id = "facility_header">Цена</th>
                  <th>Наличие</th>
                  <th id="city_header">Бренд</th>
                  <th>Поставщик</th>
                  <th>Доп информация</th>
                  <th>Дата обновления</th>
                </tr>
              </thead>
              <tbody  id="sorted-table">
                  <?php foreach($data as $k=>$dat):?>
                      <?php if(!isset($dat['orig_number']) AND !isset($dat['oem_number'])){
                          continue; 
                      }?>
                <tr>
                  <td style="width: 10%" class="bold"><?=$dat['orig_number']?></td>
                  <td style="width: 10%;"><?=$dat['oem_number']?></td>
                  <td style="width: 30%"><?=$dat['name']?></td>
                  <td style="width: 5%" class="green bold"><?=$dat['price']?></td>
                  <td style="width: 5%"><?=$dat['stock']?></td>
                  <td style="width: 10%" class="brand"><?=$dat['brand']?></td>
                  <td style="width: 10%"><?=$dat['supplier_name']?></td>
                  <td style="width: 15%"><?=$dat['notes']?></td>
                  <td style="width: 5%"><?=date('d-m-Y',strtotime($dat['price_load_date']))?></td>
                </tr>
                
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="js/jquery.accordion.js"></script>
	<script src="js/jquery.cookie.js"></script>
	<script type="text/javascript" src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script>
	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tinysort/3.2.2/jquery.tinysort.js"></script> -->
	
	<script type="text/javascript">
	 var table = $('table');
	    
	    $('#facility_header, #city_header')
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
	
<?php require 'tpl/footer.php';?>