# ROOM_911 – Employee Access Control System

Web system for employee management and access attempt control for **ROOM_911**.  
It allows managing employees, departments, access permissions, bulk data imports, and PDF report generation.

---

## Features

### Employee Management
- Create, edit, and delete employees
- Assign employees to departments
- Define whether access to ROOM_911 is granted or denied
- View the total number of access attempts per employee

### Search and Filters
- Search employees by:
    - Internal ID
    - First name
    - Last name
- Filter employees by department
- Filter access history by a specific date range

### Access History
- View the access attempt history for each employee
- Display the result of each attempt (Granted / Denied)
- Sequential numbering for better readability
- Dates formatted according to the application’s timezone

### Export and Bulk Upload
- Import employees from a CSV file into a specific department
- Generate and download a **PDF** containing an employee’s access attempt history

---

## Requirements

- **PHP** ^8.2
- **Composer** ^2.7
- **Laravel** ^11.x
- **MySQL** ^8.0
- **Node.js** ^22.12+
- **Npm** ^10.x
- **React** ^19
- **Vite** ^5.x
- **Tailwind CSS** ^3.x

---

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/judyz94/room_911.git
   cd room_911

2. **Install Laravel Dependencies**
   ```bash
   composer install

3. **Configure the .env file**

   Copy the sample file:
   ```bash
   cp .env.example .env
    ```

   Edit the .env file to configure your local environment:

    - Database credentials:
   > DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

    - Application URL:
   > APP_URL (e.g., http://127.0.0.1:8000)

    - Frontend API URL:
   > VITE_API_BASE_URL (make sure it matches APP_URL + /api, e.g., http://127.0.0.1:8000/api)

4. **Generate the application key**
   ```bash
   php artisan key:generate

5. **Create DB in MySQL**

   The default name in the .env.example is "room_911"


6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed

7. **Set the correct Node.js version**

   Before compiling the assets, make sure you are using the correct Node.js version
   ```bash
   nvm install 20
   nvm use 20

8. **Install Node.js Dependencies**
   ```bash
   npm install

9. **Compile the assets**
   ```bash
   npm run dev

10. **Start the local web server**
    ```bash
    php artisan serve

11. **Access the application**

    After the server is running, open your browser and go to the URL shown in the terminal (usually http://127.0.0.1:8000)

---

## General Structure

```bash
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Resources/
│   │   └── Requests/
│   └── Models/
├── routes/
│   └── api.php
├── resources/
│   └── js/
│       ├── Layouts/
│       └── Pages/
└── database/
    ├── migrations/
    └── seeders/
```

---

## Application Routes Overview

The routes in this system are organized by access level and responsibility:  
public access, ROOM_911 employee access, and protected administrative modules.

---

## Public Routes (No Authentication Required)

### Authentication
- **GET /login**  
  Renders the user login form.

- **POST /login**  
  Processes login credentials and starts the user session.

    > The `/login` route is used by the system for automatic redirection when authentication is required.


### ROOM_911 Employee Access (No Authentication Required)

### Access Form
- **GET /room-911/access**  
  Displays the employee access form where an internal employee ID is requested.

### Access Attempt
- **POST /room-911/access**  
  Processes an access attempt by:
  - Validating the internal employee ID
  - Checking if the employee exists
  - Verifying ROOM_911 access permissions
  - Recording the attempt (successful or failed)
  - Returning the access result

This flow operates independently of the administrative login system.

---

## Protected Routes (Authentication Required)

All routes in this section require an authenticated administrative user.

---

### Logout
- **POST /logout**  
  Ends the authenticated user session.

---

### Administrative User Registration

- **GET /user/register**  
  Displays the form to create new administrative users.

- **POST /user/register**  
  Creates a new administrative user.

This module is typically restricted to administrators.

---

### Departments Module

#### Departments View
- **GET /departments**  
  Displays the departments management interface.

#### Departments API
Provides CRUD functionality for departments.

Available actions:
- List departments
- Create a department
- Update a department
- Delete a department

---

### Employees Module

#### Employees View
- **GET /employees**  
  Displays the employee management interface.

---

#### Employee Access History

- **GET /employees/{employee}/access-logs**  
  Returns the access attempt history for a specific employee.

Features:
- Filter by date range
- Ordered by most recent attempts
- Formatted date and time
- Clear status indicators (Granted / Denied)

---

#### Bulk Employee Import (CSV)

- **POST /api/employees/import-csv**  
  Allows importing multiple employees from a CSV file.

Features:
- Assign employees to a selected department
- Validate CSV structure and data
- Useful for bulk onboarding

---

#### Access History PDF Export

- **GET /api/employees/{employee}/access-logs/pdf**  
  Generates a downloadable PDF containing the employee’s ROOM_911 access history.

Use cases:
- Auditing
- Reporting
- Compliance records

---

#### Employees API

Provides RESTful operations for employee management.

Available actions:
- List employees
- Create an employee
- Update employee information
- Delete an employee

---
