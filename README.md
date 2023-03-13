# Requirements
* **php8.0** 
* **composer 2**
* **symfony cli**
* **mysql server**
* **postman**
* **git**

# Deploy

1. Clone the project from: [Here](https://github.com/avantpage/symfony-test).
2. Create an empty database, and setup the connection info into **.env** file, 
   located in the root folder.  
3. Install the project dependencies using the **composer install** command.
4. Run migrations using **php bin/console doctrine:migrations:migrate**
5. Navigate to the project path on command line and run **symfony server:start** 
6. Once running, api will under path **/api/v1** 

# Structure

**config**:
Contains the project configurations files.

**src**:
Contains the project source files.

**Controller**:
Controller are the main entry point of the application. Receives the http request from the client 
and are responsible to send back response to client.
Each function in the Controller will expose an endpoint to the client. The logic into controller's
function are:
* validate the received request params. The request classes are located into **Http/Request** folder.
* once validated, data is passed to Handlers classes located into **Handler** folder.
* return response to client.

**Handler**:
Contains the handlers files. They are the classes that interact with the data model. 
They receive the information from the Controller, interact with the model, 
and handle the request logic. The data they receive is assumed validated since the 
Controller has already validated the information before passing the information to the Handler.
Each function in the Handler must return an object of the AppResponse class, which includes
any of its child classes. 
All the response object are located into **Http/Response** folder.

**Http**:
Contains the helpers and aux files used by Controller and Handler.

**Dto**:
**D**ata **T**ransfer **O**bject are an abstraction of the data model. By using DTO files instead of Entities, 
you can hide data and not show the client the true structure of our DB.

**Error**:
Contains the ErrorResponse class. Note that ErrorResponse is child of AppResponse.

**Request**:
Contains the request classes. The Request classes are used in the Controllers classes. 
The main class under this path is AppRequest, all classes created
under this path must extend the AppRequest. Request classes automatically validate the info 
provided. For proper validation the structure of the class is important. 
 * **requiredKeys** attribute contains the fields that are mandatory for the request and the structure is:
   an associative array where key is the name of the field, same name received from client
  and value is the error will throw in case of that field is not present in the request.
    ***'first_name' =>  ApiError::CODE_FIRST_NAME_MISSING,***

 Any new required errors need to be created into ApiError class. 
 * **validators** attribute contains the validation for each field into the request, despite is
 mandatory or not. Structure is similar to requiredFields, an associative array where key is the
 name of the field and value is the validator class that apply to the field. The applied validator
 class depended on the data in the field.

***'first_name' =>  StringValidator::class,***

Any new required validators need to be created into **Http/Validator** path. Each class represents
one validator type.

**Response**:
Contains the responses classes. The Response classes are used in the Handlers classes.
The main class under this path is AppResponse, all classes created under this path must extend the 
AppResponse. The response class should follow the proper structure for correct response.

* It should implement the **marshall** function. This function should receive array of data
with a key named **entities**. Even if is a single object response, it recommends to follow
this structure for being able to reuse the class. In this way, passing and array with one element
for single response will result in no changed when response list is required.

**Validator**:
Contains the validator classes used on Request classes. Each class represent specific validator,
and one class will always validate one data type. It means that you can not validate that data
is an email and a valid username at same time. Any new required validators need to be created under 
this path and should extend **PatternValidator** and implements **ValidatorInterface**

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
  
  Perform a list of users. Into Controller: **getList** function handle this request.
  **UserListRequest** is the request class responsible for validation.
  Can receive 3 fields for filtering the data, that are located into **validators** attribute in
  the UserListRequest: **first_name**,**last_name** and **email**.

* POST http://127.0.0.1:8000/api/v1/users
  
  Perform a user creation. Into Controller: **create** function handle this request.
  **UserCreateRequest** is the request class responsible for validation.
  Some mandatory fields are declared into **$requiredKeys** attribute.

* GET http://127.0.0.1:8000/api/v1/users/{id}
  
  Perform a retrieve user. Into Controller: **retrieve** function handle this request.
  **ApiRequest** is the request class responsible for validation. Note that because
  only ID is required for this request and not extra fields are sent in request, there is
  no need for specific Request class. In this case the Id param is validated by symfony in the
  controller function. Its ID is not sent in the request, Symfony will throw error due 
 NOT_FOUND url. In case that wrong ID is passed, the handler will throw 404 not_found error
 because such user with wrong id is not in DB.

# Tasks instructions

1. Extends the user list functionality to filter not only by **first_name**, **last_name**
   and **email**. Right now the filters by those fields are independents, this means that
   one field is used for specific column. Client needs to send another field in request 
   called **search** that filter by any of the columns **first_name**,**last_name** or **email**.
   
   Database row example:

   **first_name**: Jorge Manuel

   **last_name**: Rodríguez Fonseca

   **email**: jorgem.rodriguezfonseca@gmail.com

   Considerations:
   * The search on DB should not be exact but partial. This means that **search=Manuel**
   should match first_name=Jorge Manuel, or **search=Fons** should match last_name=Rodriguez Fonseca.
   * The search on DB should not be case-sensitive. This means that **search=jorge** should match
   Jorge.
   * Value in **search** should match any of the columns

2. Implements EmailValidator. The email field in the requests classes is assigned with the StringValidator validator, 
   **'email' => StringValidator::class**.

   This will cause that list and create endpoints fail when proper email value is send. 
   To correct this error, you must create an EmailValidator class and assign it to the email field. 
   Considerations:
   * Validator need to be created in proper folder.
   * Validator need to follow the current validators structure.
   * Validator need to validate only gmail addresses. 
   
3. Make email unique. Currently the email field is not unique on DB. 
   Considerations:
    * Migration need to be generated for this change.
    * Current endpoints should be updated with new change. Eg, create endpoint should return
    and ErrorResponse when the email already exists. 
    * Any new error need to be created in ApiError class.
   
4. Implements update user endpoint.
   Considerations:
    * Existing UserController should be used.
    * Existing UserHandler should be used.
    * Update user can not be force to client to send all fields at once. Client can send to server
   only the fields that need to be updated.

5. Implements delete user endpoint.
   Considerations:
    * Existing UserController should be used.
    * Existing UserHandler should be used.
    * In case user not found, an ErrorResponse should be returned.

6. Update user list to order data by createdAt field instead of firstName.
7. Extend the User entity to support an Address list.
   Considerations:
    * Migration need to be created.
    * Address class should have: **id**, **street**, **number**, **city**, **country**, **zip-number**.
    * All current endpoints need to be updated to use Address list.
    * Address need to have separated Dto class and this Dto should be embedded into UserDto.
    * Address are added together with user CRUD, it will not have separate endpoints for it.
   This means, the create user endpoint will be used for create address, delete user should
   delete de user addresses, and user update should be used for update addresses.
8. Add support for Logs. The current endpoints does not have any support for logs. 
   Considerations:
    * Symfony logs system should be used.
    * Only error level should be used.

# Final considerations
Once the tasks are completed, please submit the code for same repo but under new branch named
**X-main**. Where X is your name. Eg **camila-main**