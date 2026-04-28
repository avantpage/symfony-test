# Requirements
* **php8.4** 
* **composer 2**
* **symfony cli**
* **mysql server**
* **postman**
* **git**

# Deploy

1. Create an empty database and set up the connection info into the **.env** file, 
   located in the root folder.  
2. Install the project dependencies using the **composer install** command.
3. Run migrations using **php bin/console doctrine:migrations:migrate**
4. Navigate to the project path on the command line and run **symfony server:start** 
5. Once running, api will under path **/api/v1** 

# Structure

**config**:
Contains the project configurations files.

**src**:
Contains the project source files.

**Controller**:
Controller is the main entry point of the application. Receives the http request from the client 
and are responsible for sending back a response to a client.
Each function in the Controller will expose an endpoint to the client. The logic into controller's
functions are:
* validate the received request params. The request classes are located in the **Http/Request** folder.
* once validated, data is passed to Handlers classes located into the **Handler** folder.
* return a response to a client.

**Handler**:
Contains the handler files. They are the classes that interact with the data model. 
They receive the information from the Controller, interact with the model, 
and handle the request logic. The data they receive is assumed validated since the 
Controller has already validated the information before passing the information to the Handler.
Each function in the Handler must return an object of the AppResponse class, which includes
any of its child classes. 
All the response objects are located in the  **Http/Response** folder.

**Http**:
Contains the helpers and aux files used by Controller and Handler.

**Dto**:
**D**ata **T**ransfer **O**bject are an abstraction of the data model. By using DTO files instead of Entities, 
you can hide data and not show the client the true structure of our DB.

**Error**:
Contains the ErrorResponse class. Note that ErrorResponse is a child of AppResponse.

**Request**:
Contains the request classes. The Request classes are used in the Controllers classes. 
The main class under this path is AppRequest, all classes created
under this path must extend the AppRequest. Request classes to automatically validate the info 
provided. For proper validation the structure of the class is important. 
 * **requiredKeys** attribute contains the fields that are mandatory for the request, and the structure is:
   an associative array where the key is the name of the field, the same name received from a client
  and value is the error will throw in case of that field is not present in the request.
    ***'first_name' => ApiError::CODE_FIRST_NAME_MISSING,***

 Any new required errors need to be created into the ApiError class. 
 * **validators** attribute contains the validation for each field into the request, despite is
 mandatory or not. Structure is similar to requiredFields, an associative array where key is the
 name of the field and value is the validator class that applies to the field. The applied validator
 class depended on the data in the field.

***'first_name' =>  StringValidator::class,***

Any new required validators need to be created into **Http/Validator** path. Each class represents
one validator type.

**Response**:
Contains the response classes. The Response classes are used in the Handlers classes.
The main class under this path is AppResponse, all classes created under this path must extend the 
AppResponse. The response class should follow the proper structure for the correct response.

* It should implement the **marshall** function. This function should receive an array of data
with a key named **entities**. Even if there is a single object response, it recommends following
this structure for being able to reuse the class. In this way, passing an array with one element
for a single response will result in no change when a response list is required.

**Validator**:
Contains the validator classes used on Request classes. Each class represents a specific validator,
and one class will always validate one data type. It means that you cannot validate that data
is an email and a valid username at the same time. Any new required validators need to be created under 
this path and should extend **PatternValidator** and implement **ValidatorInterface**

**Model**:
Contains the model classes.

**Entity**:
The entities based on DB.

**Migrations**:
Migrations for DB changes.

**Repository**:
Repositories for DB. All queries are located here and are not allowed out of this scope.


# Entry Points
* GET http://127.0.0.1:8000/api/v1/users
  
  Perform a list of users. Into Controller: **getList** function handles this request.
  **UserListRequest** is the request class responsible for validation.
  Can receive 3 fields for filtering the data that are located into **validators** attribute in
  the UserListRequest: **first_name**,**last_name** and **email**.

* POST http://127.0.0.1:8000/api/v1/users
  
  Perform a user creation. Into Controller: **create** function handle this request.
  **UserCreateRequest** is the request class responsible for validation.
  Some mandatory fields are declared into the **$requiredKeys** attribute.

* GET http://127.0.0.1:8000/api/v1/users/{id}
  
  Perform a retrieve user. Into Controller: **retrieve** function handle this request.
  **** it's the request class responsible for validation. Note that because
  only ID is required for this request and no extra fields are sent in the request, there is
  no need for a specific Request class. In this case the id param is validated by symfony in the
  controller function. Its ID is not sent in the request, Symfony will throw an error due 
 NOT_FOUND url. In case that wrong ID is passed, the handler will throw a 404 not_found error 
 because such a user with wrong id is not in DB.


# Summary
The presented project aims to deploy an API that allows interaction with the `user` table in the database.

To deploy the project, follow these steps:

1. Create an empty MySQL database and name it as you prefer.
2. Configure the database connection in the `.env` file.
3. Install the project dependencies using Composer.
4. Run the existing database migrations using the Doctrine Migrations Bundle, which is already installed in the project.
5. Start the project using Symfony’s internal server.
6. The API should be available under the `/api/v1` path.

The `UserController` manages all endpoints related to the `User` model. Currently, the API provides three endpoints:

- User List
- User Create
- User Retrieve

Each endpoint validates the incoming request through dedicated `ApiRequest` classes, ensuring that the required fields are present and follow the expected structure.
For example, the `UserListRequest` class receives the data sent by the client for the User List endpoint and performs the corresponding validations. Similarly, the `UserCreateRequest` class handles validation for the User Create endpoint.
These request classes rely on a set of validator classes to verify each field. For instance, the `StringValidator` ensures that a value is a string, while the `EmailValidator` ensures that a value is a valid email address.
If the controller detects a validation error, it returns the corresponding response using the `ApiResponse` class and its child class `ErrorResponse`.
If the validation succeeds, the controller delegates the execution of the business logic to the appropriate handler class. In the case of `UserController`, the `UserHandler` class is responsible for handling the business logic and interacting with the database.