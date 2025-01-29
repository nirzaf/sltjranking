# SLTJ Ranking Management System

## Project Description

The SLTJ Ranking Management System is a web-based application developed in PHP for managing the ranking system of Sri Lanka Thawheedh Jamath (SLTJ). The system allows administrators to manage events, users, and rankings efficiently. It provides a platform for branches to register events, track their progress, and view their rankings.

## Features

- Event Management: Add, edit, and delete events.
- User Management: Manage user accounts and their roles.
- Ranking Management: Calculate and display rankings based on event participation and points.
- Dashboard: View summary statistics and rankings.
- Authentication: Secure login for users and administrators.
- Responsive Design: Accessible on various devices.

## Installation

1. Download and unzip the project files.
2. Copy the project folder to your web server's root directory.
3. Open phpMyAdmin and create a new database named `sltjranking`.
4. Import the `library.sql` file located in the `admin/includes/sqlfile` directory into the `sltjranking` database.
5. Update the database configuration in the `admin/includes/config.php` file with your database credentials.

## Usage

1. Open your web browser and navigate to `http://localhost/sltjranking`.
2. Login as a user using the following credentials:
   - Email: `test@gmail.com`
   - Password: `Test@123`
3. To access the admin panel, navigate to `http://localhost/sltjranking/admin`.
4. Login as an admin using the following credentials:
   - Username: `admin`
   - Password: `Test@12345`
5. Use the dashboard to manage events, users, and rankings.

## Database Configuration

The database configuration is located in the `admin/includes/config.php` file. Update the following constants with your database credentials:

```php
define('DB_HOST', 'your_database_host');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_NAME', 'sltjranking');
```

For additional details, refer to the `admin/includes/read me.txt` file.
