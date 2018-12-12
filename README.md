# Processing a CSV file and adding its contents to a database.

The purpose of the user_upload.php script is to create a DB table 'users', parse a CSV file containing names, surnames and emails to validate the information and then insert into the DB if it passes vaildation, and reject with an error otherwise.  

## Requirements 

* PHP7
* MySQL

## Quick start

1. Clone down the repo and run using ```php user_upload.php``` command in the command line.
2. Set the MySQL host, username and passwords to your settings using the ```-h```, ```-u``` and ```-p``` commands seperately.
3. Create a new schema using ```--create_database``` and following prompts.
4. Create "users" table using ```--create_table``` and following prompts.
5. Insert data into table using ```--file users.csv``` and following prompts. ```users.csv``` can be replaced with another file that is save into the repo.

## Script Command Line Directives

### --help
Will output the list of command line directives with details.

### -h
Reads current MySQL host and gives the option of changing it. Starts at default value "root".

### -u 
Reads current MySQL username and gives the option of changing it. Starts at default value "".

### -p
Reads current MySQL password and gives the option of changing it. Starts at default value "localhost".

### --create_database (optional)
This creates a new database called ```Temp_DB``` to be used to hold the ```users``` table.

### --create_table
This command will be used to create the MySQL 'users' table with id, name, surname and email columns within a database that the user specifies.

### --file [csv file name]
To be used to parse the csv file, validate the information and insert it into the ```users``` table of a specified database.

### --dry_run
Used as an add-on to the --file command, this will perform the validations without having the option of adding the data to a database.

``` --file [csv file name] --dry_run```

### --read
Lists out the contents of the csv file in the command line.

### --exit
Exits the program.

## Email Vailidation

Emails will be validated using PHP's input validation filter FILTER_VALIDATE_EMAIL.

## Data cleaning

Names, surnames and emails will be set to all lower case and have whitespace removed. Names and surnames will then be capatailised. The use of punctuation will not be scrubbed from a name or surname given the existence of at least one individual that possesses punctuation as part of their legal name (see: http://members.calbar.ca.gov/fal/Licensee/Detail/240959) though this can be revisited if the need is there to scrub.

## Error messages

```Command not recognised``` - the command given to the terminal is not one that user_upload.php is programmed to handle or is a misspelling of an accepted command.

```Error: file [file.csv] does not exist``` - the file being looked up is either not saved in the current directory or has been misspelt.

```User [name] has an invalid email address of [email]``` - the email address provided has not passed verification so the user will not be added to the database.

```Connection to database failed``` - the script has been unable to link up to the MySQL database. Check your host, username and password for MySQL using the command line directives.

```Issues inserting data``` - most commonly the data contains a duplicate email so will not be added to the database.

```Could not execute [SQL code]``` - there is an issue with the database link. Check your host, username and password for MySQL using the command line directives.


#### Credits 
Helpful websites included php.net, tutorialrepublic.com, codedevelopr.com and dev.mysql.com


