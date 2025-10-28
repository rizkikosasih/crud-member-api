# CRUD Member Management System

Sistem sederhana untuk **mengelola member dan hobinya** dengan **CRUD API dan Frontend** berbasis Laravel & Blade.

## **Fitur Utama**

- CRUD Member/User (Create, Read, Update, Delete)

- Manage Hobbies per User

- Pagination di list member


## **Tech Stack**

- **Backend:** Laravel 10, JWT

- **Frontend:** TailwindCSS 4 + DaisyUI 5.3.10, Blade

- **Database:** MySQL

- **Request Async:** Axios


## **Requirement / Prasyarat**

- PHP >= 8.2

- Laravel 10

- MySQL / MariaDB

- Composer & Node.js / npm


## **Setup / Instalasi**

1. Clone repository:


```bash 
git clone <repository-url> cd <project-folder>
```

2. Install dependencies:


```bash
composer install npm install
```

3. Copy `.env` dan konfigurasi database:


```bash
cp .env.example .env
```

Ubah konfigurasi database di `.env`:

```dotenv
DB_DATABASE=crud_member_api
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. Generate app key:


```bash
php artisan key:generate
```

5. Generate JWT secret:


```bash
php artisan jwt:secret
```

6. Migrasi dan seeder:


```bash
php artisan migrate --seed
```

7. Jalankan frontend dev server:


```bash
npm run dev
```

8. Jalankan Laravel server:


```bash
php artisan serve
```

Buka browser di `http://localhost:8000`.

## **Struktur Folder**

- `app/Models` → Model `User` & `Hobby`

- `app/Http/Controllers` → Controller untuk API & Web

- `resources/views` → Blade template frontend

- `routes/api.php` → Route API

- `routes/web.php` → Route Web


## **Catatan**

- Database default: `crud_member_api`

- Frontend dijalankan dengan `npm run dev`, tidak perlu build production

- JWT secret wajib di-generate agar autentikasi API berfungsi
