<?php 

	require('./createUserTable.php');

	$command = $argv[1];

	switch ($command) {
		case '--help':

			$mask = "%-20s | %-30s | x |\n";
			printf($mask, "--file [csv name]", "runs validations on csv file data and inserts vaild data into the users table");
			printf($mask, "--create_table", "creates the users table in the database");
			printf($mask, "--dry_run", "used as a postscript to --file [csv name] to just run data vaildations on a csv file");
			printf($mask, "-u", "MySQL username");
			printf($mask, "-p", "MySQL password");
			printf($mask, "-h", "MySQL host");
			printf($mask, "--help", "Lists command directives and their effects");
			break;
		case '-u':
			echo "MySQL username is \"catalystuser\" \n";
			break;
		case '-p':
			echo "MySQL password is \"Password1@\" \n";
			break;
		case '-h': 
			echo "MySQL host is \"??????\" \n";
			break;
		case '--create_table':
			createUserTable();
			break;
		default:
			# code...
			break;
	}

?>