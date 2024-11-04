# User
## 1. GET : account/user
*__PUT : account/update__ will have the same response structure*
Response Json
```json
{
    "id": 3,
    "first_name": "Steve",
    "last_name": "Test",
    "role": null,
    "dob": null,
    "gender": null,
    "email": "steve.test@example.com",
    "phone": null,
    "pets_count": 1,
    "is_form_filled": null,
    "profile_image": "http://localhost:8000/storage/3/uQiwb3K8yNlFztWZMubGZbleikcdSM8H1xWjTgYu.png?expires=1730755278&signature=37163eeda05c1a26d806af3324a93f9922a95187c3fb0e46adc9b79c8b1a75e2",
    "address": null
}
```

## 2. POST : account/update
Response Json
```json
{
    "id": 3,
    "first_name": "Steve",
    "last_name": "Tester",
    "role": null,
    "dob": null,
    "gender": null,
    "email": "steve.test@example.com",
    "phone": "(123) 456-7890",
    "pets_count": 1,
    "is_form_filled": null,
    "profile_image": "http://localhost:8000/storage/3/uQiwb3K8yNlFztWZMubGZbleikcdSM8H1xWjTgYu.png?expires=1730755341&signature=e0e1040eec82a0803bff01ff95bf388ac71a1af610b2a873134af56bacf76149",
    "address": {
        "id": 1,
        "user_id": 3,
        "street_name": "103 Redfox Grove",
        "city": "Waterloo",
        "province": "Alberta",
        "postal_code": "A1B 2C3",
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
            "id": 11,
            "first_name": "Apurva Test",
            "last_name": "Test",
            "email": "apurva@example.com",
            "profile_image": null
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
            "id": 11,
            "first_name": "Apurva Test",
            "last_name": "Test",
            "email": "apurva@example.com",
            "profile_image": null
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
        "id": 9,
        "first_name": "Jane",
        "last_name": "Doe 4",
        "role": null,
        "dob": null,
        "gender": null,
        "email": "jane4.doe@test.com",
        "phone": null,
        "pets_count": 0,
        "is_form_filled": "0",
        "profile_image": null,
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
        "id": 9,
        "first_name": "Jane",
        "last_name": "Doe 4",
        "role": null,
        "dob": null,
        "gender": null,
        "email": "jane4.doe@test.com",
        "phone": null,
        "pets_count": 0,
        "is_form_filled": "0",
        "profile_image": null,
        "address": null
    }
}
```

### 3. 


