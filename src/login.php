<?php
	// Passwordless Authentication Basics
	// (c) Patrick Prémartin
	//
	// Distributed under license AGPL.
	//
	// Infos and updates :
	// https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics
	
	session_start();
	require_once(__DIR__."/inc/functions.inc.php");
	require_once(__DIR__."/inc/config.inc.php");

	// This page is only available when no user is connected.
	if (hasCurrentUser()) {
		header("location: index.php");
		exit;
	}

	define("CPwdLessAuthForm", 1);
	define("CPwdLessAuthWait", 2);

	$PwdLessAuthStatus = CPwdLessAuthForm;

	$error = false;
	$error_message = "";
	$DefaultField = "User";

	if (isset($_POST["frm"]) && ("1" == $_POST["frm"])) {
		$email = isset($_POST["user"])?trim(strip_tags($_POST["user"])):"";
		if (empty($email)) {
			$error = true;
			$error_message .= "Fill your user email address to connect.\n";
		}
		else {
			// TODO : filter the email to avoid spams
			$db = getPDOConnection();
			if (! is_object($db)) {
				$error = true;
				$error_message .= "Database access error. Contact the administrator.\n";
				$PwdLessAuthStatus = CPwdLessAuthForm;
			}
			else {
				$qry = $db->prepare("select id, enabled from users where email=:email limit 0,1");
				$qry->execute(array(":email" => $email));
				if ((false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) && (0 == $rec->enabled)) {
					$error = true;
					$error_message .= "Access denied.\n";
					$PwdLessAuthStatus = CPwdLessAuthForm;
				}
				if (! $error) {
					$salt = getNewIdString(mt_rand(5,25));
					$connection_code = getNewIdString(25);
					$connection_key = substr(md5($salt.PWDLESS_SALT.$connection_code.$email),7,10);
					$connection_url = SITE_URL."login.php?a=".$connection_code."&k=".$connection_key."&e=".urlencode($email);
					require_once(__DIR__."/inc/functions-temp.inc.php");
					$TempData = LoadTempFile(getTempFileName($email));
					if (! isset($TempData->ConnectionCode)) {
						$TempData->ConnectionCode = array();
					}
					if (! isset($TempData->ConnectionCode[$email])) {
						$TempData->ConnectionCode[$email] = new stdClass();
					}
					$TempData->ConnectionCode[$email]->Salt = $salt;
					$TempData->ConnectionCode[$email]->Expiration = time()+60*60; // 1 hour
					SaveTempFile($TempData);
					if (_DEBUG)
					{
						mail($email, "Your connection link", "Hi\n\nPlease click on this link to connect to our website :\n".$connection_url."\n\nBest regards\n\nThe team");
					}
					else {
						// TODO : replace this by an email check link
						die("Sending a connexion email is not available here.");
					}
					$PwdLessAuthStatus = CPwdLessAuthWait;
				}
			}
		}
	}
	else {
		// sample connection URL : 
		// https://domain/login.php?a=GoaIC46l4FfT9zhuyc2eoZ08Q&k=50a8b9c568&e=pprem%40pprem.net
		
		$connection_code = isset($_GET["a"])?trim($_GET["a"]):false;
		if ((false !== $connection_code) && (! empty($connection_code))) {
			$key = isset($_GET["k"])?trim($_GET["k"]):false;
			if ((false !== $key) && (! empty($key))) {
				$email = isset($_GET["e"])?trim($_GET["e"]):false;
				if ((false !== $email) && (! empty($email))) {
					require_once(__DIR__."/inc/functions-temp.inc.php");
					$TempData = LoadTempFile(getTempFileName($email));
					if (isset($TempData->ConnectionCode[$email])) {
						$salt = isset($TempData->ConnectionCode[$email]->Salt)?$TempData->ConnectionCode[$email]->Salt:"";
						$expiration = isset($TempData->ConnectionCode[$email]->Expiration)?$TempData->ConnectionCode[$email]->Expiration:0;
						if (($key == substr(md5($salt.PWDLESS_SALT.$connection_code.$email),7,10)) && (time() <= $expiration)) {
							unset($TempData->ConnectionCode[$email]);
							SaveTempFile($TempData);
							$db = getPDOConnection();
							if (is_object($db)) {
								$qry = $db->prepare("select id, enabled from users where email=:email limit 0,1");
								$qry->execute(array(":email" => $email));
								if (false !== ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
									if (0 == $rec->enabled) {
										$error = true;
										$error_message .= "Access denied.\n";
										$PwdLessAuthStatus = CPwdLessAuthForm;
									}
									$id = $rec->id;
								}
								else {
									$qry = $db->prepare("insert into users (email, enabled, create_ip, create_datetime) values (:e,1,:ci,:cdt)");
									if (! $qry->execute(array(":e" => $email, ":ci" => $_SERVER["REMOTE_ADDR"], ":cdt" => date("YmdHis")))) {
										$error = true;
										$error_message .= "Can't add this user in the database.\n";
										$PwdLessAuthStatus = CPwdLessAuthForm;
									}
									else {
										$id = $db->lastInsertId();
									}
								}
								if (! $error) {
									$qry = $db->prepare("update users set email_checked=1, email_check_ip=:ip, email_check_datetime=:dt where id=:id");
									$qry->execute(array(":ip" => $_SERVER["REMOTE_ADDR"], ":dt" => date("YmdHis"), ":id" => $id));
									setCurrentUserId($id);
									setCurrentUserEmail($email);
									header("location: ".URL_CONNECTED_USER_HOMEPAGE);
									exit;
								}
							}
						}
					}
				}
			}
			if (! $error) {
				$error = true;
				$error_message .= "Access denied.\n";
				$PwdLessAuthStatus = CPwdLessAuthForm;
			}
		}
	}
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
		<title>Log or sign in - Passwordless Authentication Basics</title>
		<style>
			.error {
				color: red;
				background-color: yellow;
			}
		</style>
	</head>
	<body><?php include_once(__DIR__."/inc/header.inc.php"); ?>
		<h2>Log or sign in</h2><?php
	if ($error && (! empty($error_message))) {
		print("<p class=\"error\">".nl2br($error_message)."</p>");
	}

	switch ($PwdLessAuthStatus) {
		case CPwdLessAuthForm:
?><form method="POST" action="login.php" onSubmit="return ValidForm();"><input type="hidden" name="frm" value="1">
			<p>
				<label for="User">User email</label><br>
				<input id="User" name="user" type="email" value="<?php print(isset($email)?htmlspecialchars($email):""); ?>" prompt="Your email address">
			</p>
			<p>
				<button type="submit">Connect</button>
			</p>
		</form>
<script>
	document.getElementById('<?php print($DefaultField); ?>').focus();
	function ValidForm() {
		email = document.getElementById('User');
		if (0 == email.value.length) {
			email.focus();
			window.alert('Your email address is needed !');
			return false;
		}
		return true;
	}
</script><?php
			break;
		case CPwdLessAuthWait:
?><p>We sent an connexion link by email to your address. Please click on it.</p>
<p>Of course check your spams if you didn't see it in your inbox.</p><?php
			break;
		default :
	}
	include_once(__DIR__."/inc/footer.inc.php"); ?></body>
</html>