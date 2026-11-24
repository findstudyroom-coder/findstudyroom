# Login API Setup Instructions

## Changes Made

### 1. **Database Modifications**
- Created migration: `database/migrations/2024_06_24_add_phone_to_users_table.php`
  - Added `phone` (unique, nullable) column
  - Added `password_change_required` (boolean, default true) column
  - Added `role` (string, default 'user') column

### 2. **User Model Updates**
- Updated `app/Models/User.php`
  - Added `phone` to fillable array
  - Added `password_change_required` to fillable array
  - Added `password_change_required` to casts as boolean

### 3. **API Controller**
- Created `app/Http/Controllers/Api/AuthController.php` with methods:
  - `login()` - Login with phone and password
  - `resetPassword()` - Reset password on first login
  - `changePassword()` - Change password for logged-in users
  - `logout()` - Logout user
  - `user()` - Get current user details

### 4. **API Routes**
- Updated `routes/api.php`
  - `POST /api/login` - Public endpoint
  - `POST /api/reset-password` - Public endpoint
  - `POST /api/change-password` - Protected endpoint
  - `GET /api/user` - Protected endpoint
  - `POST /api/logout` - Protected endpoint

### 5. **Database Seeding**
- Created `database/seeders/UserSeeder.php` with test users
- Updated `database/seeders/DatabaseSeeder.php` to call UserSeeder

### 6. **Documentation**
- Created `LOGIN_API_DOCUMENTATION.md` with complete API documentation

---

## Setup Steps

### Step 1: Run Migrations
```bash
php artisan migrate
```

This will add the new columns to the users table.

### Step 2: Seed Test Data (Optional)
```bash
php artisan db:seed
```

Or only seed users:
```bash
php artisan db:seed --class=UserSeeder
```

Test users created:
- **User 1** (Needs password reset)
  - Phone: 9876543210
  - Password: password
  - password_change_required: true

- **User 2** (Already changed password)
  - Phone: 9876543211
  - Password: password123
  - password_change_required: false

- **Admin**
  - Phone: 9876543212
  - Password: admin123
  - role: admin

### Step 3: Test the API

#### Using Postman or cURL:

**1. First Login (Password Reset Required)**
```
POST /api/login
Content-Type: application/json

{
    "phone": "9876543210",
    "password": "password"
}
```

Response:
```json
{
    "status": "success",
    "message": "Password change required",
    "password_change_required": true,
    "user_id": 1,
    "temporary_token": "..."
}
```

**2. Reset Password**
```
POST /api/reset-password
Content-Type: application/json

{
    "user_id": 1,
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

Response:
```json
{
    "status": "success",
    "message": "Password reset successful. Please login with your new password."
}
```

**3. Login with New Password**
```
POST /api/login
Content-Type: application/json

{
    "phone": "9876543210",
    "password": "newpassword123"
}
```

Response:
```json
{
    "status": "success",
    "message": "Login successful",
    "password_change_required": false,
    "user": {...},
    "token": "..."
}
```

---

## Mobile App Implementation Flow

```
┌─────────────────┐
│   Login Screen  │
│ Phone + Pass    │
└────────┬────────┘
         │
         ▼
    POST /api/login
         │
    ┌────┴─────────────────────┐
    │                          │
    ▼                          ▼
password_change_required   password_change_required
      = true                    = false
    (First Login)           (Regular Login)
    │                          │
    ▼                          ▼
┌──────────────────┐     ┌──────────────┐
│Show Password     │     │App Dashboard │
│Reset Screen      │     │or Home Page  │
└────────┬─────────┘     └──────────────┘
         │
    POST /api/reset-password
         │
         ▼
┌──────────────────┐
│Redirect to       │
│Login Screen      │
│(Try new pass)    │
└──────────────────┘
```

---

## Error Handling

All endpoints return appropriate HTTP status codes:

- **200 OK** - Successful request
- **400 Bad Request** - Validation error
- **401 Unauthorized** - Authentication failed or token expired
- **422 Unprocessable Entity** - Validation error details
- **500 Internal Server Error** - Server error

---

## Security Notes

✅ Passwords are hashed using Laravel's Hash facade (bcrypt)
✅ API uses Sanctum tokens for authentication
✅ Temporary tokens are valid for password reset
✅ Password confirmation field prevents typos
✅ Minimum 8 character password requirement

---

## Troubleshooting

**Issue**: "The phone field is required" error
- Make sure the phone number is valid and follows the expected format

**Issue**: "Invalid phone number or password"
- Check that phone number exists in database
- Verify password is correct

**Issue**: Migration fails
- Ensure no existing `phone` column in users table
- Run `php artisan migrate:refresh --seed` to reset (development only)

---

## Next Steps

1. Update mobile app to use these API endpoints
2. Implement password reset UI screen
3. Handle token storage and refresh
4. Add more validation as needed
5. Implement password strength requirements
