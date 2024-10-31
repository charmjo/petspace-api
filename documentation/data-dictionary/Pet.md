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
## 3. PUT : pet/update
Request JSON
```json
{
    "name": "dookie",
    "breed": "chihuahua",
    "animal_type": "dog",
    "dob": "July 01, 2020",
    "color": "black",
    "gender": "female"
}
```
Response JSON
```json
{
    "id": 2,
    "name": "dookie",
    "breed": "dog",
    "dob": "July 01, 2020",
    "gender": "female"
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
    "id": 2,
    "name": "Meatball",
    "breed": "dog",
    "dob": "2022/06/28",
    "gender": "male"
}
```

