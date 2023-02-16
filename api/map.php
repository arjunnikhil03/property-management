<?php
require_once "./common.php";

if(isPost()){
  $data =  getRequestData();
  if(!empty($data->action) && !empty($data->publisherid)){
     authMiddleware($data->action,$data->publisherid);
  }else{
    response(HTTP_NOT_ACCEPTABLE,array("data"=>"Invalid Action Call"));
  }
  
}else{
 response(HTTP_METHOD_NOT_ALLOWED,array("data"=>"Invalid Method Call"));
}

