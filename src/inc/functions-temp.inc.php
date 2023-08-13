<?php
	// Passwordless Authentication Basics
	// (c) Patrick Prémartin
	//
	// Distributed under license AGPL.
	//
	// Infos and updates :
	// https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics

function getTempFileName($par1, $par2="", $par3="", $par4="", $par5="") {
	$name = md5($par1.$par2.$par3.$par4.$par5);
	$name = substr($name, 0, strlen($name)/2);
	$path = "";
	for ($i=0; $i < strlen($name); $i+=3) {
		$path .= ((0 < strlen($path))?"/":"").substr($name, $i, 3);
	}
	return $path;
}

function getTempFilePath($FileName) {
	return __DIR__."/../temp/".$FileName;
}

function LoadTempFile($FileName) {
	$fn = getTempFilePath($FileName)."/tempdata.tmp";
	if (file_exists($fn)) {
		$result = unserialize(file_get_contents($fn));
	}
	else {
		$result = new stdClass();
		$result->tmp_filename = $FileName;
	}
	return $result;
}

function SaveTempFile($Data) {
	if ((! isset($Data)) || (! is_object($Data)) || (! isset($Data->tmp_filename))) {
		die("Can't store temporary data ! (format error)");
	}
	$fn = getTempFilePath($Data->tmp_filename);
	if ((! is_dir($fn)) && (! mkdir($fn,0777,true))) {
		die("Can't store temporary data ! (storrage error)");
	}
	file_put_contents($fn."/tempdata.tmp", serialize($Data));
}
