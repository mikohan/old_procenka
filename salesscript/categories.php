<?php
session_start();
include_once 'config.php';
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

function get_categories_angara(){
    $m = db();
    $query = 'SELECT ang_category FROM ang_categories ORDER BY ang_category';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function get_subcategories_angara(){
    $m = db();
    $query = 'SELECT ang_subcat FROM ang_subcategories ORDER BY ang_subcat';
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
                    <p class="p1">Редактирование категорий <i class="fa fa-pencil"></i></p>
                    <div class="categories">
                        <div class="categories_wrap">
                            <?php 
                            $data = get_categories();
                            $data_count = count($data);
                            for ($i=0; $i < $data_count; $i++){
                                $name_old = "'" . $data[$i]['category_name'] . "'";    
                                $id_to_rename = /*"'" .*/ $data[$i]['id'] /*. "'"*/;    
                                echo '<div class="category_row" id="category_row_id_' . $id_to_rename . '">';
                                echo '<input id="input_rename_' . $id_to_rename .'" class="input input_category" value="' .$data[$i]['category_name'] . '">';
                                echo '</input>';                                
                                echo '<button class="button_categories button_neutral" onclick="ajax_rename(' . $id_to_rename  . ',' . $name_old . ')">Переименовать</button>';
                                echo '<button class="button_categories button_delete" onclick="ajax_delete(' . $id_to_rename  . ')">Удалить</button>';
                                echo '</div>';
                            }
                                echo '<div style="margin-top:40px;"></div>';
                                echo '<div class="category_row">';
                                echo '<form action="db.php" method="post" class="form_horizontal">';
                                echo '<input id="new_category" name="add_category_php"  class="input input_category" placeholder="Новая категория" value="' .$data[$i]['category_name'] . '" required></input>';
                                echo '<button type="submit" class="button_categories button_ok" onclick="" >Добавить</button>';
                                echo '<form>';
                                echo '</div>';             
                                ?>
                        </div>
                    </div>
                </div>
                <div class="div_bottom_wrap">
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
        $(document).ready(function() {
            var value_public = '';
            var for_model = '';
        });
        
        function set_element(id, id_parent, text_question, text_answer) {
            console.log(id + " " + id_parent + " " + text_question + " " + text_answer);
            $('#textarea_edit_question').text(text_question);
            $('#textarea_edit_answer').text(text_answer);
            $('#select_parent_edit').val(id_parent);
        }        

        function ajax_rename(id,old_name) {
            alert('Название изменено');
            var new_name = $('#input_rename_'+id).val();
            //console.log('id: ' + id + ' new_name: '+ new_name);            
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'rename_category',
                    id_to_rename: id,
                    new_name: new_name,
                    old_name: old_name
                }
            }).done(function(result) {
              //  console.log("Result rename: " + result);
            });
        }
        
        function ajax_add() {
            var new_category = $('#new_category').val();
            console.log('add: ' + new_category);            
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'add_category',
                    new_category: new_category
                }
            }).done(function(result) {
               // console.log("Result add: " + result);
            });
        }
        
        function ajax_delete(id_to_delete) {
            $("#category_row_id_"+id_to_delete).slideUp(slide_speed);
           // console.log('delete: ' + id_to_delete);            
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'delete_category',
                    id_to_delete: id_to_delete,
                }
            }).done(function(result) {
              //  console.log("Result add: " + result);
            });
        }
    </script>

    </html>