# User Endpoints

## Authentication

**POST** - Register a new user
http://127.0.0.1:8000/api/V1/users/register

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

**POST** - Login as user
http://127.0.0.1:8000/api/V1/users/login

Query Parameters:<br/> 
**email**: string<br/>
**password**: string

#### Everything under this line needs beared token authentication

**POST** - Logout the authenticated user
http://127.0.0.1:8000/api/V1/users/logout

## User Profile

**GET** - Retrieve the authenticated user's profile
http://127.0.0.1:8000/api/V1/users/profile

**PUT** - Update the authenticated user's profile
http://127.0.0.1:8000/api/V1/users/profile

Query Parameters:<br/>
**name**: string<br/>
**email**: string<br/>
**password**: string

## Books

**GET** - Retrieve all books
http://127.0.0.1:8000/api/V1/books

**GET** - Retrieve a specific books by ID
http://127.0.0.1:8000/api/V1/books/{id}

## Booking

**GET** - Retrieve booking
http://127.0.0.1:8000/api/V1/booking

**POST** - Create a booking
http://127.0.0.1:8000/api/V1/books/{id}/booking

**PUT** - Return a booking
http://127.0.0.1:8000/api/V1/booking/return

### Fines

**GET** - Retrieve fine
http://127.0.0.1:8000/api/V1/fine

**POST** - Pay fine
http://127.0.0.1:8000/api/V1/fine/pay

# Admin Endpoints

## Authentication

**POST** - Login as admin
http://127.0.0.1:8000/api/V1/users/login

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

## Users

**GET** - Retrieve all users
http://127.0.0.1:8000/api/V1/admin/users

**GET** - Retrieve a specific user by ID
http://127.0.0.1:8000/api/V1/admin/users/{id}

**DELETE** - Delete a user
http://127.0.0.1:8000/api/V1/admin/users/{id}

## Books

**GET** - Retrieve all books
http://127.0.0.1:8000/api/V1/admin/books

**GET** - Retrieve a specific book by ID
http://127.0.0.1:8000/api/V1/admin/books/{id}

**POST** - Create a book
http://127.0.0.1:8000/api/V1/admin/books

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: string<br/>
**published_year**: string

**PUT** - Update a book
http://127.0.0.1:8000/api/V1/admin/books/{id}

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: strin<br/>
**published_year**: string

**DELETE** - Delete a book
http://127.0.0.1:8000/api/V1/admin/books/{id}

## Bookings

**GET** - Retrieve all bookings
http://127.0.0.1:8000/api/V1/admin/bookings

**GET** - Retrieve a specific booking by ID
http://127.0.0.1:8000/api/V1/admin/bookings/{id}

## Fines

**GET** - Retrieve all fines
http://127.0.0.1:8000/api/V1/admin/fines

**GET** - Retrieve a specific fine by ID
http://127.0.0.1:8000/api/V1/admin/fines/{id}

## Stock

**GET** - Retrieve all stock
http://127.0.0.1:8000/api/V1/admin/stock

**GET** - Retrieve a specific stock by ID
http://127.0.0.1:8000/api/V1/admin/stock/{id}

**PUT** - Update a stock quantity
http://127.0.0.1:8000/api/V1/admin/stock/{id}/{quantity}# User Endpoints

## Authentication

**POST** - Register a new user
http://127.0.0.1:8000/api/V1/users/register

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

**POST** - Login as user
http://127.0.0.1:8000/api/V1/users/login

Query Parameters:<br/> 
**email**: string<br/>
**password**: string

#### Everything under this line needs beared token authentication

**POST** - Logout the authenticated user
http://127.0.0.1:8000/api/V1/users/logout

## User Profile

**GET** - Retrieve the authenticated user's profile
http://127.0.0.1:8000/api/V1/users/profile

**PUT** - Update the authenticated user's profile
http://127.0.0.1:8000/api/V1/users/profile

Query Parameters:<br/>
**name**: string<br/>
**email**: string<br/>
**password**: string

## Books

**GET** - Retrieve all books
http://127.0.0.1:8000/api/V1/books

**GET** - Retrieve a specific books by ID
http://127.0.0.1:8000/api/V1/books/{id}

## Booking

**GET** - Retrieve booking
http://127.0.0.1:8000/api/V1/booking

**POST** - Create a booking
http://127.0.0.1:8000/api/V1/books/{id}/booking

**PUT** - Return a booking
http://127.0.0.1:8000/api/V1/booking/return

### Fines

**GET** - Retrieve fine
http://127.0.0.1:8000/api/V1/fine

**POST** - Pay fine
http://127.0.0.1:8000/api/V1/fine/pay

# Admin Endpoints

## Authentication

**POST** - Login as admin
http://127.0.0.1:8000/api/V1/users/login

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

## Users

**GET** - Retrieve all users
http://127.0.0.1:8000/api/V1/admin/users

**GET** - Retrieve a specific user by ID
http://127.0.0.1:8000/api/V1/admin/users/{id}

**DELETE** - Delete a user
http://127.0.0.1:8000/api/V1/admin/users/{id}

## Books

**GET** - Retrieve all books
http://127.0.0.1:8000/api/V1/admin/books

**GET** - Retrieve a specific book by ID
http://127.0.0.1:8000/api/V1/admin/books/{id}

**POST** - Create a book
http://127.0.0.1:8000/api/V1/admin/books

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: string<br/>
**published_year**: string

**PUT** - Update a book
http://127.0.0.1:8000/api/V1/admin/books/{id}

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: strin<br/>
**published_year**: string

**DELETE** - Delete a book
http://127.0.0.1:8000/api/V1/admin/books/{id}

## Bookings

**GET** - Retrieve all bookings
http://127.0.0.1:8000/api/V1/admin/bookings

**GET** - Retrieve a specific booking by ID
http://127.0.0.1:8000/api/V1/admin/bookings/{id}

## Fines

**GET** - Retrieve all fines
http://127.0.0.1:8000/api/V1/admin/fines

**GET** - Retrieve a specific fine by ID
http://127.0.0.1:8000/api/V1/admin/fines/{id}

## Stock

**GET** - Retrieve all stock
http://127.0.0.1:8000/api/V1/admin/stock

**GET** - Retrieve a specific stock by ID
http://127.0.0.1:8000/api/V1/admin/stock/{id}

**PUT** - Update a stock quantity
http://127.0.0.1:8000/api/V1/admin/stock/{id}/{quantity}