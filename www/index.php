<?php

echo "Hallo World";

$data_string = '{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}';

$ch = curl_init('172.18.0.3:8545');                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
                                                                                                                     
$result = curl_exec($ch);

echo "<pre>"; var_dump($result); echo "</pre>";


#curl -H "Content-Type: application/json" -X POST --data '{"jsonrpc":"2.0","method":"admin_peers","params":[],"id":74}' 172.18.0.3:8545

?>