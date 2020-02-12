# Requirements

- php 7.*
- mariadb / mongodb
- elasticsearch

# How to start

1. Run ```composer install```
2. Rename ```config/env-example to config/.env```
3. Choose in ```models/Article.php -> __construct() MongoService or MySQLService```
4. ```GET http://domain/helpers/seeder.php``` to seed fake records

# Endpoints

GET http://domain/?search_field=field&query=query

@params
* (string) search_field - Field to search
* (string) query - Query to search


GET http://domain/?id=stringID

@params
* (string) id - Record ID

POST http://domain/

@body (JSON)

{
"field1" : "Value1",
"field2": "value2"
}
