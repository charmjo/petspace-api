# Pet
## 1. GET : pet/pet-list
Response Json
```json
{
    "pets_owned": [
        {
            "id": 1,
            "name": "Doggo",
            "breed": "border collie"
        },
        {
            "id": 2,
            "name": "Meatball",
            "breed": "shiba"
        },
        {
            "id": 3,
            "name": "Meatball",
            "breed": "shiba"
        },
        {
            "id": 4,
            "name": "Meatball",
            "breed": "chow chow"
        }
    ],
    "linked_pets": []
}
```
## 2. POST : pet/create
```json
{
    "message": "Pet added successfully"
}
```
## 3. POST : pet/update
**Request Parameters**
| Field   | Value          |
|---------|----------------|
| pet_owner_id      | 2              |
| breed    | border collie          |
| animal_type   | dog  |
| dob     | 2022/06/28     |
| gender  | male           |
| type    | dog            |
| color   | white          |
| name   | fluffy          |

Response JSON
```json
{
    "id": 1,
    "name": "Doggo",
    "breed": "border collie",
    "dob": "2022/06/28",
    "gender": "male",
    "animal_type": "dog",
    "color": "white"
}
```
## 3. DELETE : pet/delete/{id}
```json
{
    "message": "Pet removed successfully"
}
```
## 4. GET : pet/pet-detail/{id}
```json
{
    "id": 1,
    "name": "Doggo",
    "breed": "border collie",
    "dob": "2022/06/28",
    "gender": "male",
    "animal_type": "dog",
    "color": "white"
}
```

