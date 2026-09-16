# Student Event Registration Management System

## Macmillan Medical Training College (MMTC)

A web-based **Student Event Registration Management System** developed for Macmillan Medical Training College (MMTC). The system allows students to view available college events and register online, while authorised users can view and manage submitted registration records.

---

## Project Overview

The Student Event Registration Management System provides a centralized platform for managing student participation in college events.

Instead of relying on manual registration, students can browse available events, select an event, provide their details and submit their registration online.

The system stores registration information in a MySQL database and provides an administration section where authorised users can view, search and manage submitted registration records.

---

## Project Objectives

The main objectives of the system are to:

* Allow students to view available events.
* Allow students to register for events online.
* Validate student registration information.
* Store registration information in a MySQL database.
* Allow authorised users to view submitted registration records.
* Provide search and filtering functionality for registration records.
* Display the number of registered students.
* Provide a responsive and user-friendly interface.

---

## Main Features

### Student Features

* View upcoming events.
* Browse different event categories.
* Search for events.
* View event information such as date and venue.
* Register for an event online.
* Receive feedback after submitting a registration.

### Administration Features

* Administrator login.
* View submitted student registrations.
* Search registration records.
* Filter registrations by event.
* View the total number of registrations.
* Edit registration information.
* Delete registration records.

---

## Technologies Used

The system was developed using:

* **HTML5** – Website structure
* **CSS3** – Custom styling and layout
* **Bootstrap 5** – Responsive user interface
* **JavaScript** – Client-side interactivity and search functionality
* **PHP** – Server-side processing and database communication
* **MySQL** – Database management
* **XAMPP** – Local development environment
* **Git & GitHub** – Version control

---

## System Requirements

To run the project locally, the following are required:

* Windows computer
* XAMPP
* Apache
* MySQL
* PHP
* Web browser
* Visual Studio Code or another code editor

---

## Installation and Setup

### 1. Install XAMPP

Install XAMPP with Apache, PHP and MySQL.

### 2. Start XAMPP

Open the XAMPP Control Panel and start:

* Apache
* MySQL

### 3. Copy the Project

Place the project folder inside the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\Student Event Registration System
```

### 4. Set Up the Database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create the required database and tables for the Student Event Registration Management System.

### 5. Configure the Database Connection

Open:

```text
config/db.php
```

Make sure the database connection details match your local MySQL configuration.

### 6. Run the System

Open a web browser and visit:

```text
http://localhost/Student%20Event%20Registration%20System/
```

---

## Registration Process

A student can register for an event through the following process:

1. Visit the system homepage.
2. Browse the available events.
3. Select an event.
4. Click the registration option.
5. Enter the required student information.
6. Submit the registration form.
7. The system validates the submitted information.
8. Valid information is stored in the MySQL database.
9. The student receives registration feedback.

---

## Administration

Authorised users can access the administration section to manage registration records.

The administrator can:

* Log in to the system.
* View registered students.
* Search registration records.
* Filter records by event.
* View the total number of registrations.
* Update registration information.
* Delete registration records.

The registration records include:

* Registration ID
* Student Name
* Admission Number
* Email
* Phone
* Course
* Event
* Registration Date

---

## Responsive Design

The system uses **Bootstrap 5** together with custom CSS to provide a responsive interface that can be used on:

* Desktop computers
* Laptops
* Tablets
* Mobile devices

---

## Search Functionality

The system provides search functionality to make it easier to find information.

Students can search available events, while authorised users can search submitted registration records from the administration section.

---

## Project Structure

```text
Student Event Registration System/
│
├── admin/
│   ├── login.php
│   └── records.php
│
├── config/
│   └── db.php
│
├── images/
│   └── Website and event images
│
├── index.php
├── events.php
├── register.php
├── process_registration.php
├── about.php
├── contact.php
└── README.md
```

---

## Academic Purpose

This project was developed as a web application development project to demonstrate practical skills in:

* Web design and development
* Responsive web design
* JavaScript programming
* PHP programming
* MySQL database management
* Form processing and validation
* CRUD operations
* Git and GitHub version control

---

## Future Improvements

Possible future improvements include:

* Student accounts and profiles.
* Email registration confirmations.
* Event capacity management.
* Duplicate registration prevention.
* Event creation and management by administrators.
* Registration statistics and charts.
* Student notification system.

---

## Project Information

**Project:** Student Event Registration Management System
**Institution:** Macmillan Medical Training College (MMTC)
**Year:** 2026
