# 👟 Nike E-Commerce System (FYP)

![Project Banner](https://img.shields.io/badge/Project-Diploma_Final_Year-blue?style=for-the-badge)
![Tech Stack](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Database](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

A comprehensive, web-based e-commerce platform dedicated to Nike sports shoes, developed as a Diploma Final Year Project. This system bridges the gap between high-performance footwear and a seamless online shopping experience, featuring a robust administration backend.

---

## 🌟 Key Features

### 🛒 Customer Experience
- **User Authentication**: Secure registration and login for personalized shopping.
- **Dynamic Catalog**: Browse Nike shoes across multiple categories (Lifestyle, Running, Basketball, etc.).
- **Smart Cart**: Fully functional shopping cart with real-time price updates and item management.
- **Advanced Search**: Find specific models quickly with integrated search functionality.
- **Checkout Flow**: Integrated multi-step checkout with address management.
- **Order Tracking**: Detailed order history and status tracking.
- **Password Recovery**: Secure "Forgot Password" functionality via email.

### 🛡️ Admin Dashboard
- **Comprehensive Analytics**: Visual reports on sales and recent transactions.
- **Inventory Management**: Full CRUD (Create, Read, Update, Delete) operations for products and categories.
- **Order Fulfillment**: Manage customer orders, update statuses, and track history.
- **Data Export**: Export order data to Excel for external reporting.
- **User Management**: View and manage customer records.

---

## 🛠️ Tech Stack

- **Frontend**: `HTML5`, `CSS3`, `JavaScript`, `Bootstrap`
- **Backend**: `PHP` (Core)
- **Database**: `MySQL`
- **Libraries**: 
  - `PHPMailer` (Email notifications)
  - `FPDF` (Invoice generation)
- **Environment**: `XAMPP` (Apache, MySQL)

---

## 🚀 Installation & Setup

Follow these steps to get the project running locally:

### 1. Prerequisites
- Install [XAMPP](https://www.apachefriends.org/index.html) (Version 7.4 or higher recommended).
- A code editor like [VS Code](https://code.visualstudio.com/).

### 2. Clone the Project
```bash
git clone https://github.com/sylvesterkho1113/nike-ecommerce-fyp-diploma.git
```
*Or download the ZIP and extract it to `C:\xampp\htdocs\nike-ecommerce`.*

### 3. Database Configuration
1. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Go to `http://localhost/phpmyadmin/`.
3. Create a new database named `final_year_project`.
4. Import the SQL file located at: `final_year_project.sql`.

### 4. Application Configuration
Ensure your database credentials in `config.php` match your local environment:
```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "final_year_project";
```

### 5. Run the App
Navigate to `http://localhost/nike-ecommerce/home.php` in your browser.

---

## 📂 Project Structure

```text
├── admin/               # Administrative backend modules
├── css/                 # Stylesheets (Bootstrap & custom)
├── img/                 # UI assets and icons
├── image/               # Product images
├── js/                  # Frontend logic & animations
├── phpmailer/           # Email handling library
├── SQL/                 # Database migration scripts
├── config.php           # DB Connection settings
└── final_year_project.sql # Main database export
```

---

## 👥 Team Members

- **Kho Wei Cong** - *Lead Developer*
- **Tey Soon Hong** - *UI/UX Designer*
- **Tee Chin Yean** - *Database Architect*

---

## 📄 License

This project is developed for educational purposes as part of a Diploma Final Year Project.

---
*Developed with ❤️ for Nike enthusiasts.*
