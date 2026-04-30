# CRUD Member Management System

Sistem sederhana untuk **mengelola member dan hobinya** dengan **CRUD API dan Frontend** berbasis Laravel & Blade.

## **Fitur Utama**

- CRUD Member/User (Create, Read, Update, Delete)
- Manage Hobbies per User
- Pagination di list member

---

## **Tech Stack**

- **Backend:** Laravel 10, JWT
- **Frontend:** TailwindCSS 4 + DaisyUI 5.3.10, Blade
- **Database:** MySQL
- **Request Async:** Axios

---

## **Requirement / Prasyarat**

- PHP >= 8.2
- Laravel 10
- MySQL / MariaDB
- Composer & Node.js / npm

---

## **Setup / Instalasi**

1. Clone repository:

```bash
git clone https://github.com/rizkikosasih/crud-member-api.git <project-folder>
cd <project-folder>
```

2. Install dependencies:

```bash
composer install
npm install
```

3. Copy `.env`

```terminal
cp .env.example .env
```

4. Ubah konfigurasi database di `.env`:

```env
DB_DATABASE=crud_member_api
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. Generate app key:

```artisan
php artisan key:generate
```

6. Generate JWT secret:

```artisan
php artisan jwt:secret
```

7. Migrasi dan seeder:

```artisan
php artisan migrate --seed
```

8. Jalankan frontend dev dan laravel server:

```npm
npm run dev:all
```

9. Buka browser di

```
http://localhost:8000
```

---

## **Struktur Folder**

- `app/Models` → Model `User` & `Hobby`

- `app/Http/Controllers` → Controller untuk API & Web

- `resources/views` → Blade template frontend

- `routes/api.php` → Route API

- `routes/web.php` → Route Web

---

## **Catatan**

- Database default: `crud_member_api`
- Frontend dijalankan dengan `npm run dev`, tidak perlu build production
- JWT secret wajib di-generate agar autentikasi API berfungsi
