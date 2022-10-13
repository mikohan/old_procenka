<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ob_start();
 require '../tpl/header.php';?>
 <div class="spacer-60"></div>
    <div class="container-fluid">
      <div class="row">
        
<?php require '../tpl/left.php';?>
        <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
          <!-- <h1>Dashboard</h1> -->
          <!-- <h2>Добавление поставщика</h2> -->
          
          <?php
          

 
 $sup = new Suppliers;
 $sup->getSuppliers();
 $sup->form = $_GET;
 $fields = $sup->checkSupplierPriceFields($_GET['supplier_id'], 'ang_prices_all');
 $fields_like = $sup->checkSupplierPriceFields($_GET['supplier_id'], 'ang_prices_all_no_beginning');
 
 //p($fields_like);
 // конвертим в буквы
 
 // нужно конвертить обратно в вставке
 if(isset($_GET['price_orig_number'])){
    $_GET['price_orig_number'] = $sup->converter($_GET['price_orig_number']);
     $_GET['price_oem_number'] = $sup->converter($_GET['price_oem_number']);
     $_GET['price_brand'] = $sup->converter($_GET['price_brand']);
     $_GET['price_name'] = $sup->converter($_GET['price_name']);
     $_GET['price_stock'] = $sup->converter($_GET['price_stock']);
     $_GET['price_price'] = $sup->converter($_GET['price_price']);
     $_GET['price_notes'] = $sup->converter($_GET['price_notes']);
     $_GET['price_kratnost'] = $sup->converter($_GET['price_kratnost']);
     }   
// $sup->p($_GET);
 
 if(isset($_GET['supplier_id'])){
     @$sup_id = $sup->getSupplier($_GET['supplier_id'])[0];
    // p($sup_id);
 }
 if(@$_GET['action'] == 'update'){
     $sup->form = $_GET;
        $sup->UpdateSupplier($_GET['supplier_id']);
        $sup->makeDir($sup->form['supplier_folder']);
        header('Location: form.php?supplier_id='. $_GET['supplier_id']); 
 }

      
          ?>
          <div class="container-fluid">
<div class="row">
    <div class="col-md-12">
          
          
                <h2>Работа с поставщиками</h2>
                    
                <form role="form" class="form-horizontal supplier-form" name="suppliers" method="get" action="">    
                <fieldset>
               <?php if(isset($sup_id['name'])):?>
               <legend>Поставщик: <span class="supplier"> <?=@$sup_id['name'] ?></span> ID = <span class="supplier"><?=  @$sup_id['id']?></span></legend>
               <?php else: ?>
                   <legend>Добавление нового поставщика</legend>
               <?php endif?>
               <input type="hidden" name="supplier_id" value="<?php if(isset($_GET['supplier_id'])): echo $_GET['supplier_id']; else: echo 0; endif;?>"> 
                
                <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="textinput">Название поставщика</label>  
              <div class="col-md-4">
              <input  name="supplier_name" type="text"  placeholder="название поставщика" class="form-control input-md" required="" <?php if(isset($sup_id['name'])):?> value="<?=@$sup_id['name']?>" <?php else: ?>value="<?=@$_GET['supplier_name']?>" <?php endif?> >
              </div>
            
              <label class="col-md-4 control-label" >Тема письма поставщика</label>  
              <div class="col-md-4">
              <input id="email" name="supplier_email" type="text"  placeholder="Тема письма поставщика" class="form-control input-md" <?php if(isset($sup_id['email'])):?> value="<?=$sup_id['email']?>" <?php elseif(isset($_GET['supplier_email'])): ?>value="<?=$_GET['supplier_email']?>" <?php else:?> value="" <?php endif?> >
              </div>
              <label class="col-md-4 control-label" >Емейл поставщика</label>  
              <div class="col-md-4">
              <input id="email" name="supplier_email2" type="text"  placeholder="Email" class="form-control input-md" <?php if(isset($sup_id['email2'])):?> value="<?=$sup_id['email2']?>" <?php elseif(isset($_GET['supplier_email2'])): ?>value="<?=$_GET['supplier_email2']?>" <?php else:?> value="" <?php endif?> >
              </div>
              <label class="col-md-4 control-label" >Папка для хранения прайсов</label>  
              <div class="col-md-4">
              <input id="address" name="supplier_folder" type="text"  placeholder="Папка для прайса" class="form-control input-md" <?php if(isset($sup_id['folder'])):?> value="<?=$sup_id['folder']?>" <?php elseif(isset($_GET['supplier_folder'])): ?>value="<?=$_GET['supplier_folder']?>" <?php else:?> value="" <?php endif?> >
              </div>
              <label class="col-md-4 control-label" >Имя файла выгрузки</label>  
              <div class="col-md-4">
              <input id="address" name="supplier_file1" type="text"  placeholder="Файл 1 прайса" class="form-control input-md" <?php if(isset($sup_id['supplier_file1'])):?> value="<?=$sup_id['supplier_file1']?>" <?php elseif(isset($_GET['supplier_file1'])): ?>value="<?=$_GET['supplier_file1']?>" <?php else:?> value="" <?php endif?> >
              </div>
              <label class="col-md-4 control-label" >Разделитель CSV файла</label>  
              <div class="col-md-4">
              <input  name="supplier_delimeter" type="text"  placeholder="Разделитель" class="form-control input-md" <?php if(isset($sup_id['delimeter'])):?> value="<?=$sup_id['delimeter']?>" <?php elseif(isset($_GET['supplier_delimeter'])): ?>value="<?=$_GET['supplier_delimeter']?>" <?php else:?> value="" <?php endif?> >
              </div>
              <label class="col-md-4 control-label" >Пустые поля CSV файла</label>  
              <div class="col-md-4">
              <input  name="supplier_empty_fields" type="text"  placeholder="Пустые поля" class="form-control input-md" <?php if(isset($sup_id['empty_fields'])):?> value="<?=$sup_id['empty_fields']?>" <?php elseif(isset($_GET['supplier_empty_fields'])): ?>value="<?=$_GET['supplier_empty_fields']?>" <?php else:?> value="" <?php endif?> >
              </div>
              <label class="col-md-4 control-label" >Номер в таблице</label>  
              <div class="col-md-4">
              <select class="form-control" name="supplier_price_table">
              <option value='ang_prices_all'>Номер в начале</option>
              
              <option value='ang_prices_all_no_beginning' <?php if($sup_id['price_table'] == 'ang_prices_all_no_beginning'):?> <?='selected'?> <?php endif ?>>Номер не сначала</option>
              
              </select>
              </div>
              <label class="col-md-4 control-label" for="period_price_days">Период обновления прайса дней</label>
              <div class="col-md-1">
                  <input id="period_price_days" name="period_price_days" type="text"  placeholder="Период обновления прайса" class="form-control input-md" required="" <?php if(isset($sup_id['period_price_days'])):?> value="<?=@$sup_id['period_price_days']?>" <?php else: ?>value="<?=@$_GET['period_price_days']?>" <?php endif?> >
              </div>
              <label class="col-md-4 control-label" for="delivery_days">Срок доставки до нас</label>
              <div class="col-md-1">
                  <input id="supplier_delivery_days" name="supplier_delivery_days" type="text"  placeholder="Период обновления прайса" class="form-control input-md" required="" <?php if(isset($sup_id['delivery_days'])):?> value="<?=@$sup_id['delivery_days']?>" <?php else: ?>value="<?=@$_GET['supplier_delivery_days']?>" <?php endif?>  >
              </div>
              <label class="col-md-4 control-label" for="delivery_days">Вес поставщика 0-100</label>
              <div class="col-md-2">
                  <input id="supplier_weight" name="supplier_weight" type="text"  placeholder="Вес поставщика" class="form-control input-md" required="" <?php if(isset($sup_id['weight'])):?> value="<?=@$sup_id['weight']?>" <?php else: ?>value="<?=@$_GET['supplier_weight']?>" <?php endif?>  >
              </div>
              <label class="col-md-4 control-label" for="address">Примечание</label>  
              <div class="col-md-4">
              <textarea name="supplier_note" type="text" placeholder="Введите дополнительную информацию о поставщике" class="form-control input-md" rows="4"><?php if(isset($sup_id['note'])):?><?=trim($sup_id['note'])?><?php
               elseif(isset($_GET['supplier_note'])): echo $_GET['supplier_note']; endif?></textarea>
              </div>
            
              <label class="col-md-4 control-label" >Адрес</label>  
              <div class="col-md-4">
              <input id="address" name="supplier_address" type="text"  placeholder="Адрес поставщика" class="form-control input-md" <?php if(isset($sup_id['address'])):?> value="<?=$sup_id['address']?>" <?php elseif(isset($_GET['supplier_address'])): ?>value="<?= $_GET['supplier_address']?>" <?php endif?> >
              </div>
            </div>
            <label class="col-md-4 control-label" for="delivery_days">Активен 1 Неактивен 0</label>
              <div class="col-md-2">
                  <input id="supplier_enabled" name="supplier_enabled" type="number"  placeholder="Активен" class="form-control input-md" required="" <?php if(isset($sup_id['enabled'])):?> value="<?=@$sup_id['enabled']?>" <?php else: ?>value="<?=@$_GET['supplier_enabled']?>" <?php endif?>  >
              </div>
            </fieldset>
            <h2>Назначение полей прайслиста поставщика</h2>
            
                <fieldset>
                        <table class="table table-responsive table-bordered">
                            <tr>
                                <th>Оригинальный номер</th>
                                <th>Аналог номер</th>
                                <th>Бренд</th>
                                <th>Название запчасти</th>
                                <th>Сколько в наличии</th>
                                <th>Цена</th>
                                <th>Кратность</th>
                                <th>Примечание</th>
                            </tr>
                            <tr>
                                
                                <td><input class="form-control input-md  field" type="text" name="price_orig_number" <?php if(isset($sup_id['price_orig_number'])):?> value="<?=$sup_id['price_orig_number']?>" <?php elseif(isset($_GET['price_orig_number'])): ?>value="<?= $_GET['price_orig_number']?>" <?php endif?> ></td>
                                <td><input class="form-control input-md  field" type="text" name="price_oem_number" <?php if(isset($sup_id['price_oem_number'])):?> value="<?=$sup_id['price_oem_number']?>" <?php elseif(isset($_GET['price_oem_number'])): ?>value="<?= $_GET['price_oem_number']?>" <?php endif?> ></td>
                                <td><input class="form-control input-md  field" type="text" name="price_brand" <?php if(isset($sup_id['price_brand'])):?> value="<?=$sup_id['price_brand']?>" <?php elseif(isset($_GET['price_brand'])): ?>value="<?= $_GET['price_brand']?>" <?php endif?> ></td>
                                <td><input class="form-control input-md  field" type="text" name="price_name" <?php if(isset($sup_id['price_name'])):?> value="<?=$sup_id['price_name']?>" <?php elseif(isset($_GET['price_name'])): ?>value="<?= $_GET['price_name']?>" <?php endif?> ></td>
                                <td><input class="form-control input-md  field" type="text" name="price_stock" <?php if(isset($sup_id['price_stock'])):?> value="<?=$sup_id['price_stock']?>" <?php elseif(isset($_GET['price_stock'])): ?>value="<?= $_GET['price_stock']?>" <?php endif?> ></td>
                                <td><input class="form-control input-md  field" type="text" name="price_price" <?php if(isset($sup_id['price_price'])):?> value="<?=$sup_id['price_price']?>" <?php elseif(isset($_GET['price_price'])): ?>value="<?= $_GET['price_price']?>" <?php endif?> ></td>
                                    <td><input class="form-control input-md  field" type="text" name="price_kratnost" <?php if(isset($sup_id['price_kratnost'])):?> value="<?=$sup_id['price_kratnost']?>" <?php elseif(isset($_GET['price_kratnost'])): ?>value="<?= $_GET['price_kratnost']?>" <?php endif?> ></td>
                                <td><input class="form-control input-md  field" type="text" name="price_notes" <?php if(isset($sup_id['price_notes'])):?> value="<?=$sup_id['price_notes']?>" <?php elseif(isset($_GET['price_notes'])): ?>value="<?= $_GET['price_notes']?>" <?php endif?> ></td>
                                <input type="hidden" name="" value="">
                            </tr>
                        </table>
                       
                              
                               

                              
                            
                        <!-- Кнопка -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="">Сохранить изменения</label>  
                                <div class="col-md-4">
                                    <button class="btn btn-outline-primary" type="submit" name="action" value="update">Сохранить поставщика</button>
                                </div>
                        </div>
            </fieldset>   
        </form>
              
               
               
            
            
    </div>
</div>

<?php if(!empty($fields)):?>
    <hr>
<div class="row">
    
    <h3>Проверка соответствия полей прайса <?=@$sup_id['name'] ?></h3>
    <table class="table table-responsive table-bordered">
        <thead class="thead-inverse">
        <tr>
            <?php foreach($fields[0] as $fieldsk=>$fieldsv): ?>
            <th><?=$fieldsk?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <?php foreach($fields as $fieldsk=>$fieldsv): ?>
            <tr>
            
            <?php
                foreach($fieldsv as $v):?>
                <td><?=$v?></td>
                <?php endforeach ?>
                </tr>
            <?php endforeach ?>
    </table>
    
</div>

<?php elseif(!empty($fields_like)):?>
    <hr>
    
<div class="row">
    
    <h3>Проверка соответствия полей прайса <?=@$sup_id['name'] ?></h3>
    <table class="table table-responsive table-bordered">
        <thead class="thead-inverse">
        <tr>
            <?php foreach($fields_like[0] as $fieldsk=>$fieldsv): ?>
            <th><?=$fieldsk?></th>
            <?php endforeach ?>
        </tr>
        </thead>
        <?php foreach($fields_like as $fieldsk=>$fieldsv): ?>
            <tr>
            
            <?php
                foreach($fieldsv as $v):?>
                <td><?=$v?></td>
                <?php endforeach ?>
                </tr>
            <?php endforeach ?>
    </table>
    
</div>
<?php endif ?>
</div>
          
          
          
        </main>
      </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery.accordion.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    <script>
        $('input.fields').val (function () {
        return this.value.toUpperCase();
        })
    </script>
    
<?php require '../tpl/footer.php';?>