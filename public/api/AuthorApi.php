<?php
$debug = 'I entered AuthorApi with Action '. $_GET['action'];


if (!isset($_GET['action'])){die();}
// with help from https://github.com/RobDWaller/ReallySimpleJWT
require_once ('vendor/autoload.php');
require_once ('/home/vx5ui10wb4ln/farm/CelebrateSql.php');
use ReallySimpleJWT\Token;
define("VERSION", '0.8');
$secret = 'sJeSuStyhgt23*&';


// assign variables

$out = array(); 
$debug = 'Using ActionApi'. "\n";
$debug .= '$p[] = ' . "\n";
$debug .= 'parameters:' . "\n";
foreach ($_POST as $param_name => $param_value) {
	$$param_name = $param_value;
	$p[$param_name] =  $param_value;
	$debug .= $param_name . ' = ' . $param_value. "\n";
}
$debug .= 'end of parameters' . "\n";
$debug .= 'finished post loop' . "\n";
// $p is passed to all functions
$p = $_POST ;
$p['version'] = VERSION;// this can be overwritten
$p['debug'] = '';

// deal with action
$action = $_GET['action'];
$debug .= 'Action: '. $action . "\n";


if (isset($_GET['page'])){
	$debug .=  'Page: ' . $_GET['page'] . '.php'. "\n";
}


// login routine
if ($action == 'login'){
	if (isset($p['email'])){
		$sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
		$data = array(
		  'email' => $p['email']
		);
		$user = sqlReturnArrayOne($sql, $data);
		$debug .= 'uid:'.  $user['uid'] ."\n";
		$debug .= isset($user['debug'])? $user['debug']:'' ."\n";
		if ($user['uid']){
			if (password_verify($p['password'], $user['password']))  {
				$expiration = time() + (30 * 24 * 60 * 60);
					   // 30 days; 24 hours; 60 mins; 60 secs
				$user['expires'] = $expiration;
				$issuer = 'celebrate.myfriends.network';
				$token = Token::create($user['uid'], $secret, $expiration, $issuer);
				unset($user['password']);
				$sql = "SELECT tid FROM members WHERE uid = :uid";
				$data = array(
					'uid' =>$user['uid']
				);
				$teams = sqlReturnArrayMany($sql, $data);
				$user['teams'] = [];
				foreach ($teams as $team){
					$user['teams'][] = $team['tid'];
				}
				$out['content'] = $user;
				$out['token'] = $token;
				$debug .= 'authorized' . "\n";
			}
		}
		else {
			$debug .= 'NOT authorized' . "\n";
		}
	}
    else {
        $debug .= 'NOT authorized' . "\n";
    }
}
else{
    // take action if authorized user
	if (!isset($p['token']) && $action != 'retrievePassword'){die();}
	if ($action != 'retrievePassword'){
		$ok = Token::validate($p['token'], $secret );
	}
	if ($action == 'retrievePassword'){
		$ok = true;
		$p['secret'] = $secret;
	}
    if($ok){
		// add any approved pages
		if (isset($_GET['page'])){
			$debug .= 'I am adding page' . "\n";
			require_once ($_GET['page'] . '.php');
		}
		$debug .= 'action is '  . $action ."\n";
		$out = $action ($p);
		if (isset($out['debug'])){
			if (is_array($out['debug'])){
				foreach ($out['debug'] as $d){
					$debug .= $d;
				}
			}
			else{
				$debug .= $out['debug'];
			}
			
			unset ($out['debug']);
		}
		else{
			$debug .= 'No error messages' . "\n";
		}
    }
    else{
        $debug .= 'NOT AUTHORIED';
    }
}
//
// write log
//
$debug .= "\n\nHERE IS JSON_ENCODE OF DATA THAT IS NOT ESCAPED\n";
$debug .= json_encode($out) . "\n";
//
//CORS	
//https://www.html5rocks.com/en/tutorials/cors/
//https://stackoverflow.com/questions/9631155/specify-multiple-subdomains-with-access-control-origin/9737907
//
 header("Access-Control-Allow-Origin: *");

$fh = fopen('logs/' . $action  . '.txt', 'w');
fwrite($fh, $debug);
fclose($fh);
// return response
header("Content-type: application/json");
echo json_encode($out, JSON_UNESCAPED_UNICODE);
die();
