<?php
require_once "./common.php";

if(isPost()){
  try{
    $valid = authMiddleware();
    response(VALID_HTTP_CODE,$valid);
  }catch(Exception $e){	
        $err = new StdClass;
        $err->errorcode = HTTP_UNAUTHORIZED;
        $err->errormsg = $e->getMessage();
        response(HTTP_UNAUTHORIZED,$err);
  }
}else{
 response(HTTP_METHOD_NOT_ALLOWED,array("data"=>"Invalid Method Call"));
}

