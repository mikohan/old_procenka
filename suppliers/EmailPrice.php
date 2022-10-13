<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(3000);
//require __DIR__ . '/../config.php';
Class EmailPrice extends Conn {

    /* connect to gmail with your credentials */
    private $hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
    private $username = 'suply.angara77@gmail.com';
    private $password = 'Ogure1234538501';
    private $list;
    private $emails;
    public $emailSearch;
    //public $priceFrom;

    /* try to connect */

    public function myImap($emailSearch, $emailSearch2='', $folder) {
        $inbox = imap_open($this -> hostname, $this -> username, $this -> password) or die('Cannot connect to Gmail: ' . imap_last_error());

        $list = imap_list($inbox, "{imap.gmail.com:993/imap/ssl/novalidate-cert}", "*");
        //$emails = imap_search($inbox, 'ON "12 Oct 2017" FROM "angara77@gmail.com"');

        //$emails = imap_search($inbox, 'UNSEEN FROM "{$priceFrom}"');
        
        // Задаем критерии поиска по емелу и теме
        if(imap_search($inbox, 'UNSEEN FROM "' . $emailSearch2 . '"')){
          // echo $emailSearch2;
            
        $emails = @imap_search($inbox, 'UNSEEN FROM "' . $emailSearch2 . '"');
        
        }else{
            $emails = @imap_search($inbox, 'UNSEEN SUBJECT "' . $emailSearch . '"');
        }

        $email_number = $emails[0];
        if($emails){
            foreach($emails as $emk=>$emailv){
            imap_setflag_full($inbox, $emails[$emk], '\\Seen' );
            }
        }else{
            return FALSE;
        }

        /* if any emails found, iterate through each email */
        //p($emails);
        $overview = imap_fetch_overview($inbox, $email_number, 0);

        $message = imap_fetchbody($inbox, $email_number, 2);

        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);

        //p($structure);

        $attachments = array();

        if (isset($structure -> parts) && count($structure -> parts)) {
            for ($i = 0; $i < count($structure -> parts); $i++) {
                $attachments[$i] = array('is_attachment' => false, 'filename' => '', 'name' => '', 'attachment' => '');

                if ($structure -> parts[$i] -> ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object -> attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object -> value;
                        }
                    }
                }

                if ($structure -> parts[$i] -> ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object -> attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object -> value;
                        }
                    }
                }

                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                    /* 3 = BASE64 encoding */
                    if ($structure -> parts[$i] -> encoding == 3) {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif ($structure -> parts[$i] -> encoding == 4) {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
        //recursiveRemoveDirectory($folder);
        $folder = __DIR__ . "/../prices/" . $folder;
        
        foreach ($attachments as $attachment) {
             //p($attachments);
            if ($attachment['is_attachment'] == 1) {

                $filename = imap_utf8($attachment['filename']);
                //p($filename);
                if (empty($filename))
                    $filename = $attachment['name'];

                if (empty($filename))
                    $filename = time() . ".dat";
                
                if (!is_dir($folder)) {
                    mkdir($folder);
                    chmod($folder, 0777);
                }
                //удаляем все файлы из папки прайса поставщика
                //recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
                $fp = fopen($folder . "/" . $email_number . "-" . strtolower($filename), "w+");
                if(fwrite($fp, $attachment['attachment']))
                fclose($fp);
                //p($overview);
                //echo $overview[0]->udate;
                
            }
            
        }

        
        /* close the connection */
        $errs = imap_errors();
        imap_close($inbox);
        return $overview[0]->date;
    }

    public function getSupplierEmail($id) {
        $m = $this -> db();
        $q = 'SELECT * FROM ang_suppliers WHERE id = ?';
        $t = $m -> prepare($q);
        $t -> execute(array($id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data;

    }
    
     public function getSupplierEmails() {
        $m = $this -> db();
        $q = 'SELECT * FROM ang_suppliers';
        $t = $m -> prepare($q);
        $t -> execute(array());
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data;

    }
    //Выдергиваем только тяжеловесных поставщиков
    public function getSupplierEmailsWeight($weight) {
        $m = $this -> db();
        $q = 'SELECT * FROM ang_suppliers WHERE weight > ?';
        $t = $m -> prepare($q);
        $t -> execute(array($weight));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        //$this->p($data);
        return $data;
        
    }

    //Проверяем расширение файла и распаковываем если zip
    public function checkExtention($folder) {
        
        $file = glob( __DIR__ . '/../prices/' . $folder . '/*.zip');
        
        $file_parts = pathinfo(@$file[0]);
        
        if (@$file_parts['extension'] == 'zip') {
            $zip = new ZipArchive;
            $res = $zip -> open($file[0]);
            $zip -> extractTo(__DIR__ . "/../prices/" . $folder);
            $zip -> close();
           
        }
         return TRUE;
        
    }
    
    public function checkExtentionRar($folder){
        $file = glob( __DIR__ . '/../prices/' . $folder . '/*.rar');
        
        $file_parts = pathinfo(@$file[0]);
        
        if (@$file_parts['extension'] == 'rar') {
            $rar_file = rar_open($file[0]);
            $list = rar_list($rar_file);
            foreach($list as $f){
                //p($f->getName());
               $entry = rar_entry_get($rar_file, $f->getName()); 
                $entry->extract(__DIR__ . "/../prices/" . $folder);
            }
            
            //p($entry);
            
            
            rar_close($rar_file);
           
        }
         return TRUE;
    }
    
    public function convertToCsv($folder){
        
        include __DIR__ . '/../Excel/PHPExcel/IOFactory.php';
        $file = glob( __DIR__ . '/../prices/' . $folder . '/*.csv');

        $objReader = PHPExcel_IOFactory::createReader('CSV');
        
        // If the files uses a delimiter other than a comma (e.g. a tab), then tell the reader
        $objReader->setDelimiter(";");
        // If the files uses an encoding other than UTF-8 or ASCII, then tell the reader
        $objReader->setInputEncoding('WINDOWS-1251');
        
        $objPHPExcel = $objReader->load($file[0]);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($file[0] . '.xlsx');
        
        echo 'csv файл проколбасился!';
    }
    
 public function insertDateEmail($date, $supplier_id){
     $m = $this->db();
    $q = 'UPDATE `ang_suppliers_load_date` 
    SET
    email_date = :email_date WHERE supplier_id = :supplier_id
    ';
    $t = $m->prepare($q);
    if($t->execute(array(':email_date' => $date, ':supplier_id' => $supplier_id))){
        return true;
    }else{
        return FALSE;
    }
 }
    
    
 //отдельная функция для акко
 
 public function myImapAkko($emailSearch, $emailSearch2='', $folder) {
        $inbox = imap_open($this -> hostname, $this -> username, $this -> password) or die('Cannot connect to Gmail: ' . imap_last_error());

        $list = imap_list($inbox, "{imap.gmail.com:993/imap/ssl/novalidate-cert}", "*");
        //$emails = imap_search($inbox, 'ON "12 Oct 2017" FROM "angara77@gmail.com"');

        //$emails = imap_search($inbox, 'UNSEEN FROM "{$priceFrom}"');
        
        // Задаем критерии поиска по емелу и теме
        if(imap_search($inbox, 'FROM "' . $emailSearch2 . '"')){
          // echo $emailSearch2;
            
        $emails = @imap_search($inbox, 'FROM "' . $emailSearch2 . '"');
        
        }else{
            $emails = @imap_search($inbox, 'UNSEEN SUBJECT "' . $emailSearch . '"');
        }

        $email_number = $emails[0];
        if($emails){
            foreach($emails as $emk=>$emailv){
            imap_setflag_full($inbox, $emails[$emk], '\\Seen' );
            }
        }else{
            return FALSE;
        }

        /* if any emails found, iterate through each email */
        //p($emails);
        $overview = imap_fetch_overview($inbox, $email_number, 0);

        $message = imap_fetchbody($inbox, $email_number, 2);

        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);

        //p($structure);

        $attachments = array();

        if (isset($structure -> parts) && count($structure -> parts)) {
            for ($i = 0; $i < count($structure -> parts); $i++) {
                $attachments[$i] = array('is_attachment' => false, 'filename' => '', 'name' => '', 'attachment' => '');

                if ($structure -> parts[$i] -> ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object -> attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object -> value;
                        }
                    }
                }

                if ($structure -> parts[$i] -> ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object -> attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object -> value;
                        }
                    }
                }

                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                    /* 3 = BASE64 encoding */
                    if ($structure -> parts[$i] -> encoding == 3) {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif ($structure -> parts[$i] -> encoding == 4) {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
        //recursiveRemoveDirectory($folder);
        $folder = __DIR__ . "/../prices/" . $folder;
        
        foreach ($attachments as $attachment) {
             //p($attachments);
            if ($attachment['is_attachment'] == 1) {

                $filename = imap_utf8($attachment['name']);
                //p($filename);
                if (empty($filename))
                    $filename = $attachment['filename'];

                if (empty($filename))
                    $filename = time() . ".dat";
                
                if (!is_dir($folder)) {
                    mkdir($folder);
                    chmod($folder, 0777);
                }
                //удаляем все файлы из папки прайса поставщика
                //recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
                $fp = fopen($folder . "/" . $email_number . "-" . strtolower($filename), "w+");
                if(fwrite($fp, $attachment['attachment']))
                fclose($fp);
                //p($overview);
                //echo $overview[0]->udate;
                
            }
            
        }

        
        /* close the connection */
        $errs = imap_errors();
        imap_close($inbox);
        return $overview[0]->date;
        
    } 
    
    
    
    public function deleteEmails(){
        $inbox = imap_open($this -> hostname, $this -> username, $this -> password) or die('Cannot connect to Gmail: ' . imap_last_error());
        //search for old emails Удаляем письма старше 2 месяцев
        $old_date = date('Y-m-d', strtotime('-2 month'));
        $old = imap_search($inbox, 'BEFORE "' . $old_date . '"');
        //p($old);
        if(!empty($old)){
            foreach($old as $old_no){
                //echo $old_no;
                //p(imap_delete($inbox, $old_no));
            }
        }
        imap_expunge($inbox);
        imap_close($inbox);
    }
    //Копия функции myImap для тестов
    public function myImapTest($emailSearch, $emailSearch2='', $folder) {
        $inbox = imap_open($this -> hostname, $this -> username, $this -> password) or die('Cannot connect to Gmail: ' . imap_last_error());
        
        $list = imap_list($inbox, "{imap.gmail.com:993/imap/ssl/novalidate-cert}", "*");
        //$emails = imap_search($inbox, 'ON "12 Oct 2017" FROM "angara77@gmail.com"');
        
        //$emails = imap_search($inbox, 'UNSEEN FROM "{$priceFrom}"');
        
        
        
        
        
        // Задаем критерии поиска по емелу и теме
        if(imap_search($inbox, 'FROM "' . $emailSearch2 . '"')){
            // echo $emailSearch2;
            
            $emails = @imap_search($inbox, 'FROM "' . $emailSearch2 . '"');
            
        }else{
            $emails = @imap_search($inbox, 'SUBJECT "' . $emailSearch . '"');
        }
        
        $email_number = max($emails);
        if($emails){
            foreach($emails as $emk=>$emailv){
                imap_setflag_full($inbox, $emails[$emk], '\\Seen' );
            }
        }else{
            return FALSE;
        }
        
        /* if any emails found, iterate through each email */
        
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        
        //$message = imap_fetchbody($inbox, $email_number, 2);
        //p($email_number);
        
        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);
        
        //p($structure);
        
        $attachments = array();
        
        if (isset($structure -> parts) && count($structure -> parts)) {
            for ($i = 0; $i < count($structure -> parts); $i++) {
                $attachments[$i] = array('is_attachment' => false, 'filename' => '', 'name' => '', 'attachment' => '');
                
                if ($structure -> parts[$i] -> ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object -> attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object -> value;
                        }
                    }
                }
                
                if ($structure -> parts[$i] -> ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object -> attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object -> value;
                        }
                    }
                }
                
                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);
                    
                    /* 3 = BASE64 encoding */
                    if ($structure -> parts[$i] -> encoding == 3) {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif ($structure -> parts[$i] -> encoding == 4) {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
        //recursiveRemoveDirectory($folder);
        $folder = __DIR__ . "/../prices/" . $folder;
        
        foreach ($attachments as $attachment) {
            //p($attachments);
            if ($attachment['is_attachment'] == 1) {
                
                $filename = imap_utf8($attachment['filename']);
                //p($filename);
                if (empty($filename))
                    $filename = $attachment['name'];
                    
                    if (empty($filename))
                        $filename = time() . ".dat";
                        
                        if (!is_dir($folder)) {
                            mkdir($folder);
                            chmod($folder, 0777);
                        }
                        //удаляем все файлы из папки прайса поставщика
                        //recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
                        $fp = fopen($folder . "/" . $email_number . "-" . strtolower($filename), "w+");
                        if(fwrite($fp, $attachment['attachment']))
                            fclose($fp);
                            //p($overview);
                            //echo $overview[0]->udate;
                            
            }
            
        }
        
        
        /* close the connection */
        $errs = imap_errors();
        
        imap_close($inbox);
        return $overview[0]->date;
    }
    public function sendEmail($subject, $message){
        $to      = 'angara99@gmail.com';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($to, $subject, $message, $headers);
    }
    

}

//echo "all attachment Downloaded";
