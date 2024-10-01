# PetSpace API
- For localhost setup debugging issues, go to ```documentation/debugging```

## Modes of Authentication
- For web requests, cookie-based authentication is used
- For mobile requests, token-base authentication is used

## Prerequisites:
- Php installed (xampp is easiest to setup)
- Composer (PHP equivalent for npm)
- Database (I did not include any other database as I am using sqlite for testing, I'm using ORM to make schema migration and versioning less painful)
- Place the api project folder inside ```xampp/htdocs``` 
- Postman (optional) for API endpoint testing

### Download xampp
Installation guide for ```Windows```
https://phpandmysql.com/extras/installing-xampp/

Xampp installer:
https://www.apachefriends.org/download.html

### Setting up Composer:
Composer is php's package manager. Only php is needed to run this.
- For ```macOS``` users, use homebrew for less pain:
https://formulae.brew.sh/formula/composer

- For ```windows``` users:
https://www.geeksforgeeks.org/how-to-install-php-composer-on-windows/


## API Setup:
Before doing anything make sure:
1. *you're entering these commands at the project root folder*
2. *your php interpreter is running*
3. *.env file exists and contains configurations*
---
* **Step 1.** run ``` composer install``` to install dependencies
* **Step 2.** run ``` php artisan migrate``` to setup the database
  * ***Before running this command,*** make sure the .env file is already configured 
  * ```DATBASE_NAME``` env variable should have the database name (ex. petspace.sqlite)
* **Step 3.** run ``` php artisan db:seed``` to seed the db (this should not matter once the user crud is set up.) \
    ***Before running this command, make sure***  
    * to change the ``DATBASE_NAME``` env variable to the absolute path of the sqlite file (c:/pathwhatever/petspace.sqlite)
    * to run the following to clear the cache after making the changes in the .env file:     
    ```
        php artisan config:clear
        php artisan cache:clear
        php artisan optimize 
    ```
* **Step 4.** Laravel sort of 'caches' its settings so the following commands have to be run should there be any changes to the .env file, routes, caches or config:
  * ```php artisan config:clear``` - clear config cache. In most cases, only when there are changes inside the config directory will this be run.
  * ```php artisan cache:clear``` - clear cache
  * ```php artisan optimize```  - general-purpose clearing and caching
* **Step 5.** run ``` php artisan serve ``` to run the laravel server for testing 
* **Step 6.** Feel free to use __Postman__  to test the web endpoints :')

### Notes:
- Message me (charmjo) for the .env file
- Make sure no localhost instance is running at port 8000, if there is, 
  check which port the app is served and change the url from there.

## Setting up mobile testing
**TODO**
