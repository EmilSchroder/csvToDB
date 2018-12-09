# Processing a CSV file and adding its contents to a database.

The purpose of the user_upload.php script is to create a DB table 'users', parse a CSV file containing names, surnames and emails to validate the information and then insert into the DB if it passes vaildation, and reject with an error otherwise.  

## Script Command Line Directives

### --create_table
This command will be used to create the MySQL 'users' table

### --file [csv file name]
To be used to parse the csv file and validate the information. Once information is validated and/or errors are resolved then it will give the option of inserting the data into the database.

### --dry_run
Used as an add-on to the --file command, this will perform the validations without having the option of adding the data to a database.

``` --file [csv file name] --dry_run```

### -u 
MySQL username

### -p
MySQL password

### -h
MySQL host

### -help
Will output the list of command line directives with details.

## Flow


