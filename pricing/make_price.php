<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require "lib/MyDb.php";
require "lib/GetPart.php";
if (isset($_GET['car'])) {
    if (isset($_GET['limit'])) {
        $limit = $_GET['limit'];
    } else {
        $limit = '';
    }
    if(isset($_GET['order'])){
        $order = $_GET['order'];
    }else{
        $order = '';
    }

    $table = $_GET['car'];
    $table = 'cat_' . $table;

    $obj = new GetPart();
    $obj -> angara_table = $table;
    $obj->table = "ang_prices_all";
    $obj->weight = 25;
    $data = $obj->GetPartsLoop($table, $limit, $order);
    //$obj -> p($d);
    //$how_many_rows_affected = $dat[1];
    
    
} else {
    die('Введите машину и лимит в урл через ?car=ducato&limit=100');
}


// $obj -> p($d[0]);
// $obj -> getPartsId("440");
// $obj -> table = "ang_prices_all_backup";
// $obj -> getPrice('77364862','FENOX');



?>
<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="/favicon.png" type="image/x-icon" />
<!-- Bootstrap CSS -->
<link rel="stylesheet"
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
	integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
	crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="css/style.css">
<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="/js/jquery.min.js"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
		integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
		crossorigin="anonymous"></script>
	<script
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
		integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
		crossorigin="anonymous">
	</script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
<title>Работа с ценами</title>
</head>
<body>
	<div class="container">
	
	<div class="row">
		<div class="col-12">
			<div class="">
              <span>Приценилось  позиций</span>
            </div>
		</div>
	</div>
	<?php $ij = 0; ?>
      <?php foreach($data as $key => $da) :?>
      <?php $value = $obj -> makePrice($da) ?>
      <?php $sum = []; ?>
      <?php $arr = []; ?>
           
      
      <div class="row">
      <div class="col-12 bg-light text-dark mb-2">
        <?php //$obj -> p($value)?>
        
      <div class="row  vhead text-dark mb-2">
			<div class="col-12 p-3"><h4><?=$value['ang_name']?></h4>
			<?php if($value['data'] != 'NO FROM SUPPLIERS'): ?>
        <!-- <p>Номер ( <?=$value['cat']?> ), Аналог(<?=$value['data'][0]['oem_number']?> ) Оригинал ( <?=$value['data'][0]['orig_number']?> )</p>-->
        <?php else: ?>
                <div class="text-danger">
                  Нет у поставщика!
                </div>
        <?php endif ?>
				<h5 class="text-primary"><?=$value['brand']?> | <?=(isset($value['data'][0]['orig_number'])) ? $value['data'][0]['orig_number'] : $value['cat'] ?></h5>
			</div>
		</div>
		
<!--			<img src="/pricing/img/graphs/<?='00' . $value['cat'] . '_' . strtoupper($value['brand']) . '.png' ?>">-->
		
		<div> 
 			<body> 
                <div id="chart_div<?=$value['id'] ?>" style="width: 900px; height: 500px"></div> 
            </body>
		</div>
		<div class="row">
			<div class="col-6">
    			<?php if($value['data'] != 'NO FROM SUPPLIERS'): ?>
              	
              	<?php foreach($value['data'] as $key1 => $value1) :?>
                <div class="row vhead mb-1">
					<div class="col">
                      	<?=$value1['sup_name']?>
                    </div>
					<div class="col"><span class="text-primary">
                        <?=(int)$value1['price']?>
                        <?php $arr[] = (int)$value1['price'] ?>
                        </span>
                    </div>
                    <div class="col"><span class="text-primary">
                        <?php //$obj -> p($value1)?>
                        <?=$value1['stock'] ?>
                        </span>
                    </div>
				</div>
          <?php endforeach ?>
          <?php endif ?>
        </div>
			<div class="col-6">
			<?php if($value['data'] != 'NO FROM SUPPLIERS'): ?>
          <?php if(count($arr) <= 2):?>
          <div class="row">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">Мин</th>
								<th scope="col">Среднее</th>
								<th scope="col">Макс</th>
								<th scope="col">Медиана</th>
								<th scope="col">Разница</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?=min($arr);?></td>
								<td><?php  echo $obj -> mmmr($arr,'mean') . "<br>";?></td>
								<td><?=max($arr);?></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
        <?php else:?>
          <div class="row">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="col">Мин</th>
								<th scope="col">Среднее</th>
								<th scope="col">Макс</th>
								<th scope="col">Медиана</th>
								<th scope="col">Разница</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?=min($arr);?></td>
								<td><?php  echo $obj -> mmmr($arr,'mean') . "<br>";?></td>
								<td><?=max($arr);?></td>
								<td><?php  echo $obj -> mmmr($arr,'median') . "<br>";?></td>
								<td><?php  echo $obj -> mmmr($arr,'range') . "<br>";?></td>
							</tr>
						</tbody>
					</table>
				</div>
         	<?php endif ?>
         <?php endif ?>
        </div>
 		</div> <!--end big row -->
 		<div class="row">
 			<div class="col-12 expand-table">
 				<table class="table table-bordered">
						<thead>
							<tr class="vhead">
								<th scope="col">Цена Анг</th>
								<?php foreach ($value['concs'] as $concs):?>
								<th scope="col"><?=$concs['conc_name']?></th>
								<?php endforeach ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?=$value['ang'][0]['price']?></td>
								<?php
								
								    $i = 0;
								?>
								<?php foreach ($value['concs'] as $concs2):?>
								<?php 
								
								$i += count(array_filter($concs2['conc_price'], function($x) { return !empty($x); }));
								if(!empty($concs2['conc_price'])){
								$sum[] = str_replace(' ', '',$concs2['conc_price'][0]['price']);
								
								}
								
								
								 ?>
								<td><?php if(isset($concs2['conc_price'][0]['price'])){echo($concs2['conc_price'][0]['price']);}
								else{echo('');}?></td>
								<?php endforeach ?>
								<?php
								//Кодим главный ценообразователь
								
								//Переменная arr  это поставщики а sum это конкуренты
								//$obj -> p($sum);
								//$obj -> p($arr);
								
								if(!empty($sum) AND !empty($arr)){// Оба не пустые
								    $mean_conc = $obj -> mmmr($sum, 'mean');
								    $mean_supp = $obj -> mmmr($arr,'mean');
								    $dif = ($mean_conc - $mean_supp)/$mean_supp;
								    if($dif < 0.2){
								        $pri = $mean_supp + $mean_supp * 0.2;
								    }else{
								        $pri = $mean_conc;
								    }
								    
								}elseif(empty($arr) AND !empty($sum)){ //Пустой поставщик и не пустой конкурент
								   
								   $mean_conc = $obj -> mmmr($sum, 'mean');
								   $pri = $mean_conc;
								}elseif(!empty($arr) AND empty($sum)){ //Пустой конкурент и не пустой поставщик
								    
								    $mean_supp = $obj -> mmmr($arr,'mean');
								    $pri = $mean_supp + $mean_supp * 0.3;
								}elseif(empty($arr) AND empty($sum)){ // оба пустые
								    
								    $pri = 0;
								}
								    
								    echo '<div class="alert alert-success" role="alert">
                                          Рекомендуемая цена: <span class="font-weight-bold text-danger">' . $pri . '</span>
                                        </div>' ;
								    if(isset($pri) AND $pri !=0){
								        //Вставляем новую цену 
								        
								    $obj -> priceUpdate($table, $value['id'], $pri);
								    $ij++;
								    echo "Обработалось :" . $ij . " Позиций";
								    }
								    
								
								
								
								
								?>
							</tr>
						</tbody>
					</table>					
 			</div>
 		</div>
 		<div class="row">
 			<div class="col-6">
 				<form class="form-inline" method="post">
					<div class="form-group mt-2 mb-2">
                        <input id="inp<?=$value['id'] ?>" type="text" class="form-control price-send"  placeholder="Цена">
                        <button id="btn-<?=$value['id'] ?>" type="button" class="btn btn-outline-primary mr-2">Confirm</button>
                        <h6>Новая цена: <span id="resp<?=$value['id'] ?>" class=""></span></h6>
                    </div>
                    </form>
 			</div>
 			<div class="col-6">
 			<?php if(isset($value['new_price']) AND $value['new_price'] != 0): ?>
 				<?php if(isset($arr)):?>
 					<?php $min_zakup = min($arr); ?>
         				<table class="table table-bordered">
         					<thead>
         						<tr>
         							<th>Old</th>
         							<th>New</th>
         							<th>Diff</th>
         							<th>Diff</th>
         							<th>From min zak</th>
         						</tr> 						
         					</thead>
         					<tbody>
         						<tr>
         							<td class="text-danger"><?=$old = $value['ang'][0]['price']?></td>
         							<td class="font-weight-bold"><?=$new = $value['new_price'] ?></td>
         							<td><?=$dif = $value['new_price'] -  $value['ang'][0]['price']?></td>
         							<td><?=round($dif * 100 / $old,1)?>%</td>
         							<td><?=round($new * 100 / $min_zakup,1) - 100?>%</td>
         							
         						</tr>
         					</tbody>
         				</table>
         			<?php endif ?>
 				<?php endif ?>
 			</div>
 		</div>
 		</div>
 		</div>
 		
 		
 		<script type="text/javascript">

             $(document).ready(function() {
            
                $("#btn-"+ <?=$value['id'] ?>).click(function() {
                    var value = $('#inp' + <?=$value['id'] ?>).val();               
            		
                  $.ajax({    //create an ajax request to display.php
                    type: "POST",
                    url: "price_ajax.php",             
                    data: { price : value,
							id : <?=$value['id'] ?>,
							table : '<?=$table ?>'
                          },
                    success: function(response){                    
                        $("#resp" + <?=$value['id'] ?>).html(response);
                    }
            
                });
            });
            });
            
		</script>
 		
 		
 		
 		
 		
 		
    <?php
    
    $sum = [];
    endforeach; ?>
    <?= 'Проценилось : ' . $ij; ?>
    
    </div>
	
		
		
</body>
</html>
