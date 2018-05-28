# Companies-REST-API

Companies can have multiple employees and employees can have multiple dependants.
Furthermore, they have the specific attributes: Company, Employee, Dependant.

## How it's works

1) For GUI interface usage swagger. Swagger allows you to describe the structure of your APIs so that machines can read them. 
The ability of APIs to describe their own structure is the root of all.

2) For REST support we use rest-bundle. This bundle help us in creating the a REST api.

3) For work with DB we use a Doctrine ORM. After install set the configuration for our data base in the configuration file .env by changing the db_name the user and the password

...

Note: The route format XXX use because cover situation when one people work in two company in the same time. Example: accountant or lawyer. 
```php
  XXX  - "/api/company/{companyId}/employee/{employeeId}/dependant/{id}"
  YYY - "/api/employee/{employeeId}/dependant/{id}"
```

## Examle

![image](https://github.com/dykyi-roman/Companies-REST-API/blob/master/public/images/postman.png)

## Stack technologies
+ symfony 4
+ swagger
+ rest-bundle
+ event-dispatcher
+ doctrine-orm

## Install
+ run php server (Example: php -S 127.0.0.1:8000 -t public)
+ create DB schema
+ run migration scripts

## Features
+ Usage of kotlin
+ Usage of API Blueprint 

## swagger interface

![image](https://github.com/dykyi-roman/Companies-REST-API/blob/master/public/images/swagger.png)

## Router schema

![image](https://github.com/dykyi-roman/Companies-REST-API/blob/master/public/images/route.png)

## Database model

![image](https://github.com/dykyi-roman/Companies-REST-API/blob/master/public/images/db.png)

## Author
[Dykyi Roman](https://www.linkedin.com/in/roman-dykyi-43428543/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)