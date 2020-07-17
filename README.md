# Paradox: Todo List API


## Installation

It's assumed you have installed all requirements the Laravel 7v requires:
https://laravel.com/docs/7.x#server-requirements

Optial and tested OS: Ubuntu 20.04

- `git clone git@github.com:alkhachatryan/paradox_test.git`
- `cd paradox_test`
- `composer install`

After installation, create (copy) the .env file, and do your configuration, such as key generation and DB conenction

- `mv .env.example .env`
- `php artisan key:generate

After database configurations run:
- `php artisan migrate`
- `php artisan passport:install`

## Usage
There are two resources: users and todos.

### Users API endpoints:
##### User Register Request
Endpoint: /api/user/register
Method: POST
Params:
- Name - required, min:2, max:32
- Email - required, email, unique in users table
- Password - required, min:6, max:32, equals password_confirmation
- Password_confirmation - the same as Password

##### User Register Response
Type: JSON
Data: 
- User access token
- User model

##### User Login Request
Endpoint: /api/user/login
Method: POST
Params:
- Email - required, email, unique in users table
- Password - required, min:6, max:32, equals password_confirmation

##### User Login Response
Type: JSON
Data: 
- User access token


##### User Account Request
Endpoint: /api/user
Method: GET

##### User Account Response
Type: JSON
Data: 
- User model

##### User Logout Request
Endpoint: /api/user/logout
Method: POST

##### User Logout Response
Type: JSON
Data: 
- string

### Todos API endpoints:
##### Todos List Request
Endpoint: /api/todos
Method: GET
Params:
- Filter - NOT REQUIRED, should equals finished or not_finished

##### Todos List Response
Type: JSON
Data: 
- Todos models


##### Todos Create Request
Endpoint: /api/todos/create
Method: POST
Params:
- Title - string, max 191
- Done - NOT REQUIRED, should equals 1 or 0

##### Todos Create Response
Type: JSON
Data: 
- Todos model

##### Todos Update Request
Endpoint: /api/todos/edit/{ID}
Method: POST
Params:
- Title - NOT REQUIRED, max 191
- Done - NOT REQUIRED, should equals 1 or 0
- _method - required, should equals PATCH

##### Todos Update Response
Type: JSON
Data: 
- Todos model

##### Todos Delete Request
Endpoint: /api/todos/edit/{ID}
Method: POST
Params:
- _method - required, should equals DELETE

##### Todos Delete Response
Type: JSON
Data: 
- string
