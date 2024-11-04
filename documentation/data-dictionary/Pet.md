# Pet
## 1. GET : pet/pet-list
Response Json
```json
{
    "pets_owned": [
        {
            "id": 1,
            "name": "Misty",
            "breed": "chihuahua",
            "animal_type": "dog",
            "pet_image": "http://localhost:8000/storage/3/sP0YWZAEnCmb767s41xi4bKOeB7Pya9PdLsDV9I9.jpg?expires=1730759487&signature=c1517b79e0a91d55ff377809d26a3c993276dd56eac3f97070f50adb283e4e32"
        },
        {
            "id": 2,
            "name": "John",
            "breed": "border collie",
            "animal_type": "dog",
            "pet_image": null
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
**Request Parameters** \
| Field         | Value          |
|---------------|----------------|
| pet_owner_id  | 2              |
| breed         | border collie  |
| animal_type   | dog            |
| dob           | 2022/06/28     |
| gender        | male           |
| type          | dog            |
| color         | white          |
| name          | fluffy         |


Response JSON
```json
{
    "id": 1,
    "name": "Misty",
    "breed": "chihuahua",
    "dob": "2022/06/28",
    "gender": "male",
    "animal_type": "dog",
    "color": "brown",
    "pet_image": "http://localhost:8000/storage/3/sP0YWZAEnCmb767s41xi4bKOeB7Pya9PdLsDV9I9.jpg?expires=1730756583&signature=9dc8392e7ca9aed20a5b2b8c1a40aeffadf395d9e28bf3fd9d20b1cc1195bfb2"
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
    "name": "Misty",
    "breed": "chihuahua",
    "dob": "2022/06/28",
    "gender": "male",
    "animal_type": "dog",
    "color": "brown",
    "bio": null,
    "pet_image": "http://localhost:8000/storage/3/sP0YWZAEnCmb767s41xi4bKOeB7Pya9PdLsDV9I9.jpg?expires=1730756727&signature=78215df06373e564c447be2f76df43b610dbed9ea866df9607a6f619d09cce73"
}
```

