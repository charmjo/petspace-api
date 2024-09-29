# PetSpace API
## TO DO
### Mobile
- [ ] Set up mobile routing
- [ ] Test connection with mobile app
- [ ] Set up mobile authentication
### Web
- [ ] Test login
- [ ] Test logout 
- [ ] Test Registration

## If you're curious as to how to run this...
This is assuming you have pulled the repo from github. 
 
### Prerequisites:
- Php installed
- Composer (PHP equivalent for npm)
- (I did not include any other database as I am using sqlite for testing)

### Setup:
Before doing anything make sure:
1. *you're entering these commands at the root folder*
2. *your php interpreter is running*
---
1. run ``` composer install``` to install dependencies
2. run ``` php artisan migrate``` to setup the database
3. run ``` php artisan db:seed``` to seed the db (this should not matter once the user crud is set up.)
4. run ``` php artisan serve ``` to run the laravel server for testing 
5. Feel free to use __Postman__  to test the web endpoints :)

### Notes:
- Message me (charmjo) for the .env file
- Make sure no localhost instance is running at port 8000, if there is, 
  check which port the app is served and change the url from there.
- 

## Setting up mobile testing
**TODO**
