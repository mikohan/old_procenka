<?php

class GetPart extends MyDb
{
    public $table;





    //Этот метод выдергивает запись по номру и бренду

    public function getParts($cat, $brand)
    {
        $m = $this -> db();
        $q = "SELECT * FROM " . $this -> table . " WHERE cat = ? AND brand = ?";
        $t = $m -> prepare($q);
        $t -> execute(array($cat,$brand));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);

        $this -> p($data);
    }
// функия колбасит в цикле таблицу с брендом и номером , для поиска цены


    public function getPartsLoop($tbl, $limit = '')
    {
        $d = array();
        $m = $this -> db();
        if($limit == ''){
        $q = "SELECT id, ang_name, cat, brand FROM " . $tbl;
      }else{
        $q = "SELECT id, ang_name, cat, brand FROM " . $tbl . " LIMIT " . $limit;
      }
        $t = $m -> prepare($q);
        $t -> execute();
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as $key => $value) {
          //$this -> p($value);

          $dt = $this -> getPrice($value['cat'], $value['brand']);
        if ($dt == FALSE){

          continue;
        }
        $i++;

        $value['data'] = $dt;
        $d[] = $value;



      }//endforeach
        echo $i;
        return $d;
    }



    // public function getPartsId($id)
    // {
    //     $m = $this -> db();
    //     $q = "SELECT * FROM " . $this -> table . " WHERE id = ?";
    //     $t = $m -> prepare($q);
    //     $t -> execute(array($id));
    //     $data = $t -> fetchAll(PDO::FETCH_ASSOC);
    //
    //     //$this -> p($data);
    // }
    public function getPrice($cat, $brand)
    {


        $m = $this -> db1();
        $oem_number = $this -> findBrandArr($cat, $brand);
        if ($oem_number != FALSE){
          $q = "SELECT a.id, a.orig_number, a.oem_number, a.brand, a.price, a.supplier, b.name as sup_name FROM " . $this -> table . " AS a
          LEFT JOIN ang_suppliers AS b
          ON a.supplier = b.id
          WHERE (orig_number = :cat AND brand = :brand) OR (orig_number = :oem AND brand = :brand) OR (oem_number = :oem AND brand = :brand) ORDER BY cast(a.price as unsigned)" ;
          $t = $m -> prepare($q);
          $t -> execute(array(':cat' => $cat, ':oem' => $oem_number, ':brand' => $brand));
          $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        }else{
          $q = "SELECT a.id, a.orig_number, a.oem_number, a.brand, a.price, a.supplier, b.name as sup_name FROM " . $this -> table . " AS a
          LEFT JOIN ang_suppliers AS b
          ON a.supplier = b.id
          WHERE (orig_number = :cat AND brand = :brand) OR (oem_number = :cat AND brand = :brand) ORDER BY cast(a.price as unsigned)" ;
          $t = $m -> prepare($q);
          $t -> execute(array(':cat' => $cat, ':brand' => $brand));
          $data = $t -> fetchAll(PDO::FETCH_ASSOC);

        }

        if (!empty($data)){
          return $data;
        } else{
          return FALSE;
        }
    }

    private function getCross($cat)
    {
        $m = $this -> db1();
        $q = 'SELECT DISTINCT (a.PartsDataSupplierArticleNumber) as partnumber,b.description FROM
              article_cross as a
              LEFT JOIN suppliers as b
              ON a.SupplierId = b.id
              WHERE a.OENbr = :cat';
        $t = $m -> prepare($q);
        $t -> execute(array(':cat'=>$cat));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);

        //$this -> p($data);
        return $data;
    }

    private function findBrandArr($cat, $brand)
    {
        $arr = $this -> getCross($cat);
        $key = $this -> recursive_array_search($brand, $arr);
        //$this -> p($arr[$key]);
        if (isset ($arr[$key]['partnumber'])) {
            return $arr[$key]['partnumber'];
        } else {
            return FALSE;
        }
    }






    private function recursive_array_search($needle, $haystack, $currentKey = '')
    {
        foreach ($haystack as $key=>$value) {
            if (is_array($value)) {
                $nextKey = $this -> recursive_array_search($needle, $value, $currentKey . '[' . $key . ']');
                if ($nextKey) {
                    return $nextKey;
                }
            } elseif ($value==$needle) {

        //return is_numeric($key) ? $currentKey . '[' .$key . ']' : $currentKey . '["' .$key . '"]';
                return trim($currentKey, "[]");
            }
        }
        return false;
    }

public function changeBrands(){
  $time1 = microtime(true);



  $m = $this -> db1();
  $q = 'SELECT brand, brand_name_2 FROM s_brands_final';
  $t = $m -> prepare($q);
  $t -> execute(array());
  $data = $t -> fetchAll(PDO::FETCH_ASSOC);
  foreach ($data as $value){
    $m = $this -> db1($data[0],$data[1]);
    $q = 'UPDATE ang_prices_all_backup
    SET brand = :brand_name WHERE brand = :brand_name_2';
    $t = $m -> prepare($q);
    $t -> execute(array(':brand_name' => $value['brand'], ':brand_name_2' => $value['brand_name_2']) );




  }

  $time2 = microtime(true);
  echo 'script execution time: ' . ($time2 - $time1)/60; //value in seconds

}




// функция вычисления, среднего, медианы, разницы между самым большим и самым малым и число встречающееся болшее кол-во раз
//Mean = The average of all the numbers
//Median = The middle value after the numbers are sorted smallest to largest
//Mode = The number that is in the array the most times
//Range = The difference between the highest number and the lowest number

public function mmmr($array, $output = 'mean'){
    if(!is_array($array)){
        return FALSE;
    }else{
        switch($output){
            case 'mean':
                $count = count($array);
                $sum = array_sum($array);
                $total = $sum / $count;
            break;
            case 'median':
                rsort($array);
                $middle = round(count($array) / 2);
                $total = $array[$middle-1];
            break;
            case 'mode':
                $v = array_count_values($array);
                arsort($v);
                foreach($v as $k => $v){$total = $k; break;}
            break;
            case 'range':
                sort($array);
                $sml = $array[0];
                rsort($array);
                $lrg = $array[0];
                $total = $lrg - $sml;
            break;
        }
        return round($total);
    }
}






























}
