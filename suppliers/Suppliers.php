<?php

//require $_SERVER['DOCUMENT_ROOT'] . '/insertClass/Conn.php';

class Suppliers extends Conn {

    public $form = [];
    public $table = 'ang_prices_all';
    public $disabledFields = ['id', 'category_id', 'subcategory_id', 'supplier', 'date_load'];
    
    
    private function getSupplierTable($supplier_id) {
        $m = $this -> db();
        $q = 'SELECT price_table FROM ang_suppliers WHERE id = ?';
        $t = $m -> prepare($q);
        $t -> execute(array($supplier_id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data[0]['price_table'];
    }

    public function getColumns($table) {
        $m = $this -> db();
        $q = 'SHOW COLUMNS FROM ' . $table;
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_COLUMN);
        //$this->p($data);
        return $data;

    }

    public function getSuppliers() {
        $starttime = microtime(true);   
        $m = $this -> db();
        $q = 'SELECT a.*, b.supplier_id, b.price_load_date, b.price_rows, b.email_date FROM ang_suppliers a
        LEFT JOIN
        ang_suppliers_load_date b
        ON a.id = b.supplier_id
        WHERE a.enabled = 1
        ORDER BY a.weight DESC, a.name';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        array_unshift($data, ['microtime' =>round($duration,4)]);
        //$this->p($data);
        return $data;

    }

    public function getSupplier($id) {
         
        $m = $this -> db();
        $q = 'SELECT * FROM ang_suppliers WHERE id = ?';
        $t = $m -> prepare($q);
        $t -> execute(array($id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        
        //$this->p($data);
        return $data;

    }

    public function UpdateSupplier($id) {
        // echo $id;
        $m = $this -> db();
        $q = 'INSERT INTO `ang_suppliers`
        (
        id,
        name,
        folder,
        supplier_file1,
        address,
        period_price_days,
        delivery_days,
        note,
        email,
        email2,
        price_orig_number,
        price_oem_number,
        price_brand,
        price_name,
        price_stock,
        price_price,
        price_kratnost,
        price_notes,
        delimeter,
        empty_fields,
        price_table,
        enabled,
        weight
        )
        VALUES
        (
        :id,
        :name,
        :folder,
        :supplier_file1,
        :address,
        :period_price_days,
        :delivery_days,
        :note,
        :email,
        :email2,
        :price_orig_number,
        :price_oem_number,
        :price_brand,
        :price_name,
        :price_stock,
        :price_price,
        :price_kratnost,
        :price_notes,
        :delimeter,
        :empty_fields,
        :price_table,
        :enabled,
        :weight
        )
        ON DUPLICATE KEY UPDATE name = :name, folder = :folder, supplier_file1 = :supplier_file1, address = :address, period_price_days = :period_price_days, delivery_days = :delivery_days, note = :note , email = :email, email2 = :email2, price_orig_number = :price_orig_number, price_oem_number = :price_oem_number, price_brand = :price_brand, price_name = :price_name, price_stock = :price_stock, price_price = :price_price, price_kratnost = :price_kratnost, price_notes = :price_notes, delimeter = :delimeter, empty_fields = :empty_fields, price_table = :price_table, enabled =:enabled, weight = :weight';
        $t = $m -> prepare($q);
        $t -> execute(array(':name' => $this -> form['supplier_name'], ':folder' => $this -> form['supplier_folder'], ':supplier_file1' => $this -> form['supplier_file1'], ':address' => $this -> form['supplier_address'], ':period_price_days' => $this -> form['period_price_days'], ':delivery_days' => $this -> form['supplier_delivery_days'], ':note' => $this -> form['supplier_note'], ':id' => $id, ':email' => $this -> form['supplier_email'], ':email2' => $this -> form['supplier_email2'], ':price_orig_number' => $this -> form['price_orig_number'], ':price_oem_number' => $this -> form['price_oem_number'], ':price_brand' => $this -> form['price_brand'], ':price_name' => $this -> form['price_name'], ':price_stock' => $this -> form['price_stock'], ':price_price' => $this -> form['price_price'], ':price_kratnost' => $this -> form['price_kratnost'],':price_notes' => $this -> form['price_notes'], ':delimeter' => $this -> form['supplier_delimeter'],  ':empty_fields' => $this -> form['supplier_empty_fields'], ':price_table' => $this -> form['supplier_price_table'], ':enabled' => $this -> form['supplier_enabled'], ':weight' => $this -> form['supplier_weight']));

    }


// Проверяем соответсвие полей уже вставленного прайса

 public function checkSupplierPriceFields($id,$table) {
        
        $m = $this -> db();
        $check = $this->getSupplierTable($id);
        if(!empty($check)){
            $this->table = $check;
        }
        //p($this->table);
        $q = 'SELECT *  FROM ' . $table . ' WHERE supplier = :id LIMIT 40';
        $t = $m -> prepare($q);
        $t -> execute(array(':id' => $id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }




    public function checkSupplierRows($id) {
        //$starttime = microtime(true);
        $m = $this -> db();
        $check = $this->getSupplierTable($id);
        if(!empty($check)){
            $this->table = $check;
        }
        $q = 'SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE supplier = :id';
        $t = $m -> prepare($q);
        $t -> execute(array(':id' => $id));
        $count = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$endtime = microtime(true);
        //$duration = $endtime - $starttime;
        //array_unshift($count, ['microtime' =>round($duration,4)]);
        //p($count);
        return $count[0]['count'];
    }


    // надо делить на 2 отдельные формы поля и поставщика

   

    public function supplierDelete($supplier_id) {
        $m = $this -> db();
        $folder = $this->getSupplier($supplier_id);
        
        $this->table = $this->getSupplierTable($supplier_id);
        $q = 'DELETE FROM ang_suppliers WHERE id = :supplier_id';
        $t = $m -> prepare($q);
        $t -> execute(array(':supplier_id' => $supplier_id));
        $q = 'DELETE FROM ' . $this->table . ' WHERE supplier = :supplier_id';
        $t = $m -> prepare($q);
        $t -> execute(array(':supplier_id' => $supplier_id));
        recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder[0]['folder']);
        rmdir(__DIR__ . "/../prices/" . $folder[0]['folder']);
    }
    
    public function supplierDisable($supplier_id) {
        $m = $this -> db();        
        $this->table = $this->getSupplierTable($supplier_id);
        $q = 'UPDATE ang_suppliers SET enabled = 0 WHERE id = :supplier_id';
        $t = $m -> prepare($q);
        $t -> execute(array(':supplier_id' => $supplier_id));
        $q = 'UPDATE ' . $this->table . ' SET enabled = 0 WHERE supplier = :supplier_id';
        $t = $m -> prepare($q);
        $t -> execute(array(':supplier_id' => $supplier_id));
        
    }
// Выбираем дату вставки прайса поставщика
    public function checkInsertDate($supplier_id) {
        $m = $this -> db();
        $this->table = $this->getSupplierTable($supplier_id);
        $q = 'SELECT date_load FROM ' . $this->table . ' WHERE supplier = ? LIMIT 1';
        $t = $m -> prepare($q);
        $t -> execute(array($supplier_id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data;
    }
// Выбираем переодичность обновления прайса поставщика
    public function checkSupplierPriceLoadDays($supplier_id) {
        $m = $this -> db();
        $q = 'SELECT period_price_days FROM ang_suppliers WHERE id = ?';
        $t = $m -> prepare($q);
        $t -> execute(array($supplier_id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        if (empty($data)) {
            return FALSE;
        } else {
            return $data;
        }

    }
//Конвертируем буквы колонок таблицы ексель в цифры ключей массива
    public function converter($string) {
        $converter = array('0' => 'A', '1' => 'B', '2' => 'C', '3' => 'D', '4' => 'E', '5' => 'F', '6' => 'G', '7' => 'H', '8' => 'I', '9' => 'J', '10' => 'K', '11' => 'L', '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', '16' => 'Q');
        $str = strtr($string, $converter);
        return strtoupper($str);
    }
    
// Создаем директорию для каждого поставщика
    public function makeDir($dirName) {
        if (!file_exists(ANG_ROOT . '/prices/' . $dirName)) {
            mkdir(ANG_ROOT . '/prices/' . $dirName, 0777, true);
            chmod(ANG_ROOT . '/prices/' . $dirName,0777);
        }

    }

}
