<?php
################################################
#                                              #
# This file will initiate a basic curl request #
#                                              #
################################################
date_default_timezone_set('America/Sao_Paulo');


stream_context_set_default(['http'=>['proxy'=>'10.221.113.200:4492']]); 




$url="https://timbrasil.service-now.com/api/now/table/u_task_evento?sysparm_query=assignment_group.nameLIKENFVI^ORDERBYDESC&state=0&sysparm_fields=sys_created_on%2Cu_interrupcao_servicos%2Cstate%2Cimpact%2Cu_falha%2Cu_event.u_fabricante%2Cu_event.u_fabricante%2Cu_event.u_modelo%2Cu_event.u_nota_de_abertura%2Cu_ne_ids%2Cclosed_by.name%2Cnumber%2Cu_grupo_criados.u_setor&sysparm_display_value=true";
$proxy = '10.221.113.200:4492';
$proxyauth = 'nfvops:NFv10ps!!';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_USERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$output = curl_exec($ch);
curl_close($ch);


$data = json_decode($output,true);
$number = $data[result][0][number];
$u_falha = $data[result][0][u_falha];
$u_ne_ids = $data[result][0][u_ne_ids];
$u_event = $data[result][0][u_event.u_modelo];
$u_interrupcao = $data[result][0][u_interrupcao_servicos];
$u_create = $data[result][0][sys_created_on];
$impact = $data[result][0][impact];
$data[result][0][u_event.u_fabricante];
$state = $data[result][0][u_state];
$u_facility = $data[result][0][u_event.u_nota_de_abertura];

if($number === NULL){  

	echo "sem ticket";
		

} else {
	
	
		$date = $rowtelegram[1];
		$token = "746700805:AAGpKRhHXDSwmhhbCT9g2rAYwd1AZnubwV0";
		$user_id = -358367738;
		$msg= "Ticket: $number \n Criado em: $u_create \n Resumo da falha: $u_facility \n Elemento: $u_ne_ids \n Impacto: $impact \n Status: $state \n Interrupcao do serviço: $u_interrupcao";


		$request_params = [
							'chat_id' => $user_id,
							'text' => $msg
						  ];

		$request_url = 'https://api.telegram.org/bot'.$token.'/sendMessage?'. http_build_query($request_params);
		file_get_contents($request_url);
};
?>

