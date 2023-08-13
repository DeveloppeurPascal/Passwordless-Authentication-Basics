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
			$password = isset($_POST["password"])?trim(strip_tags($_POST["password"])):"";
			if (empty($password)) {
				$error = true;
				$error_message .= "Fill your password to connect.\n";
				$DefaultField = "Password";
			}
			else {
				$db = getPDOConnection();
				if (! is_object($db)) {
					$error = true;
					$error_message .= "Database access error. Contact the administrator.\n";
				}
				else {
					$qry = $db->prepare("select id, password, pwd_salt, enabled from users where email=:email limit 0,1");
					$qry->execute(array(":email" => $email));
					if (false === ($rec = $qry->fetch(PDO::FETCH_OBJ))) {
						$error = true;
						$error_message .= "Unknown user.\n";
					}
					else if (1 != $rec->enabled) {
						$error = true;
						$error_message .= "Access denied.\n";
					}
					else if (getEncryptedPassword($password, $rec->pwd_salt) != $rec->password) {
						$error = true;
						$error_message .= "Access denied.\n";
					}
					else {
						setCurrentUserId($rec->id);
						setCurrentUserEmail($email);
						header("location: ".URL_CONNECTED_USER_HOMEPAGE);
						exit;
					}
				}
			}
		}
	}
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
		<title>Log in - Passwordless Authentication Basics</title>
		<style>
			.error {
				color: red;
				background-color: yellow;
			}
		</style>
	</head>
	<body><?php include_once(__DIR__."/inc/header.inc.php"); ?>
		<h2>Log in</h2><?php
	if ($error && (! empty($error_message))) {
		print("<p class=\"error\">".nl2br($error_message)."</p>");
	}
?><form method="POST" action="login.php" onSubmit="return ValidForm();"><input type="hidden" name="frm" value="1">
			<p>
				<label for="User">User email</label><br>
				<input id="User" name="user" type="email" value="<?php print(isset($email)?htmlspecialchars($email):""); ?>" prompt="Your email address">
			</p>
			<p>
				<label for="Password">Password</label><br>
				<input id="Password" name="password" type="password" value="" prompt="Your password">
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
		pwd = document.getElementById('Password');
		if (0 == pwd.value.length) {
			pwd.focus();
			window.alert('New password needed !');
			return false;
		}
		return true;
	}
</script>
		<p><a href="lostpassword.php">Lost password</a></p>
		<p><a href="signup.php">Sign up</a></p>
<?php include_once(__DIR__."/inc/footer.inc.php"); ?></body>
</html>