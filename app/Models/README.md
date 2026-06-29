LSS Management System

A comprehensive School Management Information System (SMIS) built with PHP CodeIgniter, designed to automate and streamline school administration processes including student management, attendance tracking, staff administration, academic records, reporting, and SMS notifications.

📖 Overview

LSS Management System provides a centralized platform for managing daily school operations. It helps administrators, teachers, and staff maintain accurate records, monitor student performance and attendance, manage employees, generate reports, and communicate effectively through SMS notifications.

The system is designed to reduce paperwork, improve data accuracy, and enhance operational efficiency within educational institutions.

✨ Features
👨‍🎓 Student Management
Student registration and enrollment
Student profile management
Student search and filtering
Student promotion to new classes
Student academic records management
📅 Attendance Management
Daily student attendance tracking
Attendance reports and summaries
Attendance history monitoring
👩‍🏫 Staff Management
Employee registration and management
Position and role assignment
Staff profile management
Administrative employee records
🏫 Academic Administration
Class management
Academic year management
Subject management
Student promotion workflow
📊 Reporting
Student reports
Attendance reports
Staff reports
School statistics and summaries
📱 SMS Notification System
Bulk SMS notifications
Student and parent communication
Administrative announcements
Attendance-related alerts
🔐 User Management
Secure authentication
Role-based access control
Administrator dashboard
User account management
🛠️ Technology Stack
Backend: PHP
Framework: CodeIgniter
Database: MySQL/MariaDB
Frontend: HTML, CSS, Bootstrap, JavaScript
SMS Integration: Third-party SMS Gateway APIs
📂 Project Structure
LSS-MANAGEMENT-SYSTEM/
│
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Views/
│   └── Config/
│
├── public/
│
├── writable/
│
├── tests/
│
└── vendor/
🚀 Installation
Prerequisites
PHP 8.0+
Composer
MySQL/MariaDB
Apache/Nginx
Clone the Repository
git clone https://github.com/your-username/LSS-MANAGEMENT-SYSTEM.git
cd LSS-MANAGEMENT-SYSTEM
Install Dependencies
composer install
Configure Environment

Copy the environment file:

cp env .env

Update the database settings in .env:

database.default.hostname = localhost
database.default.database = lss_db
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
Run Database Migrations
php spark migrate
Start Development Server
php spark serve

Access the application at:

http://localhost:8080
⚙️ Configuration
SMS Gateway Setup

Configure your SMS provider credentials in the application's configuration file:

SMS_API_KEY=
SMS_SENDER_ID=
SMS_USERNAME=
SMS_PASSWORD=
🔒 Security Features
Password hashing
Session-based authentication
Role-based authorization
Input validation and sanitization
CSRF protection
Secure database queries using CodeIgniter Query Builder
📈 Benefits
Centralized school data management
Improved administrative efficiency
Reduced paperwork
Faster communication through SMS
Accurate attendance monitoring
Better reporting and decision-making
🤝 Contributing

Contributions are welcome. Please fork the repository and submit a pull request for any improvements, bug fixes, or new features.

Steps
Fork the repository
Create a feature branch
git checkout -b feature/new-feature
Commit changes
git commit -m "Add new feature"
Push to GitHub
git push origin feature/new-feature
Open a Pull Request
📄 License

This project is licensed under the MIT License. See the LICENSE file for details.

👨‍💻 Author

LSS Management System

A modern school management platform developed to simplify educational administration, improve record management, and enhance communication within schools.