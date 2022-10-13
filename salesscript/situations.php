<?php
session_start(); 
if (isset($_SESSION['login'])){
    $login = $_SESSION['login'];
    $auth = true;
}
else {
    $auth = false;
     header('Location: login.php'); 
}
?>

<!DOCTYPE html>

<?php
function get_categories(){
    $m = db();
    $query = 'SELECT * FROM salesscript_categories ORDER BY category_name';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function get_situations(){
    $m = db();
    $query = 'SELECT * FROM salesscript_situations ORDER BY category_name';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function get_models(){
    $m = db();
    $query = 'SELECT * FROM salesscript_models';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function db() {
    $ANG_HOST = 'localhost';
    $ANG_DBNAME ='test';
    $ANG_DBUSER = 'root';
    $ANG_DBPASS = 'manhee33338';
    try {
        $dsn = 'mysql:dbname=' . $ANG_DBNAME . ';host=' . $ANG_HOST;
        $pdo = new PDO($dsn, $ANG_DBUSER, $ANG_DBPASS);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> exec("set names utf8");
    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
    return $pdo;
}

function p($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

?>
    <html>
    <head>
        <meta charset="utf-8" />
        <title>Скрипт продаж компании Ангара</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link href="style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    </head>
    <body>
        <?php include "header.php"?>
        <div class="wrapper">
            <div class="middle">                
                <div class="div_top">
                    <p class="p1">Редактирование ситуаций <i class="fa fa-pencil"></i></p>
                    
                    <?php 
        $data_categories = get_categories();
        $data_count_categories = count($data_categories);
        
        $data_situations = get_situations();
        $data_count_situations = count ($data_situations);
        
        $data_models = get_models();
        $data_count_models = count ($data_models);        
        
        for($i=0; $i< $data_count_situations; $i++){
            $c = '"' . $data_situations[$i]['category_name'] . '"';
            $q = '"' . $data_situations[$i]['text_question'] . '"';
            $a = '"' . $data_situations[$i]['text_answer'] . '"';
            $m = '"' . $data_situations[$i]['model'] . '"';
            echo '<div class="synonyms_row" id="synonyms_row_' . $i . '" style="margin-bottom:20px;">'; 
            echo '<input name="category_name" maxlength="50" class="input input_situations input_disabled" value="' . $data_situations[$i]['category_name'] . '" readonly>';            
            echo '<input id="text_question_' . $i . '" name="text_question" maxlength="200" class="input input_situations" value=' . $q . '>'   ;              
            echo '<textarea id="text_answer_' . $i . '" name="text_answer" class="input input_situations" >' . $data_situations[$i]['text_answer'] . '</textarea>'   ;            
            echo '<div class="models_column">';
            if ($m =='' || $m == '""'|| strlen($m) < 2 ){
                echo '<label><input type="checkbox" checked="checked"/> Все</label>';
            }
            else{
                echo '<label><input type="checkbox"/> Все</label>';
            }            
            for ($j=0; $j < $data_count_models; $j++){                
                if (strpos($data_situations[$i]['model'], $data_models[$j]['model']) !== false) {
                    echo '<label><input type="checkbox" name="models_' .$i. '" checked="checked" value="' . $data_models[$j]['model'] . '"/>' . $data_models[$j]['model'] . ' </label>';                    
                }
                else {
                    echo '<label><input type="checkbox" name="models_' .$i. '" value="' . $data_models[$j]['model'] . '"/>' . $data_models[$j]['model'] . ' </label>';                    
                }
            }
            echo '</div>';          
            
            echo '<button type="submit" class="button button_margin button_neutral" onclick="ajax_edit_situation(' . $i . ',' . "'" . $data_situations[$i]['category_name'] . "'" . ',' . "'" . $data_situations[$i]['text_question'] . "'"  . ',' . "'" . $data_situations[$i]['text_answer'] . "'"  . ',' . "'" . $data_situations[$i]['model'] . "'"  . ')">Сохранить</button>';
            
            echo '<button id="button_delete_' . $i . '" class="button button_margin button_delete" onclick="ajax_delete_situation(' . $i . ',' . "'" . $data_situations[$i]['category_name'] . "'" . ',' . "'" . $data_situations[$i]['text_question'] . "'"  . ',' . "'" . $data_situations[$i]['text_answer'] . "'"  . ',' . "'" . $data_situations[$i]['model'] . "'"  . ')">Удалить</button>';
            
            echo '</div>';              
            }        
        
        // Добавить
 
        echo '<div class="situations_row">';
        echo '<div class="spacer_synonyms"></div>';
        echo '<div class="synonyms_row">'; 
        echo '<form action="db.php" method="post" class="form_horizontal_situations">';
        echo '<select name="category_situation" class="select select_situations">';        
                    for ($i=0; $i < $data_count_categories; $i++){                   
                        echo '<option value="' .$data_categories[$i]['category_name'] . '">' . $data_categories[$i]['category_name'] . '</option>';                         
                    }
                    echo '</select>';
        echo '<input name="text_question" class="input input_situations" placeholder="Ситуация">'   ;  
        echo '<textarea name="text_answer" class="input input_situations" placeholder="Действие менеджера"></textarea>'   ; 

        echo '<div class="models_column">';
        echo '<label><input type="checkbox" name="models[]" id="" checked="checked" value=""/>Все</label>';
        for ($i=0; $i < $data_count_models; $i++){
            echo '<label><input type="checkbox" name="models[]" id="" value="' . $data_models[$i]['model'] . '" />' . $data_models[$i]['model'] . ' </label>';            
        }
        echo '</div>';  
        
        echo '<button type="submit" class="button button_margin button_ok">Добавить</button>';
        echo '</form>';
        echo '</div>';     
        echo '</div>';
        
                    ?>

                </div>              
            </div>
        </div>
        <?php include "footer.php"?>
    </body>
    
    <script>
        var session_auth = '<?php echo $auth ?>';
        var session_login = '<?php echo $login ?>';
        if (session_auth == true){
            $('.header_second').css('display','block');
            $('.header_login').text(session_login + "\n" + '(Выход)');
            $('.header_login').attr('href','logout.php');
        }
        else {
           //  console.log('auth false');
        }
        var slide_speed = 100;
        function ajax_delete_situation(i,c,q,a,m) {
            console.log(i + ' ' + c + ' ' + q + ' ' + a + ' ' + m);            
            $("#synonyms_row_"+i).slideUp(slide_speed);          
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'delete_situation',
                    category_name: c,
                    text_question: q,
                    text_answer: a,
                    model: m
                }
            }).done(function(result) {
                console.log("Result delete situation: " + result);
            });            
        }
        
        function ajax_edit_situation(i,c,q,a,m) {
            alert('Сохранено');
            console.log(i + ' ' + c + ' ' + q + ' ' + a + ' ' + m);            
            var m_array = [];
            $("input:checkbox[name=models_" + i +"]:checked").each(function(){
                m_array.push($(this).val());
            });
            var m_new = m_array.toString();            
            var q_new = $('#text_question_'+i).val();
            var a_new = $('#text_answer_'+i).val();          
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'edit_situation',
                    category_name: c,
                    text_question: q,
                    text_question_new: q_new,
                    text_answer: a,
                    text_answer_new: a_new,
                    model: m,
                    model_new: m_new
                }
            }).done(function(result) {
                console.log("Result edit situation: " + result);
            });            
        }
       
    </script>

    </html>