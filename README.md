# Acadova Backend 🚀

**Acadova Backend** is a robust, high-performance RESTful API and Admin Management system built with **Laravel**. It powers the entire **Acadova Academic Workspace Ecosystem**, serving mobile clients (Flutter) and providing administrators with full control over users, departments, subjects, sections, semesters, faculty allocations, and quiz lifecycle management.

---

## 🌟 Key Features

- 🔐 **Authentication & Authorization**:
  - Email/Password authentication & Sanctum Token APIs.
  - Native **Google OAuth 2.0 Integration** with single-sign-on (SSO).
  - Role-based Access Control (Admin, Faculty, Student).

- 🏫 **Academic Management**:
  - Dynamic CRUD for Universities, Colleges, Departments, Courses, Branches, Sections, Subsections, and **Semesters**.
  - Flexible **Faculty Subject & Section Allocation Engine**.
  - Student Profile setup & dynamic validation.

- 📝 **Quiz & Assessment Engine**:
  - Comprehensive Quiz creation with timed tests, multiple choice, auto-grading, and attempt logs.
  - Real-time result processing, student attempt history, and analytics.

- 📊 **Admin Portal (Blade & Tailwind/Vanilla UI)**:
  - Clean responsive dashboard for managing users, faculty allocations, and academic structure.
  - Live Migration Runner script (`migrate.php`) for seamless database management.

---

## 🏗️ Architecture & Tech Stack

| Component | Technology |
| :--- | :--- |
| **Framework** | Laravel 10.x / PHP 8.x |
| **Database** | MySQL / MariaDB |
| **Authentication** | Laravel Sanctum & Google Auth API |
| **Frontend Admin UI** | Blade Templates, HTML5, Vanilla CSS / Tailwind |
| **API Architecture** | RESTful JSON API |

---

## ⚙️ Setup & Installation

### Prerequisites
- PHP `>= 8.1`
- Composer `>= 2.0`
- MySQL / MariaDB Database Server
- XAMPP / WPN-Tools or Local PHP Environment

### Installation Steps

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/neodyit/Acadova-backend.git
   cd Acadova-backend
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment (`.env`)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Set your MySQL Database Credentials in `.env`:*
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=acadova_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

5. **Start Local Development Server**:
   ```bash
   php artisan serve
   ```
   The backend API will be available at `http://127.0.0.1:8000`.

---

## 📡 API Endpoints Overview

| Category | Endpoint | Method | Description |
| :--- | :--- | :--- | :--- |
| **Auth** | `/api/login` | `POST` | User login (Password) |
| **Auth** | `/api/google-login` | `POST` | Google SSO Login / Register |
| **Academic** | `/api/academic/semesters` | `GET/POST` | List or create academic semesters |
| **Academic** | `/api/academic/branches` | `GET/POST` | Manage academic branches |
| **Academic** | `/api/academic/subjects` | `GET/POST` | Manage subjects |
| **Academic** | `/api/academic/sections` | `GET/POST` | Manage sections |
| **Admin** | `/api/admin/faculty/allocations` | `POST` | Assign subject/section to faculty |
| **Quiz** | `/api/quizzes` | `GET/POST` | Retrieve and create quizzes |

---

## 🤝 Contributing

Contributions are welcome! Please feel free to open a Pull Request or report issues.

---

## 📄 License

This project is licensed under the **MIT License**.
