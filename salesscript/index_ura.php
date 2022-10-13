<link rel="stylesheet" href="style.css">
<?php
session_start();
include_once 'config.php';
//p($_SESSION);

$_SESSION['car_name'] = 'porter2';
$_SESSION['car_id'] = 1;
//p($_SESSION);
if (isset($_SESSION['login'])){
    $login = $_SESSION['login'];
    $auth = true;
}
else {
    $auth = false;
}

$data_all = get_qa_all(); 
$data = get_qa(0); 
$count_all = count ($data_all);

function get_qa_all(){
    $m = db();
    $query = 'SELECT DISTINCT * FROM salesscript ORDER BY id_parent';
    $sth = $m -> prepare($query);
    $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

  function get_qa($num){
    $m = db();
    $query = 'SELECT DISTINCT *
    FROM salesscript
    WHERE id_parent= ? ORDER BY id';
    $sth = $m -> prepare($query);
    $sth -> execute(array($num));
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

?>
        <?php include "header.php"?>
        <div class="wrapper">
            <div class="">
                <div class="div_models">

                    <?php
                    $data_models = get_models();
                    $data_count_models = count($data_models);
                    echo '<ul class="ul_models">';               
                    echo '<li onclick="set_model(' . "'" .'all' . "'" . ')">' . 'Все' . '</li>';
                    for ($i=0 ; $i < $data_count_models; $i++){
                        $id_to_delete = $data_models[$i]['i'];    
                        echo '<li onclick="set_model(' . "'" . $data_models[$i]['model'] . "'" . ')">' . $data_models[$i]['model'] . '</li>';                        
                    }                  
                    echo '</ul>';                                   
                    ?>

                </div>
                <div class="div_top">

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
            </div>
            <div class="right">
                <div class="">
                    <div class="div_models">
                        <div class="right_content">
                        <div>Мы находимся в машине: <?=$_SESSION['car_name']?></div>
                            <div class="div_search">
                                <div class="v_align">
                                    <form action="" style="width:100%">
                                        <input id="search_input" type="text" class="input_search" placeholder="&#128270; Поиск">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right_content">
                        <div>
                            <div class="div_p2_wrap">
                                <div style="margin-right: 5px;">
                                    <p class="p2">Ситуации<span class="span_situations"></span></p>
                                </div>
                            </div>
                            <div class="search_result">
                                <div id="situations_container">
                                    -
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="div_p2_wrap">
                                <div style="margin-right: 5px;">
                                    <p class="p2">Синонимы<span class="span_synonyms"></span></p>
                                </div>
                            </div>
                            <div class="search_result">
                                <div id="synonyms_container">
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="div_p2_wrap">
                                <div style="margin-right: 5px;">
                                    <p class="search_p2">Результаты поиска<span class="span_search"></span></p>
                                </div>
                            </div>
                            <div class="search_result">
                                <div id="search_container">
                                    -
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <div class="div_p2_wrap">
                                <div style="margin-right: 5px;">
                                    <p class="p2">Сопутствующие товары<span class="span_associations"></span></p>
                                </div>
                            </div>
                            <div class="search_result">
                                <div id="associations_container">
                                    -
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"?>
    </body>

    <script>
    	
    	
        var session_auth = true;
        var session_login = true;
        if (session_auth == true){
            $('.header_second').css('display','block');
            $('.header_login').text(session_login + "\n" + '(Выход)');
            $('.header_login').attr('href','logout.php');
        }      
        
        var for_model = '';
        var model_public = '';
        var n = 0;

        function set_element(id, id_parent, text_question, text_answer) {
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
//Обработка выбора машины вверху страницы
         $(document).ready(function() {
            var value_public = '';
            menu_action();
            document.querySelector('.ul_models').addEventListener('click', function(e) {
                value_public = $("#search_input").val().toLowerCase();
                var selected;
                if (e.target.tagName === 'LI') {
                    selected = document.querySelector('li.selected');
                    if (selected) selected.className = '';
                    e.target.className = 'selected';
                }
                ajax_situations(value_public);
                ajax_search(value_public);
                ajax_associations(value_public);
                ajax_synonyms(value_public);
            });
        }); 
        $("#search_input").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            value_public = value;
            if (value.length > 3) {
            	ajax_search(value);
                ajax_synonyms(value);
                //  сколько символов введено
                //ajax_situations(value);
                //ajax_associations(value);
            }           
        });
        var car_name = "<?=$_SESSION['car_name']?>";
        function ajax_situations(query) {
            $('.span_situations').text('"' + query + '" ' + for_model);
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'get_situations',
                    to_search: query,
                    car_name: car_name
                }
            }).done(function(result) {
            });
        }
        function ajax_search(query) { //Принимаем строку запроса и стоп слова из синонимов
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'get_search_query',
                    to_search: query,
                    car_name: car_name
                }
            }).done(function(result) {
                //console.log(result);
                var data = JSON.parse(result);
                $('.span_search').text('"' + query + '" ' /*+ for_model*/ + ' в наличии товаров:' + ' ' + data.length);                
                $('#search_container').html('');
                search_synonyms_array = [];
                for (i = 0; i < data.length; i++) {
                        let ang_name = data[i]['ang_name'];
                    let brand = data[i]['brand'];
                    var search_synonym_string = ang_name + brand;
                     if(search_synonyms_array.indexOf(search_synonym_string) > -1){
                     //   
                    }
                    else {
                        search_synonyms_array.push(search_synonym_string);
                        var newElement = $('<div class="goods_block">' + '<div class="goods_name">' + '<span class="span_result_number">' + (i + 1) + ') ' + '</span>' + data[i]['ang_name'] +
                        '</div>' +

                        '<div class="goods_price_avaibility">' +
                        '<div class="goods_price">' + 'Цена: ' + data[i]['price'] + ' руб.' + '<span class="goods_brand">' + ' (' + 'Бренд: ' + data[i]['brand'] + ')' + '</span>' + '</div>' +

                        '<div class="goods_avaibility">' + data[i]['nal'] + ' шт.' + '</div>' + '</div>' +

                        '</div>' +
                        '<div style="margin-top:5px;margin-bottom:5px;border-bottom:1px solid #e4e4e4"></div>'
                    );
                    $('#search_container').append(newElement);
                    }                    
                }
            });
        }

        function ajax_synonyms(query) {
            
            console.log($('.span_synonyms').text('"' + query + '"'));
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'get_synonyms_query',
                    to_search: query,
                    car_name: car_name
                }
            }).done(function(result) {
                var data = JSON.parse(result);
                var synonyms_array = [];
                var j = 0;
                for (i = 0; i < data.length; i++) {
                    if ((synonyms_array.indexOf(data[i]['orig_name']) > 0)) {} else {
                        synonyms_array[j] = data[i]['orig_name'];
                        j++;
                    }
                }
                $('#synonyms_container').html('');
                $('#situations_container').html('');
                n = 0;
                situations_array = [];
                for (i = 0; i < synonyms_array.length; i++) {
                    var newElement = $('<div class="someelement">' + '<span class="span_result_number">' + (i + 1) + ') ' + '</span>' + synonyms_array[i] + '</div>');
                    $('#synonyms_container').append(newElement);
                    get_search_synonyms(synonyms_array[i]);
                }
            });
        }

        function ajax_associations(query) {
            $('.span_associations').text('"' + query + '"');
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'get_associations_query',
                    to_search: query,
                    car_name: car_name
                }
            }).done(function(result) {
                var data = JSON.parse(decodeURIComponent(result));
                var associations_array = [];
                var j = 0;
                for (i = 0; i < data.length; i++) {
                    if ((associations_array.indexOf(data[i]['category_name']) > -1)) {} else {
                        associations_array[j] = data[i]['category_name'];
                        j++;
                    }
                    if ((associations_array.indexOf(data[i]['category_second']) > -1)) {} else {
                        associations_array[j] = data[i]['category_second'];
                        j++;
                    }
                }
                $('#associations_container').html('');
                for (i = 0; i < associations_array.length; i++) {
                    var newElement = $('<div class="someelement">' + '<span class="span_result_number">' + (i + 1) + ') ' + '</span>' + associations_array[i] + '</div>');
                    $('#associations_container').append(newElement);
                }
            });
        }

        function get_situations(query) {        
          //  console.log("model_public " + model_public);
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'get_situations_query',
                    to_search: query,
                    car_name: car_name,
                    to_model: model_public
                }
            }).done(function(result) {
               // console.log(result);
                var data = JSON.parse(decodeURIComponent(result));
                if (data.length > 0) {                      
                    for (j = 0; j < data.length; j++) {
                        let q = data[j]['text_question'];
                        let a = data[j]['text_answer'];
                        let m = data[j]['model'];
                        var l = m.length;
                        var situation_string = q + a + m;
                        let t = 'show'
                        if (m.length > 0) {
                            if (m.indexOf(model_public) > -1) {
                                t = 'show'
                            } else {
                                t = 'hide'
                            }
                            m = '(' + m + ')';
                        }
                        if (situations_array.indexOf(situation_string) > -1) {
                           
                        } else {
                            
                            if (t == 'show') {
                                situations_array.push(situation_string);
                                var newElement = $('<div class="list_situations">' /*+ t*/ + '<div>' + '<span class="span_result_number">' + (n + 1) + ') ' + '</span>' + '<span class="span_result_question" onclick=show_answer(' + n + ')>' + q + '</span>' + '</div>' + '<span id="span_answer_' + n + '" class="span_result_answer">' + a + '<br>' /*+l*/ + ' ' + m + '</span>' + '</div>');
                                $('#situations_container').append(newElement);
                                n++;
                            }
                        }
                    }
                } else {
                    
                }
            });
        }
        
        function get_search_synonyms(query) {
			
            $.ajax({
                type: "POST",
                url: "ajax_functions.php",
                data: {
                    action: 'get_search_query',
                    to_search: query,
                    car_name: car_name
                }
            }).done(function(result) {
                //console.log(result)
                var data = JSON.parse(result);
                for (i = 0; i < data.length; i++) {
                   // let product = data[i];
                    let ang_name = data[i]['ang_name'];
                    let brand = data[i]['brand'];
                    var search_synonym_string = ang_name + brand;
                    if(search_synonyms_array.indexOf(search_synonym_string) > -1){
                     //   
                    }
                    else {
                        search_synonyms_array.push(search_synonym_string);
                        var newElement = $('<div class="goods_block">' + '<div class="goods_name">' + '<span class="span_result_number">' + (i + 1) + ') ' + '</span>' + data[i]['ang_name'] +
                        '</div>' +

                        '<div class="goods_price_avaibility">' +
                        '<div class="goods_price">' + 'Цена: ' + data[i]['price'] + ' руб.' + '<span class="goods_brand">' + ' (' + 'Бренд: ' + data[i]['brand'] + ')' + '</span>' + '</div>' +

                        '<div class="goods_avaibility">' + data[i]['nal'] + ' шт.' + '</div>' + '</div>' +

                        '</div>' +
                        '<div style="margin-top:5px;margin-bottom:5px;border-bottom:1px solid #e4e4e4"></div>'
                    );
                    $('#search_container').append(newElement);
                    }                    
                }
                 let input = $('.input_search').val();
                $('.span_search').text('"' + input + '" ' /*+ for_model*/ + ' в наличии товаров :' + ' ' + search_synonyms_array.length);
            });
        }

        function show_answer(n) {
            $('#span_answer_' + n).toggle();
        }

        function set_model(set_model_string) {
            let search = $('.input_search').val();
            var space = '';
            let search_new = search.replace(model_public, "");
            var last_symbol = search_new.charAt(search.length - 1);
            if (last_symbol == '' || last_symbol == ' ') {
                space = '';                
            } else {
                space = ' ';               
            }            
            var model = set_model_string;         
            for_model = 'для ' + model;
            if (model == 'all') {
                model = '';
                for_model = '';
            }
            $('.span_search').text('"' + search_new + '" ' + space + model);
            $('.input_search').val((search_new + '' + space + model));
            model_public = model;
        }

        $(document).ready(function() {
            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>

    </html>