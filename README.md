# Projet_7_BileMo

Projet 7 d'openclassrooms, création d'une api B2B pour l'achat/vente de téléphone portable

## Prerequisites
- Docker
- Docker compose
- PHP 8.2

*All the command below are to be used in the root folder of the project.*

## Setup:
If you have make installed:  
- simply use `make install`    
  :warning: docker can take some time to create the DB, if you run into some error, fallback to the step by step setup :warning:

### Step by step installation:
-start the symfony project with :  
`docker compose up -d`  
`composer install`  
`symfony serve -d`  

-then create the database and the migrations.

`symfony console doctrine:database:create --if-not-exists`  
`symfony console doctrine:migration:migrate`  

-finally load the fixtures with:  
`symfony console doctrine:fixtures:load`  

## Usage:

To access the documentation got to /api/doc
 
You can create new User within our site , if you're in development you can use one of our fake users to connect,  

>username: "green"  
>password: "password"

>username: "seb"  
>password: "password"

## Endpoint:
You can use either our documentation or postman to access those endpoints
### Login
#### POST  Api/check_login:
authenticate yourself and retrieve the JWT token
body :  
> {  
>   username: "username",  
>   password: "password"  
> }
### Product

#### GET api/products
retrieve a list of products  
#### GET api/products/{id}
retrieve the details of a product  
### User
#### GET api/client/{username}/users
Retrieve the list of user of a specific client
#### GET api/users/{id}
Retrieve the details of a user
#### POST api/users
create a new user.
Body:
> {  
> name: "string",  
> firstName: "string",  
> email: "valid@email.com",  
> phoneNumber: "string of 8 number lenght minimum"  
> }
#### DELETE api/users/{id}
delete a user from the current client list.   
it's also deleted from our db if the client was the only client who had this user
