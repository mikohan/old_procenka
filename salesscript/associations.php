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
function get_categories(){
    $m = db();
    $query = 'SELECT *
    FROM salesscript_categories
    ORDER BY category_name';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function get_associations(){    
    $m = db();
    $query = 'SELECT *
    FROM salesscript_associations
    ORDER BY category_name';
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
                    <p class="p1">Редактирование сопутствующих товаров <i class="fa fa-pencil"></i></p>
                </div>
                <div class="categories">
                    <div class="categories_wrap">
                        <?php 
                    $data_categories = get_categories();
                    $data_count_categories = count($data_categories);
                    $data_associations = get_associations();
                    $data_count_associations = count($data_associations);
                    for ($i=0; $i < $data_count_associations; $i++){
                        $id_to_delete = /*"'" .*/ $data_associations[$i]['id'] /*. "'"*/;   
                        $first = $data_associations[$i]['id'];
                        $second = $data_associations[$i]['id_second'];
                        echo '<div class="category_row" id="category_row_id_' . $first . "_" . $second . '">';
                        echo '<input class="input input_associations input_disabled" readonly value="' .$data_associations[$i]['category_name'] . '">';
                        echo '<img style="margin-left:10px;margin-right:10px;max-height:10px" src="images/associations_20.png">';
                        echo '<input class="input input_associations input_disabled" readonly value="' .$data_associations[$i]['category_second'] . '">';
                        echo '<button class="button button_margin button_delete" onclick="ajax_delete(' . $first . ',' . $second . ')">Удалить</button>';
                        echo '</div>';                    
                    }
                    echo '<div class="category_row"><p>Добавить новые связи:</p></div>';
                    echo '<div class="category_row">';
                    echo '<form action="db.php" method="post" class="form_horizontal">';

                    echo '<select name="first_association" class="select select_associations">';        
                    for ($i=0; $i < $data_count_categories; $i++){                   
                        echo '<option value="' .$data_categories[$i]['category_name'] . '">' . $data_categories[$i]['category_name'] . '</option>';                         
                    }
                    echo '</select>';
                    echo '<img style="margin-left:10px;margin-right:10px;max-height:10px" src="images/associations_20_light.png">';
                    echo '<select name="second_association" class="select select_associations">';        
                    for ($i=0; $i < $data_count_categories; $i++){                   
                        echo '<option value="' .$data_categories[$i]['category_name'] . '">' . $data_categories[$i]['category_name'] . '</option>';                         
                    }
                    echo '</select>';
                    echo '<button type="submit" class="button button_margin button_ok">Добавить</button>';
                    echo '</form>';
                    echo '</div>';
                    ?>
                    </div>
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
        
        function ajax_delete(first, second) {             
            $("#category_row_id_" + first + "_" +second).slideUp(slide_speed);        
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'delete_association',                    
                    first: first,
                    second: second
                }
            }).done(function(result) {
               // console.log("Result delete association: " + result);
            });
        }       
        
    </script>

    </html>