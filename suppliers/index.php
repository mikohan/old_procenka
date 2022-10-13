<?php
ob_start();
require '../tpl/header.php';?>
    <div class="spacer-60"></div>
    
    <div class="container-fluid">
      <div class="row">
        
<?php require '../tpl/left.php';?>
        <main class="col-sm-10 ml-sm-auto col-md-11 pt-3" role="main">
          <!-- <h1>Dashboard</h1> -->
          <a href="form.php" title="Добавить нового поставщика"><i class="fa fa-user-plus" aria-hidden="true"></i></a>
          
          <?php
 $sup = new Suppliers;
 $data = $sup->getSuppliers();
 //p($data);
 
 if(@$_GET['action'] == 'delete'){
     $sup->supplierDelete($_GET['supplier_id']);
     header('Location: index.php');
 }
 if(@$_GET['action'] == 'disable'){
     echo "im in disable";
     $sup->supplierDisable($_GET['supplier_id']);
     header('Location: index.php');
 }
 
          
          ?>
<h6 class="badge badge-info badge-margin float-right"><?=$data[0]['microtime']?></h6>
    <div class="table-responsive">
            <table class="table table-striped supplier-table">
              <thead>
                <tr>
                  <th>Id поставщика</th>
                  <th>Название поставщика</th>
                  <th>Папка с прайсом</th>
                  <th>Адрес</th>
                  <th>Период обновления прайса</th>
                  <th>Вес поставщика</th>
                  <th>Последний емайл получен</th>
                  <th>Примечание</th>
                  <th>Прайс загружен</th>
                  <th>Просрочен прайс</th>
                  <th>Позиций в прайсе</th>
                  <th>Получить почту вручную</th>                  
                  <th>Выгрузить прайс вручную</th>
                  <th>Редактировать</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($data as $kAngara=>$dat):?>
                      <?php if($kAngara == 0){
                          continue; 
                      }?>
                <tr>
                    <!-- Здесь сравниваем даты прайсов и период обновления этих прайсов по каждому поставщику -->
                    <?php $priceDays = $sup->checkSupplierPriceLoadDays($dat['id'])[0]['period_price_days']; 
                    $datePrice = date('d-m-Y H:i', strtotime(@$dat['price_load_date']));
                    $dateCheck = new DateTime($datePrice);
                    $dateCheck->modify('+'.$priceDays .' days');
                    //echo $dateCheck->format('d-m-Y');
                    //echo $priceDays;
                    $now = date('d-m-Y');
                    
                    
                    
                    ?>
                  <td class="small-text"><?=$dat['id']?></td>
                  <td class="small-text supplier-name"><a href="/suppliers/form.php?supplier_id=<?=$dat['id']?>"><?=$dat['name']?></a></td>
                  <td class="small-text"><?=$dat['folder']?></td>
                  <td class="small-text"><?=$dat['address']?></td>
                  <td class="small-text text-center"><?=@$dat['period_price_days']?></td>
                  <td class="small-text text-center"><?=$dat['weight']?></td>
                  <?php
                  //echo $dat['delivery_days'];
                  $check2 = strtotime($dat['email_date'] . '+ ' . $dat["period_price_days"] . ' days');
                  
                  if($check2 < strtotime($now)):
                  ?>
                  <td class="small-text  bold"><a class="red" href="/suppliers/emailprice.php?action=get_email&supplier_id=<?=$dat['id']?>"><?=$dat['email_date']?></a></td>
                  <?php else:?>
                   <td class="small-text bold"><a class="green" href="/suppliers/emailprice.php?action=get_email&supplier_id=<?=$dat['id']?>"><?=$dat['email_date']?></td>
                   <?php endif ?>   
                  <td class="small-text"><?=$dat['note']?></a></td>
                  <?php
                        $d = date('d-m-Y H:i', strtotime(@$dat['price_load_date']));
                   ?>
                  <td class="small-text  text-center"><?php echo $d;?></td>
                  <td class="small-text red  text-center"><?php
                  //echo strtotime($dateCheck->format('d-m-Y')); 
                  //echo $now; $dateCheck->format('d-m-Y')
                  if(strtotime($dateCheck->format('d-m-Y')) < strtotime($now)){
                      $due = (strtotime($now) - strtotime($dateCheck->format('d-m-Y')))/86400;
                        echo $due . ' дней просрочен';
                  }else{ echo '<span class="green">ok</span>';
                  }
                        
                     ?></td>
                  
                  <td class="text-center small-text"><?php 
                  //Медленно работает
                      echo $dat['price_rows'];
                      ?></td>
                      
                  <td class=" text-center"><a href="/suppliers/emailprice.php?action=get_email&supplier_id=<?=$dat['id']?>"><i class="fa fa-envelope" aria-hidden="true"></i></a></td>
                  <td class=" text-center"><a href="/insertClass/secondinsert.php?action=insert_price&supplier_id=<?=$dat['id']?>"><i class="fa fa-upload" aria-hidden="true"></i></a></td>
                  <td  class="text-center"><span><a href="/suppliers/form.php?supplier_id=<?=$dat['id']?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></span> <span>&nbsp;&nbsp;</span> <span class="delete"><a href="?action=delete&supplier_id=<?=$dat['id']?>" onclick="return confirm('Удалить поставщика?')"><i class="fa fa-trash-o red" aria-hidden="true"></i></a></span> <span class="delete"><a href="?action=disable&supplier_id=<?=$dat['id']?>" onclick="return confirm('Деактивировать поставщика?')"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a></span></td>
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
    <script src="/js/jquery.accordion.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    
<?php require '../tpl/footer.php';?>