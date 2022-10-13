var car_name = "<?=$_SESSION['car_name']?>";

$("#search_input").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            value_public = value;
			// слова из
        	
            if (value.length > 2) {// сколько символов введено
            	ajax_search(value);
            	ajax_search_situations(value);
            }           
        });
//Из локал стоража берем значене, если есть, то вызываем функцию
var form_value = localStorage.getItem(1);
if(form_value){
	$('#search_input').attr("value", form_value);
	ajax_search(form_value);
	ajax_search_situations(form_value);
}

function ajax_search(query) { // Принимаем строку запроса
	//console.log(query);
	id = 1// и стоп
	localStorage.setItem(id,query);
    $.ajax({
        type: "POST",
        url: "ajaxSearch.php",
        data: {
            action: 'get_search_query',
            to_search: query
        }
    }).done(function(result) {
    	//console.log(result);
        var data = JSON.parse(result);
        $('#countItems').text(data.length + ' по запросу ' + query);                
        $('#search_container').html('');
        search_synonyms_array = [];
        var myElement = '';
        for (i = 0; i < data.length; i++) {
                let ang_name = data[i]['ang_name'];
                let brand = data[i]['brand'];
                var newElement = 
                		'<tr>' +
                		'<td class="clickRow" style="width: 64%">' + data[i]['ang_name'] + '</td>' +
                		'<td style="width: 10%">' + data[i]['brand'] + '</td>' +
                		'<td style="width: 3% color:#e84393; font-weight: bold; color:#e84393;">' + data[i]['nal'] + '</td>' +
                		'<td style="width: 10%">' + data[i]['cat'] + '</td>' +
                		'<td class="tolocal" style="width: 6%; font-weight: bold;">' + data[i]['price'] + '</td>' +
                		'<td style="width: 3%; font-weight: bold; color: #d63031;"><a href="/salesscript/parser/?cat=' + data[i]['cat'] + '">P</a></td>' +
                		'<td style="width: 3%; font-weight: bold; color: #d63031;"><a href="/armtec/armtec_search.php?cat=' + data[i]['cat'] + '">A</a></td>'
                		'</tr>' 
            ;  
                myElement += newElement;
                //$('#search_res_table').html(newElement);
                                
        }//end for
        $('#search_res_table').html(myElement);
    });
}
// пробуем модальный диалог для заказа

//добавляем позиции в localStorage

$('#search_res_table').on('click', 'td.clickRow', function(){
	
	var row = $(this).html();
	var rowObject = $.parseHTML(row);
	//let nquery = rowObject[4].innerText;
	let nquery = $(this).closest('td').next().next().next().text();
	getFromSupplier(nquery);
	$( function() {
	    $( "#dialog" ).dialog({
	    	 minWidth: 600,
	    	 minHeight: 200,
	    	 position: { my: "right bottom", at: "right bottom", of: window }
	    });
	    	
	  } );
	$('.float-window-body').append('<tr>' + row + '</tr>');
});
//Курсор поинтер
$('#search_res_table').on('mouseover', 'td.clickRow', function(){
    $(this).css('cursor','pointer');
});

function getFromSupplier(nquery){
	//console.log(nquery);
	$.ajax({
        type: "POST",
        url: "ajaxSearchsupplier.php",
        data: {
            action: 'get_supplier',
            search: nquery,
        }
    }).done(function(result) {
    	//console.log(result);
        var data = JSON.parse(result); 
        //console.log(data);
        if(!data[1]){
        	//console.log('Empty');
        	$('#search_res_table2').html('<tr><td style="color:#d63031; font-size:1rem; padding-left: 20px;">нет у поставщика</td></tr>');
        }else{
        $('#search_container2').html('');
        search_synonyms_array = [];
        var myElement = '';
        for (i = 1; i < data.length; i++) {
                var newElement =
                	//'<thead><tr><th>Название</th><th>Номер</th><th>Аналог</th><th>Бренд</th><th>Количество</th><th>Цена</th></tr></thead>' + 
                		'<tr class="clickRow2">' +
                		'<td style="width: 40%; word-wrap: break-word;">' + data[i]['name'] + '</td>' +
                		'<td style="width: 15%; word-wrap: break-word;">' + data[i]['orig_number'] + '</td>' +
                		//'<td style="width: 5%; word-wrap: break-word;">' + data[i]['oem_number'] + '</td>' +
                		'<td style="width: 15%; word-wrap: break-word;">' + data[i]['brand'] + '</td>' +
                		'<td style="width: 5%; color:#e84393; font-weight: bold;">' + data[i]['stock'] + '</td>' +
                		'<td style="width: 15%; text-align:left; word-wrap: break-word;">' + data[i]['supplier_name'] + '</td>' +
                		'<td style="width: 10%; word-wrap: break-word;  font-weight: bold; text-align:left;">' + data[i]['price'] + '</td>' +
                		'</tr>' 
            ;  
                myElement += newElement;
                //$('#search_res_table').html(newElement);
                                
        }//end for
        
        $('#search_res_table2').html(myElement);
        }
    });
}
//Вытаскиваем ситуации по машинам
function ajax_search_situations(query) { // Принимаем строку запроса
    $.ajax({
        type: "POST",
        url: "ajaxSearchSituations.php",
        data: {
            action: 'get_situations',
            to_search: query
        }
    }).done(function(result) {
    	//console.log(result);
        var data = JSON.parse(result);
        //console.log(data);
        search_situations_array = [];
        var myElement = '';
        for (i = 0; i < data.length; i++) {
                var newElement = 
                		'<div id ="sit-' + data[i]['id'] + '" onClick="ajax_situations_tree(this.id)" style="background-color: rgba(85, 239, 196, 0.2); margin-bottom:3px; padding: 10px;">' +
                		 '</span>' + ' ' +
                		 data[i]['situation_name'] + '</b></div><div id="sibling-' + data[i]['id'] + '"class="tree"></div><div id ="sittree-' + data[i]['id'] + '"></div>'
            ;  
                myElement += newElement;
                $('#situations').html(myElement);
                
                
                
        }//end for
        //Проверяем если нет ситуации то выводим текст ничего нет
        //console.log(data);
        if(data.length == 0){
    		$('#situations').html('<div class="situation-no">Нет подходящей ситуации!</div>');
    	}else{
        	$('#situations').html(myElement);
        	
    	}
        
    });
}

function ajax_situations_tree(situation_id) { // Принимаем строку запроса
    $.ajax({
        type: "POST",
        url: "ajaxSearchSituationsTree.php",
        data: {
            action: 'get_tree',
            situation_id: situation_id
        }
    }).done(function(result) {
    	//console.log(result);
        var data = JSON.parse(result);
        var myElement = '';
        for (i = 0; i < data.length; i++) {
        	
        	let name = data[i]['tree_name'];
        	let answer = data[i]['answer'];
        	let id = data[i]['id'];
        	let newElement = 
                		'<div>' +
              		'<div id ="tree-' + data[i]['id'] + '" onClick="clFunction(' + id + ')" class="flip" style="background-color: rgba(253, 121, 168, 0.2); margin-top: 3px; padding: 10px;">' + 
               		 data[i]['tree_name'] + '</div><div  class="flip-panel" style="margin-top: 3px; background-color: rgba(116, 185, 255, 0.2);  padding: 10px;">' + data[i]['answer'] + '</div>'
            ;  
                myElement += newElement;
                
                
        }//end for
       // Проверяем если нет ситуации то выводим текст ничего нет
        //console.log(data);
        if(data.length == 0){
    		$('.tree').text('Не записана информация!');
    	}else{
    		var split_id = situation_id.split('-');
    		//console.log(split_id[1]);
        	$('#sibling-' + split_id[1]).html(myElement);
    		//$('.tree').text(data);
    	}
        
    });
}

//Вызов ситуации по клику
function clFunction(id){
      $('#tree-' + id).next().slideToggle();
 };
 
