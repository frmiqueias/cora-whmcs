<?php
require_once('coraConfigurations.php');

function coraboleto_config($params)
{
	$urlReferer = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$jsScript = "javascript:alert('Antes de autorizar, informe sua chave de licença e clique em salvar.')";
	$urlButtonAuth = $params['cora_license_key'] ?  CORA_API_DOMAIN_BASE . '/corabrowser/auth?license_key=' . $params['cora_license_key'] . '&redirect_uri=' . urlencode($urlReferer) : $jsScript;
	$configarray = [
		"FriendlyName" => [
			"Type" => "System",
			"Value" => "Cora - Boleto"
		],
		"cora_authentication" => [
			"FriendlyName" => "Autorização",
			"Description" => "<a href=\"$urlButtonAuth\" class='btn btn-primary'>Autorizar App</a>"
		],
		"cora_license_key" => [
			"FriendlyName" => "Chave de Licença",
			"Type" => "text",
			"Size" => "255",
			"Description" => "Enviamos para o seu e-mail a chave de licen&ccedil;a, juntamente com o arquivo do plugin, ela tamb&eacute;m pode ser obtida no portal de suporte."
		]
	];
	return $configarray;
}

function coraboleto_link($params)
{
	$html = coraHtmlForm($params);
	return $html;
}

function coraHtmlForm($params)
{
	$html = '<form action="' . CORA_URL_ACTION . '" method="post" target="_BLANK">';
	$html .= '<input type="hidden" name="return_url" value="' . $params['systemurl'] . 'modules/gateways/callback/' . $params['paymentmethod'] . '.php'  . '">';
	$html .= '<input type="hidden" name="license_key" value="' . $params['cora_license_key']  . '">';
	$html .= '<input type="hidden" name="name" value="' . $params['clientdetails']['firstname'] . ' ' . $params['clientdetails']['lastname'] . '">';
	$html .= '<input type="hidden" name="email" value="' . $params['clientdetails']['email'] . '">';
	$html .= '<input type="hidden" name="street" value="' . $params['clientdetails']['address1'] . '">';
	$html .= '<input type="hidden" name="complement" value="' . $params['clientdetails']['address2'] . '">';
	$html .= '<input type="hidden" name="city" value="' . $params['clientdetails']['city'] . '">';
	$html .= '<input type="hidden" name="state" value="' . $params['clientdetails']['state'] . '">';
	$html .= '<input type="hidden" name="country" value="' . $params['clientdetails']['country'] . '">';
	$html .= '<input type="hidden" name="zip_code" value="' . $params['clientdetails']['postcode'] . '">';
	$html .= '<input type="hidden" name="code" value="' . $params['invoiceid'] . '">';
	$html .= '<input type="hidden" name="total_amount" value="' . $params['amount'] . '">';
	$html .= '<input type="hidden" name="due_date" value="' . $params['dueDate'] . '">';
	$html .= '<input type="hidden" name="services[0][name]" value="' . $params['description'] . '">';
	$html .= '<input type="submit" value="' . $params['langpaynow'] . '">';
	$html .= '</form>';
	return $html;
}
