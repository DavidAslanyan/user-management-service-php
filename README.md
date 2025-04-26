# Laravel Authentication Project

This is a Laravel authentication project that implements various features related to user management, profile handling, and authentication workflows. It includes models, repositories, mappers, and exception handling, all of which help manage user data and profiles in a B2B context.

## Features

- User authentication (Login, Register)
- B2B Profile management (Create, Update, Delete, Get by ID)
- Role-based access control (RBAC)
- Profile status management (Active, Inactive, Rejected)
- Relationships between users and their profiles
- Pagination for listing profiles
- Exception handling for not found profiles

## Prerequisites

Make sure you have the following installed before you begin:

- PHP >= 8.0
- Composer
- Laravel >= 9.x
- MySQL or PostgreSQL
- Node.js (for front-end)

## Installation

1. **Clone the repository**
 ```bash
 git clone https://github.com/yourusername/laravel-auth-project.git
```
2. **Install dependencies**
  ```bash
  cd laravel-auth-project
  composer install
  ```
3. **Set up the environment file**
```bash
cp .env.example .env
   ```
4. **Generate the application key**
```bash
php artisan key:generate
   ```

## Usage

### Authentication

- **Registration**: Create a new user by submitting the registration form with the required details (name, email, password, etc.).
  
- **Login**: After registration, users can log in with their credentials.
  
- **JWT Authentication**: The system uses JWT tokens for authentication. Tokens are issued upon successful login and are used for subsequent requests to authenticate the user.

### B2B Profile Management

- **Create a Profile**: Users can create a B2B profile, including fields like profile type, status, role, social media links, and bio.
  
- **Update a Profile**: Users can update their B2B profile with new details.
  
- **Get Profile by ID**: The application allows fetching a profile by its ID.
  
- **List Profiles**: Profiles can be listed with pagination for easier navigation.
  
- **Delete Profile**: Profiles can be deleted by their ID.
  
- **Profile Status**: Profiles can have a status like "Active", "Inactive", or "Rejected".

### Custom Pagination

The system supports pagination for listing profiles to ensure a smooth and efficient browsing experience when dealing with large datasets.

## Directory Structure

Here is a brief overview of the key directories in this project:

- `app/Models/`: Contains the Eloquent models for handling database operations.
  
- `app/Repositories/`: Implements the logic for interacting with the database.
  
- `app/Mappers/`: Maps between models and entities for data transformation.
  
- `app/Exceptions/`: Custom exception handling for various error scenarios.
  
- `routes/api.php`: API routes related to authentication and user/profile management.

## API Documentation

### Authentication Endpoints

1. **POST /api/auth/register**

   - Register a new user.
   - **Request Body**:
     ```json
     { 
       "name": "John Doe", 
       "email": "john@example.com", 
       "password": "password123" 
     }
     ```

2. **POST /api/auth/login**

   - Log in and get a JWT token.
   - **Request Body**:
     ```json
     { 
       "email": "john@example.com", 
       "password": "password123" 
     }
     ```

### B2B Profile Endpoints

1. **POST /api/b2b-profile**

   - Create a new B2B profile.
   - **Request Body**:
     ```json
     { 
       "profile_type": "business", 
       "profile_role": "admin", 
       "website": "http://example.com", 
       "bio": "Sample Bio" 
     }
     ```

2. **PUT /api/b2b-profile/{id}**

   - Update an existing B2B profile by ID.
   - **Request Body**:
     ```json
     { 
       "profile_type": "business", 
       "profile_role": "admin", 
       "website": "http://newwebsite.com", 
       "bio": "Updated Bio" 
     }
     ```

3. **GET /api/b2b-profile/{id}**

   - Retrieve a B2B profile by its ID.

4. **DELETE /api/b2b-profile/{id}**

   - Delete a B2B profile by ID.

5. **GET /api/b2b-profile/user/{userId}**

   - Get the B2B profile associated with a specific user.
     

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

Feel free to fork the repository, make improvements, and submit pull requests. Please follow the standard GitHub flow.

