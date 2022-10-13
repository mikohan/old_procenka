<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require "lib/MyDb.php";
require "lib/GetPart.php";
if(isset($_GET['car'])){
  if(isset($_GET['limit'])){
    $limit = $_GET['limit'];
  }else{
    $limit = '';
}


$table = $_GET['car'];
$table = 'cat_' . $table;

$obj = new GetPart;
$obj -> table = "ang_prices_all_backup";
$d = $obj -> GetPartsLoop($table, $limit);
}else{
  die('Введите машину и лимит в урл через ?car=ducato&limit=100');
}
//$obj -> p($d[0]);
//$obj -> getPartsId("440");
//$obj -> table = "ang_prices_all_backup";
//$obj -> getPrice('77364862','FENOX');
//$obj -> changeBrands();








?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Hello, world!</title>
  </head>
  <body>



    <div class="container">

      <?php foreach($d as $key => $value) :?>
        <?php //$obj -> p($value)?>
      <div class="row ">
        <div class="col-12 border border-info p-3 vhead text-dark"><?=$value['ang_name']?>
        <p>Номер ( <?=$value['cat']?> ), Аналог(<?=$value['data'][0]['oem_number']?> ) Оригинал ( <?=$value['data'][0]['orig_number']?> )
        <p><h3 class="text-primary"><?=$value['brand']?><h3></div>
      </div>
      <div class="row">
        <div class="col-6 border border-info p-3 bg-light text-dark prices">
          <?php $arr = []; ?>
          <?php foreach($value['data'] as $key1 => $value1) :?>
                <div class="row">
                <div class="col">
                  <?=(int)$value1['price']?>
                  <?php $arr[] = (int)$value1['price'] ?>
                </div>
                <div class="col">
                  <?=$value1['sup_name']?>
                </div>
                </div>
          <?php endforeach ?>
        </div>
        <div class="col-6 border border-info p-3 bg-light text-dark">
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
        </div>

      </div>

    <?php endforeach ?>









    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  </body>
</html>
