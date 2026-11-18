# 🌱 MULA : Mulai Untuk Langkah Awal
> *"Little Steps, Lighter Days."*

**MULA** adalah aplikasi produktivitas digital berbasis *mindful micro-productivity* yang dirancang khusus untuk membantu mahasiswa mengatasi kebiasaan prokrastinasi (menunda pekerjaan akademik). MULA tidak memaksa mahasiswa langsung belajar berjam-jam, melainkan memandu mereka mengenali kondisi emosional saat ini dan mengambil satu tindakan kecil berdurasi **2 menit** sebagai langkah awal.

---

## 📌 Mengapa MULA Berbeda?

Kebanyakan aplikasi *to-do list* justru menimbulkan rasa cemas (*productivity guilt*) karena menampilkan daftar tugas yang menumpuk. MULA hadir dengan pendekatan berbeda:

1. **Zero-Friction (Guest-First Onboarding):** Tidak ada *login wall* atau formulir registrasi yang rumit di awal. Pengguna yang sedang kewalahan bisa langsung memulai tugas dalam hitungan detik.
2. **Empathy-Driven Productivity:** Sapaan dan rekomendasi tindakan disesuaikan dengan kondisi mental pengguna (*Bingung, Kewalahan, Lelah, Takut*).
3. **AI Task Deconstruction:** Menggunakan **Google Gemini AI** untuk memecah tugas kuliah besar menjadi 4–5 langkah mikro, di mana langkah pertama selalu berupa aksi super mudah berdurasi 2 menit.
4. **SOS Deadline:** Otomatis menyusun prioritas realistis dan batas waktu belajar malam ketika tugas menumpuk tanpa memaksa semua harus selesai sekaligus.

---

## 🚀 Fitur Utama

- **Ruang untuk Memulai (Home):** Sapaan personal, check-in kondisi emosional, dan tombol cepat "Mulai 2 Menit".
- **Mulai 2 Menit (Core Feature):** 
  - *Ritual 20 Detik:* Latihan napas sejenak dan mematikan distraksi.
  - *Timer 2 Menit:* Panduan fokus pada langkah mikro pertama.
  - *Apresiasi Proses:* Refleksi hangat atas keberhasilan menaklukkan hambatan awal.
- **Daftar Tugas (Task Hub):** Manajemen tugas kuliah terstruktur dengan tenggat waktu (*deadline*) dan estimasi durasi.
- **Pecah Tugas dengan MULA AI:** Rekomendasi sub-tugas otomatis dan terukur yang langsung dihubungkan ke timer fokus.
- **SOS Deadline:** Analisis beban kerja dan penjadwalan blok waktu belajar manusiawi saat menghadapi tenggat berdekatan.
- **Jejak Langkah:** Pencatatan perkembangan tanpa tekanan skor atau peringkat kompetitif.

---

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 11 (RESTful API Architecture)
- **Bahasa:** PHP 8.3
- **Database:** SQLite (Lightweight & zero-configuration)
- **AI Integration:** Google Gemini API (`gemini-3.8-flash`) via HTTP Client
- **Date & Time Manipulation:** Carbon Library

### Frontend
- **Framework:** Vue.js 3 / Single Page Application
- **Build Tool:** Vite
- **Styling:** Tailwind CSS (Mobile-First Viewport Container)
- **Icons:** Lucide Icons
- **HTTP Client:** Axios

---

## 📁 Struktur Repositori

```text
mula-app/
├── backend/                       # Laravel 11 REST API
│   ├── app/
│   │   ├── Http/Controllers/Api/  # Controller API MULA
│   │   ├── Models/                # Model Task, Subtask, ActivityLog
│   │   └── Services/              # GeminiService (Prompt & AI Handler)
│   ├── database/
│   │   └── migrations/            # Skema database SQLite
│   └── routes/
│       └── api.php                # Endpoint rute API
│
├── frontend/                      # Mobile-First Vue.js Client
│   ├── src/
│   │   ├── components/            # Komponen BottomNav, TimerModal, dll.
│   │   ├── views/                 # Halaman Home, TaskList, SOS, History
│   │   └── services/              # Konfigurasi Axios instance
│   └── tailwind.config.js
│
└── README.md
```

---

## 💻 Panduan Instalasi & Menjalankan Lokal

### Prasyarat
- PHP >= 8.3
- Composer >= 2.x
- Node.js >= 18.x & npm
- Koneksi Internet (untuk akses Gemini AI)

### 1. Setup Backend
```bash
# Masuk ke folder backend
cd backend

# Install dependensi PHP
composer install

# Siapkan file environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Konfigurasi file .env:
# Pastikan database menggunakan SQLite:
# DB_CONNECTION=sqlite
# Masukkan API Key Gemini kamu:
# GEMINI_API_KEY=AIzaSy...

# Jalankan migrasi database
php artisan migrate

# Nyalakan server backend
php artisan serve
```
> Server backend akan berjalan di: `http://127.0.0.1:8000`

---

### 2. Setup Frontend
Buka terminal baru:
```bash
# Masuk ke folder frontend
cd frontend

# Install dependensi JavaScript
npm install

# Jalankan server frontend
npm run dev
```
> Server frontend akan berjalan di: `http://localhost:5173`

---

## 📡 Daftar Endpoint API Utama

| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `GET` | `/api/tasks` | Mengambil seluruh daftar tugas & sub-tugas |
| `POST` | `/api/tasks` | Menambahkan tugas kuliah baru |
| `PUT` | `/api/tasks/{id}` | Memperbarui data / status tugas |
| `DELETE` | `/api/tasks/{id}` | Menghapus tugas |
| `POST` | `/api/tasks/{id}/breakdown` | Memecah tugas menjadi langkah kecil menggunakan Gemini AI |
| `GET` | `/api/sos-deadline` | Mendapatkan analisis urgensi dan rekomendasi jadwal SOS |
| `POST` | `/api/logs` | Menyimpan log aktivitas sesi timer yang selesai |
| `GET` | `/api/stats` | Mengambil data statistik untuk halaman Jejak Langkah |

---

## 🗺️ Roadmap Pengembangan (Fase Selanjutnya)

- [ ] **Sinkronisasi Akun Multi-Device:** Fitur login/registrasi opsional bagi pengguna yang ingin menyimpan data lintas gawai.
- [ ] **Distribusi Mobile Native (APK / PWA):** Konversi bundel frontend menggunakan Capacitor untuk distribusi Play Store.
- [ ] **Pengingat Notifikasi Push:** Notifikasi berbasis *micro-habit* untuk mengingatkan ritual sebelum tidur dan jeda istirahat.
- [ ] **Koneksi Konseling Kampus:** Integrasi direktori resmi layanan bimbingan konseling mahasiswa.

---

## 👥 Tim Pengembang

- **Product & Business:** [Cantika Putri Permatasari]
- **UI/UX Designer:** [Natasya Alfanisa]
- **Software Development (Backend & AI):** [Diny Eka Zharafah]
- **Software Development (Frontend & UI):** [Shafa Takia Zahra]

---
*Dikembangkan dengan empati untuk mendukung kesehatan mental dan produktivitas mahasiswa.*
