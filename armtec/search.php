<?php
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('max_execution_time', 60000000);
require_once 'config.php';
require_once 'autoloader.php';
function p($array){
    echo "<pre>" . print_r($array, true) . "</pre>";
}


//$r = tovar_category(1304793080);
 
    //$r2 = $r1['article_orig'];
  
    //var_dump($r2);

// use ArmtekRestClient\Http\Exception\ArmtekException as ArmtekException;
// use Armtekrestclient\http\config\config as ArmtekRestClientConfig;
 //use ArmtekRestClient\Http\ArmtekRestClient as ArmtekRestClient;
 
$r2 = '583054BA20';
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
    p ($json_responce_data);

} catch (ArmtekException $e) {

    $json_responce_data = $e -> getMessage();

}

