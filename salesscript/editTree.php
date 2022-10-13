<?php
session_start();
include 'header.php';
echo '<script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>';
//echo '<script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/inline/ckeditor.js"></script>';
require_once __DIR__ . '/lib/ModelInsert.php';
if(!isset($_SESSION['car_name']) OR empty($_SESSION['car_name'])){
    $_SESSION['car_name'] = 'porter';
}
//$car_name = $_SESSION['car_name'];
$obj = new ModelInsert;
$id = $_GET['id'];
$case[0]['situation_name'] = '';
if($tree = $obj->getTree($id)){
    if(!$case = $obj->getCaseName($tree[0]['situation_id'])){
        $case[0]['situation_name'] = '';
    }
}





// p($obj->getSyn('колес'));
//p($obj->getSearchQuery('шин', 'ALLCARS'));
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
      		 
    	</div>
    	<div class="col-md-10 mt-3 case-table">
    	<h3 style="color:#00cec9;"><?=$case[0]['situation_name'] ?></h3>
    		<table class="table table-bordered">
    			<thead>
    			<tr>
    				<th>ID</th>
    				<th>Вопрос</th>
    				<th>Ответ</th>
    				<th>Edit</th>
    			</tr>
    			</thead>
    			<tbody>
    			<form action="saveTree.php" method="post">
    				<?php $i=0; ?>
            		<?php foreach($tree as $tre): ?>
            		<?php $i++; ?>
            		<tr>
            			<td><?=$tre['id'] ?></td>
            			<td class="red-text"><textarea name="question[<?=$tre['id'] ?>]"  id="editor-<?=$tre['id'] ?>"><?=$tre['tree_name'] ?></textarea></td>
            			<td class="red-text"><textarea name="answer[<?=$tre['id'] ?>]"  id="editor2-<?=$tre['id'] ?>"><?=$tre['answer'] ?></textarea></td>
            			<td class="trash-tree text-center"><a href="saveTree.php?action=delete&element_id=<?=$tre['id'] ?>"><i class="fa fa-trash"></i></a></td>
            			<input type="hidden" name="parent_id" value="<?=$id ?>">
            			<input type="hidden" name="action" value="update">
            		</tr>
            		<script>
            					
                                ClassicEditor
                                    .create( document.querySelector( '#editor-<?=$tre['id'] ?>' ) )
                                    
                                    .catch( error => {
                                        console.error( error );
                                    } );
                                ClassicEditor
                                .create( document.querySelector( '#editor2-<?=$tre['id'] ?>' ) )
                                .catch( error => {
                                    console.error( error );
                                } );
                                

                                
                    </script>
            		<?php endforeach ?>
    			</tbody>
    		</table>
    		<button type="submit" class="btn btn-outline-primary">Ok</button>
    		</form>
    		<button id="toggle-button" class="btn btn-outline-warning">Новая строка</button>
    	</div>
		<div class="col-md-1"><?php include_once 'right.php';?></div>
	</div>
	<div class="row" id="new-form-row" style="display:none">
		<div class="col-md-1">
      		 
    	</div>
    	<div class="col-md-10 mt-3 case-table">
    		<h3 style="color:#00cec9;">Новая строка</h3>
    		<table class="table table-bordered">
    			<thead>
    			<tr>
    				<th>ID</th>
    				<th>Вопрос</th>
    				<th>Ответ</th>
    			</tr>
    			</thead>
    			<tbody>
    			<form action="saveTree.php" method="post">
    				
            		<tr>
            			<td></td>
            			<td class="red-text"><textarea name="question"  id="edi-1"></textarea></td>
            			<td class="red-text"><textarea name="answer"  id="edi-2"></textarea></td>
            			<input type="hidden" name="parent_id" value="<?=$id ?>">
            			<input type="hidden" name="action" value="insert">
            		</tr>
            		<script>
            					
                                ClassicEditor
                                    .create( document.querySelector( '#edi-1' ) )
                                    
                                    .catch( error => {
                                        console.error( error );
                                    } );
                                ClassicEditor
                                .create( document.querySelector( '#edi-2' ) )
                                .catch( error => {
                                    console.error( error );
                                } );
                                

                                
                    </script>
    			</tbody>
    		</table>
    		<button type="submit" class="btn btn-outline-primary">Ok</button>
    		</form>
    	</div>
		<div class="col-md-1"></div>
	</div>
</div>

<div id="dialog" title="На проценку">
  <table  class="float_window table table-striped table-hover search-table">
  <tbody class="float-window-body"></tbody>
  </table>
</div>



<?php include_once 'footer.php';?>
<script src="/js/jquery.jeditable.js"></script>
<script>
$( "#toggle-button" ).click(function() {
  $( "#new-form-row" ).toggle( "slow");
});
</script>


 




