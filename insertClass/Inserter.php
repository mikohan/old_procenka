<?php

require 'Conn.php';



class Inserter extends Conn {
    
    public $price_table;

    private function getWord() {
        $m = $this -> db();
        $q = 'SELECT * FROM hd_category WHERE word3 <> "" ';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data;
        
    }
    
    private function get_search1($word1,$word2,$word3){
        $m = $this -> db();
        $q = 'SELECT id,name FROM ' .  $this->price_table . ' WHERE name LIKE :word1 AND name LIKE :word2 AND name LIKE :word3 AND category_id = 0';
        $t = $m -> prepare($q);
        $t -> execute(array( ':word1'=>'%'.$word1.'%', ':word2'=>'%'.$word2.'%', ':word3'=>'%'.$word3.'%'));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data; 
    }
    
    private function update_search3($id,$category){
         $m = $this -> db();
        $q = 'UPDATE ' .  $this->price_table . ' SET category_id = :category WHERE id = :id';
        $t = $m -> prepare($q);
        $t -> execute(array( ':category'=>$category, ':id'=>$id));
    }
    
    
    
    public function query_three(){
        $data = $this->getWord();
        foreach( $data as $key=>$value){
           // $this->p($value);
            $hz = $this->get_search1($value['word1'], $value['word2'], $value['word3']);
            if(empty($hz)) continue;
            foreach ($hz as $k => $v) {
                //$this->p($value['category_id']);
                //$this->p($v);
                $this->update_search3($v['id'], $value['category_id']);
            }
            //$this->p($hz);
            
        }
    }
    
     public function getEmpty() {
        $m = $this -> db();
        $q = 'SELECT * FROM ' .  $this->price_table . ' WHERE category_id = "" ';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data;
        
    }

}
