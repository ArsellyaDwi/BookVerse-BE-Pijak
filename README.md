# Deskripsi Singkat Proyek

Proyek ini adalah Backend BookVerse yang dibuat menggunakan Laravel. Backend ini berfungsi sebagai pusat layanan API untuk aplikasi BookVerse yang menyediakan autentikasi pengguna, manajemen data buku, sistem ulasan dan rating, pengelolaan koleksi buku, serta integrasi dengan layanan AI untuk rekomendasi buku dan analisis emosi.

Selain sebagai API, backend ini juga menyediakan **Admin Panel BookVerse** yang dapat diakses melalui URL utama untuk mengelola seluruh data sistem melalui antarmuka web.

# Pentunjuk Setup Environment

Pastikan telah melakukan instalasi:

* PHP 8.3
* Composer
* MySQL

1. Salin file `.env.example` dan ubah namanya menjadi `.env`.

2. Sesuaikan konfigurasi pada file `.env` sesuai dengan environment yang digunakan.

Contoh konfigurasi:

```env id="env4"
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
FRONTEND_URL=http://localhost:5173

AI_SERVICE_KEY=<AI_SERVICE_KEY>
AI_SERVICE_URL=<AI_SERVICE_URL>

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookverse_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=<MAIL_HOST>
MAIL_PORT=<MAIL_PORT>
MAIL_USERNAME=<MAIL_USERNAME>
MAIL_PASSWORD=<MAIL_PASSWORD>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<MAIL_FROM_ADDRESS>
MAIL_FROM_NAME="<MAIL_FROM_NAME>"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

JWT_SECRET=
```

3. Pastikan database `bookverse_db` sudah dibuat sesuai konfigurasi `.env`.

---

# Konfigurasi AI Service

Backend ini terhubung dengan layanan AI BookVerse untuk fitur:

* Emotion Detection
* Content-Based Recommendation
* Collaborative Recommendation
* Personality Recommendation

```env
AI_SERVICE_URL=<AI_SERVICE_URL>
AI_SERVICE_KEY=<AI_SERVICE_KEY>
```

Keterangan:

* `AI_SERVICE_URL` adalah endpoint layanan AI yang digunakan backend untuk mengakses seluruh fitur kecerdasan buatan.
* `AI_SERVICE_KEY` adalah kunci autentikasi yang digunakan untuk mengamankan request dari backend ke layanan AI.

---

# Konfigurasi Mail

Backend menggunakan layanan email SMTP untuk mengirim notifikasi sistem dan komunikasi ke pengguna.

```env
MAIL_MAILER=smtp
MAIL_HOST=<MAIL_HOST>
MAIL_PORT=<MAIL_PORT>
MAIL_USERNAME=<MAIL_USERNAME>
MAIL_PASSWORD=<MAIL_PASSWORD>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<MAIL_FROM_ADDRESS>
MAIL_FROM_NAME="<MAIL_FROM_NAME>"
```

Keterangan:

* `MAIL_HOST` adalah server SMTP yang digunakan untuk pengiriman email.
* `MAIL_PORT` adalah port SMTP (umumnya 587 untuk TLS).
* `MAIL_USERNAME` adalah email yang digunakan sebagai pengirim.
* `MAIL_PASSWORD` adalah password atau app password email.
* `MAIL_FROM_ADDRESS` adalah alamat email pengirim yang tampil di email user.
* `MAIL_FROM_NAME` adalah nama pengirim yang tampil di email.

Catatan:

* Gunakan App Password jika menggunakan Gmail.
* Jangan membagikan kredensial email ke publik.

---

# Akses Admin Panel

Jika URL aplikasi dibuka di browser:

```text id="admin4"
http://localhost:8000
```

maka akan langsung diarahkan ke **Admin Panel BookVerse**.

---

# Cara Menjalankan aplikasi

1. Install dependency Laravel:

```bash id="c20"
composer install
```

2. Generate application key:

```bash id="c21"
php artisan key:generate
```

3. Jalankan migrasi database:

```bash id="c22"
php artisan migrate
```

4. Jalankan database seeder:

```bash id="c23"
php artisan db:seed
```

5. Generate JWT secret:

```bash id="c24"
php artisan jwt:secret
```

6. Jalankan aplikasi:

```bash id="c25"
php artisan serve
```

7. Setelah aplikasi berjalan, backend dapat diakses melalui:

```text id="c26"
http://localhost:8000
```
