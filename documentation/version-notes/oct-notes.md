# Version Notes October 2024
## October 28
### Auth - Web
- I know this seems useless to implement stateful authentication but i am still figuring out how to have the same prefixes for web and mobile. For now, I added ```web``` as a prefix for web requests as workaround to bypass csrf token mismatches for requests done on mobile.
- Please add the ```X-XSRF-TOKEN cookie``` in the headers when making requests with **POST, PUT, DELETE** 
- User management routes added:
    - GET /member-list
    - POST /member/add
    - DELETE /member/delete/{id}

## October 25
### Auth
- Added email verification. After user registers, an email is sent with the link. If you don't like how the email and the user confirmation page looks, contact me.
- Remember Me already exists, just pass the 
```json
    "remember":true 
```
parameter to generate a Remember Me token.
- Added resend verification route. user must be authenticated to access this route.
``` /email/verification-notification```
- Changed session driver to file to reduce database bloat. Sessions are stored in file with encryption.

## October 22
### Pet Management
- added ```POST :: /create``` route.
- added ```DELETE :: /delete/{id}``` route.
- added ```PUT :: /update/{id} ``` route.
- added ```POST :: /pet-list``` route.
    - TODO: add linked pets to access. 
- added ```POST :: /pet-detail``` route.
    - TODO: verify if user retrieving this has acces to view pet details
    - TODO: add photo to the list. i was thinking of not adding the photo to this as the FE people will need to manage on storing it on their end.
    -should I add the records as well?
- so requests will look like:
    - PUT account/update/{id}
    - DELETE account/delete/{id}
- delete uses the DELETE method (route looks like account/update/{id}), update uses the UPDATE method (route looks like account/delete/{id})

## October 9

### Account and Pet Management
- added delete and update user account for mobile, still working out web route.
- so requests will look like:
    - PUT account/update/{id}
    - DELETE account/delete/{id}
- delete uses the DELETE method (route looks like account/update/{id}), update uses the UPDATE method (route looks like account/delete/{id})

## October 8

### Account and Pet Management
- added tables for pets and users
- added soft delete so as to inactivate users instead of deleting them from db.
- For **developers with existing petspace.sqlite db's** please, delete your db as I made changes to the migration schema. Run ```php artisan migrate``` and ```php artisan db:seed```. 
- I added 5 users in the seed for testing
- name is now divided into two: first_name and last_name. 
- When doing a request, please do separate paramters for **first_name** and **last_name**

### Auth
#### Mobile
- Implemented single-token per user and device type. Old tokens get deleted when a new one is requested
- Tokens now expire after two hours.
- sanctum/get-token now changed to http://localhost:8000/api/get-token

#### Mobile
- Uses the same route as web.