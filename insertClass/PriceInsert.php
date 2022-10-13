<?php
//Этот класс будет записывать массив в базу данных и обрабатывать предварительно поля перед вставкой




class PriceInsert extends Conn {

    //public $fields =  ['orig_number'=>2, 'oem_number'=>3, 'brand'=>4, 'name'=>5, 'stock'=>6, 'price'=>8, 'supplier'=>'tokus'];
    public $fields = [];
    private $table = 'ang_prices_all'; 
    
    public function converterBack($string) {

        //$string = preg_replace('~[^-a-z0-9_]+~u', '-', $string);

        $converter = array('A' => '0', 'B' => '1', 'C' => '2', 'D' => '3', 'E' => '4', 'F' => '5', 'G' => '6', 'H' => '7', 'I' => '8', 'J' => '9', 'K' => '10', 'L' => '11', 'M' => '12', 'N' => '13', 'O' => '14' , 'P' => '15', 'Q' => '16');

        return strtr($string, $converter);

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
    
    
    public function getSuppliers() {
           
        $m = $this -> db();
        $q = 'SELECT id FROM ang_suppliers';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function getSuppliersWeigt($weight) {
        
        $m = $this -> db();
        $q = 'SELECT id FROM ang_suppliers WHERE weight > ? AND enabled = 1';
        $t = $m -> prepare($q);
        $t -> execute(array($weight));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    //Получаем таблицу в которую вставляем прайс
     private function getSupplierTable($supplier_id) {
        $m = $this -> db();
        $q = 'SELECT price_table FROM ang_suppliers WHERE id = ?';
        $t = $m -> prepare($q);
        $t -> execute(array($supplier_id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data[0]['price_table'];

    }

    public function deleteOldPrice($supplier_id) {
        $m = $this -> db();
        $check = $this->getSupplierTable($supplier_id);
        if(!empty($check)){
            $this->table = $check;
        }
        $delete = 'DELETE FROM ' . $this->table . ' WHERE supplier = :supplier_id ORDER BY `id`';
        $t = $m -> prepare($delete);
        $t -> execute(array(':supplier_id' => $supplier_id));
        return true;
    }

    public function insertPrice($rowData) {
        $m = $this -> db();
        $supplier_id = (int)$rowData[0][$this -> fields['supplier_id']];
        $check = $this->getSupplierTable($supplier_id);
        if(!empty($check)){
            $this->table = $check;
        }
        $q = 'INSERT INTO ' . $this->table . ' (
        orig_number,
        oem_number,
        brand,name,
        stock,
        price,
        kratnost,
        supplier,
        notes
        )
        VALUES
        (
        :orig_number,
        :oem_number,
        :brand,
        :name,
        :stock,
        :price,
        :kratnost,
        :supplier,
        :notes
         )';
        $t = $m -> prepare($q);
        //$this->p($rowData);
        $t -> execute(array(
        ':orig_number' => str_replace('-', '', @$rowData[0][$this -> fields['price_orig_number']]),
        ':oem_number' => str_replace('-', '',@$rowData[0][$this -> fields['price_oem_number']]),
        ':brand' => @$rowData[0][$this -> fields['price_brand']],
        ':name' => @$rowData[0][$this -> fields['price_name']],
        ':stock' => @$rowData[0][$this -> fields['price_stock']],
        ':price' => @$rowData[0][$this -> fields['price_price']],
        ':kratnost' => @$rowData[0][$this -> fields['price_kratnost']],
        ':supplier' => $supplier_id,
        ':notes' => @$rowData[0][$this -> fields['price_notes']],
        ));

        return $t -> rowCount();
    }


    public function rowKnife($text, $limit) {
      if (str_word_count($text, 0,"АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") > $limit) {
          $words = str_word_count($text, 2,"АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя");
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]);
      }
      return $text;
    }
    
    //Проверяем разделитель файла csv

    public function getFileDelimiter($file, $checkLines = 20) {
        $file = new SplFileObject($file);
        $delimiters = array(',', '\t', ';', '|', ':');
        $results = array();
        $i = 0;
        while ($file -> valid() && $i <= $checkLines) {
            $line = $file -> fgets();
            foreach ($delimiters as $delimiter) {
                $regExp = '/[' . $delimiter . ']/';
                $fields = preg_split($regExp, $line);
                if (count($fields) > 1) {
                    if (!empty($results[$delimiter])) {
                        $results[$delimiter]++;
                    } else {
                        $results[$delimiter] = 1;
                    }
                }
            }
            $i++;
        }
        $results = array_keys($results, max($results));
        return $results[0];
    }
   //тянем делиметр из таблицы поставщиков
    
    private function getSupplierDelimeter($id) {
        $m = $this -> db();
        $q = 'SELECT delimeter FROM ang_suppliers WHERE id = ?';
        $t = $m -> prepare($q);
        $t -> execute(array($id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data[0]['delimeter'];

    }
    
     private function getSupplierEmptyFields($id) {
        $m = $this -> db();
        $q = 'SELECT empty_fields FROM ang_suppliers WHERE id = ?';
        $t = $m -> prepare($q);
        $t -> execute(array($id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        $empty_array = explode(',', $data[0]['empty_fields']);
        foreach($empty_array as $key=>$value){
            $new_empty_array['@empty'.$key] = $this->converterBack($value);
        }
        return $new_empty_array;

    }

    public function insertCsv($file,$supplier_id){
        $m = $this -> db();
        //Заполняем поля таблицы для вставки
        //p($this->fields);

        $new_order = [
            'orig_number' => $this->fields['price_orig_number'],
            'oem_number' => $this->fields['price_oem_number'],
            'brand' => $this->fields['price_brand'],
            'name' => $this->fields['price_name'],
            'stock' => $this->fields['price_stock'],
            'price' => $this->fields['price_price'],
            'kratnost' => $this->fields['price_kratnost'],
            'notes' => $this->fields['price_notes']

        ];
        $empty = $this->getSupplierEmptyFields($supplier_id);
        //p($empty);
        if(!empty($empty)){
            $new_array = array_merge($new_order, $empty); 
        }else{
            $new_array = $new_order;
        }

        foreach($new_array as $k=>$v){
            if($v == ''): continue; endif;
            $new_order1[$k] = $v; 
        }
        // p($new_order1);


        asort($new_order1,1);
        //p($new_order1);
        $fields = '';
        foreach ($new_order1 as $kn=>$vn){
            $fields .= $kn . ',';
        }
        $fields = trim($fields,',');
        // Заканчиваем с полями

        //конвертируем файл bash скриптом
        $delimeter = $this->getSupplierDelimeter($supplier_id);

        if(empty($delimeter)){
            $delimeter = $this->detectDelimiter($file);
        }

        //echo 'delimetre is === ';
        //p($delimeter) ;

        if (($handle = fopen($file, "r")) !== FALSE) {
            $maxLine = 9;
            $test_row = '';
            for($i = 0; $i < $maxLine && !feof($handle); $i++){
                $test_row .= fgetcsv($handle, 1024, $delimeter)[$this -> fields['price_name']];
                //p( $test_row);
            }
            //echo  
            $encoding = $this->get_codepage($test_row);
            if($encoding !== 'UTF-8'){
                $encoding = 'CP1251';
                $p = ANG_ROOT;
                $file_to_encode = basename($file);
                //echo $file_to_encode;
                $f = file_get_contents($file);
                $html_utf8 = mb_convert_encoding($f, "utf-8", "windows-1251"); 
                file_put_contents($file, $html_utf8);
                //$realpath = realpath($file);
                //echo '<br>real path is - ' . $realpath;

                //$shell_path = "{$p}prices/encoding.sh {$realpath} '" . $encoding ."'";
                //echo $shell_path;
                //echo shell_exec($shell_path);
            }
        }
        // If RinCar do reorder price
        if($supplier_id == 83){
            $path_parts = pathinfo($file);
            $direct = $path_parts['dirname'];
            $np = $direct . '/' . 'tmp.csv';
            $i = 0;
            $f = fopen($file, 'r');
            $n = fopen($np, 'w');
            $new_array = array();
            while (($data = fgetcsv($f, 1024, ';')) !== FALSE) {
                $new_row = array();
                @$new_row[0] = $data[0];
                @$new_row[1] = $data[0];
                @$new_row[2] = $data[1];
                @$new_row[3] = $data[2];
                @$new_row[4] = $data[5];
                @$new_row[5] = $data[3];
                @$new_row[6] = $data[4];
                fputcsv($n, $new_row, ';');
            }
            fclose($f);
            fclose($n);
            rename($np, $file);
            echo count($new_array);
        }


        $row = 1;


        $starttime = microtime(true);    
        $check = $this->getSupplierTable($supplier_id);
        if(!empty($check)){
            $this->table = $check;
        }
        print_r($fields); 
        $q = <<<eof
                LOAD DATA LOCAL INFILE '$file'
                INTO TABLE $this->table
                CHARACTER SET utf8mb4
                FIELDS TERMINATED BY '$delimeter' OPTIONALLY ENCLOSED BY '"'
                ESCAPED BY ''
                LINES TERMINATED BY '\n'
                ($fields) SET supplier = '$supplier_id'
eof;

        //p($q);
        $t = $m->prepare($q,[PDO::MYSQL_ATTR_LOCAL_INFILE => true]);
        $t -> execute(array());

        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        //array_unshift($count, ['microtime' =>round($duration,4)]);
        //echo $duration . '<br>';
        $row = $t->rowCount();
        //echo 'rows: ' . $row . '<br>';
        return $row;
    }

    //Вставляем прайс ангара

    public function insertAngara($file){
        $m = $this -> db();
        $q = <<<eof
                    LOAD DATA LOCAL INFILE '$file'
                    INTO TABLE angara
                    FIELDS TERMINATED BY ';' OPTIONALLY ENCLOSED BY '"'
                    ESCAPED BY ''
                    LINES TERMINATED BY '\n'
                    (ang_name,nal,1c_id,cat,parent,ang_sort,price,car, @ignore, brand)
eof;

        //p($q);
        $t = $m->prepare($q,[PDO::MYSQL_ATTR_LOCAL_INFILE => true]);
        if($t -> execute(array())){
            return true;
        }
    }


// Пробуем получить разделитель в очередной раз

private function detectDelimiter($file)
{
        
    $fh = fopen($file, "r");
    $delimiters = ["\t", ";", "|", ","];
    $data_1 = null; $data_2 = null;
    $delimiter = $delimiters[0];
    foreach($delimiters as $d) {
        $data_1 = fgetcsv($fh, 4096, $d);
        if(sizeof($data_1) > sizeof($data_2)) {
            $delimiter = sizeof($data_1) > sizeof($data_2) ? $d : $delimiter;
            $data_2 = $data_1;
        }
        rewind($fh);
    }

    return $delimiter;
}

//Вставляем прайс через fgetcsv
public function getCsvTest($file){
    $delimeter = $this->detectDelimiter($file);
    //p($delimeter) ;
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        $maxLine = 9;
        $test_row = '';
        for($i = 0; $i < $maxLine && !feof($handle); $i++){
            $test_row .= fgetcsv($handle, 1024, $delimeter)[$this -> fields['price_name']];
            //p( $test_row);
        }
        
        $encoding = $this->get_codepage($test_row);
        if($encoding !== 'UTF-8'){
            $encoding = 'CP1251';
            $p = ANG_ROOT;
            $file_to_encode = basename($file);
        //echo $file_to_encode;
        
        
            $shell_path = "{$p}prices/encoding.sh {$p}prices/froza/{$file_to_encode} '" . $encoding ."'";
            //echo $shell_path;
            echo shell_exec($shell_path);
        }
    }
        
    
    $row = 1;
  
if (($handle = fopen($file, "r")) !== FALSE) {
    $starttime = microtime(true);
     
   while (($rowData = fgetcsv($handle, 1000000, $delimeter)) !== FALSE) {
        $row++;
       
        //echo $encoding;
        
        /*if($encoding !== 'UTF-8'){
            $rowData[0] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[0],$encoding));
            $rowData[1] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[1],$encoding));
            $rowData[2] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[2],$encoding));
            $rowData[3] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[3],$encoding));
            $rowData[4] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[4],$encoding));
            $rowData[5] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[5],$encoding));
            $rowData[6] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[6],$encoding));
            $rowData[7] =  iconv($encoding, 'UTF-8',mb_strtolower($rowData[7],$encoding));
        }
         */
        
        //p($this->fields);
        //p($rowData);
        
        $m = $this -> db();
        $check = $this->getSupplierTable($id);
        if(!empty($check)){
            $this->table = $check;
        }
        $q = 'INSERT INTO ' . $this->table . ' (
        orig_number,
        oem_number,
        brand,name,
        stock,
        price,
        kratnost,
        supplier,
        notes
        )
        VALUES
        (
        :orig_number,
        :oem_number,
        :brand,
        :name,
        :stock,
        :price,
        :kratnost,
        :supplier,
        :notes
         )';
        $t = $m -> prepare($q);
        //$this->p($rowData);
       $t -> execute(array(
        ':orig_number' => @$rowData[$this -> fields['price_orig_number']],
         ':oem_number' => @$rowData[$this -> fields['price_oem_number']],
         ':brand' => @$rowData[$this -> fields['price_brand']],
         ':name' => @$rowData[$this -> fields['price_name']],
         ':stock' => @$rowData[$this -> fields['price_stock']],
         ':price' => @$rowData[$this -> fields['price_price']],
         ':kratnost' => @$rowData[$this -> fields['price_kratnost']],
         ':supplier' => @$this -> fields['id'],
         ':notes' => @$rowData[$this -> fields['price_notes']]));
        
    }
     
    fclose($handle);
}
    
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        //array_unshift($count, ['microtime' =>round($duration,4)]);
        //echo $duration . '<br>';
        //echo 'rows: ' . $row . '<br>';
        return $row;

}


/**
 * Определение кодировки текста
 * @param String $text Текст
 * @return String Кодировка текста
 */

 public function  get_codepage($text = '') {
    if (!empty($text)) {
        $utflower  = 7;
        $utfupper  = 5;
        $lowercase = 3;
        $uppercase = 1;
        $last_simb = 0;
        $charsets = array(
            'UTF-8'       => 0,
            'CP1251'      => 0,
            'KOI8-R'      => 0,
            'IBM866'      => 0,
            'ISO-8859-5'  => 0,
            'MAC'         => 0,
        );
        for ($a = 0; $a < strlen($text); $a++) {
            $char = ord($text[$a]);

            // non-russian characters
            if ($char<128 || $char>256)
                continue;

            // UTF-8
            if (($last_simb==208) && (($char>143 && $char<176) || $char==129))
                $charsets['UTF-8'] += ($utfupper * 2);
            if ((($last_simb==208) && (($char>175 && $char<192) || $char==145))
                || ($last_simb==209 && $char>127 && $char<144))
                $charsets['UTF-8'] += ($utflower * 2);

            // CP1251
            if (($char>223 && $char<256) || $char==184)
                $charsets['CP1251'] += $lowercase;
            if (($char>191 && $char<224) || $char==168)
                $charsets['CP1251'] += $uppercase;

            // KOI8-R
            if (($char>191 && $char<224) || $char==163)
                $charsets['KOI8-R'] += $lowercase;
            if (($char>222 && $char<256) || $char==179)
                $charsets['KOI8-R'] += $uppercase;

            // IBM866
            if (($char>159 && $char<176) || ($char>223 && $char<241))
                $charsets['IBM866'] += $lowercase;
            if (($char>127 && $char<160) || $char==241)
                $charsets['IBM866'] += $uppercase;

            // ISO-8859-5
            if (($char>207 && $char<240) || $char==161)
                $charsets['ISO-8859-5'] += $lowercase;
            if (($char>175 && $char<208) || $char==241)
                $charsets['ISO-8859-5'] += $uppercase;

            // MAC
            if ($char>221 && $char<255)
                $charsets['MAC'] += $lowercase;
            if ($char>127 && $char<160)
                $charsets['MAC'] += $uppercase;

            $last_simb = $char;
        }
        arsort($charsets);
        return key($charsets);
    }
}


// Вставляем количество строк и дату обновления прайса в таблицу ang_suppliers_load_date

public function supplierLoadDate($supplier_id, $rows){
    $m = $this->db();
    $q = 'INSERT INTO `ang_suppliers_load_date` 
    (
    supplier_id,
    price_rows
    )
    VALUES
    (
    :supplier_id,
    :rows
    )
    ON DUPLICATE KEY UPDATE price_rows = :rows, price_load_date = now()
    ';
    $t = $m->prepare($q);
    if($t->execute(array(':supplier_id' => $supplier_id,':rows' => $rows))){
        return true;
    }else{
        return FALSE;
    }
}

// Merge several csv files to one big one

public function joinFiles(array $files, $result) {
    if(!is_array($files)) {
        throw new Exception('`$files` must be an array');
    }

    $wH = fopen($result, "w+");

    foreach($files as $file) {
        $fh = fopen($file, "r");
        while(!feof($fh)) {
            fwrite($wH, fgets($fh));
        }
        fclose($fh);
        unset($fh);
        fwrite($wH, "\n"); //usually last line doesn't have a newline
    }
    fclose($wH);
    unset($wH);
}

public function sendEmail($subject, $message){
    $to      = 'angara99@gmail.com';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: angara99@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    
    mail($to, $subject, $message, $headers);
}
public function truncateTable($table){
    $m = $this->db();
    $q = 'TRUNCATE ' . $table;
    $t = $m->prepare($q);
    $t->execute();
}


}
