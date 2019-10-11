<?php

/**
 * mailchimp_api_integration_wordpress
 *
 * @param  object $user
 * @param  string $status
 * @param  string $FNAME
 * @param  string $LNAME
 * @param  int $listID
 *
 * @return int
 */
function mailchimp_api_integration_wordpress( $user, $status, $FNAME , $LNAME, $listID ){
	/**
	 * Create a custom field to storage this value
	 */
	$apiKey = get_option('options_mailchimp_api_key_user_suscriber');
	$memberId = md5(strtolower($user->user_email));
	$dataCenter = substr($apiKey, strpos($apiKey, '-') + 1);
	print $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberId;
	//Member info
	$data = array(
		'email_address' => $user->user_email,
		'status' => $status,
		'merge_fields'  => [
			'FNAME'     => $FNAME,
			'LNAME'     => $LNAME,
		]
	);
	$jsonString = json_encode($data);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
	$result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return $httpCode;
}