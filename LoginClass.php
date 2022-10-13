<?php
Class LoginClass extends Conn {
    
    
    public function authorization($user, $pass) {
        $m = $this->db();
        $q = "SELECT * FROM userlist WHERE user = ? AND pass = ? AND enabled = '1'";
        $t = $m -> prepare($q);
        $t -> execute(array($user, $pass));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //p($data);
        return $data;
    }
    
}