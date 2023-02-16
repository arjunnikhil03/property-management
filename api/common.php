<?php

require_once "./constant.php";
require_once "./vendor/autoload.php";
require_once "models/base.php";
require_once "models/auth.php";
require_once "models/map.php";

function base64urlEncoded($data) {
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64urlDecoded($data) {
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

function isOption(){
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
	$data = new StdClass;
	$data->ok = true;
        response(200,$data);
    }
}

function isPost(){

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		return true;
	}else{
		return false;
	}
}

/** 
 * Get header Authorization
 * */
function getAuthorizationHeader(){
	$headers = null;
	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	}
	else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} elseif (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		//print_r($requestHeaders);
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}
	return $headers;
}

/**
 * get access token from header
 * 
*/
function getBearerToken() {
	$headers = getAuthorizationHeader();
	// HEADER: Get the access token from the header
	if (!empty($headers)) {
		if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
			return $matches[1];
		}
	}else{
	  throw new Exception("Invalid Token");
        }
}

function authMiddleware(){
 try{ 
  $jwt = getBearerToken();

  if(empty($jwt)){
     throw new Expection("Invalid Token");
  }

   return true;

  }catch(Exception $e){
      throw new Exception($e->getMessage()); 
  }
}


function response($status_code,$data){
	header("Access-Control-Allow-Origin: * ");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With,Token");
	http_response_code($status_code);
	echo json_encode($data);exit;
}

function getRequestData(){
	return json_decode(file_get_contents("php://input"));
}


function getHttpToken(){
	return $_SERVER["HTTP_TOKEN"];
}

function cmslog($log_msg)
{
	$log_filename = "log";
	if (!file_exists($log_filename))
	{
		// create directory/folder uploads.
		mkdir($log_filename, 0644, true);
	}
	$log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
	file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}

function filterString($str){
	$newstr = filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
	return $newstr;
}

function stripUnwantedTagsAndAttrs($html_str){
	$xml = new DOMDocument();
	//Suppress warnings: proper error handling is beyond scope of example
	libxml_use_internal_errors(true);
	//List the tags you want to allow here, NOTE you MUST allow html and body otherwise entire string will be cleared
	$allowed_tags = array("html", "body", "b", "br", "em", "hr", "i", "li", "ol", "p", "s", "span", "table", "tr", "td", "u", "ul");
	//List the attributes you want to allow here
	$allowed_attrs = array ("class", "id", "style");
	if (!strlen($html_str)){return false;}
	if ($xml->loadHTML($html_str, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)){
		foreach ($xml->getElementsByTagName("*") as $tag){
			if (!in_array($tag->tagName, $allowed_tags)){
				$tag->parentNode->removeChild($tag);
			}else{
				foreach ($tag->attributes as $attr){
					if (!in_array($attr->nodeName, $allowed_attrs)){
						$tag->removeAttribute($attr->nodeName);
					}
				}
			}
		}
	}
	return $xml->saveHTML();
}

isOption();
