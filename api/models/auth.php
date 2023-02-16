<?php
use \Firebase\JWT\JWT;

const PRIVATE_KEY = "
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDVW9LakrQHpyTZ
UHePSUX2eC4hNORkrW8OJwn1zC/Lom/C9zC7rebOmmMAJ1g4lPNYVQrgah5hl8N7
GUpEeqfrz4Hn9ghsHKXZr3E3ywzy532zF5L9oW0+75+S0zqnkf75aM/0uxnXMTJD
ou3ejzKwLDB3WyZyCWA4QoVHJoPlIV/J0EeyYAmw8MrIxpM5B15fFUEC8YdlXn3S
R5hUop6FzyoNjq9RzQ0PMPJdDPT0acxfzDO+4NudweUS892bkRYwDnTpX87LVS94
a0bj34WWTkY+xthsMj81826oMyapi8w/BkC62L1HM39mCLufq4F77hYP7cC559CX
CWraJ3C1AgMBAAECggEAePQKT/D08JW31R7B+NAAQp6VACw9Ajo0CCDni5nKyrV2
iF+cF/mZLCznzPGBGOqQHdZwSRK/0HG51zJaIQIr6goEShz3X1rKRuMn2wpYd+f2
Ia5OnUyDYzdzDkiDKvYgRwI9Si87kfwI08Es+ovRn4Adpbxdyi44hwxnBBP4l87P
vXiC4dM4RZSWR0GQUUM36hrWwttKM6nLNVjL/LTehkMOn58/XXXZzrNsEYZXLyYI
6BCFaJTpX718aAWaItmjuoDW+0qW6JpXv1wrudTo09882GM9/nMbVdjQ5TrOyoIw
QjOUf9Cw9LFSd0n2/HTfg0RSs9+bHcxn/FVsA3z1DQKBgQDs+xUSVxPoXUUUDbI9
EnocnefqRLEQtF11pzTQPUcmS81ZFNVjt5qggX9eqITkv5d6yYPM2aw/zqzI29BV
ajTrGN3Gc8OEe/ugHIVP9CfGlsTLoc2f/R5AaRdPtiG9Mb56d3AYa4psIfFjDFGJ
jMJ02aMqMAZjjeaEYX9aoeLMFwKBgQDme2kXwMdkcOMbXdUXnUGgVlX6wE4Hzdqx
yDIaaiILEUthGAxvbz9ZP+Yv3QDHV/YSnyQWDJwRlR1A8dsCWZ2N8lDJm1arBAkn
OBihGjMioqBGQWKntaKn+y7iMT+Vp3MQOK/m8408Kx69uFM4c5WAzjomefGqSyx7
Qj4RA0ftEwKBgDWyhwnK1WX3jGmI2PRhtW26kgxRWFXSqMK6CouM9wxEbNlckSWx
OsZX0YafZM14ZFicRNMsF421xQ0WUdSo5ijHT6liHdFtTozvKX3+cuzayOjjVvaD
olEx/ug++tIl7WVOnwXJiGoMHug0qF0kmgNkNUaf7zKruqoQdY0R1ZQtAoGBAJ3S
K6hELUStuBVpLx04ZVOxSralL8lQQEw0VXqY6i7B7OfTW9CCUNKAWtKzdnEJ3knq
Bv/CKqwvexSZh+oYCzbDkmY6pCH+ZXYhh1vpYNJ6oVz6MN++FF8KNJRWPL/xJW0a
I/j1FOT6cdZffTzOiLTmghMOtVhymUbdmTp1EK9ZAoGBAMNi3CSTg8tThDOBHgFY
Kdd6BucIIYgYgeR29X3cbkY7yX+TQYthvuYgNta/rOKme5SY/Va+7R+VpLYayZRW
Cdbg/RmRDNrkVcseZZ6k4WhLalKWOaRmGUNJ1bo9vbZ1X6iUv+SQpp7jwd0gtcwk
trKel+Ijg/Y8k8HwzFA03XbJ
-----END PRIVATE KEY-----
";

const PUBLIC_KEY = "
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1VvS2pK0B6ck2VB3j0lF
9nguITTkZK1vDicJ9cwvy6Jvwvcwu63mzppjACdYOJTzWFUK4GoeYZfDexlKRHqn
68+B5/YIbByl2a9xN8sM8ud9sxeS/aFtPu+fktM6p5H++WjP9LsZ1zEyQ6Lt3o8y
sCwwd1smcglgOEKFRyaD5SFfydBHsmAJsPDKyMaTOQdeXxVBAvGHZV590keYVKKe
hc8qDY6vUc0NDzDyXQz09GnMX8wzvuDbncHlEvPdm5EWMA506V/Oy1UveGtG49+F
lk5GPsbYbDI/NfNuqDMmqYvMPwZAuti9RzN/Zgi7n6uBe+4WD+3AuefQlwlq2idw
tQIDAQAB
-----END PUBLIC KEY-----
";

//echo sha1("hello@123")."\n";

//use Ramsey\Uuid\Uuid;

//$uuid = Uuid::uuid4();

//echo $uuid->toString()."\n";


function _authLogin($data){
	try{
                $password = hash("sha512",$data->password);
         	$sql = "SELECT * from users WHERE username=:username LIMIT 1";
 		$rdata = fetchAssocWithoutCache($sql,array("username"=>$data->username));
		if(empty($rdata)){
		   throw new Exception("auth/user-not-found");
                }

                if(!password_verify($password,$rdata[0]["password"])){
		   throw new Exception("auth/wrong-password");
                }
		return current($rdata);
		
	}catch(Exception $e){
		throw new Exception($e->getMessage());
        }
}


function _userDataByUUID($uuid){
        $sql = "SELECT * from users WHERE uuid=:uuid LIMIT 1";
        $data = fetchAssocWithoutCache($sql,array("uuid"=>$uuid));
        return !empty($data)?current($data):array();
}

function _generateToken($auth_data){
	$tokenId    = base64_encode(random_bytes(32));
	$issuedAt   = time();
	$notBefore  = $issuedAt + 10;             //Adding 10 seconds
	$expire     = $notBefore + 900;            // Adding 60 seconds
	$serverName = $_SERVER['SERVER_NAME']; // Retrieve the server name from config file

	/*
	 * Create the token as an array
	 */
	$data = [
		'iat'  => $issuedAt,         // Issued at: time when the token was generated
		'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
		'iss'  => $serverName,       // Issuer
		'nbf'  => $notBefore,        // Not before
		'exp'  => $expire,           // Expire
		'data' => [                  // Data related to the signer user
			'userId'   => $auth_data['uuid'], // userid from the users table
		        'userName' => $auth_data["username"], // User name
		]
	];

	$jwt = JWT::encode($data, PRIVATE_KEY, 'RS256');
        generateSession(array(
                                "token"  => $jwt,
                                "uuid" => $auth_data['uuid'],
                                "userid" => $auth_data['id'],
                                "roleid" => 0
                             ));

	return $jwt; 
}

function authLogin($data){
	try{
	$auth_data = _authLogin($data);
	$jwt = _generateToken($auth_data);
	return array("token"=>$jwt);
}catch(Exception $e){
	throw new Exception($e->getMessage());
  }
}


function validateToken($jwt,$action,$publisherid){
	try{
		$jwtData = JWT::decode($jwt, PUBLIC_KEY, array('RS256'));

		$data = _validateToken($jwt,$jwtData);

                if(empty($data)){
                        throw new Exception("Invalid Token");
                }
 		$expiretime = $jwtData->exp - time();
		if ($expiretime < 30) {
		  $auth_data =  _userDataByUUID($jwtData->data->userId);
                  $jwt =  _generateToken($auth_data);
		}

		$roleData = getUserRoleByUUIDandPublisherId($jwtData->data->userId,$publisherid);

		if(empty($roleData)){
                  throw new Exception("Invalid Publisher id");
                }

                $permissionData = getPermissionByName($action);

		if(empty($permissionData)){
                  throw new Exception("Invalid Permission Access Name");
                }


		$permissionRoleMappingData = getPermissionRoleMapping($roleData["id"],$permissionData["id"]);
                
	        if(empty($permissionRoleMappingData)){
                  throw new Exception("Invalid Acess");
                }
	
		$data = array("jwt"=>$jwt,"roleData"=>$roleData,"permissionData"=>$permissionData);
			
	}catch(Exception $e){
		$data = array("error"=>$e->getMessage());
	} 
	return $data;
}

function getPermissionRoleMapping($roleid,$permissionid){
  $sql = "SELECT * FROM `roles_permission_mapping` WHERE roleid=:roleid and permissionid=:permissionid LIMIT 1";
  $data = fetchAssocWithoutCache($sql, array("permissionid="=>$permissionid,"roleid"=>$roleid));
  return !empty($data)?current($data): null;
}

function _validateToken($jwt,$data){

	$sql = "SELECT * from session WHERE token=:token and uuid=:uuid";
	$data = fetchAssocWithoutCache($sql, array("token"=>sha1($jwt),"uuid"=>$data->data->userId));

	return !empty($data)?current($data): null;
}


function getUserRoleByUUIDandPublisherId($uuid,$publisherid){
  $sql = "SELECT roles.id, roles.rolename,users.firstname, users.lastname FROM users INNER JOIN users_roles_mapping ON (users_roles_mapping.userid = users.id) INNER JOIN roles ON (users_roles_mapping.roleId = roles.id ) WHERE users.uuid=:uuid and users_roles_mapping.publisherid=:publisherid LIMIT 1";
  $data = fetchAssocWithoutCache($sql, array("uuid"=>$uuid,"publisherid"=>$publisherid));
  return !empty($data)?$data:null;
}


function getPermissionByName($action){
  $sql = "SELECT permission.id,permissionname,url,description from permission WHERE permissionname =:permissionname LIMIT 1";
  $data = fetchAssocWithoutCache($sql, array("permissionname"=>$action));
  return !empty($data)?current($data): null;
}


function generateSession($data){
        $sql = "DELETE FROM session WHERE token=:token";
        dmlFunctions($sql,array("token"=> sha1($data["token"])));
	$sql = "INSERT INTO session(token,uuid,userid,roleid,createdat) VALUES(:token,:uuid,:userid,:roleid,CURRENT_TIMESTAMP)";
	dmlFunctions($sql,array(
				"token"=> sha1($data["token"]),
				"uuid"=> $data["uuid"],
				"userid"=> $data["userid"],
				"roleid"=> $data["roleid"],
			       ));
}
