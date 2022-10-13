<?php

class GetPart extends MyDb
{

    // Таблица поставщиков
    public $table;

    public $brand_dict_table = 'brands_dict';

    public $ang_prices_all_table = 'ang_prices_all';

    // таблица словаря брендов
    // public $suppliers_table = 'ang_prices_all_backup';
    public $angara_table = 'angara';

    // Вес поставщика от которого идет выборка из таблицы
    public $weight = - 100;

    // Этот метод выдергивает запись по номру и бренду
    public function getParts($cat, $brand)
    {
        $m = $this->db();
        $q = "SELECT * FROM " . $this->table . " WHERE cat = ? AND brand = ?";
        $t = $m->prepare($q);
        $t->execute(array(
            $cat,
            $brand
        ));
        $data = $t->fetchAll(PDO::FETCH_ASSOC);

        $this->p($data);
    }

    // Ищем цену на ангаре по номеру и бренду
    public function getPartsPriceFromAngara($cat, $brand)
    {
        $m = $this->db();
        $q = "SELECT ang_name, price FROM " . $this->angara_table . " WHERE cat = ? AND brand = ?";
        $t = $m->prepare($q);
        $t->execute(array(
            $cat,
            $brand
        ));
        $data = $t->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getPrice($cat, $brand)
    {
        $m = $this->db1();
        $oem_number = $this->findBrandArr($cat, $brand);
        if ($oem_number != FALSE) {
            $q = "SELECT a.id, a.orig_number, a.oem_number, a.brand, a.price, a.supplier, a.stock, b.weight, b.name as sup_name FROM " . $this->table . " AS a
          LEFT JOIN ang_suppliers AS b
          ON a.supplier = b.id
          WHERE ((orig_number = :cat AND brand = :brand) OR (orig_number = :oem AND brand = :brand) OR (oem_number = :oem AND brand = :brand)) ORDER BY cast(a.price as unsigned)";
            $t = $m->prepare($q);
            $t->execute(array(
                ':cat' => $cat,
                ':oem' => $oem_number,
                ':brand' => $brand
            ));
            $data = $t->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $q = "SELECT a.id, a.orig_number, a.oem_number, a.brand, a.price, a.supplier, a.stock, b.weight, b.name as sup_name FROM " . $this->table . " AS a
          LEFT JOIN ang_suppliers AS b
          ON a.supplier = b.id
          WHERE ((orig_number = :cat AND brand = :brand) OR (oem_number = :cat AND brand = :brand)) ORDER BY cast(a.price as unsigned)";
            $t = $m->prepare($q);
            $t->execute(array(
                ':cat' => $cat,
                ':brand' => $brand
            ));
            $data = $t->fetchAll(PDO::FETCH_ASSOC);
        }

        if (! empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }

    private function getCross($cat)
    {
        $m = $this->db1();
        $q = 'SELECT DISTINCT (a.PartsDataSupplierArticleNumber) as partnumber,b.description FROM
              article_cross as a
              LEFT JOIN suppliers as b
              ON a.SupplierId = b.id
              WHERE a.OENbr = :cat';
        $t = $m->prepare($q);
        $t->execute(array(
            ':cat' => $cat
        ));
        $data = $t->fetchAll(PDO::FETCH_ASSOC);

        // $this -> p($data);
        return $data;
    }

    private function findBrandArr($cat, $brand)
    {
        $arr = $this->getCross($cat);
        $key = $this->recursive_array_search($brand, $arr);
        // $this -> p($arr[$key]);
        if (isset($arr[$key]['partnumber'])) {
            // $this->p($arr);
            return $arr[$key]['partnumber'];
        } else {
            return FALSE;
        }
    }

    private function recursive_array_search($needle, $haystack, $currentKey = '')
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $nextKey = $this->recursive_array_search($needle, $value, $currentKey . '[' . $key . ']');
                if ($nextKey) {
                    return $nextKey;
                }
            } elseif ($value == $needle) {

                // return is_numeric($key) ? $currentKey . '[' .$key . ']' : $currentKey . '["' .$key . '"]';
                return trim($currentKey, "[]");
            }
        }
        return false;
    }

    // Функция меняет левые названия брендов на нормальные в таблице поставщиков
    public function changeBrands($suppliers_table)
    {
        $time1 = microtime(true);

        $m = $this->db1();
        $q = 'SELECT brand, brand_name_2 FROM ' . $this->brand_dict_table;
        $t = $m->prepare($q);
        $t->execute(array());
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $value) {
            $m = $this->db1();
            $q = "UPDATE " . $suppliers_table . "
    SET brand = :brand_name WHERE brand = :brand_name_2";
            $t = $m->prepare($q);
            $t->execute(array(
                ':brand_name' => $value['brand'],
                ':brand_name_2' => $value['brand_name_2']
            ));
        }

        $time2 = microtime(true);
        echo 'script execution time: ' . ($time2 - $time1) / 60; // value in seconds
    }

    // Будем прогонять по словарю брендов все прайсы конкурентов
    public function changeBrandsTestDb($table)
    {
        $time1 = microtime(true);
        $m = $this->db1(); // ang_tecdoc
        $m2 = $this->db(); // ang_test
        $q = 'SELECT brand, brand_name_2 FROM ' . $this->brand_dict_table;
        $t = $m->prepare($q);
        $t->execute(array());
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $value) {
            $q = "UPDATE " . $table . "
    SET brand = :brand_name WHERE brand = :brand_name_2";
            $t2 = $m2->prepare($q);
            $t2->execute(array(
                ':brand_name' => $value['brand'],
                ':brand_name_2' => $value['brand_name_2']
            ));
        }

        $time2 = microtime(true);
        echo 'Таблица ' . $table . ' Обработана за ';
        echo ': ' . ($time2 - $time1) / 60; // value in seconds
    }

    public function changeCompsBrand()
    {
        $m = $this->db();
        $q = "SELECT * FROM parse_concurents WHERE enabled = 1 ORDER BY weight DESC";
        $t = $m->prepare($q);
        $t->execute(array());
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        foreach ($data as $comp) {
            try {
                $table = 'parse_' . $comp['name'];
                $this -> changeBrandsTestDb($table);
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }
        }
    }

    public function changeBrandsAngPricesAll($table)
    {
        $time1 = microtime(true);

        $m = $this->db1();
        $q = 'SELECT brand, brand_name_2 FROM ' . $this->brand_dict_table;
        $t = $m->prepare($q);
        $t->execute(array());
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $value) {
            $m = $this->db1($data[0], $data[1]);
            $q = "UPDATE " . $table . "
    SET brand = :brand_name WHERE brand = :brand_name_2";
            $t = $m->prepare($q);
            $t->execute(array(
                ':brand_name' => $value['brand'],
                ':brand_name_2' => $value['brand_name_2']
            ));
        }

        $time2 = microtime(true);
        echo 'script execution time: ' . ($time2 - $time1) / 60; // value in seconds
    }

    // Функция убирает пробел + KOREA из поля бранд таблицы поставщиков
    public function clearBrands($supplier_table)
    {
        $time1 = microtime(true);
        $m = $this->db1();
        $q = "UPDATE " . $supplier_table . " SET brand = REPLACE(brand, ' KOREA','');";
        $t = $m->prepare($q);
        $t->execute(array());

        $time2 = microtime(true);
        echo 'script execution time: ' . ($time2 - $time1) / 60; // value in seconds
    }

    // функция вычисления, среднего, медианы, разницы между самым большим и самым малым и число встречающееся болшее кол-во раз
    // Mean = The average of all the numbers
    // Median = The middle value after the numbers are sorted smallest to largest
    // Mode = The number that is in the array the most times
    // Range = The difference between the highest number and the lowest number
    public function mmmr($array, $output = 'mean')
    {
        if (! is_array($array) OR empty($array)) {
            return FALSE;
        } else {
            switch ($output) {
                case 'mean':
                    $count = count($array);
                    $sum = array_sum($array);
                    $total = $sum / $count;
                    break;
                case 'median':
                    rsort($array);
                    $middle = round(count($array) / 2);
                    $total = $array[$middle - 1];
                    break;
                case 'mode':
                    $v = array_count_values($array);
                    arsort($v);
                    foreach ($v as $k => $v) {
                        $total = $k;
                        break;
                    }
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

    // Выбираем цену позиции у конкурентов
    // выбираем конкуренов из таблицы конкурентов( пока такой таблицы нет, нужно создать)
    private function getComps()
    {
        $m = $this->db();
        $q = "SELECT * FROM parse_concurents WHERE enabled = 1 ORDER BY weight DESC";
        $t = $m->prepare($q);
        $t->execute(array());
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    private function getPriceComp($cat, $brand)
    {
        $new_data = array();
        $concs = [];
        $m = $this->db();

        foreach ($this->getComps() as $comp) {
            if (isset($comp['name'])) {
                $data = [];
                $table = 'parse_' . $comp['name'];
                $q = "SELECT price FROM " . $table . " WHERE cat LIKE ? AND brand LIKE ?";
                $t = $m->prepare($q);
                $t->execute(array(
                    '%' . $cat . '%',
                    '%' . $brand . '%'
                ));
                $data[] = $t->fetchAll(PDO::FETCH_ASSOC);
                // $this->p($data);
                $new_data['conc_name'] = $comp['name'];
                $new_data['conc_price'] = $data[0];
                $concs[] = $new_data;
            }
        }
        // $this->p($concs);
        return $concs;
    }

    // функия колбасит в цикле таблицу с брендом и номером , для поиска цены
    public function getPartsLoop($tbl, $limit = '', $order = '')
    {
        
        $m = $this->db();

        if ($order == '') {
            if ($limit == '') {
                $q = "SELECT id, ang_name, cat, brand, new_price FROM " . $tbl;
            } else {
                $q = "SELECT id, ang_name, cat, brand, new_price FROM " . $tbl . " LIMIT " . $limit;
            }
        } else {
            if ($limit == '') {
                $q = "SELECT id, ang_name, cat, brand, new_price FROM " . $tbl . " ORDER BY " . $order;
            } else {
                $q = "SELECT id, ang_name, cat, brand, new_price FROM " . $tbl . " ORDER BY " . $order . " LIMIT " . $limit;
            }
        }
        $t = $m->prepare($q);
        $t->execute();
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
        
        
    }
    
    public function makePrice($value){
        //$d = array();
        
            // $this -> p($value);

            $dt = $this->getPrice($value['cat'], $value['brand']);
            $ang = $this->getPartsPriceFromAngara($value['cat'], $value['brand']);
            $concs = $this->getPriceComp($value['cat'], $value['brand']);
            if ($dt == FALSE) {
                $dt = 'NO FROM SUPPLIERS';
            }
            // $this -> p($ang);
            //$value['data'] = $data;
            $value['data'] = $dt;
            $value['ang'] = $ang;
            $value['concs'] = $concs;
            $d[] = $value;
        
        return $d[0];
    }

    // Метод для выдергивания названия бренда из поля cat таблицы parse_zelezaka
    public function clearBrandsZelezaka()
    {
        $m = $this->db();
        $q = "SELECT id,cat FROM parse_zelezaka";
        $t = $m->prepare($q);
        $t->execute(array());
        $data = $t->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $d) {
            preg_match('#\|\s(\b[A-Z-]+\b)#', $d['cat'], $arr);
            if (isset($arr[1])) {
                $this->p($arr[1] . '--' . $d['id']);
                $quer = "UPDATE parse_zelezaka SET brand = ? WHERE id = ?";
                $t = $m->prepare($quer);
                $t->execute(array(
                    $arr[1],
                    $d['id']
                ));
            }
        }
    }

    // Вставляем новю цену в проценку ajax
    public function updateNewPrice($table, $id, $price)
    {
        $m = $this->db();
        $quer = "UPDATE " . $table . " SET new_price = ? WHERE id = ?";
        $t = $m->prepare($quer);
        $t->execute(array(
            $price,
            $id
        ));
    }

    // Вытягиваем новую цену на аяксе
    public function getNewPrice($table, $id)
    {
        $m = $this->db();
        $quer = "SELECT new_price FROM " . $table . " WHERE id = ?";
        $t = $m->prepare($quer);
        $t->execute(array(
            $id
        ));
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    // Убираем строки с пустым прайсом и пустыми номерами ориг и оем
    public function clearBrandsEmp($supplier_table)
    {
        $m = $this->db1();
        $q = "DELETE FROM " . $supplier_table . " WHERE (orig_number IS NULL OR orig_number = '') AND (oem_number IS NULL OR oem_number = '');";
        $t = $m->prepare($q);
        $t->execute(array());
        $r = $t->rowCount();
        echo $r . ' | ';
        $q2 = "DELETE FROM " . $supplier_table . " WHERE  price IS NULL OR price =''";
        $t = $m->prepare($q2);
        $t->execute(array());
        $rows = $t->rowCount();
        echo $rows;
    }

    // Выбираем массив для построения графика
    public function getSales($brand, $cat)
    {
        $m = $this->db();
        $q = "SELECT * FROM ang_date a
                LEFT JOIN ang_date_24 b
                ON a.id = b.id_ang_date
                WHERE a.brand = ? AND a.cat = ?";
        $t = $m->prepare($q);
        $t->execute(array(
            $brand,
            $cat
        ));
        $data = $t->fetchAll(PDO::FETCH_ASSOC);
        $dd = [];
        if (isset($data[0]) and ! empty($data[0])) {
            foreach ($data[0] as $k => $d) {
                if (! isset($d) or empty($d))
                    $d = 0;
                $dd[$k] = $d;
            }
            // $this -> p($dd);
            return $dd;
        } else {
            return $dd = array(
                'feb17' => 0,
                'mar17' => 0,
                'apr17' => 0,
                'may17' => 0,
                'jun17' => 0,
                'jul17' => 0,
                'aug17' => 0,
                'sept17' => 0,
                'oct17' => 0,
                'dec17' => 0,
                'jan18' => 0,
                'feb18' => 0,
                'mar18' => 0,
                'apr18' => 0,
                'may18' => 0,
                'jun18' => 0,
                'jul18' => 0,
                'jun18' => 0,
                'aug18' => 0,
                'sept18' => 0,
                'oct18' => 0,
                'nov18' => 0,
                'dec18' => 0,
                'jan19' => 0
            );
        }
    }
    
    
    public function priceUpdate($table, $id, $price)
    {
        $m = $this->db();
                $q = "UPDATE " . $table . " SET price = ? WHERE id = ?";
                $t = $m->prepare($q);
                $t->execute(array(
                    $price,
                    $id
                ));
                echo $id;
    }

    /*
     * CREATE TABLE cat_ang_all_cars_pricing_a AS
     * SELECT a.*,b.margin,b.shared_percent,b.variation FROM cat_ang_all_cars as a
     * INNER JOIN parse_abc_a as b
     * ON a.ang_name = b.name ORDER BY b.margin DESC;
     *
     *
     * SELECT * FROM `ang_prices_all_backup` WHERE (orig_number IS NULL OR orig_number = '') AND (price IS NULL OR price ="")
     *
     *
     * DELETE FROM `ang_prices_all_backup` WHERE (price IS NULL OR price ="")
     *
     * DELETE FROM `ang_prices_all_backup` WHERE (orig_number IS NULL OR orig_number = '') AND (oem_number IS NULL OR oem_number = '')
     */
}
