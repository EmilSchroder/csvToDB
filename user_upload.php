<?php 

	$GLOBALS['username'] = "catalystuser";
	$GLOBALS['password'] = "Password1@";
	$GLOBALS['host'] = "localhost";

	echo "Welcome to csv inputter. For options on what to do type --help \n";



	$command = explode(" ", fgets(STDIN));
	call_switch($command);

function call_switch($command){

	switch (trim($command[0])) {
		case '--help':

			$mask = "%-20s | %-30s | x |\n";
			printf($mask, "--file [csv name]", "runs validations on csv file data and inserts vaild data into the users table");
			printf($mask, "--create_table", "creates the users table in the database");
			printf($mask, "--dry_run", "used as a postscript to --file [csv name] to just run data vaildations on a csv file");
			printf($mask, "-u", "MySQL username");
			printf($mask, "-p", "MySQL password");
			printf($mask, "-h", "MySQL host");
			printf($mask, "--help", "Lists command directives and their effects");
			printf($mask, "--read [csv file]", "Lists out the file specified in the terminal");
			printf($mask, "--create_database", "creates a dedicated database called Temp_DB in MySQL which can be used to store data");

			call_switch(end_statement());	
			break;
		case '-u':
			echo "Current MySQL username is ".$GLOBALS['username'].". Type -k to keep or write in a new username \n";
			$GLOBALS['username'] = trim(fgets(STDIN));
			if($GLOBALS['username']!="-k"){
				echo "username changed to ".$GLOBALS['username']." \n";
			}
			call_switch(end_statement());
			break;
		case '-p':
			echo "Current MySQL password is ".$GLOBALS['password'].". Type -k to keep or write in a new password \n";
			$GLOBALS['password'] = trim(fgets(STDIN));
			if($GLOBALS['password']!="-k"){
				echo "password changed to ".$GLOBALS['password']." \n";
			}
			call_switch(end_statement());
			break;
		case '-h': 
			echo "Current MySQL host is ".$GLOBALS['host'].". Type -k to keep or write in a new host \n";
			$GLOBALS['host'] = trim(fgets(STDIN));
			if($GLOBALS['host']!="-k"){
				echo "host changed to ".$GLOBALS['host']." \n";
			}
			call_switch(end_statement());
			break;
		case '--read':
			$file = trim($command[1]);
			readfile($file);
			echo " \n";
			call_switch(end_statement());
			break;
		case '--create_table':
			create_table();
			call_switch(end_statement());
			break;
		case '--create_database':
			create_database();
			call_switch(end_statement());
			break;
		case '--file':
			$file = trim($command[1]);
			echo "commad is ".sizeof($command)." big";
			if(sizeof($command) < 3){
				parseFile($file, false);

			} else if(trim($command[2])==='--dryrun'){
				parseFile($file, true);

			} else {
				echo "Error: invaild command $command[2] ";

			}

			call_switch(end_statement());
			break;
		case '--exit':
			break;
		default:
			echo "Command not recognised, please use --help to get list of valid commands \n";
			call_switch(end_statement());
			break;
	}
}

	function end_statement(){
		echo "\n please enter next command or --exit \n";
		$command = explode(" ", fgets(STDIN));
		return $command;
	}


	function parseFile($file, $dryrun){
	// open and read csv file into an array
		if(file_exists($file)){
		$explore_file = fopen($file, 'r');
		while(!feof($explore_file)){
			$data_entries[] = fgetcsv($explore_file, 1024);
		}
		fclose($explore_file);

		validateData($data_entries, $dryrun);
		}else{
			echo "Error: file $file does not exist";
		}

	}

	function validateData($data_entries, $dryrun){

			echo "Please type in your MySQL database name: \n";
			$db = trim(fgets(STDIN));

		foreach ($data_entries as $key => $value) {
			if($key!='0'){

				$modified_data = array(capName($value[0]), capSurname($value[1]), validateEmail($value[2]));

				if($modified_data[2]!==false){
					if(!$dryrun){

						insert_data($modified_data, $GLOBALS["host"], $GLOBALS["username"], $GLOBALS["password"], $db);
					}else{
						echo "dryrun complete for ".$modified_data[0]." ".$modified_data[1].". No data inserted \n";
					}

				} else {
					echo "User ".$modified_data[0]." ".$modified_data[1]." has an invalid email address of ".$value[2].". Data not inserted \n";
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

		# Create the database
		$link = mysqli_connect($GLOBALS["host"], $GLOBALS["username"], $GLOBALS["password"]);

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

		echo "Please type in your MySQL database you would like to use \n";
		$db = trim(fgets(STDIN));
		
		# Create the table within the database
		$link = mysqli_connect($GLOBALS["host"], $GLOBALS["username"], $GLOBALS["password"], $db);

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

	}

?>