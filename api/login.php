<?php

require_once "./common.php";

$post = isPost();

if(isPost()){
   $data =  getRequestData(); 
   if (!empty($data->username) && !empty($data->password)){
      try{
        response(VALID_HTTP_CODE,authLogin($data));
     }catch(Exception $e){
	      $err = new StdClass;
         $err->errorcode = HTTP_UNAUTHORIZED;
	      $err->errormsg = $e->getMessage();
	      response(HTTP_UNAUTHORIZED,$err);
     }
   }else{
      response(HTTP_BAD_REQUEST,array("data"=>"Invalid data passed!"));
   }
 
 
}else{
 response(HTTP_METHOD_NOT_ALLOWED,array("data"=>"Invalid Method Call"));
}
