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
