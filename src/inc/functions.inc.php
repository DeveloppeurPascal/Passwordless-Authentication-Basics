<?php
	// Passwordless Authentication Basics
	// (c) Patrick Prémartin
	//
	// Distributed under license AGPL.
	//
	// Infos and updates :
	// https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics

// Return current connected user ID to find it in the database
function getCurrentUserId() {
	return (isset($_SESSION["id"]) && (! empty($_SESSION["id"])))?intval($_SESSION["id"]):0;
}

// Set current connected user ID
function setCurrentUserId($user_id) {
	$_SESSION["id"] = $user_id;
}

// Return current connected user email
function getCurrentUserEmail() {
	return (isset($_SESSION["email"]) && (! empty($_SESSION["email"])))?$_SESSION["email"]:"";
}

// Set current connected user email
function setCurrentUserEmail($user_email) {
	$_SESSION["email"] = $user_email;
}

// Check if a user is connected and the session started
function hasCurrentUser() {
	return ((! empty(getCurrentUserEmail())) && (0 < getCurrentUserId()));
}

// Get PDO object connected to the database
function getPDOConnection() {
	global $PrivatePDOConnection;

	if (! isset($PrivatePDOConnection)) {
		require_once(__DIR__."/config.inc.php");

		if ((!defined("DB_NAME")) || empty(DB_NAME)) {
			die("Unconfigured site. Please customize the settings before running the programs.");
		}

		try {
			$PrivatePDOConnection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=UTF8", DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
		}
		catch (PDOException $e) {
			die("Problème de base de données. Contactez l'administrateur du site.");
		}
	}

	return $PrivatePDOConnection;
}

function getNewIdNumber($size = 10) {
    $id = "";
    for ($j = 0; $j < $size / 5; $j++) {
        $num = mt_rand(0, 99999);
        for ($i = 0; $i < 5; $i++) {
            $id = ($num % 10) . $id;
            $num = floor($num / 10);
        }
    }
    return (substr($id, 0, $size));
}

function getNewIdString($size = 10) {
    $id = "";
	while (strlen($id) < $size) {
        $num = mt_rand(1, 10+26+26)-1;
		if ($num < 10) {
			$id .= chr(ord('0')+$num);
		}
		else if ($num < 10+26) {
			$id .= chr(ord('a')+$num-10);
		}
		else if ($num < 10+26+26) {
			$id .= chr(ord('A')+$num-10-26);
		}
    }
    return $id;
}
