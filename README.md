# Elasticsearch Symfony Demo System
This "Elasticsearch Symfony Demo System" is used to retrieve elasticsearch records from symfony framework.

## Table of contents
* [System Requirements](#system-requirements)
* [External Library Used](#external-library-used)
* [Setup](#setup)

System Requirements
-------------------

* PHP 7.2 or higher
* Mysql 8
* ElasticSearch 7.10.2
* Apache as a web server

External Library Used
-------------------

* lexik/jwt-authentication-bundle: 2.12
* ongr/elasticsearch-dsl: 7.2 
* ongr/elasticsearch-bundle: 7.0

Setup
------------
1. Clone the git repository
```bash
 git clone https://github.com/mohamedmubashir89/elasticsearch-backend.git
 cd elasticsearch-backend
```

2. Install dependencies

```bash
    composer install
```
3. Run elastic search with docker

```bash
    docker-compose -f docker-compose.yaml up -d
```
* Add ecommerce data to kibana

4. Import the db to local

```bash
    import the database "elasticsearch_db.sql" from root folder
```
* change the database credentials in .env file

#### Credentials
* Username : admin@123.com
* Password : 123

5. Generate jwt keys

```bash
    php bin/console lexik:jwt:generate-keypair
```
   
6. Run the application

```bash
    install sysmfony from the given link  https://symfony.com/download
    symfony server:start
```

## Api to include in postman

```bash
import "elasticsearch-api.postman_collection.json" from root folder in postman
```
