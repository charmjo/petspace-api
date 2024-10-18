# Version Notes October 2024
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

## October 9

### Account and Pet Management
- added delete and update user account for mobile, still working out web route.
- so requests will look like:
    - PUT account/update/{id}
    - DELETE account/delete/{id}
- delete uses the DELETE method (route looks like account/update/{id}), update uses the UPDATE method (route looks like account/delete/{id})