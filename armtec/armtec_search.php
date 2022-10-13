<?php
// session_start();
// error_reporting(E_ALL);
//echo(phpversion());
ini_set("display_errors", 1);
include __DIR__ . '/../salesscript/header.php';

ini_set('max_execution_time', 60000000);
require_once 'config.php';
require_once 'autoloader.php';


//$r = tovar_category(1304793080);
 
    //$r2 = $r1['article_orig'];
if(isset($_GET['cat']) AND !empty($_GET['cat'])){
    $r2 = $_GET['cat'];

    
    //var_dump($r2);

// use ArmtekRestClient\Http\Exception\ArmtekException as ArmtekException;
// use Armtekrestclient\http\config\config as ArmtekRestClientConfig;
// use ArmtekRestClient\Http\ArmtekRestClient as ArmtekRestClient;
try {

    // init configuration
    $armtek_client_config = new \armtekrestclient\http\config\config($user_settings);

    // init client
    $armtek_client = new \armtekrestclient\http\armtekrestclient($armtek_client_config);

    // получение значений




    $params = [
        'VKORG'         => '5000'
        ,'KUNNR_RG'     => '43039901'
        ,'PIN'          => $r2
        ,'BRAND'        => ''
        ,'QUERY_TYPE'   => ''
        ,'KUNNR_ZA'     => ''
        ,'INCOTERMS'    => ''
        ,'VBELN'        => ''
    ];

    // requeest params for send
    $request_params = [

        'url' => 'search/search',
        'params' => [
            'VKORG'         => !empty($params['VKORG'])?$params['VKORG']:(isset($ws_default_settings['VKORG'])?$ws_default_settings['VKORG']:'')
            ,'KUNNR_RG'     => isset($params['KUNNR_RG'])?$params['KUNNR_RG']:(isset($ws_default_settings['KUNNR_RG'])?$ws_default_settings['KUNNR_RG']:'')
            ,'PIN'          => isset($params['PIN'])?$params['PIN']:''
            ,'BRAND'        => isset($params['BRAND'])?$params['BRAND']:''
            ,'QUERY_TYPE'   => isset($params['QUERY_TYPE'])?$params['QUERY_TYPE']:''
            ,'KUNNR_ZA'     => isset($params['KUNNR_ZA'])?$params['KUNNR_ZA']:(isset($ws_default_settings['KUNNR_ZA'])?$ws_default_settings['KUNNR_ZA']:'')
            ,'INCOTERMS'    => isset($params['INCOTERMS'])?$params['INCOTERMS']:(isset($ws_default_settings['INCOTERMS'])?$ws_default_settings['INCOTERMS']:'')
            ,'VBELN'        => isset($params['VBELN'])?$params['VBELN']:(isset($ws_default_settings['VBELN'])?$ws_default_settings['VBELN']:'')
            ,'format'       => 'json'
        ]

    ];

    //var_dump($request_params);

    //var_dump($request_params['params']['PIN']);

    // send data
    $response = $armtek_client->post($request_params);
    // in case of json
    $json_responce_data = $response->json();
    if($json_responce_data){
        $res = $json_responce_data->RESP;
    }else{
        $res = '';
    }
    

} catch (ArmtekException $e) {

    $json_responce_data = $e -> getMessage();

}
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
      		<?php include_once __DIR__ . '/../salesscript/left_short.php';?>
    	</div>
		<div class="col col-md-8">
				<div class="div_search">
				<h3>Проценка на Армтеке</h3>
					<table class="table table-striped table-hover search-table" >
						<thead>
							<tr>
        						<th>Название</th>
        						<th>Номер</th>
        						<th id="brane">Бренд<small> <i class="fa fa-arrows-v" aria-hidden="true"></i></small></th>
        						<th>Наличие</th>
        						<th id="prob">Вер<small> <i class="fa fa-arrows-v" aria-hidden="true"></i></small></th>
        						<th id="date">Дата<small> <i class="fa fa-arrows-v" aria-hidden="true"></i></small></th>
        						<th>Кратность</th>
        						<th id="price">Цена<small> <i class="fa fa-arrows-v" aria-hidden="true"></i></small></th>
    						</tr>
    					</thead>
    					<tbody id="search_res_table">
    					<?php foreach($res as $r): ?>
    						<tr>
    							<td style="width: 35%"><?=@$r->NAME ?></td>
    							<td style="width: 15%"><?=@$r->PIN ?></td>
    							<td style="width: 15%"><?=@$r->BRAND ?></td>
    							<td style="width: 5%"><?=@$r->RVALUE ?></td>
    							<td style="width: 5%"><?=@$r->VENSL ?></td>
    							<td style="width: 10%"><?=date('d-m-Y',strtotime(@$r->DLVDT)) ?></td>
    							<td style="width: 5%"><?=@$r->RDPRF ?></td>
    							<td style="width: 10%; font-weight: bold;"><?=@$r->PRICE ?></td>
    						</tr>
    					<?php endforeach ?>
    					</tbody>
					</table>
				</div>
		</div>
		<div class="col-md-3">
			<div class="mt-5" >
				<div id="situations" ></div>
				<div class="testdiv"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script>
	
	<script type="text/javascript">
	 var table = $('table');
	    
	    $('#brand, #price, #date, #prob')
	        .wrapInner('<span title="sort this column"/>')
	        .each(function(){
	            
	            var th = $(this),
	                thIndex = th.index(),
	                inverse = false;
	            
	            th.click(function(){
	                
	                table.find('td').filter(function(){
	                    
	                    return $(this).index() === thIndex;
	                    
	                }).sortElements(function(a, b){
	                    
	                    return $.text([a]) > $.text([b]) ?
	                        inverse ? -1 : 1
	                        : inverse ? 1 : -1;
	                    
	                }, function(){
	                    
	                    // parentNode is the element we want to move
	                    return this.parentNode; 
	                    
	                });
	                
	                inverse = !inverse;
	                    
	            });
	                
	        });
	</script>

<?php include_once __DIR__ . '/../salesscript/footer.php';?>
