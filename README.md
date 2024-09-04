## Installation

- clone project repository
- go to project root and run command cp .env.example .env
- open .env file and make sure you updated absolute path to storage/testdb.sqlite database file
- make sure you have installed PHP version ^8.2 
- execute commands:
    - composer install
    - php artisan key:generate
    - php artisan migrate:fresh --seed
    - php artisan serve
    - php artisan l5-swagger:generate 


## Endpoints
You can see OpenApi project documentation by link: http://127.0.0.1:8000/api/documentation 
### Authentication
For Authentication you should send endpoint:

POST /api/login HTTP/1.1

Host: 127.0.0.1:8000

Accept: application/json

Content-Type: application/json

Content-Length: 59
```json
{
"email": "test@example.com",
"password": "password"
}
```

In response will get:

```json
{
    "data": {
        "token": "2|4eHYPAV2u7LROAkoVEoCxsyUXEGOlwienL2GcAxM831a5ab0"
    }
}
```

Use token in next requests as Bearer token 

For not authenticated users returns status 401 with message:

```json
{
"message": "The provided credentials do not match our records."
}
```

### Domain
For store domain you send endpoint:

POST /api/domain/store HTTP/1.1

Host: 127.0.0.1:8000

Accept: application/json

Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW

Authorization: Bearer 2|4eHYPAV2u7LROAkoVEoCxsyUXEGOlwienL2GcAxM831a5ab0

Content-Length: 181

----WebKitFormBoundary7MA4YWxkTrZu0gW
Content-Disposition: form-data; name="file"; filename="test.json"
Content-Type: application/json

(data)
----WebKitFormBoundary7MA4YWxkTrZu0gW

In response will get:
```json
{
    "data": {
        "issuer": "Accredify",
        "result": "verified"
    }
}
```

Example of .json file with correct data:

```json

{
"data": {
"id": "63c79bd9303530645d1cca00",
"name": "Certificate of Completion",
"recipient": {
"name": "Marty McFly",
"email": "marty.mcfly@gmail.com"
},
"issuer": {
"name": "Accredify",
"identityProof": {
"type": "DNS-DID",
"key": "did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller",
"location": "ropstore.accredify.io"
}
},
"issued": "2022-12-23T00:00:00+08:00"
},
"signature": {
"type": "SHA3MerkleProof",
"targetHash": "ad92d4a217c414d6c16ee538934b099ae7b03baa2b60914929961e1906a08767"
}
}
```

## Tests
To run unit and feature tests execute:
- php artisan test
