<?php 

	

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
			printf($mask, "--read_file [csv file]", "Lists out the file specified in the terminal");
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
		case '--read_file':
			$file = $argv[2];
			readfile($file);
			break;
		case '--create_table':
			createTable();
			break;
		case '--file':
			$file = $argv[2];
			parseFile($file);
			inputFile($file);
			break;
		default:
			echo "Command not recognised, please use --help to get list of valid commands";
			break;
	}

	function createTable(){
		echo "Creating a table \n";
	}

	function parseFile($file){
	// open and read csv file into an array
		$explore_file = fopen($file, 'r');
		while(!feof($explore_file)){
			$data_entries[] = fgetcsv($explore_file, 1024);
		}
		fclose($explore_file);

		validateData($data_entries);
	}

	function validateData($data_entries){
		foreach ($data_entries as $key => $value) {
			if($key!='0'){
				$modified_data = array(capName($value[0]), capSurname($value[1]), validateEmail($value[2]));
			}
		}
	}

	function capName($name){
		return $name = trim(ucfirst(strtolower($name)));
	}

	function capSurname($surname){			
		return $surname = trim(ucfirst(strtolower($surname)));
	}

	function validateEmail($email){
		$email = trim(strtolower($email));

		$is_valid=filter_var($email, FILTER_VALIDATE_EMAIL);

		if($is_valid){
			echo "legit \n";
		} else {
			echo "nah man \n";
		}

	}

	function inputFile($file){
		echo "The file ".$file." be in \n";
	}

?>