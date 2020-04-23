<?php

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');



$SETUP = array(
	'SkillName' => "test",
	'SkillVersion' => '1.0',
	'ApplicationID' => 'amzn1.ask.skill.2895891b-df17-40a3-8e58-95b0d8072a05', 
	'CheckSignatureChain' => true, 
	'ReqValidTime' => 60, 
	'AWSaccount' => '', 
	'validIP' => FALSE, 
	'LC_TIME' => "es_ES"

	

);
setlocale(LC_TIME, $SETUP['LC_TIME']);

$rawJSON = file_get_contents('php://input');
$EchoReqObj = json_decode($rawJSON);

if (is_object($EchoReqObj) === false) ThrowRequestError();
$RequestType = $EchoReqObj->request->type;


if (is_array($SETUP['validIP']))
	{
	$isAllowedHost = false;
	foreach($SETUP['validIP'] as $ip)
		{
		if (stristr($_SERVER['REMOTE_ADDR'], $ip))
			{
			$isAllowedHost = true;
			break;
			}
		}

	if ($isAllowedHost == false) ThrowRequestError(403, "Forbidden, your Host is not allowed to make this request!");
	unset($isAllowedHost);
	}



if (strtolower($EchoReqObj->session->application->applicationId) != strtolower($SETUP['ApplicationID']) || empty($EchoReqObj->session->application->applicationId))
	{
	ThrowRequestError(401, "Forbidden, unkown Application ID!");
	}



if ($SETUP['CheckSignatureChain'] == true)
	{
	if (preg_match("/https:\/\/s3.amazonaws.com(\:443)?\/echo.api\/*/i", $_SERVER['HTTP_SIGNATURECERTCHAINURL']) == false)
		{
		ThrowRequestError(403, "Forbidden, unkown SSL Chain Origin!");
		}



	$local_pem_hash_file = sys_get_temp_dir() . '/' . hash("sha256", $_SERVER['HTTP_SIGNATURECERTCHAINURL']) . ".pem";
	if (!file_exists($local_pem_hash_file))
		{
		file_put_contents($local_pem_hash_file, file_get_contents($_SERVER['HTTP_SIGNATURECERTCHAINURL']));
		}

	$local_pem = file_get_contents($local_pem_hash_file);
	if (openssl_verify($rawJSON, base64_decode($_SERVER['HTTP_SIGNATURE']) , $local_pem) !== 1)
		{
		ThrowRequestError(403, "Forbidden, failed to verify SSL Signature!");
		}

	

	$cert = openssl_x509_parse($local_pem);
	if (empty($cert)) ThrowRequestError(424, "Certificate parsing failed!");



	if (stristr($cert['extensions']['subjectAltName'], 'echo-api.amazon.com') != true) ThrowRequestError(403, "Forbidden! Certificate SANs Check failed!");

	

	if ($cert['validTo_time_t'] < time())
		{
		ThrowRequestError(403, "Forbidden! Certificate no longer Valid!");

		
		if (file_exists($local_pem_hash_file)) unlink($local_pem_hash_file);
		}


	unset($local_pem_hash_file, $cert, $local_pem);
	}


if (time() - strtotime($EchoReqObj->request->timestamp) > $SETUP['ReqValidTime']) ThrowRequestError(408, "Request Timeout! Request timestamp is to old.");


if (!empty($SETUP['AWSaccount']))
	{
	if (empty($EchoReqObj->session->user->userId) || $EchoReqObj->session->user->userId != $SETUP['AWSaccount'])
		{
		ThrowRequestError(403, "Forbidden! Access is limited to one configured AWS Account.");
		}
	}

$JsonOut = GetJsonMessageResponse($RequestType, $EchoReqObj);
header('Content-Type: application/json');
header("Content-length: " . strlen($JsonOut));
echo $JsonOut;
exit();

// -----------------------------------------------------------------------------------------//
//					     functions
// -----------------------------------------------------------------------------------------//
// This function returns a json blob for output

function GetJsonMessageResponse($RequestMessageType, $EchoReqObj)
	{
	GLOBAL $SETUP;
	$RequestId = $EchoReqObj->request->requestId;
	$ReturnValue = "";
	if ($RequestMessageType == "LaunchRequest")
		{
		
$myObj->var = "a";
$myObj->courtid = 1;
$myJSON = json_encode($myObj);
$ch = curl_init('https://fuelseuba.herokuapp.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $myJSON);


$response = curl_exec($ch);

curl_close($ch);

			
		$return_defaults = array(
			'version' => $SETUP['SkillVersion'],
			'sessionAttributes' => array(
				'countActionList' => array(
					'read' => true,
					'category' => true
				)
			) ,
			'response' => array(
				'outputSpeech' => array(
					'type' => "PlainText",
					'text' => "Orden aceptada Albert,acabo de activar las luces"
				) ,
				'card' => array(
					'type' => "Simple",
					'title' => "Lights Home",
					'content' => "Orden aceptada Albert,acabo de activar las luces"
				) ,
				'reprompt' => array(
					'outputSpeech' => array(
						'type' => "PlainText",
						'text' => "Te ayudo en algo mas?"
					)
				)
			) ,
			'shouldEndSession' => false
		);
		$ReturnValue = json_encode($return_defaults);
		}
	elseif ($RequestMessageType == "SessionEndedRequest")
		{
		$ReturnValue = json_encode(array(
			'type' => "SessionEndedRequest",
			'requestId' => $RequestId,
			'timestamp' => date("c") ,
			'reason' => "USER_INITIATED"
		));
		}
	elseif ($RequestMessageType == "IntentRequest")
		{
		if ($EchoReqObj->request->intent->name == "thank you") // Alexa Intent name
			{

			$SpeakPhrase = "OK";
			}
		elseif ($EchoReqObj->request->intent->name == "tracking") // 2nd Alexa Intent name
			{

			$SpeakPhrase = "OK ok";
			}

		$ReturnValue = json_encode(array(
			'version' => $SETUP['SkillVersion'],
			'sessionAttributes' => array(
				'countActionList' => array(
					'read' => true,
					'category' => true
				)
			) ,
			'response' => array(
				'outputSpeech' => array(
					'type' => "PlainText",
					'text' => $SpeakPhrase
				) ,
				'card' => array(
					'type' => "Simple",
					'title' => "Lights",
					'content' => $SpeakPhrase
				)
			) ,
			'shouldEndSession' => true
		));
		}
	  else
		{
		ThrowRequestError();
		}

	return $ReturnValue;
	} // end function GetJsonMessageResponse

function ThrowRequestError($code = 400, $msg = 'Bad Request')
	{
	GLOBAL $SETUP;
	http_response_code($code);
	echo "Error " . $code . "<br />\n" . $msg;
	error_log("alexa/" . $SETUP['SkillName'] . ":\t" . $msg, 0);
	exit();
	}

?>
