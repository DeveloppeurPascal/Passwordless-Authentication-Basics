<?php
	// Passwordless Authentication Basics
	// (c) Patrick Prémartin
	//
	// Distributed under license AGPL.
	//
	// Infos and updates :
	// https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics

	if (! defined("f2ghj54s2dfghfd5gnbfgjkgfn")) {
		define("f2ghj54s2dfghfd5gnbfgjkgfn",true);
		
		if (("127.0.0.1" == $_SERVER["SERVER_ADDR"]) || ("::1" == $_SERVER["SERVER_ADDR"])) {
			// parameters for a localhost web site (dev, debug, test)
			@include_once(__DIR__."/../protected/config-dev.inc.php");
		} else {
			// parameters for a real domain name or IP (release)
			@include_once(__DIR__."/../protected/config-release.inc.php");
		}
		// default values for all parameters
		require_once(__DIR__."/../protected/config-dist.inc.php");
	}