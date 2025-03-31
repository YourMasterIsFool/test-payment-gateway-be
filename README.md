# Laravel Payment Gateway

🚀 **Laravel test payment gateway** adalah aplikasi berbasis Laravel yang menyediakan fitur transaksi **deposit** dan **withdraw** beserta menggunakan websocket

## 📋 Requirements

- **PHP 8.2**
- **Docker & Docker Compose**
- **Composer** (Dependency Manager untuk PHP)

## ✨ Features
- ✅ **Deposit**: Fitur untuk melakukan deposit saldo.
- ✅ **Withdraw**: Fitur untuk penarikan saldo.
- ✅ **Pusher/WebSocket**: Komunikasi real-time untuk update status transaksi.

## 🛠️ Installation Guide
### 1️⃣ Clone Repository
```bash
git clone https://github.com/your-repo/laravel-payment-gateway.git
cd laravel-payment-gateway
```

### 2️⃣ Jalankan Docker Compose
Jika menggunakan Docker, jalankan perintah berikut:
```bash
docker-compose up -d
```

### 3️⃣ Install Dependencies
Jalankan perintah berikut untuk menginstal dependensi Laravel:
```bash
composer install
```

### 4️⃣ Copy dan Konfigurasi Environment
template env sudah tersedia .env
```bash
cp .env
```
Lalu, edit file `.env` untuk menyesuaikan konfigurasi database dan service lainnya.

### 5️⃣ Generate Application Key
```bash
php artisan key:generate
```

### 6️⃣ Migrasi Database
Jalankan migrasi database untuk membuat tabel yang diperlukan:
```bash
php artisan migrate
```

### 7️⃣ Jalankan Seeder
Tambahkan data awal ke dalam database dengan menjalankan seeder:
```bash
php artisan db:seed
```

### 8️⃣ Jalankan Server
Setelah semua konfigurasi selesai, jalankan Laravel:
```bash
php artisan serve
```
Akses aplikasi melalui **[http://localhost:8000](http://localhost:8000)**

Kemudian jalankan perintah berikut untuk memulai WebSocket menggunakan Laravel Echo Server:
```bash
php artisan queue:work
```

## 🎯 API Endpoints
| Method | Endpoint | Description |
|--------|---------|-------------|
| `POST` | `/api/deposit` | Melakukan deposit saldo |
| `POST` | `/api/withdraw` | Melakukan penarikan saldo |
| `GET`  | `/api/transactions/list` | Melihat daftar transaksi 
| `POST`  | `/api/login` | Login
| `POST`  | `/api/xendit/callback` | Callback Dari Xendit
| `POST`  | `/transaction/{orderId}/payment` | Detail Payment Transaksi
| `POST`  | `/transaction/{orderId}/payment` | Detail Payment Transaksi
| `POST`  | `/transaction/{order_id}/simulate/qr_codet` | Simulasi QR Code
 
 




