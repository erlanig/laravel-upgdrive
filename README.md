# 🚀 Google Drive Upload Service

Layanan API sederhana berbasis Laravel untuk mengunggah file ke Google Drive dan mengembalikan URL file yang dapat disimpan ke database aplikasi lain seperti TraKerja, ERP, HRIS, dan sistem internal lainnya.

## ✨ Fitur

* Upload file ke Google Drive
* Menggunakan OAuth 2.0 + Refresh Token
* Tidak perlu login ulang setelah setup
* Mengembalikan File ID dan URL file
* Mendukung integrasi antar aplikasi
* Mendukung API Key Authentication
* Siap digunakan sebagai File Storage Service

---

## 🏗️ Arsitektur

```text
Client / Application
        │
        ▼
Laravel Upload API
        │
        ▼
Google Drive
        │
        ▼
Response JSON
```

---

## 📋 Requirements

* PHP 8+
* Laravel 9+
* Composer
* Google Cloud Project
* Google Drive API Enabled

---

## ⚙️ Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd drive-upload
```

### 2. Install Dependency

```bash
composer install
```

### 3. Copy Environment

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

## 🔑 Environment Configuration

Tambahkan konfigurasi berikut pada file `.env`

```env
APP_NAME="Drive Upload Service"

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REFRESH_TOKEN=
GOOGLE_DRIVE_FOLDER_ID=

UPLOAD_API_KEY=
```

---

## ☁️ Google Cloud Setup

### Enable Google Drive API

1. Buka Google Cloud Console
2. Pilih Project
3. APIs & Services → Library
4. Cari **Google Drive API**
5. Klik **Enable**

### Create OAuth Client

1. APIs & Services → Credentials
2. Create Credentials
3. OAuth Client ID
4. Application Type: Web Application
5. Simpan Client ID dan Client Secret

### Add Test User

1. OAuth Consent Screen
2. Test Users
3. Tambahkan akun Gmail yang digunakan

### Get Refresh Token

1. Login melalui endpoint OAuth
2. Approve permission
3. Salin refresh token
4. Simpan ke `.env`

---

## 📤 Upload Endpoint

### Request

```http
POST /upload
```

### Headers

```http
X-API-KEY: your-secret-key
```

### Body

```form-data
file : <file>
```

---

## ✅ Success Response

```json
{
    "success": true,
    "message": "Upload berhasil",
    "data": {
        "file_id": "1Q2BfF2kPw_rCCLR_G-4R1GNKYqnK0jaE",
        "filename": "Catatann Pembuatan Akun PMO.txt",
        "download_url": "https://drive.google.com/uc?id=gadfywqa26361S126aAASA2156"
        "view_url": "https://drive.google.com/file/d/gsaksa211383AOWQIE/view",
        "uploaded_at": "2026-06-25 02:15:21"
    }
}

```

---

## ❌ Error Response

```json
{
    "success": false,
    "message": "Unauthorized"
}
```

---

## 🧪 Testing With Postman

### URL

```http
POST http://localhost:8000/upload
```

### Headers

```http
X-API-KEY: your-secret-key
```

### Body

```form-data
file : sample.pdf
```

---

## 💾 Recommended Database Structure

```sql
google_file_id
original_filename
mime_type
file_size
```

Contoh:

```text
google_file_id     : 1abcDEFxyz
original_filename : Dokumen.pdf
mime_type         : application/pdf
file_size         : 1048576
```

---

## 🔒 Security

* OAuth 2.0 Authentication
* Refresh Token Based Access
* API Key Protection
* Private Upload Endpoint

---

## 👨‍💻 Developed By

Teknalogi

Building The Future With Teknalogi 🚀
