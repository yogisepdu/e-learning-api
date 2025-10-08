# E-Learning Kampus API

Backend API untuk sistem E-Learning Kampus menggunakan **Laravel 12** dan **Sanctum**.  
Mendukung mahasiswa dan dosen dalam mengelola mata kuliah, tugas, forum, dan laporan.

---

## Fitur Utama

1. **Autentikasi Pengguna**

    - Registrasi Mahasiswa & Dosen (`POST /api/register`)
    - Login & dapatkan token (`POST /api/login`)
    - Logout & revoke token (`POST /api/logout`)

2. **Manajemen Mata Kuliah & Kelas Online**

    - List mata kuliah (`GET /api/courses`)
    - Tambah mata kuliah (Dosen) (`POST /api/courses`)
    - Edit mata kuliah (Dosen) (`PUT /api/courses/{id}`)
    - Hapus mata kuliah (Dosen) (`DELETE /api/courses/{id}`)
    - Mahasiswa daftar mata kuliah (`POST /api/courses/{id}/enroll`)

3. **Upload & Unduh Materi Perkuliahan**

    - Upload materi (Dosen) (`POST /api/materials`)
    - Download materi (Mahasiswa) (`GET /api/materials/{id}/download`)

4. **Tugas & Penilaian**

    - Buat tugas (Dosen) (`POST /api/assignments`)
    - Submit jawaban (Mahasiswa) (`POST /api/submissions`)
    - Nilai tugas (Dosen) (`POST /api/submissions/{id}/grade`)
    - Email notifikasi untuk tugas baru

5. **Forum Diskusi**

    - Buat diskusi (`POST /api/discussions`)
    - Balas diskusi (`POST /api/discussions/{id}/replies`)
    - Real-time chat (WebSockets, optional)

6. **Laporan & Statistik**

    - Statistik mahasiswa per mata kuliah (`GET /api/reports/courses`)
    - Statistik tugas sudah/belum dinilai (`GET /api/reports/assignments`)
    - Statistik tugas & nilai mahasiswa tertentu (`GET /api/reports/students/{id}`)

7. **Tambahan**
    - Soft delete untuk data (`softDeletes()`)
    - Queue email untuk performa
    - Laravel Storage untuk upload file
    - Real-time forum dengan WebSockets (catatan: Laravel WebSockets tidak bisa diinstal pada Laravel 12 + PHP 8.2 karena paket `beyondcode/laravel-websockets` belum kompatibel dengan versi ini; sementara ini real-time menggunakan queue/log biasa)

---
