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
$data_all = get_qa_all(); 
$data = get_qa(0); 
$count_all = count ($data_all);

function get_qa_all(){
    $m = db();
    $query = 'SELECT *
    FROM salesscript
    ORDER BY id_parent';
    $sth = $m -> prepare($query);
     $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

  function get_qa($num){
    $m = db();
    $query = 'SELECT *
    FROM salesscript
    WHERE id_parent=' . $num . ' ORDER BY id';
    $sth = $m -> prepare($query);
    $sth -> execute(array($num));
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
                    <p class="p1">Редактирование дерева <i class="fa fa-pencil"></i></p>
                    
                    <?php             
                    echo '<ul id="my-menu" class="menu-top">';                    
                      set_tree_2(0);
                    function set_tree_2($id_parent){
                        $data = get_qa($id_parent);
                        $data_count = count ($data);      
                        echo '<ul class="ul">';
                        for ($i=0; $i < $data_count; $i++){                            
                            echo '<li class="li">';
                            echo '<span class="span_question">';
                            echo $data[$i]['text_question'] ;
                            echo '</span>';
                            echo '<span class="span_answer">' . $data[$i]['text_answer'] . '</span>'; 
                            echo '</li>';
                            set_tree_2($data[$i]['id']);
                            echo '</ul>';
                        }
                    };                    
                    echo '</ul>';                                   
                    ?>

                </div>
                <div class="div_bottom_wrap">
                    <div class="div_bottom_blocks">
                        <div class="div_bottom">
                            <div class="div_edit">
                                <p class="p2"><strong>Редактировать элемент:</strong></p>
                                <form action="db.php" class="form_vertical" method="post">
                                    <select class="select_tree" name="id" required>                               
                                <option></option>
                                <?php 
                                    for ($i=0;$i<$count_all;$i++){
                                        $id_to_set = $data_all[$i]['id'];
                                        $id_parent_to_set = $data_all[$i]['id_parent'];
                                        $text_question_to_set = "'" . $data_all[$i]['text_question'] . "'";
                                        $text_answer_to_set = "'" . $data_all[$i]['text_answer'] . "'";                                      
                                        echo ' <option onclick="set_element(' . $id_to_set . ',' . $id_parent_to_set . ',' . $text_question_to_set . ',' . $text_answer_to_set . ')" value="' . $data_all[$i]['id'] . '">' . $data_all[$i]['text_question'] . '</option> ';
                                       // echo '<input type="hidden" name="text_question" value="' .$data_all[$i]['text_question']. '">';
                                    }
                                    ?>                                 
                               </select>
                                   
                                    <textarea id="textarea_edit_question" name="text_question_new" value="text_question" placeholder="Ситуация" class="textarea" rows="2"></textarea>
                                    <textarea id="textarea_edit_answer" name="text_answer" placeholder="Действие менеджера" class="textarea" rows="10"></textarea>
                                    <p class="p3">Родительский элемент:</p>
                                    <select class="select_tree" id="select_parent_edit" name="id_parent" required>                               
                                <option ></option>
                                <?php 
                                    for ($i=0;$i<$count_all;$i++){
                                        echo ' <option  value="' . $data_all[$i]['id'] . '">' . $data_all[$i]['text_question'] . '</option> ';
                                    }
                                    ?>
                               </select>
                                    <div class="div_buttons">
                                        <button type="submit" name="delete" class="button button_delete">Удалить</button>
                                        <button type="submit" name="update" class="button button_ok">OK</button>
                                    </div>
                                </form>
                            </div>
                            <div class="div_edit">
                                <p class="p2"><strong>Добавить новый элемент:</strong></p>
                                <form action="db.php" class="form_vertical" method="post">
                                    <p></p>
                                    <textarea id="textarea_add_question" name="text_question" placeholder="Ситуация" class="textarea" rows="2" required></textarea>
                                    <textarea id="textarea_add_answer" name="text_answer" placeholder="Действие менеджера" class="textarea" rows="10" required></textarea>
                                    <p class="p3">Родительский элемент:</p>
                                    <select class="select_tree" name="id_parent" required>                               
                                <option></option>
                                <?php 
                                    for ($i=0;$i<$count_all;$i++){
                                        echo ' <option value="' . $data_all[$i]['id'] . '">' . $data_all[$i]['text_question'] . '</option> ';
                                    }
                                    ?>
                               </select>
                                    <div class="div_one_button">
                                        <button type="submit" name="insert" class="button button_ok">OK</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
        var for_model = '';        

        function set_element(id, id_parent, text_question, text_answer) {
            console.log(id + " " + id_parent + " " + text_question + " " + text_answer);
            $('#textarea_edit_question').text(text_question);
            $('#textarea_edit_answer').text(text_answer);
            $('#select_parent_edit').val(id_parent);
        }
        
        function menu_action() {
            $('#my-menu ul li').next().find('ul').hide();
            var slide_speed = 100;
            $('ul#my-menu').find('ul').each(function(index) {
                if ($(this).find('li').length) {
                    //
                } else {
                    $(this).remove(); // пустые li
                }
            });
            $('ul#my-menu').find('li').each(function(index) {
                if ($(this).next('ul').length) {
                    $(this).find('.span_question').after('<i class="fa fa-arrow-right" style="margin-left:10px"></i>');
                    $(this).addClass('cursor_pointer');
                } else {
                    //
                }                
            });            
            $('ul#my-menu ul').each(function(index) {
                $(this).prev().addClass('collapsible').click(function() {
                    var id_for_save = $(this).attr('id');
                    if ($(this).next().css('display') == 'none') {
                        //добавить стрелку вниз
                        // $(this).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-down fa-lg');
                        // $(this).find('span').removeClass('widget-category-list_span').addClass('widget-category-list_span_large');
                        $(this).next().slideDown(slide_speed, function() {
                            $(this).prev().removeClass('collapsed').addClass('expanded');
                        });
                    } else {
                        //добавить стрелку вправо
                        //  $(this).find('i').removeClass('fa-chevron-down fa-lg').addClass('fa-chevron-right');
                        //  $(this).find('span').removeClass('widget-category-list_span_large');
                        //  $(this).find('span.span_2').addClass('widget-category-list_span');
                        $(this).next().slideUp(slide_speed, function() {
                            $(this).prev().removeClass('expanded').addClass('collapsed');
                            $(this).find('ul').each(function() {
                                $(this).hide().prev().removeClass('expanded').addClass('collapsed');
                            });
                        });
                    }
                    return true;
                });
            });

            $('ul#my-menu ul ul li').each(function(index) {
                $(this).addClass('collapsible');
            });
        }     
                
        $(document).ready(function() {
            var value_public = '';
            menu_action();
        });        
       
    </script>

    </html>