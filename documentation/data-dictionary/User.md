# User
## 1. GET : account/user
*__PUT : account/update__ will have the same response structure*
Response Json
```json
{
    "id": 6,
    "first_name": "Mob User",
    "last_name": "Test",
    "role": null,
    "dob": null,
    "gender": null,
    "email": "mob_user@test.com",
    "phone": null,
    "pets_count": 4,
    "is_form_filled": "0",
    "address": null
}
```

## 2. POST : account/update
Response Json
```json
{
    "id": 6,
    "first_name": "Mob User",
    "last_name": "Useroo",
    "role": null,
    "dob": null,
    "gender": null,
    "email": "mob_user@test.com",
    "phone": null,
    "pets_count": 4,
    "is_form_filled": "0",
    "address": {
        "id": 1,
        "user_id": 6,
        "street_name": "103 Redfox Grove",
        "city": "Waterloo",
        "province": "Nova Scotia",
        "postal_code": "A1B 3C4",
        "country": "Canada"
    }
}
```

## 3. GET : account/member/member-list
   Response Json
```json
{
    "list": [
        {
            "id": 1,
            "first_name": "Apurva Test",
            "last_name": "Test",
            "email": "apurva@example.com"
        },
        {
            "id": 2,
            "first_name": "Steve",
            "last_name": "Test",
            "email": "steve.test@example.com"
        }
    ]
}
```

## 3. POST : account/member/add
Response Json
```json
{
    "message": "Family member added successfully.",
    "list": [
        {
            "id": 1,
            "first_name": "Apurva Test",
            "last_name": "Test",
            "email": "apurva@example.com"
        },
        {
            "id": 2,
            "first_name": "Steve",
            "last_name": "Test",
            "email": "steve.test@example.com"
        },
        {
            "id": 3,
            "first_name": "Charm",
            "last_name": "Test",
            "email": "test2@example.com"
        }
    ]
}
```

## 3. DELETE : account/member/remove
Response Json
```json
{
    "message": "Family member removed successfully.",
    "list": [
        {
            "id": 1,
            "first_name": "Apurva Test",
            "last_name": "Test",
            "email": "apurva@example.com"
        },
        {
            "id": 3,
            "first_name": "Charm",
            "last_name": "Test",
            "email": "test2@example.com"
        }
    ]
}
```

## other web routes:

### 1. api/register
Response JSON
```json
{
    "two_factor": false,
    "data": {
        "id": 7,
        "first_name": "Jane",
        "last_name": "Doe 5",
        "role": null,
        "dob": null,
        "gender": null,
        "email": "jane5.doe@test.com",
        "phone": null,
        "pets_count": 0,
        "is_form_filled": "0",
        "address": null
    }
}
```

### 2. api/login
Response JSON
```json
{
    "two_factor": false,
    "data": {
        "id": 7,
        "first_name": "Jane",
        "last_name": "Doe 5",
        "role": null,
        "dob": null,
        "gender": null,
        "email": "jane5.doe@test.com",
        "phone": null,
        "pets_count": 0,
        "is_form_filled": "0",
        "address": null
    }
}
```

### 3. 


