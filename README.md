```markdown
# Academic Grading System

This is an Academic Grading Management System built using the [Laravel](https://laravel.com/) framework. This project was developed as a university assignment to manage and evaluate student grades efficiently.

## 👥 Project Team (Group 7)
This project was collaboratively developed by:
1. MUHAMAD AZRIR – NIM: 1062575 
2. MUHAMMAD SALMAN  – NIM: 1062579
3. SYIFA NAILATUR RAHMA  – NIM: 1062587 

## 📋 Prerequisites

Before running this project, please make sure you have the following installed on your local machine:
* PHP >= 8.1
* Composer
* Node.js & NPM
* MySQL (XAMPP / Laragon)
* Git

## 🛠️ How to Run / Installation Guide

Follow these step-by-step instructions to clone and run this project locally on your machine:

**1. Clone the repository**
```bash
git clone [https://github.com/just-karen-its-her/academic-grading-system.git](https://github.com/just-karen-its-her/academic-grading-system.git)
cd academic-grading-system

```

**2. Install PHP Dependencies**

```bash
composer install

```

**3. Install and Compile Frontend Assets**

```bash
npm install
npm run build

```

**4. Environment Configuration**
Duplicate the `.env.example` file and rename it to `.env`:

```bash
cp .env.example .env

```

Update your database credentials inside the new `.env` file to match your local setup:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sistem_penilaian
DB_USERNAME=root
DB_PASSWORD=

```

**5. Generate Application Key**

```bash
php artisan key:generate

```

**6. Run Database Migrations**
Run the migrations to set up your database tables. If you want to include dummy data, run the command with the `--seed` flag:

```bash
php artisan migrate
# Or, to run migrations with seeders:
php artisan migrate --seed

```

**7. Start the Local Server**

```bash
php artisan serve

```

```

```
