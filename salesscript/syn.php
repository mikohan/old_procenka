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
    $query = 'SELECT angara_name
    FROM salesscript_synonims2
    ORDER BY angara_name';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function get_synonyms(){    
    $m = db();
    $query = 'SELECT *
    FROM salesscript_synonims2';
    $sth = $m -> prepare($query);
    $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;    
}

function get_synonyms_new($angara_name){    
    $m = db();
    $query = 'SELECT * FROM salesscript_synonims2 WHERE angara_name = ?';
    $sth = $m -> prepare($query);
    $sth -> execute(array($angara_name));
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
                    <p class="p1">Редактирование синонимов <i class="fa fa-pencil"></i></p>
                    
                    <?php     
                    $data_categories = get_categories();
                    $data_count_categories = count($data_categories);
                    $data_synonyms = get_synonyms();
                    $data_count_synonyms = count($data_synonyms);

                    for($i=0; $i < $data_count_categories; $i++){                        
                        $data_synonyms_new = get_synonyms_new($data_categories[$i]['angara_name']);
                        $data_count_synonyms_new = count($data_synonyms_new);                        
                        echo '<div class="synonyms_row">';                        
                        echo '<input name="category_synonym" class="input input_associations input_disabled" readonly value="' .$data_categories[$i]['angara_name'] . '">'   ;  
                        echo '<div class="synonyms_column">';
                        for ($j=0; $j < $data_count_synonyms_new; $j++){
                             $id_to_delete = /*"'" .*/ $data_synonyms_new[$j]['id'] /*. "'"*/;   
                            $category_to_delete = "'" . $data_synonyms_new[$j]['angara_name'] . "'";   
                            $synonym_to_delete = "'" . $data_synonyms_new[$j]['synonim'] . "'";                             
                            echo '<div class="category_row" id="row_synonym_id_' . $data_synonyms_new[$j]['id'] .'_' . $j . '">';
                            echo '<form action="db.php" method="post" class="form_horizontal_synonyms">';
                            echo '<input type="hidden" name="category_synonym" value="' .$data_categories[$i]['angara_name'] . '">';
                            echo '<input name="synonym_word" class="input input_associations input_disabled" readonly value="' .$data_synonyms_new[$j]['synonim'] . '">'   ;  
                            echo '<button type="submit" name="action" value="delete" class="button button_margin button_delete">Удалить</button>';
                            echo '</form>';
                            echo '</div>';                
                        }
                        echo '<form action="db.php" method="post" class="form_horizontal_synonyms">';
                        echo '<input type="hidden" name="category_synonym" value="'  .$data_categories[$i]['angara_name'] . '">';
                        echo '<div class="category_row">';
                        echo '<input name="synonym_new" class="input input_associations" placeholder="Новый синоним">'   ;  
                        echo '<button type="submit" name="action" value="add" class="button button_margin button_ok">Добавить</button>';
                        echo '</div>';
                        echo '</div>';   
                        echo '</form>';
                        echo '</div>';
                        echo '<div class="spacer_synonyms"></div>';
                    }
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

        function ajax_delete(i, id_to_delete, category_to_delete, synonym_to_delete) {
            $("#row_synonym_id_" + id_to_delete + '_' + i).slideUp(slide_speed);
            console.log(i + " " + id_to_delete + " " + category_to_delete + " " + synonym_to_delete);
        }
        
    </script>

    </html>