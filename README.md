# User Endpoints

## Authentication

**POST** - Register a new user
/api/V1/users/register

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

**POST** - Login as user
/api/V1/users/login

Query Parameters:<br/> 
**email**: string<br/>
**password**: string

#### Everything under this line needs beared token authentication

**POST** - Logout the authenticated user
/api/V1/users/logout

## User Profile

**GET** - Retrieve the authenticated user's profile
/api/V1/users/profile

**PUT** - Update the authenticated user's profile
/api/V1/users/profile

Query Parameters:<br/>
**name**: string<br/>
**email**: string<br/>
**password**: string

## Books

**GET** - Retrieve all books
/api/V1/books

**GET** - Retrieve a specific books by ID
/api/V1/books/{id}

## Booking

**GET** - Retrieve booking
/api/V1/booking

**POST** - Create a booking
/api/V1/books/{id}/booking

**PUT** - Return a booking
/api/V1/booking/return

### Fines

**GET** - Retrieve fine
/api/V1/fine

**POST** - Pay fine
/api/V1/fine/pay

# Admin Endpoints

## Authentication

**POST** - Login as admin
/api/V1/users/login

Query Parameters:<br/> 
**name**: string<br/>
**email**: string<br/>
**password**: string

## Users

**GET** - Retrieve all users
/api/V1/admin/users

**GET** - Retrieve a specific user by ID
/api/V1/admin/users/{id}

**DELETE** - Delete a user
/api/V1/admin/users/{id}

## Books

**GET** - Retrieve all books
/api/V1/admin/books

**GET** - Retrieve a specific book by ID
/api/V1/admin/books/{id}

**POST** - Create a book
/api/V1/admin/books

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: string<br/>
**published_year**: string

**PUT** - Update a book
/api/V1/admin/books/{id}

Query Parameters:<br/>
**title**: string<br/>
**author**: string<br/>
**description**: strin<br/>
**published_year**: string

**DELETE** - Delete a book
/api/V1/admin/books/{id}

## Bookings

**GET** - Retrieve all bookings 
/api/V1/admin/bookings

**GET** - Retrieve a specific booking by ID
/api/V1/admin/bookings/{id}

## Fines

**GET** - Retrieve all fines
/api/V1/admin/fines

**GET** - Retrieve a specific fine by ID
/api/V1/admin/fines/{id}

## Stock

**GET** - Retrieve all stock
/api/V1/admin/stock

**GET** - Retrieve a specific stock by ID
/api/V1/admin/stock/{id}

**PUT** - Update a stock quantity
/api/V1/admin/stock/{id}/{quantity}
