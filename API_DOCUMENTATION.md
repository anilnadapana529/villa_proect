# VILLA BOOKING PLATFORM - API DOCUMENTATION

## Base URL
`https://yourdomain.com/`

---

## PUBLIC ENDPOINTS (No Authentication Required)

### 1. Get Home Page Data
**GET** `/home-data`

Returns sliders, categories, and featured villa listings.

**Response:**
```json
{
  "status": true,
  "sliders": [...],
  "categories": [...],
  "listings": [...]
}
```

---

### 2. Search Villas
**GET** `/search?q=keyword`

Search for villas by name, location, or address.

**Parameters:**
- `q` (string) - Search keyword

**Response:**
```json
{
  "status": true,
  "results": [
    {
      "id": 1,
      "name": "Villa Name",
      "location": "Goa"
    }
  ]
}
```

---

### 3. Admin Login
**POST** `/admin-login`

**Body:**
```json
{
  "email": "admin@villa.com",
  "password": "password"
}
```

**Response:**
```json
{
  "status": true,
  "token": "jwt_token_here",
  "role": "admin",
  "user": {...}
}
```

---

### 4. Owner Login
**POST** `/owner-login`

**Body:**
```json
{
  "email": "owner@villa.com",
  "password": "password"
}
```

**Response:**
```json
{
  "status": true,
  "token": "jwt_token_here",
  "role": "owner",
  "user": {...}
}
```

---

### 5. User Login
**POST** `/user-login`

**Body:**
```json
{
  "email": "user@villa.com",
  "password": "password"
}
```

**Response:**
```json
{
  "status": true,
  "token": "jwt_token_here",
  "role": "user",
  "user": {...}
}
```

---

## ADMIN ENDPOINTS (Require JWT with admin role)

**Headers Required:**
```
Authorization: Bearer <jwt_token>
```

### 6. Get Admin Stats
**GET** `/admin-stats`

Returns dashboard statistics.

**Response:**
```json
{
  "status": true,
  "total_owners": 25,
  "total_villas": 100,
  "total_bookings": 50,
  "total_revenue": 50000
}
```

---

### 7. Get All Owners
**GET** `/admin-owners`

List all owners with their status.

**Response:**
```json
{
  "status": true,
  "owners": [
    {
      "id": 1,
      "name": "Owner Name",
      "email": "owner@email.com",
      "status": "approved"
    }
  ]
}
```

---

### 8. Get Owner Detail
**GET** `/admin-owner-detail?id=1`

Get specific owner details.

**Parameters:**
- `id` (integer) - Owner ID

---

### 9. Get All Villas (Admin)
**GET** `/admin-villas`

List all villas with status.

---

### 10. Approve Villa
**POST** `/admin-approve-villa`

**Body:**
```json
{
  "villa_id": 1
}
```

---

### 11. Reject Villa
**POST** `/admin-reject-villa`

**Body:**
```json
{
  "villa_id": 1
}
```

---

## OWNER ENDPOINTS (Require JWT with owner role)

**Headers Required:**
```
Authorization: Bearer <jwt_token>
```

### 12. Get Owner Stats
**GET** `/owner-stats`

Returns owner's dashboard statistics.

**Response:**
```json
{
  "status": true,
  "total_villas": 5,
  "total_bookings": 20,
  "total_earnings": 10000,
  "pending_villas": 1
}
```

---

### 13. Get My Villas
**GET** `/owner-villas`

List all villas owned by the logged-in owner.

**Response:**
```json
{
  "status": true,
  "villas": [
    {
      "id": 1,
      "name": "Villa Name",
      "status": "approved",
      "location": "Goa"
    }
  ]
}
```

---

### 14. Add New Villa
**POST** `/owner-add-villa`

**Body:**
```json
{
  "name": "Villa Name",
  "location": "Goa",
  "address": "Full address",
  "description": "Description",
  "amenities": "Pool,AC,WiFi",
  "guests": 6,
  "bedrooms": 3,
  "beds": 3,
  "bathrooms": 2,
  "weekday_price": 5000,
  "weekend_price": 7000
}
```

---

### 15. Edit Villa
**POST** `/owner-edit-villa`

**Body:**
```json
{
  "villa_id": 1,
  "name": "Updated Villa Name",
  ...
}
```

---

### 16. Delete Villa
**POST** `/owner-delete-villa`

**Body:**
```json
{
  "villa_id": 1
}
```

---

### 17. Upload Villa Images
**POST** `/owner-upload-images`

**Content-Type:** `multipart/form-data`

**Body:**
```
villa_id: 1
images[]: file1.jpg
images[]: file2.jpg
```

---

## USER ENDPOINTS (Require JWT with user role)

**Headers Required:**
```
Authorization: Bearer <jwt_token>
```

### 18. Get User Profile
**GET** `/user-profile`

Returns logged-in user's profile.

**Response:**
```json
{
  "status": true,
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@email.com",
    "phone": "1234567890"
  }
}
```

---

### 19. Update User Profile
**POST** `/update-user-profile`

**Body:**
```json
{
  "name": "Updated Name",
  "phone": "9876543210"
}
```

---

### 20. Get User Bookings
**GET** `/user-bookings`

List all bookings made by the user.

**Response:**
```json
{
  "status": true,
  "bookings": [
    {
      "id": 1,
      "villa_name": "Villa Name",
      "check_in": "2025-12-01",
      "check_out": "2025-12-05",
      "total_amount": 20000,
      "status": "confirmed"
    }
  ]
}
```

---

### 21. Create Booking
**POST** `/user-create-booking`

**Body:**
```json
{
  "villa_id": 1,
  "check_in": "2025-12-01",
  "check_out": "2025-12-05",
  "user_name": "User Name",
  "user_phone": "1234567890",
  "total_amount": 20000
}
```

---

## VILLA ENDPOINTS (Public for authenticated users)

**Headers Required:**
```
Authorization: Bearer <jwt_token>
```

### 22. Get Villa List
**GET** `/villa-list`

List all approved villas.

**Response:**
```json
{
  "status": true,
  "villas": [
    {
      "id": 1,
      "name": "Villa Name",
      "location": "Goa",
      "weekday_price": 5000,
      "weekend_price": 7000,
      "image": "image.jpg"
    }
  ]
}
```

---

### 23. Get Villa Detail
**GET** `/villa-detail?id=1`

Get detailed information about a specific villa.

**Parameters:**
- `id` (integer) - Villa ID

**Response:**
```json
{
  "status": true,
  "villa": {
    "id": 1,
    "name": "Villa Name",
    "location": "Goa",
    "description": "...",
    "amenities": "Pool,AC,WiFi",
    "guests": 6,
    "bedrooms": 3,
    "bathrooms": 2,
    "weekday_price": 5000,
    "weekend_price": 7000
  },
  "images": [
    {"image": "image1.jpg"},
    {"image": "image2.jpg"}
  ]
}
```

---

### 24. Get Villa Calendar
**GET** `/villa-calendar?id=1`

Get booked and blocked dates for a villa.

**Parameters:**
- `id` (integer) - Villa ID

**Response:**
```json
{
  "status": true,
  "booked": ["2025-12-01", "2025-12-02"],
  "blocked": []
}
```

---

### 25. Check Availability
**POST** `/check-availability`

Check if a villa is available for given dates.

**Body:**
```json
{
  "villa_id": 1,
  "check_in": "2025-12-01",
  "check_out": "2025-12-05"
}
```

**Response:**
```json
{
  "status": true,
  "available": true
}
```

---

## ERROR RESPONSES

All endpoints may return error responses:

```json
{
  "status": false,
  "message": "Error message here"
}
```

**Common Status Codes:**
- `200` - Success
- `401` - Unauthorized (Invalid/Missing JWT)
- `404` - Not Found
- `500` - Server Error

---

## AUTHENTICATION

All protected endpoints require JWT token in the Authorization header:

```
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

Get JWT token from login endpoints:
- `/admin-login`
- `/owner-login`
- `/user-login`

---

## TEST CREDENTIALS

**Admin:**
- Email: admin@villa.com
- Password: admin123

**Owner:**
- Email: admin@villas.com
- Password: (check database)

**User:**
- Email: kiran@user.com
- Password: (check database - MD5 hash: 6ad14ba9986e3615423dfca256d04e3f)

---
