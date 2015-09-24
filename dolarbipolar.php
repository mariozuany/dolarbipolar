<?php
/**
 * Created by PhpStorm.
 * User: mariozuany
 * Date: 05/03/15
 * Time: 19:19
 */

namespace Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuth;

require "twitteroauth/autoload.php";

$access_token = 'ACCESS_TOKEN';
$access_token_secret = 'ACCESS_TOKEN_SECRET';
$connection = new TwitterOAuth('CONSUMER_KEY', 'CONSUMER_SECRET', $access_token, $access_token_secret);

date_default_timezone_set('America/Sao_Paulo');

$url = "http://cotacoes.economia.uol.com.br/cambioJSONChart.html?type=d&cod=BRL&mt=off";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

// Captura cotação do UOL transforma JSON em array
$cotacoes_uol = json_decode($result, true);
$cotacao_dolar_uol = $cotacoes_uol[1];
$cotacao_dolar_uol_lenght = count($cotacao_dolar_uol);

// Formata cotacao substituindo ponto por virgula e restringindo a duas casas decimais e formata a data
$cotacao_atual = $cotacao_dolar_uol[$cotacao_dolar_uol_lenght-1]['ask'];
$cotacao_atual_duas_casas_decimais = substr_replace($cotacao_atual, '', 4);
$cotacao_atual_com_virgula = str_replace(".", ",", $cotacao_atual_duas_casas_decimais);
$cotacao_atual_data = date('H:i', $cotacao_dolar_uol[$cotacao_dolar_uol_lenght-1]['ts']/1000);

// Pega a última cotacao do arquivo cotacoes.txt
$cotacoes_salvas = file_get_contents("cotacoes_real.txt");
$array_cotacoes_salvas = array_reverse(explode("\n", $cotacoes_salvas));
$ultima_cotacao = $cotacao_atual_com_virgula;
$cotacao_anterior = $array_cotacoes_salvas[1];


// Faz a comparação de cotações e realiza a postagem
if ($ultima_cotacao != $cotacao_anterior) {

    if ( $ultima_cotacao > $cotacao_anterior ) {
        $status_content = "Dólar subiu :( - R$" . $ultima_cotacao . ' às '.$cotacao_atual_data;
    } elseif ( $ultima_cotacao < $cotacao_anterior ) {
        $status_content = "Dólar caiu :) - R$" . $ultima_cotacao . ' às '.$cotacao_atual_data;
    } 
    
	    // Posta tweet
	    $statues = $connection->post("statuses/update", array("status" => $status_content));
	
	    // Salva cotações somente se foi postado no twitter
	    if ($connection->getLastHttpCode() == 200) {
	        file_put_contents("cotacoes_real.txt", $cotacao_atual_com_virgula."\n", FILE_APPEND);
	    } else {
	        // Handle error case
	    }       

}








