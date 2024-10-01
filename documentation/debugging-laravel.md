# Debugging Laravel app
## SQLite issue
- So, in the .env file, what is initially added for database schema generation is the database name under
DATABASE_NAME 
- So, when there is an issue with the querying to the databse, the **absolute path of the the sqlite file** must be used
- Don't forget to save the .env file 
- run the following after changing the path of your database name:  
    ```
        php artisan config:clear
        php artisan cache:clear
        php artisan optimize 
    ```
- then start up the localhost server through ```php artisan serve```