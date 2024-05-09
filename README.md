# LibraBook

This simple RESTful API project implements a Library Management System (LMS) aimed at simplifying the borrowing and lending of books.<br/>
It facilitates smooth interactions for both users and administrators, offering comprehensive features to efficiently handle books, bookings, fines, and user profiles.<br/> 

## How to use:

## User endpoints:

Register a new user:<br/> 
**POST: ```/api/V1/users/register```**

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

Login as user:<br/> 
**POST: ```/api/V1/users/login```**

Query Parameters:<br/> 
**email**: string<br/>
**password**: string

##### Everything under this line needs beared token authentication

Logout the authenticated user:<br/> 
**POST: ```/api/V1/users/logout```**

Retrieve the authenticated user's profile:<br/> 
**GET: ```/api/V1/users/profile```**

Update the authenticated user's profile:<br/> 
**PUT: ```/api/V1/users/profile```**

Query Parameters:<br/>
**name**: string<br/>
**email**: string<br/>
**password**: string

### Books endpoints:

Retrieve all books:<br/> 
**GET: ```/api/V1/books```**

Retrieve a specific books by ID:<br/> 
**GET: ```/api/V1/books/{id}```**

### Bookings endpoints:

Retrieve booking:<br/> 
**GET: ```/api/V1/booking```**

Create a booking:<br/> 
**POST: ```/api/V1/books/{id}/booking```**

Return a booking:<br/> 
**PUT: ```/api/V1/booking/return```**

### Fines endpoints:

Retrieve fine:<br/> 
**GET: ```/api/V1/fine```**

Pay fine:<br/> 
**POST: ```/api/V1/fine/pay```**

## Admin endpoints

Login as admin:<br/> 
**POST: ```/api/V1/users/login```**

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

### Users endpoints

Retrieve all users:<br/> 
**GET: ```/api/V1/admin/users```**

Retrieve a specific user by ID:<br/> 
**GET: ```/api/V1/admin/users/{id}```**

Delete a user:<br/> 
**DELETE: ```/api/V1/admin/users/{id}```**

### Books endpoints

Retrieve all books:<br/> 
**GET: ```/api/V1/admin/books```**

Retrieve a specific book by ID:<br/> 
**GET: ```/api/V1/admin/books/{id}```**

Create a book:<br/> 
**POST: ```/api/V1/admin/books```**

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: string<br/>
**published_year**: string

Update a book:<br/> 
**PUT: ```/api/V1/admin/books/{id}```**

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: strin<br/>
**published_year**: string

Delete a book:<br/> 
**DELETE: ```/api/V1/admin/books/{id}```**

### Bookings endpoints

Retrieve all bookings:<br/> 
**GET: ```/api/V1/admin/bookings```**

Retrieve a specific booking by ID:<br/> 
**GET: ```/api/V1/admin/bookings/{id}```**

### Fines endpoints

Retrieve all fines:<br/> 
**GET: ```/api/V1/admin/fines```**

Retrieve a specific fine by ID:<br/> 
**GET: ```/api/V1/admin/fines/{id}```**

### Stock endpoints

Retrieve all stock:<br/> 
**GET: ```/api/V1/admin/stock```**

Retrieve a specific stock by ID:<br/> 
**GET: ```/api/V1/admin/stock/{id}```**

Update a stock quantity:<br/> 
**PUT: ```/api/V1/admin/stock/{id}/{quantity}```**
