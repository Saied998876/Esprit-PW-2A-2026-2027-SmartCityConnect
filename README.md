<<<<<<< HEAD
# Smart Living – Smart City Platform for Tunisian Municipalities

## Overview
This project was developed as part of the **PIWEB – 2nd Year Engineering Program** at **Esprit School of Engineering** (Academic Year 2026–2027).

Smart Living is a web platform that connects citizens and their municipality. Citizens report urban problems, join green events, discuss in a community forum, earn green rewards, file complaints and support local campaigns with donations. Administrators manage the whole platform from a secured back office.

## Table of Contents
- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Security](#security)
- [Contributors](#contributors)
- [Academic Context](#academic-context)
- [Getting Started](#getting-started)
- [Contribution](#contribution)
- [License](#license)
- [Acknowledgments](#acknowledgments)

## Features

### User Management (shared module) – done
| Feature | Who | Action |
|---|---|---|
| Register (password hashed with bcrypt) | Visitor | `?action=register` |
| Log in / Log out (PHP sessions) | Everyone | `?action=login` / `?action=logout` |
| Personal space (front office) | User | `?action=dashboard` |
| Read / update my profile | User | `?action=profile` |
| Change my password | User | `?action=password` |
| Delete my account | User | `?action=account_delete` |
| Admin dashboard (statistics) | Admin | `?action=admin` |
| List, search and filter users | Admin | `?action=admin_users` |
| Create a user and choose his role | Admin | `?action=admin_user_create` |
| Update a user, change role, status and points | Admin | `?action=admin_user_edit&id=…` |
| Block / unblock an account | Admin | `?action=admin_user_toggle` |
| Delete a user | Admin | `?action=admin_user_delete` |

**Roles:** `user` (citizen → front office) and `admin` (back office). Every sensitive page checks the role on the server (`requireAuth()` / `requireRole('admin')`). A user who opens an admin URL gets **403 – Access denied**. A blocked account cannot log in and is logged out immediately.

### Modules (one per team member)
| Module | Entities |
|---|---|
| Urban Reporting | Incident, Task |
| Green Events | GreenEvent, EventParticipation |
| Forum | Post, Comment |
| Green Rewards | Reward, Redemption |
| Complaints | Reclamation, Response |
| Donations | Campaign, Donation |

## Tech Stack
### Frontend
- HTML5, CSS3
- JavaScript (client-side form validation)

### Backend
- PHP 8 (MVC architecture, PDO)
- MySQL / MariaDB (XAMPP)

## Architecture
The project follows the **MVC** architecture of the workshop *Sessions and PHP Security*:

```
SmartLiving/
├── config/
│   └── database.php          # PDO connection (Singleton)
├── controllers/
│   ├── AuthController.php    # register, login, logout, sessions, roles, CSRF
│   └── UserController.php    # user CRUD + profile
├── models/
│   └── UserModel.php         # attributes + getters/setters only
├── views/
│   ├── assets/               # css, js (validation.js), img
│   ├── partials/             # helpers (escaping, icons), alerts
│   ├── layout.php            # front office skin
│   ├── layout_admin.php      # back office skin
│   ├── login.php, register.php
│   ├── user_space.php, profile.php
│   ├── admin_space.php, admin_users.php, admin_user_form.php
│   └── error_403.php
├── docs/class_diagram.png
├── smartliving.sql           # database + demo accounts
└── index.php                 # single entry point (?action=...)
```

- **Model:** one piece of data, no SQL.
- **Controller:** all business logic, prepared SQL queries, validation, sessions, CSRF, roles.
- **View:** HTML only, every value escaped with `htmlspecialchars()`.

## Security
| Risk | Protection | Where |
|---|---|---|
| SQL injection | PDO prepared statements, `ATTR_EMULATE_PREPARES = false` | controllers, `config/database.php` |
| Plain-text passwords | `password_hash()` / `password_verify()` | `register()`, `login()`, `UserController` |
| XSS | `htmlspecialchars()` on output, input validation, CSP header | views, controllers, `index.php` |
| CSRF | Token per session, checked with `hash_equals()` on every form | `AuthController`, all forms |
| Session fixation | `session_regenerate_id(true)` after login | `login()` |
| Session theft | Cookie `HttpOnly` + `SameSite=Lax`, strict mode | `index.php` |
| Clickjacking | `X-Frame-Options: DENY` | `index.php` |
| Privilege escalation | Role forced to `user` at sign up, `requireRole()` on each admin page, role re-read from the database on every request | `register()`, `index.php` |
| Abandoned sessions | Expire after 30 minutes of inactivity | `index.php` |

**Input validation:** every form is checked twice: in JavaScript (`views/assets/js/validation.js`) and again in PHP (controllers).

## Contributors
| Name | Module |
|---|---|
| Student 1 | User management + Urban Reporting |
| Student 2 | Green Events |
| Student 3 | Forum |
| Student 4 | Green Rewards |
| Student 5 | Complaints |
| Student 6 | Donations |

## Academic Context
Developed at **Esprit School of Engineering – Tunisia**
PIWEB – 2A | 2026–2027

## Getting Started
### Installation
1. Clone the repository into the `htdocs` folder of XAMPP:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs     # macOS
   # cd C:\xampp\htdocs                         # Windows
   git clone https://github.com/<organisation>/Esprit-PIWEB-<Classe>-2026-SmartLiving.git SmartLiving
   ```
2. Start **Apache** and **MySQL** in XAMPP.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`), click the **SQL** tab, paste the content of `smartliving.sql` and click **Go**. It creates the `smartliving` database, all the tables and two demo accounts.
4. If your MySQL has a password, change it in `config/database.php`.

### Usage
Open `http://localhost/SmartLiving/`

| Account | Email | Password |
|---|---|---|
| Administrator (back office) | `admin@smartliving.tn` | `Admin@1234` |
| Citizen (front office) | `user@smartliving.tn` | `User@1234` |

## Contribution
1. Always start with: `git pull origin main`
2. Work on your own branch: `git checkout -b <your-name>`
3. Commit often: `git commit -m "Add event list page"`
4. Push your branch: `git push origin <your-name>`
5. Open a **Pull Request** to `main`, then merge.
6. Update the task on the **GitHub Project Board** (To do → In progress → Done).

## License
This project is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.

## Acknowledgments
We thank our supervisors at **Esprit School of Engineering** for their guidance during this project.
=======
# Smart City Connect

## Overview

Smart City Connect is a web application developed by a team of six students at Esprit School of Engineering – Tunisia during the academic year 2026–2027.

The main goal of this project is to improve communication between citizens and local authorities and encourage people to participate in making their cities cleaner, safer, and more sustainable.

The platform allows citizens to report urban problems, participate in environmental activities, communicate through a community forum, and contribute to local initiatives.

## Features

The main features of Smart City Connect are:

- **User Management:** Manage user accounts and profiles.
- **Urban Reporting:** Report problems such as damaged roads, broken streetlights, and waste.
- **Green Events:** Create and participate in environmental events.
- **Forum Management:** Create posts, add comments, and share ideas with other citizens.
- **Green Rewards:** Encourage citizens to participate in environmental activities through a reward system.
- **Complaints Management:** Submit complaints and receive responses.
- **Donations:** Support environmental campaigns through donations.
- **Account security:** Citizen registration, profile management, and role-based administrator access.

## Tech Stack

### Frontend
- HTML
- CSS
- JavaScript
- Bootstrap
- Arsha Template

### Backend
- PHP
- AdminLTE 4 Template

### Development Tools
- Visual Studio Code
- XAMPP
- Git
- GitHub

## Architecture

The project follows the MVC (Model-View-Controller) architecture.

### Model
Manages application data and database operations.

### View
Displays the user interface using HTML, CSS, JavaScript, and PHP.

### Controller
Handles user requests and connects the Model with the View.

This architecture helps organize the code and makes the application easier to maintain and develop.

## Project Modules

The project includes the following proposed modules:

| Module | Entities |
|---|---|
| User Management | User, Profile |
| Urban Reporting | Incident, IncidentCategory |
| Green Events | GreenEvent, EventParticipation |
| Forum Management | Post, Comment |
| Green Rewards | Reward, Redemption |
| Complaints Management | Reclamation, Response |
| Donations | Campaign, Donation |

Each module contains two related entities.

## Contributors

This project is developed by six students:

- Rasheed Said
- Ahmed Remi
- Syrine Laaribi
- Yasmine Chaari
- Meriem Romdhan
- Aladdin Hamrouni

## Academic Context

**University:** Esprit School of Engineering – Tunisia

**Academic Year:** 2026–2027

**Module:** Projet Technologies Web 2A

**Project Name:** Smart City Connect

## Getting Started

### Requirements

To run this project locally, you need:

- XAMPP
- Visual Studio Code
- A web browser

### Installation

**Step 1: Download the project**

Download the project from GitHub.

**Step 2: Install XAMPP**

Install XAMPP on your computer if it is not already installed.

**Step 3: Move the project folder**

Copy the project folder into:

`C:\xampp\htdocs\`

**Step 4: Start XAMPP**

Open the XAMPP Control Panel and start:

- Apache

**Step 5: Open the project**

Open your web browser and enter:

`http://localhost/Esprit-PW-2A-2026-2027-SmartCityConnect/`

**Step 6: Set up the first administrator**

Enable PHP's `pdo_sqlite` extension. In PowerShell, set your own administrator email and a unique password of at least 12 characters, then run the setup script:

```powershell
$env:ADMIN_EMAIL = 'admin@example.com'
$env:ADMIN_PASSWORD = 'replace-with-a-unique-password'
& 'C:\xampp\php\php.exe' 'C:\xampp\htdocs\Esprit-PW-2A-2026-2027-SmartCityConnect\scripts\createAdmin.php'
Remove-Item Env:ADMIN_EMAIL, Env:ADMIN_PASSWORD
```

Update the script path if the project is in another folder. The script runs only in the command line, creates the local database if needed, and refuses to create a second initial administrator. Do not use the sample values as real credentials or store actual passwords in Git.

**Step 7: Start using the application**

- Open `http://localhost/Esprit-PW-2A-2026-2027-SmartCityConnect/` to browse campaigns.
- Register a citizen account at `/index.php?page=register` to manage a profile and make a pledge.
- Sign in at `/index.php?page=login`.
- Administrator accounts can manage campaigns and pledges at `/admin.php`, and user accounts at `/users.php`.

The database is created at `data/donations.sqlite` on the first request. Existing campaign data is kept when the users table is added. The database is local and is not included in Git.

### User management, security, and validation

Public registration creates citizen accounts. Administrators can create, view, update, and delete accounts, assign citizen or administrator roles, and reset a user's password. Citizens can update their own name, email, and password. The application prevents deleting/demoting the last administrator and prevents administrators from deleting or demoting themselves.

Passwords are stored using PHP `password_hash()` and checked with `password_verify()`. SQL queries use prepared statements. Forms have browser and server-side validation and CSRF tokens. Session cookies use HttpOnly and SameSite settings; session IDs are regenerated at sign-in, and idle/old sessions expire. Serve the project over HTTPS outside local development.

The Donations module records pledges only; it does not collect payment details.

### Team collaboration and integration

Use the repository's GitHub Project Board to assign validation work to named team members and track status, review, and integration. Each contributor should work on their own branch with attributable commits; merge reviewed work into the shared validation branch and test the complete project from one clean checkout. Only record contributions that team members actually made.

## Sustainable Development Goals

Smart City Connect supports sustainable development by encouraging citizens to participate in improving their cities.

The project is related to:

- **SDG 11:** Sustainable Cities and Communities
- **SDG 9:** Industry, Innovation and Infrastructure
- **SDG 13:** Climate Action
- **SDG 8:** Decent Work and Economic Growth

## Future Improvements

Future improvements may include:

- Artificial intelligence for incident classification.
- Better communication between citizens and municipalities.
- Improved reporting and statistics.
- Mobile application development.
- Additional features for environmental participation.

## Acknowledgments

We would like to thank our supervisors and Esprit School of Engineering – Tunisia for their guidance and support throughout this academic project.
>>>>>>> 25b60f4340519499c295a42cef743b41ba25892a
