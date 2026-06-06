# Residental_final

A lightweight PHP web application for managing residential properties, owners, tenants, and related financial data. The system provides separate interfaces for managers and owners, allowing CRUD operations on flats, employees, owners, rents, and expenditures.

---

## Overview

`Residental_final` is a **PHP‑based** property management solution that:

- Stores data in a MySQL database (`resident_db.sql`).
- Offers role‑based access (manager vs. owner) with secure login/logout flows.
- Provides a clean UI built with HTML/CSS.
- Enables managers to add, edit, view, and delete records for employees, flats, owners, rents, and expenditures.
- Allows owners to manage their flats.

The repository contains all source files needed to run the application locally or on a web server.

---

## Features

| Feature | Description |
|---------|-------------|
| **User Authentication** | Manager and owner login pages (`login.php`, `manager_login.php`) with session handling and logout (`logout.php`, `manager/logout.php`). |
| **Dashboard** | Manager dashboard (`manager/manager_dashboard.php`) with quick links to CRUD operations. |
| **CRUD Operations** | Add, edit, view, and delete for:<br>• Employees (`add_employe.php`, `edit_employee.php`, `view_employees.php`)<br>• Flats (`add_flat.php`, `edit_flat.php`, `view_flats.php`)<br>• Owners (`add_owner.php`, `edit_owner.php`, `view_owners.php`)<br>• Rents (`add_rent.php`, `edit_rent.php`, `view_rents.php`)<br>• Expenditures (`add_expendeture.php`, `edit_expenditure.php`, `view_expenditures.php`). |
| **Responsive UI** | Simple, clean styling (`css/style.css`) and navigation (`navbar.php`, `manager/navbar.php`). |
| **Image Support** | Flat pictures stored under `manager/flat_pictures/`. |
| **Configuration** | Centralized DB connection settings (`config.php`, `manager/config.php`, `owner/config.php`). |

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.x+ |
| **Database** | MySQL (SQL dump in `Database/resident_db.sql`) |
| **Frontend** | HTML5, CSS3 |
| **Web Server** | Apache / Nginx (any server supporting PHP) |

---

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/Residental_final.git
   cd Residental_final
   ```

2. **Set up the database**

   - Create a new MySQL database (e.g., `resident_db`).
   - Import the provided schema:

     ```bash
     mysql -u YOUR_DB_USER -p resident_db < Database/resident_db.sql
     ```

3. **Configure the application**

   - Open `config.php`, `manager/config.php`, and `owner/config.php`.
   - Replace placeholder values with your database credentials:

     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'resident_db');
     define('DB_USER', 'YOUR_DB_USER');
     define('DB_PASS', 'YOUR_DB_PASSWORD');
     ```

4. **Set up the web server**

   - Place the project folder inside your web root (e.g., `/var/www/html/Residental_final`).
   - Ensure the server points to `index.php` as the default document.
   - Adjust file permissions so PHP can read/write where needed (e.g., `manager/flat_pictures/`).

5. **Optional: Enable URL rewriting (Apache)**

   If you prefer clean URLs, add a `.htaccess` file: