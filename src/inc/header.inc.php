<?php
	// Passwordless Authentication Basics
	// (c) Patrick Prémartin
	//
	// Distributed under license AGPL.
	//
	// Infos and updates :
	// https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics
?><header>
	<h1>Passwordless Authentication Basics</h1>
	<p>Open source project available on <a href="https://github.com/DeveloppeurPascal/Passwordless-Authentication-Basics">GitHub</a>.</p>
	<p><?php
		print("<button onclick=\"document.location = 'index.php'; return true;\">Home</button> ");
		if (hasCurrentUser()) {
			print("<button onclick=\"document.location = 'logout.php'; return true;\">Log out</button> ");
		}
		else {
			print("<button onclick=\"document.location = 'login.php'; return true;\">Log in</button> ");
		}
?></p>
</header>