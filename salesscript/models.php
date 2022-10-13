<?php
include_once 'config.php';
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
error_reporting(E_ALL);
ini_set("display_errors", 1);
function get_models(){
    $m = db();
    $query = 'SELECT * FROM salesscript_models';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function get_models_angara(){
    $m = db();
    $query = 'SELECT DISTINCT car FROM angara ORDER BY car';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
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
                    <p class="p1">Редактирование моделей <i class="fa fa-pencil"></i></p>
                    
                    <?php 
                    $data_models = get_models();
                    $data_count_models = count($data_models);
                    for ($i=0 ; $i < $data_count_models; $i++){
                        $id_to_delete = $data_models[$i]['i']; 
                        $model = $data_models[$i]['model']; 
                        echo '<div class="category_row" id="row_model_id_' . $data_models[$i]['i'] . '">';        
                        echo '<input name="model" class="input input_associations input_disabled" readonly value="' .$data_models[$i]['model'] . '">'   ;  
                        if ($model == 'Все'){
                            echo '<button type="submit" name="action" value="delete" class="button button_margin button_neutral" >Удалить</button>';
                        }
                        else{
                            echo '<button type="submit" name="action" value="delete" class="button button_margin button_delete" onclick="ajax_delete_model(' . $id_to_delete  . ')">Удалить</button>';
                        }                        
                        echo '</div>';      
                    }
                    echo '<div class="category_row">';
                    echo '<form action="db.php" method="post" class="form_horizontal">';
                    echo '<input name="model" class="input input_associations" placeholder="Новая модель">'   ;  
                    echo '<button type="submit" class="button button_margin button_ok">Добавить</button>';
                    echo '</form>';
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
            $('.header_login').text(session_login +  '(Выход)');
            $('.header_login').attr('href','logout.php');
        }
        else {
           //  console.log('auth false');
        }
        var slide_speed = 100;
        function ajax_delete_model(id_to_delete){
            $("#row_model_id_"+id_to_delete).slideUp(slide_speed);       
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'delete_model',
                    id_to_delete: id_to_delete
                }
            }).done(function(result) {
               // console.log("Result del: " + result);
            });            
        }
        
    </script>

    </html>