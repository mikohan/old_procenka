<?php
include_once 'config.php';

 if(isset($_POST['insert']) || isset($_POST['update']) || isset($_POST['delete'])){
    header('Refresh: 1; tree.php'); 
}
if(isset($_POST['add_category_php'] )){
    header('Refresh: 1; categories.php'); 
}
if(isset($_POST['first_association'] )){
    header('Refresh: 1; associations.php'); 
}
if(isset($_POST['category_synonym'] )){
    header('Refresh: 1; synonyms.php'); 
}
if(isset($_POST['model'] )){
    header('Refresh: 1; models.php'); 
}
if(isset($_POST['category_situation'] )){
    header('Refresh: 1; situations.php'); 
}

if(isset($_POST['insert'])) {
    echo 'Добавлено: ' . $_POST['text_question'];
    insert_qa($_POST['id_parent'],$_POST['text_question'],$_POST['text_answer']);
} 

if(isset($_POST['update'])) {
   // p($_POST);
    echo 'Обновлено: ' . $_POST['text_question_new'];
    update_qa($_POST['id'], $_POST['id_parent'],$_POST['text_question'],$_POST['text_question_new'],$_POST['text_answer']);    
}

if(isset($_POST['delete'])) {
    echo 'Удалено'; //. $_POST['id'] . ' ' . $_POST['text_question'];
    delete_qa($_POST['id'], $_POST['id_parent'],$_POST['text_question'],$_POST['text_answer']);
}

if(isset($_POST['add_category_php'])) {
    echo 'Добавлено ' . $_POST['add_category_php']; 
    add_category_php($_POST['add_category_php']);
}

if(isset($_POST['first_association'])) {
    $id_first_array = get_category_id($_POST['first_association']);
    $id_first = $id_first_array['0']['id'];    
    $id_second_array = get_category_id($_POST['second_association']);
    $id_second = $id_second_array['0']['id'];  
    echo 'Добавлена связь: ' . $_POST['first_association'] . ' <-> ' . $_POST['second_association']; 
    add_associations($id_first, $_POST['first_association'], $id_second, $_POST['second_association']);
}

if(isset($_POST['category_synonym'])) {
    if($_POST['action'] == 'add'){
    $synonym_new = $_POST['synonym_new'];     
    echo 'Добавлен синоним: ' . $_POST['category_synonym'] . ' - ' . $synonym_new; 
        add_synonym($_POST['category_synonym'], $synonym_new);        
    }        
    if($_POST['action'] == 'delete'){
        echo 'Удалено '. $_POST['category_synonym'] . ' - ' . $_POST['synonym_word'];
        delete_synonym($_POST['category_synonym'], $_POST['synonym_word']);        
    }  
}

if(isset($_POST['model'])) {
    echo 'Добавлено ' . $_POST['model']; 
    add_model($_POST['model']);
}

if(isset($_POST['category_situation'])){
    $category_name = $_POST['category_situation'];
    $id_category_array = get_category_id($_POST['category_situation']);
    $id_category = $id_category_array['0']['id'];  
    $text_question = $_POST['text_question'];
    $text_answer = $_POST['text_answer'];
    $models_array = $_POST['models'];
    $models_array_count = count ($models_array);
    $models_string = '';
    for ($i=0; $i < $models_array_count; $i++){
        $models_string .= $models_array[$i];
        if ($i < $models_array_count-1){
            $models_string .= ',';            
        }
    }
    echo 'Добавлена ситуация: ' . $category_name . ' - ' . $text_question . ' - '  . $text_answer . ' - ' . $models_string ;   
    add_situation($id_category,$category_name,$text_question,$text_answer,$models_string);    
}

//p($_POST);

function get_category_id($category_name){
    $m = db();
    $query = 'SELECT * FROM salesscript_categories WHERE category_name="' . $category_name . '"' ;
    $sth = $m -> prepare($query);
    $sth -> execute(array(0));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function insert_qa($id_parent,$text_question,$text_answer){
    $m = db();
    $query = "INSERT INTO salesscript (id_parent, text_question, text_answer)  VALUES (:id_parent, :text_question, :text_answer)"; 
    $sth = $m -> prepare($query);
    $sth -> execute(array(':id_parent' => $id_parent, ':text_question' => $text_question, ':text_answer' => $text_answer));
}

function update_qa($id, $id_parent,$text_question,$text_question_new,$text_answer){
    $m = db();
    $query = 'UPDATE salesscript SET id_parent=' . $id_parent . ',text_question="' . $text_question_new . '",text_answer="' . $text_answer . '" WHERE id="' . $id . '"'; 
    $sth = $m -> prepare($query);
    $sth -> execute(array($num));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
}

function delete_qa($id,$id_parent,$text_question,$text_answer){
    $m = db();
    $query = 'DELETE FROM salesscript WHERE id= :id OR id_parent= :id' ; 
    $sth = $m -> prepare($query);
    $sth -> execute(array(':id' => $id));
}

 function add_category_php ($category_name) {
     $m = db();
     $query = 'INSERT INTO salesscript_categories (category_name) VALUES ("' . $category_name .'")'; 
     $sth = $m -> prepare($query);
     $sth -> execute(array($category_name));
     $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
     return $data;
}
 function add_associations($id_first,$first,$id_second,$second) {     
     $m = db();
     $query = 'INSERT INTO salesscript_associations (id,category_name,id_second,category_second) VALUES (' . $id_first .',"'. $first .'",' .$id_second .',"' . $second . '")'; 
     $sth = $m -> prepare($query);
     $sth -> execute(array($category_name));
     $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
     return $data;     
}

 function add_synonym($category_name,$synonym_word) {     
     $m = db();
     $query = 'INSERT INTO salesscript_synonims2 (angara_name, synonim) VALUES (:angara_name, :synomim)'; 
     $sth = $m -> prepare($query);
     $sth -> execute(array(':angara_name' => $category_name,':synomim' => $synonym_word));
     //return $data;     
}

function delete_synonym($category_name, $synonym_word){
    $m = db();
    $query = 'DELETE FROM salesscript_synonims2 WHERE angara_name="' . $category_name . '" AND synonym="' . $synonym_word . '"' ; 
    $sth = $m -> prepare($query);
    $sth -> execute(array(0));
    return $data;
}

function add_model($model) {     
     $m = db();
     $query = 'INSERT INTO salesscript_models (model) VALUES ("'. $model .'")'; 
     $sth = $m -> prepare($query);
     $sth -> execute(array($id));
     $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
     return $data;     
}

function add_situation ($id_category, $category_name, $text_question, $text_answer, $models_string) {   
     $m = db();
     $query = 'INSERT INTO salesscript_situations (id, category_name, text_question, text_answer, model) VALUES (' . $id_category . ',"' . $category_name . '","' . $text_question . '","' . $text_answer  .  '","' .  $models_string . '")'; 
     $sth = $m -> prepare($query);
     $sth -> execute(array($id));
     $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
     return $data;        
}




?>