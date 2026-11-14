# TWINEX – Freelancer Management System

## Project Overview

TWINEX is a web-based Freelancer Management System designed to help freelancers and small teams manage their clients, projects, payments, invoices, tasks, and team members in one place.

The system provides a centralized dashboard to monitor business activities, track project progress, manage financial information, and organize daily work efficiently.

## Key Features

- **Dashboard:** View new projects, ongoing projects, completed projects, net profit, total projects, and total clients.
- **Client Management:** Add, view, update, and manage client details.
- **Project Management:** Track projects, clients, budgets, payments, expenses, deadlines, and project status.
- **Payment Management:** Manage incoming and outgoing payments.
- **Invoice Management:** Create and manage invoices, amounts, due dates, and invoice status.
- **Task Management:** Create tasks, assign team members, and track task progress.
- **Team Management:** Maintain team member details, roles, email addresses, and phone numbers.
- **Authentication:** Password-protected workspace with session-based login.

## Technologies Used

| Component | Technology |
|---|---|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP |
| Database | MySQL |
| Database Connection | PHP PDO |
| Development Environment | XAMPP |
| Database Management | MySQL Workbench |
| Version Control | Git and GitHub |

## Project Structure

```text
freelancer-management-system/
│
├── api/
│   ├── auth.php
│   ├── clients.php
│   ├── invoices.php
│   ├── payments.php
│   ├── projects.php
│   ├── tasks.php
│   └── team.php
│
├── icons/
│
├── index.html
├── styles.css
├── bundle.v2.js
├── bundle.js
├── config.php
├── config.example.php
├── db.php
├── helpers.php
├── schema.sql
├── manifest.json
├── service-worker.js
├── .htaccess
├── .gitignore
└── README.md
```

## Database Details

The project uses a MySQL database to store and manage application information.

The database includes the following main tables:

1. `clients`
2. `projects`
3. `team_members`
4. `payments`
5. `invoices`
6. `tasks`

The database schema and initial sample records are provided in `schema.sql`.

## Installation and Setup

### 1. Install XAMPP

Download and install XAMPP to run Apache and PHP locally.

### 2. Clone or Download the Repository

Download the project from GitHub or clone it using:

```bash
git clone YOUR_GITHUB_REPOSITORY_URL
```

### 3. Place the Project in XAMPP

Copy the project folder into:

```text
C:\xampp\htdocs\
```

### 4. Create the MySQL Database

1. Open MySQL Workbench.
2. Create a database named `freelancer_db`.
3. Open the `schema.sql` file.
4. Select the database and execute the SQL script.

### 5. Configure the Database Connection

Update the local `config.php` file with your database details:

- Database host
- Database name
- Database username
- Database password
- Application login password
- Session secret

Keep actual credentials private. Do not upload real passwords or secrets to GitHub.

### 6. Run the Application

1. Start Apache in XAMPP.
2. Make sure your MySQL server is running.
3. Open your browser.
4. Visit:

```text
http://localhost/freelancer%20project%20file/index.html
```

5. Log in using the application password configured in `config.php`.

## Security

- The application uses password-based authentication.
- Database operations use PHP PDO prepared statements.
- Database credentials and application secrets should be kept outside public source control.
- HTTPS should be enabled when deploying the application online.

## Future Enhancements

- Online deployment and public access.
- Improved reports and analytics.
- Automated invoice generation.
- Email notifications and reminders.
- Enhanced user roles and permissions.
- Advanced financial reporting.

## Project Objective

The main objective of TWINEX is to simplify freelancer business operations by providing a single platform to manage clients, projects, finances, tasks, and team activities efficiently.

---

**Project Name:** TWINEX – Freelancer Management System

**Project Type:** Web Application

**Domain:** Freelancer and Business Management
