# Processing a CSV file and adding its contents to a database.

The purpose of the user_upload.php script is to create a DB table 'users', parse a CSV file containing names, surnames and emails to validate the information and then insert into the DB if it passes vaildation, and reject with an error otherwise.  

To start clone the repository, create the database and run the vaildations using ```php user_upload.php``` then the appropriate command line directive.


## Requirements 

* PHP7
* MySQL


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

## Email Vailidation

Emails will be validated using PHP's input validation filter FILTER_VALIDATE_EMAIL.

## Data cleaning

Names, surnames and emails will be set to all lower case and have whitespace removed. Names and surnames will then be capatailised. The use of punctuation will not be scrubbed from a name or surname given the existence of at least one individual that possesses punctuation as part of their legal name (see: http://members.calbar.ca.gov/fal/Licensee/Detail/240959) though this can be revisited if the need is there to scrub.

## Error messages

```[email address] is not a valid email address``` - the email address provided has not passed verification so the user will not be added to the database.

## Desicions made

* Capatalisation of first and last names will only apply to the first letter i.e. munro -> Munro, o'connor->O'connor.


#### Credits 
Helpful websites included php.net, tutorialrepublic.com, codedevelopr.com and dev.mysql.com


