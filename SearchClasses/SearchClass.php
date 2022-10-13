<?php
class SearchClass extends Conn {
    
    public $price_table;
    public $search;
    
    private function getSuppliersDisabled() {
        $m = $this -> db();
        $q = "SELECT id FROM ang_suppliers WHERE enabled_search = '0';";
        $t = $m -> prepare($q);
        $t -> execute();
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        if(isset($data[0]['id'])){
            $arr = [];
            foreach($data as $d){
               $arr[] = $d['id']; 
            }
                return($arr);
        }
        
    }
//Функция для поиска по номеру
    public function getSearch($search) {
        $starttime = microtime(true);
        $m = $this -> db();
        $supps_zerro = $this->getSuppliersDisabled();
        $q = 'SELECT a.*, b.id, b.name as supplier_name, b.weight, c.price_load_date FROM '. $this->table_name .' as a
        LEFT JOIN ang_suppliers as b
        ON a.supplier = b.id
        LEFT JOIN ang_suppliers_load_date as c
        ON a.supplier =  c.supplier_id
        WHERE (a.orig_number LIKE :search OR a.oem_number LIKE :search) AND a.supplier NOT IN (' . implode(",", $supps_zerro) . ') ORDER BY a.price LIMIT 400';
        $t = $m -> prepare($q);
        $t -> execute(array(':search'=> $search . '%'));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        $data['microtime'] = round($duration,4);
        //p($data);
        return $data;
        
    }
    
     public function getSearchSlow($search) {
        $starttime = microtime(true);
        $supps_zerro = $this->getSuppliersDisabled();
        
        $m = $this -> db();
        $q = 'SELECT a.*, b.id, b.name as supplier_name, c.price_load_date FROM '. $this->table_name .' as a
        LEFT JOIN ang_suppliers as b
        ON a.supplier = b.id
        LEFT JOIN ang_suppliers_load_date as c
        ON a.supplier =  c.supplier_id
        WHERE (a.orig_number LIKE :search OR a.oem_number LIKE :search OR a.name LIKE :search2 ) AND a.supplier NOT IN (' . implode(",", $supps_zerro) . ') ORDER BY c.price_load_date DESC LIMIT 400';
        $t = $m -> prepare($q);
        $t -> execute(array(':search'=>'%' . $search . '%',':search2'=>'%' . $search . '%'));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        //array_unshift($data, ['microtime' =>round($duration,4)]);
        //p($data);
        return $data;
        
    }
     
     public function getSearchCross($search) {
        $starttime = microtime(true);
        $supps_zerro = $this->getSuppliersDisabled();
        $m = $this -> db();
        $q = 'SELECT a.*, b.id, b.name as supplier_name, c.price_load_date FROM '. $this->table_name .' as a
        LEFT JOIN ang_suppliers as b
        ON a.supplier = b.id
        LEFT JOIN ang_suppliers_load_date as c
        ON a.supplier =  c.supplier_id
        WHERE (a.orig_number = :search OR a.oem_number = :search ) AND a.supplier NOT IN (' . implode(",", $supps_zerro) . ') ORDER BY a.price LIMIT 400' ;
        $t = $m -> prepare($q);
        $t -> execute(array(':search'=>$search,':search2'=> $search));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        //echo $duration;
        //echo '<br>';
        //array_unshift($data, ['microtime' =>round($duration,4)]);
        //p($data);
        return $data;
        
    }
     
     
      public function getCroses($search) {
        $starttime = microtime(true);
        $m = $this -> db();
        $q = 'SELECT DISTINCT (PartsDataSupplierArticleNumber) as partnumber FROM article_cross WHERE OENbr = :search';
        $t = $m -> prepare($q);
        $t -> execute(array(':search'=>$search ));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        array_unshift($data, ['microtime' =>round($duration,4)]);
        //p($data);
        return $data;
        
    }
    
    
    public function getSearchAngara($search){
         $starttime = microtime(true);
        $m = $this -> db();
        $q = 'SELECT * FROM angara  
        WHERE cat LIKE :orig_number OR ang_name LIKE :oem_number';
        $t = $m -> prepare($q);
        $t -> execute(array(':orig_number'=>'%' . $search . '%', ':oem_number'=>'%' . $search . '%'));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        array_unshift($data, ['microtime' =>round($duration,4)]);
        //p($data);
        return $data;
    }


public function getAllSuppliers(){
         
        $m = $this -> db();
        $q = 'SELECT id FROM ang_suppliers';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> rowCount();        
        //p($data);
        return $data;
    }
public function getAllRows(){
         
        $m = $this -> db();
        $q = 'SELECT count(id) FROM `ang_prices_all`';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_NUM);        
        //p($data);
        return $data[0][0];
    }
}