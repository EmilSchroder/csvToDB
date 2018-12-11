<?php 

	

	$command = $argv[1];

	$username = "";
	$password = "";
	$host = "";

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
			create_table();
			break;
		case '--file':
			$file = $argv[2];
			parseFile($file);
			break;
		default:
			echo "Command not recognised, please use --help to get list of valid commands";
			break;
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

				if($modified_data[2]!==false){
					insert_data($modified_data);
				} else {
					echo "User ".$modified_data[0]." ".$modified_data[1]." has an invalid email address of ".$value[2]." \n";
				}
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
			return $email;
		} else {
			return false;
		}

	}

	function insert_data($data){
		echo "AOK \n";
	}

	function create_table(){
		echo "Please type your MySQL username: \n";
		$username = trim(fgets(STDIN));
		echo "Please type your MySQL password: \n";
		$password = trim(fgets(STDIN));
		echo "Please type in your MySQL host name: \n";
		$host = trim(fgets(STDIN));

		$link = mysqli_connect($host, $username, $password);

		if($link===false){
			die("MySQL could not connect".mysqli_connect_error()." \n");
		}

		$sql_db = "CREATE DATABASE Emil_temp_DB";

		if(mysqli_query($link,$sql_db)){
			echo "DB Emil_temp_DB successfully created \n";
		} else {
			echo "Could not execute $sql_db ".mysqli_error($link);
		}
		mysqli_close($link);
	
		$link = mysqli_connect($host, $username, $password, "Emil_temp_DB");

		if($link===false){
			die("Connection to database failed: ".mysqli_connect_error()." \n");
		}

		$sql_table = "CREATE TABLE users(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			name VARCHAR(40) NOT NULL,
			surname VARCHAR(40) NOT NULL,
			email VARCHAR(80) NOT NULL UNIQUE 
			)";

		if(mysqli_query($link,$sql_table)){
			echo "users table successfully created in Emil_temp_DB \n";
		} else {
			echo "Could not execute $sql_table ".mysqli_error($link);
		}
		mysqli_close($link);


	}

?>