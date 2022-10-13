<?php

class ModelGet extends Conn
{
    private $queryLimit = 'LIMIT 100';

    public function getSyn($search)
    {
        
        $m = $this->db();
        $query =    "SELECT b.orig_name, b.stop_words, c.slang_id FROM ss_slang as a
                    LEFT JOIN ss_slang_orig_bound as c
                    ON a.id = c.slang_id
                    LEFT JOIN ss_syn_orig as b
                    ON b.id = c.orig_id
                    WHERE a.slang_name LIKE :search";
        $sth = $m->prepare($query);
        $sth->execute(array(
            ':search' => '%' . $search . '%'
        ));
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        // p($data);
        return $data;
    }
    
    
    public function getSearchQuery($search, $car_name) {
        $m = $this->db();
        $search_array = explode(" ", $search);
        $search_string = '';
        $and = '';
        for ($i=0; $i < count($search_array); $i++){
            if ($i != 0 ){
                $and = ' AND ';
            }
            else {
                $and = '';
            }
            $search_string .= $and . ' ang_name LIKE "%' . $search_array[$i] . '%"';
        }
        //Проверяем если машина равна все то убираем из запроса отбор по машине
        if($car_name != 'ALLCARS'){
            $car_string = ' AND car = ?';
        }
        if($car_name == 'ALLCARS'){
            $car_string = '';
        }
        $search_string = '(' . $search_string . ')';
        $syn = $this->getSyn($search);
        if(!$syn){
            $query = 'SELECT * FROM angara WHERE ' . $search_string .   $car_string  . ' ORDER BY ang_name ' . $this->queryLimit;
        }else{
            
            
            $synonymString = $this->synonymArray($syn[0]['orig_name']);
            $stopWordsString = $this->stopWordsArray($syn[0]['stop_words']);
            if($stopWordsString){
            $query = 'SELECT * FROM angara WHERE ((' .$synonymString . ' OR '. $search_string . ') ' . $stopWordsString .') '. $car_string . '  ORDER BY price ' . $this->queryLimit;
            }else{
                $query = 'SELECT * FROM angara WHERE (' .$synonymString . ' OR '. $search_string  .') '. $car_string . '  ORDER BY price ' . $this->queryLimit;
            }
        }
        //p($query);
        $sth = $m -> prepare($query);
        $sth -> execute(array($car_name));
        $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
        //p($data);
        return $data;
    }
    
    //Разбираем массив стопслов
    private function stopWordsArray($stopWords){
        $array = explode(",", $stopWords);
        $query_string = '';
        $and = '';
        for ($i=0; $i < count($array); $i++){
            if ($i != 0 ){
                $and = ' AND ';
            }
            else {
                $and = '';
            }
            $query_string .= $and . ' ang_name NOT LIKE "%' . trim($array[$i]) . '%"';
        }
        if(empty($stopWords)){
            return false;
        }
        return 'AND (' . $query_string . ')';
    }
    //Разбираем синонимы и делаем стоку запроса
    private function synonymArray($synonym){
        $array = explode(",", $synonym);
        $query_string = '';
        $and = '';
        for ($i=0; $i < count($array); $i++){
            if ($i != 0 ){
                $and = ' OR ';
            }
            else {
                $and = '';
            }
            $query_string .= $and . ' ang_name LIKE "%' . trim($array[$i]) . '%"';
        }
        return '(' . $query_string . ')';
    }
    
    //Функция вывода машин в левое меню
    public function getAllCars(){
        
        $m = $this->db();
        $query =    "SELECT * FROM ss_cars ORDER BY weight DESC";
        $sth = $m->prepare($query);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public function getSituationQuery($search, $car_name) {
        $m = $this->db();
        $search_string = ' sit.ang_name LIKE "%' . $search . '%"';
        //Проверяем если машина равна все то убираем из запроса отбор по машине
        if($car_name != 32){
            $car_string = ' AND bound.car_id = ? ';
        }
        if($car_name == 32){
            $car_string = '';
        }
        $search_string = '(' . $search_string . ')';
        $syn = $this->getSyn($search);
        if(!$syn){
            $query = 'SELECT sit.* FROM ss_situation as sit
                        LEFT JOIN ss_car_situation_bound as bound
                        ON sit.id = bound.situation_id
                        LEFT JOIN ss_cars as car
                        ON car.id = bound.car_id
                        WHERE ' . $search_string .   $car_string  .  $this->queryLimit;
        }else{
            
            //$stopWordsString = $this->stopWordsArray($syn[0]['stop_words']);
            $stopWordsString = '';
            $synonymString = $this->synonymSituation($syn[0]['orig_name']);
            
            
            $query =    'SELECT sit.* FROM ss_situation as sit
                        LEFT JOIN ss_car_situation_bound as bound
                        ON sit.id = bound.situation_id
                        LEFT JOIN ss_cars as car
                        ON car.id = bound.car_id
                        WHERE (' .$synonymString . ' OR '. $search_string . ' ' . $stopWordsString .') '. $car_string . $this->queryLimit;
        }
         //p($query);
        $sth = $m -> prepare($query);
        $sth -> execute(array($car_name));
        $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
        //p($data);
        //$newdata = "";
        //foreach($data as $k => $d){
         //   $newdata[$k] = array('situation' => $d ,'tree' => $this->getSituationTree($d['id']));
        //}
        //p($newdata);
        //p($mydata);
        //return $newdata;
    }
    
    private function synonymSituation($synonym){
        $array = explode(",", $synonym);
        $query_string = '';
        $and = '';
        for ($i=0; $i < count($array); $i++){
            if ($i != 0 ){
                $and = ' OR ';
            }
            else {
                $and = '';
            }
            $query_string .= $and . ' sit.ang_name LIKE "%' . trim($array[$i]) . '%"';
        }
        return '(' . $query_string . ')';
    }
    //Получаем данные для дерева ситуаций по id ситуации
    public function getSituationTree($situation_id){
        $m = $this->db();
        $query = 'SELECT * FROM ss_situation_tree WHERE situation_id = ?';
        $sth = $m->prepare($query);
        $sth -> execute(array($situation_id));
        $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    
}