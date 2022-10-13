<?php

//require 'Conn.php';



class InserterOne extends Conn {
    
    public $price_table;

    private function getWord() {
        $m = $this -> db();
        $q = 'SELECT * FROM hd_category WHERE word1 <> "" AND word2 = "" AND word3 = ""';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data;
        
    }
    
    private function get_search1($word1){
        $m = $this -> db();
        $q = 'SELECT id,name FROM ' .  $this->price_table . ' WHERE LCASE(name) RLIKE :word1  AND category_id = 0';
        $t = $m -> prepare($q);
        $t -> execute(array(':word1'=>'[[:<:]]'.$word1));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data; 
    }
    
    private function update_search3($id,$category){
         $m = $this -> db();
        $q = 'UPDATE ' .  $this->price_table . ' SET category_id = :category WHERE id = :id';
        $t = $m -> prepare($q);
        $t -> execute(array(':category'=>$category, ':id'=>$id));
    }
    
    
    
    public function query_three(){
        $data = $this->getWord();
        foreach( $data as $key=>$value){
           // $this->p($value);
            $hz = $this->get_search1($value['word1']);
            if(empty($hz)) continue;
            foreach ($hz as $k => $v) {
                //$this->p($value['category_id']);
                //$this->p($v);
                $this->update_search3($v['id'], $value['category_id']);
            }
            //$this->p($hz);
            
        }
    }

}
