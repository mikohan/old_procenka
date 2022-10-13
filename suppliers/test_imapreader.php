<?php
require_once "func.php";//подключаем функции
require_once "connect.php";//подключаем настройки к MySQL
 
wr("\n=============================\n");
 
//отключаем ограничение по времени испольнения
//по умолчанию тайм-аут 60 секунд
set_time_limit(0);
 
//предполагается, что данный скрипт будет запускаться планировщиком
//через равные промежутки времени. Например, каждые 30 секунд.
//Но может возникнуть ситуация, что предыдущий может не успеть завершить
//свою работу. Для решения этой проблемы, когда скрипт запускается он
//накладывает блокировку на заданный нами файл, совершив свою работу
//скрипт снимает блокировку с файла и удаляет его. В это время, если по
//расписанию сработал еще один запуск скрипта, то он увидит блокировку и
//завершиться ничего не делая
 
//reader.lock - файл, который мы будем использовать для блокировки.
//Файл блокировки будем храниться рядом со скриптом.
//Можно было бы хранить во временной директории, но на хостинге
//может быть ограничен доступ к временной директории
$lock = "reader.lock";
 
//логическая переменная $aborted будет сигнализировать о том, что
//предыдущий скрипт был прерван. Для этого проверяем существование
//файла, а затем пытается получить время последней модификации файла
//Если файл блокировки существует и удалось получить время последнего
//изменения файла, то значит предыдущий скрипт был прерван  
$aborted = file_exists($lock) ? filemtime($lock) : false;
 
$fp = fopen($lock,'w');//открывает файл с возможностью записи
 
//регистрируем функцию, которая выполниться по завершению работы скрипта
register_shutdown_function(function() use ($fp, $lock) {
    wr("shutdown");
    flock($fp, LOCK_UN);//снимаем блокировку с файла
    fclose($fp);//закрываем файл
    unlink( __DIR__ . DIRECTORY_SEPARATOR . $lock);//удаляем файл
});
 
//пытаемся установить на файл блокировку на запись (LOCK_EX),
//так же передаем флаг LOCK_NB, чтобы скрипт не ждал освобождения
//блокировки
if(!flock($fp,LOCK_EX|LOCK_NB)){
    //если не удалось наложить блокировку, то значит предыдущий 
    //скрипт еще работает.
    wr("busy\n");//пишем в лог, что занято
}else{
 
    if($aborted){
        //если выполнение предыдущего скрипта было прервано,
        //то в нашем случае просто пишем в лог, что прервано. 
        wr("Aborted\n");
    }
 
    //отключаем Autocommit, будем сами управлять транзакциями
    mysql_query("SET AUTOCOMMIT=0");
    mysql_query("START TRANSACTION");//стартуем транзакцию
 
    //запрос из базы списка действующих почтовых ящиков
    $sql = "SELECT * FROM mailboxes WHERE is_deleted = false";
    $res = mysql_query($sql);
 
    //перебираем почтовые ящики
    while($row = mysql_fetch_array($res)){
        $mailbox_id = $row['id'];
        $host = $row['host'];//адрес почтового сервера
        $port = $row['port'];//порт почтового сервера
        $user = $row['email'];//имя пользователя (почтовый ящик)
        $password = $row['password'];//пароль к почтовому ящику
        $last_uid = $row['last_message_uid'];//uid последнего считанного сообщения
        //если подключение идет через SSL, 
        //то достаточно добавить "/ssl" к строке подключения, и
        //поддержка SSL будет включена
        $ssl = $row['is_ssl'] ? "/ssl" : "";
 
        //строка подключения
        $conn = "{{$host}:{$port}{$ssl}}";
        wr("Read $user, conn = $conn");
 
        //открываем IMAP-поток
        $mail = imap_open($conn,$user,$password);
        if(!$mail){     
            //пишем влог сообщение о неудачной попытке подключения
            wr("Error opening IMAP. " . imap_last_error());
            continue;//переходим к следующему ящику
        }
 
        //Считываем только письма, у которых UID больше, 
        //чем UID последнего считанного сообщения. Для этого воспользуемся 
        //функцией imap_fetch_overview, у которой первый параметр - IMAP поток,
        //второй параметр - диапазон номеров и третий параметр - константа FT_UID.
        //FT_UID говорит о том, что диапазоны задаются UID-ами, иначе порядковыми
        //номерами сообщений. Здесь важно понять разницу. 
        //Порядковый номер письма показывает номер писема среди писем почтового ящика,
        //но если кол-во писем уменьшить, то порядковый номер может измениться.
        //UID письма - это уникальный номер письма, также присваивается по попрядку, 
        //но не изменяется.
 
        //Сейчас и в дальнейшем мы же будем полагаться только на UID писем.
        //Диапазаны можно задать следующим образом:
        //"2,4:6" - что соответствует UID-ам 2,4,5,6
        //"7:10" - соответствует 7,8,9,10
        //В нашем случае для удобста будем брать диапазон от последнего UID + 1
        //и до 2147483647.
        $uid_from = $last_uid + 1;
        $uid_to = 2147483647;       
        $range = "$uid_from:$uid_to";       
        $arr = imap_fetch_overview($mail,$range,FT_UID);        
        $message_uid = -1;
        //перебираем сообщения
        foreach($arr as $obj){
            //получаем UID сообщения
            $message_uid = $obj->uid;
            wr("add message $message_uid");
 
            //создаем запись в таблице messages,
            //тем самым поставив сообщение в очередь на загрузку
            $sql = "INSERT INTO messages(mailbox_id,uid,create_date,is_ready)
                    VALUES($mailbox_id,$message_uid,now(),0)";
            mysql_query($sql) or die(mysql_error());
        }
 
        if($message_uid != - 1){
            wr("last message uid = $message_uid");
 
            //если появились новые сообщения, 
            //то сохраняем UID последнего сообщения
            $sql = "UPDATE mailboxes 
                    SET last_message_uid = $message_uid
                    WHERE id = $mailbox_id";
            mysql_query($sql) or die(mysql_error());
        }else{
            //нет новых сообщений
            wr("no new messages");
        }
    }
 
    mysql_query("COMMIT");//завершаем транзакцию
 
    //закрываем IMAP-поток
    imap_close($mail);
}