<?php

class ModelInsert extends Conn
{

    // Выбираем все записи из таблицы синонимов и всех связанных таблиц
    public function getSyn()
    {
        $m = $this->db();
        $query = "SELECT a.slang_name, b.*, c.orig_name,c.stop_words
                    FROM ss_slang a
                    LEFT JOIN ss_slang_orig_bound b
                    ON a.id = b.slang_id
                    LEFT JOIN ss_syn_orig c
                    ON c.id = b.orig_id ORDER BY a.id DESC";
        $sth = $m->prepare($query);
        $sth->execute(array());
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        // p($data);
        return $data;
    }

    public function getAllCars()
    {
        $m = $this->db();
        $query = "SELECT * FROM ss_cars ORDER BY weight DESC";
        $sth = $m->prepare($query);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    // Обновляем записи в таблицах синонимов
    public function synUpdate($table, $field, $value, $id)
    {
        $m = $this->db();
        $query = "UPDATE " . $table . " SET " . $field . " =:value WHERE id = :id";
        $sth = $m->prepare($query);
        $sth->execute(array(
            ':value' => $value,
            ':id' => $id
        ));
        $query = "SELECT " . $field . " FROM " . $table . "  WHERE id = ?";
        $sth = $m->prepare($query);
        $sth->execute(array(
            $id
        ));
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return @$data[0];
    }

    // Обновляем записи в таблице кейсов
    public function caseUpdate($table, $field, $value, $id)
    {
        $m = $this->db();
        $query = "UPDATE " . $table . " SET " . $field . " =:value WHERE id = :id";
        $sth = $m->prepare($query);
        $sth->execute(array(
            ':value' => $value,
            ':id' => $id
        ));
        $query = "SELECT " . $field . " FROM " . $table . "  WHERE id = ?";
        $sth = $m->prepare($query);
        $sth->execute(array(
            $id
        ));
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data[0];
    }

    public function insertCarsId($carIds, $caseId)
    {
        $tmp = explode(',', $carIds);
        $cars = array_map('trim', $tmp);
        $m = $this->db();
        foreach ($cars as $car) {
            $q = "INSERT INTO ss_car_situation_bound ( situation_id, car_id )
            VALUES (:sit_id, :car_id)
            ON DUPLICATE KEY UPDATE car_id = :car_id";
            $sth = $m->prepare($q);
            $sth->execute(array(
                ':car_id' => $car,
                ':sit_id' => $caseId
            ));
        }
        $query = "SELECT car_id FROM ss_car_situation_bound  WHERE situation_id = ?";
        $sth = $m->prepare($query);
        $sth->execute(array(
            $caseId
        ));
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        $string = '';
        for ($i = 0; $i < count($data); $i ++) {
            $string .= $data[$i]['car_id'] . ', ';
        }
        $string = rtrim($string, ', ');
        return $string;
    }

    // Вставляем новую запись в таблицы синонимов
    public function synInsertTestPdo($array)
    {
        $m = $this->db();
        $query1 = "INSERT INTO ss_slang (slang_name)
                            VALUES ( :slang_name );";
        $query3 = "INSERT INTO ss_syn_orig ( orig_name, stop_words )
                            VALUES ( :orig_name, :stop_words );";
        $query4 = "INSERT INTO ss_slang_orig_bound ( slang_id, orig_id )
                            VALUES ( (SELECT MAX(id) FROM ss_slang), (SELECT MAX(id) FROM ss_syn_orig));";
        $sth = $m->prepare($query1);
        $sth->execute(array(
            ':slang_name' => $array['slang_name']
        ));
        $sth = $m->prepare($query3);
        $sth->execute(array(
            ':orig_name' => $array['orig_name'],
            ':stop_words' => $array['stop_words']
        ));

        $sth = $m->prepare($query4);
        if ($sth->execute()) {
            return true;
        }
    }

    // Вставляем новую ситуацию
    public function caseInsert($array)
    {
        $m = $this->db();
        $query = "INSERT INTO ss_situation (ang_name, situation_name) VALUES (?, ?)";
        $sth = $m->prepare($query);
        $sth->execute(array(
            $array['keys'],
            $array['case']
        ));
        // Здесь надо перебирать Id в цикле завтра array['cars']
        $tmp = explode(',', $array['cars']);
        $carIds = array_map('trim', $tmp);
        $count = array();
        foreach ($carIds as $id) {
            $query = "INSERT INTO ss_car_situation_bound (situation_id, car_id)
                         VALUES 
                        ((SELECT MAX(id) FROM ss_situation), (:car_id))";
            $sth = $m->prepare($query);
            $count[] = $sth->execute(array(
                ':car_id' => $id
            ));
        }
        if (count($count > 0)) {
            return true;
        }
    }

    // выбираем несколко самых нужных машин по весу ALLCARS, Porter, Porter2, Starex, Ducato, HD
    // ставим лимит выбоки(костыль конечно)
    private function getAllSituations()
    {
        $m = $this->db();
        $query = "SELECT * FROM ss_situation ORDER BY id DESC";
        $sth = $m->prepare($query);
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function getCases()
    {
        $m = $this->db();
        $cases = $this->getAllSituations();
        $caseData = '';
        foreach ($cases as $case) {
            $query = "SELECT * FROM ss_cars c
                        LEFT JOIN ss_car_situation_bound b
                        ON c.id = b.car_id
                     WHERE b.situation_id = ?";
            $sth = $m->prepare($query);
            $sth->execute(array(
                $case['id']
            ));
            $data = $sth->fetchAll(PDO::FETCH_ASSOC);
            $caseData[] = array(
                'case' => $case,
                'cars' => $data
            );
            // p($cases);
        }
        // p($caseData);

        return $caseData;
    }

    public function getTree($id)
    {
        $m = $this->db();
        $query = "SELECT * FROM ss_situation_tree WHERE situation_id = ?";
        $sth = $m->prepare($query);
        $sth->execute(array(
            $id
        ));
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function treeInsert($array)
    {   
        $parent_id = $array['parent_id'];
        //p($parent_id);
        //$array = $this-> reorderArray($array);
        $m = $this->db();
        $q = "INSERT INTO ss_situation_tree (situation_id, tree_name, answer )
                VALUES (:situation_id, :tree_name, :answer)
                ";
        $sth = $m->prepare($q);
        $count = $sth->execute(array(
            ':situation_id' => $parent_id,
            ':answer' => $array['answer'],
            ':tree_name' => $array['question']
        ));
        
        if(count($count)){
            return true;
        }
    
    }
    public function updateInsert($array)
    {
        $parent_id = $array['parent_id'];
        $array = $this-> reorderArray($array);
        $m = $this->db();
        $count = [];
        foreach($array as $k => $a){
            $q = "UPDATE ss_situation_tree SET situation_id = :situation_id, tree_name=:tree_name, answer = :answer WHERE id = :id";
            $sth = $m->prepare($q);
            $count[] = $sth->execute(array(
                ':situation_id' => $parent_id,
                ':answer' => $a['answer'],
                ':tree_name' => $a['question'],
                ':id' => $k
            ));
        }
        if(count($count)){
            return true;
        }
    }

    private function reorderArray($array)
    {
        $newArray = array(
            'question' => $array['question'],
            'answer' => $array['answer']
        );
        $id = [];
        foreach ($newArray as $key => $value) {
            foreach ($value as $k => $v) {
                $id[$k] = array(
                    'id' => $k,
                    'question' => $newArray['question'][$k],
                    'answer' => $newArray['answer'][$k]
                );
            }
        }
        return $id;
    }
    public function deleteTree($id)
    {
        $m = $this->db();
        $query = "DELETE FROM ss_situation_tree WHERE id = ?";
        $sth = $m->prepare($query);
        if($sth->execute(array($id))){
            return true;
        }
    }
    //Выдергиваем имя ситуации для страницы дерева
    public function getCaseName($id)
    {
        $m = $this->db();
        $query = "SELECT * FROM ss_situation WHERE id = ?";
        $sth = $m->prepare($query);
        $sth->execute(array($id));
        $data = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function caseDelete($id){
        $m = $this->db();
        $query = "DELETE FROM ss_situation WHERE id = ?";
        $sth = $m->prepare($query);
        if($sth->execute(array($id))){
            return true;
        }
    }
    public function synDelete($id){
        $m = $this->db();
        $query = "DELETE a,c FROM ss_slang a
                    JOIN ss_slang_orig_bound b
                    ON a.id = b.slang_id
                    JOIN ss_syn_orig c
                    ON b.orig_id = c.id
                     WHERE a.id = ?";
        $sth = $m->prepare($query);
        if($sth->execute(array($id))){
            return true;
        }
    }
    
}