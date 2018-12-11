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
			printf($mask, "--create_database", "creates a dedicated database called Temp_DB in MySQL which can be used to store data");
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
		case '--create_database':
			create_database();
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

		echo "Please type your MySQL username: \n";
		$username = trim(fgets(STDIN));
		echo "Please type your MySQL password: \n";
		$password = trim(fgets(STDIN));
		echo "Please type in your MySQL host name: \n";
		$host = trim(fgets(STDIN));
		echo "Please type in your MySQL host name: \n";
		$db = trim(fgets(STDIN));

		foreach ($data_entries as $key => $value) {
			if($key!='0'){

				$modified_data = array(capName($value[0]), capSurname($value[1]), validateEmail($value[2]));

				if($modified_data[2]!==false){



					insert_data($modified_data, $host, $username, $password, $db);
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

	function insert_data($data, $host, $username, $password, $db){


		$link = mysqli_connect($host, $username, $password, $db);

		if($link===false){
			die("Connection to database failed: ".mysqli_connect_error()." \n");
		}

		$query = "INSERT INTO users (name, surname, email) VALUES (\"$data[0]\", \"$data[1]\", \"$data[2]\")";

		if(mysqli_query($link, $query)){
			echo "Inserts successful \n";
		} else {
			echo "Issues inserting data : ".mysqli_error($link).". Data for $data[0] $data[1] not added to table \n";
		}

		mysqli_close($link);

	}

	function create_database(){

		echo "Please type your MySQL username: \n";
		$username = trim(fgets(STDIN));
		echo "Please type your MySQL password: \n";
		$password = trim(fgets(STDIN));
		echo "Please type in your MySQL host name: \n";
		$host = trim(fgets(STDIN));

		# Create the database
		$link = mysqli_connect($host, $username, $password);

		if($link===false){
			die("MySQL could not connect".mysqli_connect_error()." \n");
		}

		$sql_db = "CREATE DATABASE Temp_DB";

		if(mysqli_query($link,$sql_db)){
			echo "Database Temp_DB successfully created \n";
		} else {
			echo "Could not execute $sql_db ".mysqli_error($link)." \n";
		}
		mysqli_close($link);
	}

	function create_table(){
		echo "Please type your MySQL username: \n";
		$username = trim(fgets(STDIN));
		echo "Please type your MySQL password: \n";
		$password = trim(fgets(STDIN));
		echo "Please type in your MySQL host name: \n";
		$host = trim(fgets(STDIN));
		echo "Please type in your MySQL database you would like to use \n";
		$db = trim(fgets(STDIN));
		
		# Create the table within the database
		$link = mysqli_connect($host, $username, $password, $db);

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
			echo "users table successfully created in Temp_DB \n";
		} else {
			echo "Could not execute $sql_table ".mysqli_error($link);
		}
		mysqli_close($link);
			echo "Connection closed \n";

	}

?>