# Deskripsi Menu Aplikasi Tani Sehat Berdasarkan Role

Aplikasi Tani Sehat memiliki 3 hak akses (role) utama: **Admin**, **Kader**, dan **Warga**. Masing-masing role memiliki akses ke menu-menu yang disesuaikan dengan kebutuhan dan wewenangnya. Berikut adalah deskripsi untuk setiap menu pada masing-masing role:

## 1. Role: Admin
Admin memiliki akses penuh terhadap manajemen pengguna, konten edukasi, dan monitoring data secara keseluruhan (global).

* **Dashboard** (`/dashboard`): Menampilkan ringkasan statistik (summary) keseluruhan sistem, grafik tekanan darah global, dan tren kuesioner GAD-7.
* **Manajemen User** (`/users`): Menu untuk mengelola (menambah, mengedit, menghapus) seluruh akun pengguna yang ada di sistem, baik akun Warga, Kader, maupun Admin lainnya.
* **Kader Warga** (`/admin/warga-kader`): Menu untuk mengatur penugasan atau memetakan Warga binaan ke Kader tertentu, sehingga Kader dapat memantau kesehatan warga yang menjadi tanggung jawabnya.
* **Riwayat Data Kesehatan** (`/admin/kesehatan`): Menampilkan riwayat cek Tekanan Darah (TD) dan skrining GAD-7 seluruh warga dalam sistem, dilengkapi dengan fitur filter tanggal dan export data ke Excel/PDF.
* **Atur Jadwal** (`/admin/jadwal`): Menu untuk mengatur dan mengelola jadwal pengecekan rutin (seperti interval waktu untuk cek tekanan darah dan pengisian kuesioner GAD-7).
* **Kuesioner GAD7** (`/admin/kuesioner`): Menu untuk mengelola bank soal kuesioner GAD-7 yang akan dijawab oleh warga.
* **Data Reproduksi** (`/admin/reproduksi`): Menu untuk memantau data siklus dan keluhan kesehatan reproduksi seluruh warga, beserta fitur untuk mengekspor laporan.
* **Manajemen Rekomendasi** (`/admin/rekomendasi`): Menu untuk mengelola konten edukasi yang diberikan sebagai rekomendasi kepada warga. Termasuk di dalamnya manajemen Video panduan, Materi bacaan, Gambar infografis, dan anjuran Olahraga.
* **Chat** (`/chat`): Fitur perpesanan internal untuk berkomunikasi dengan Kader maupun Warga secara langsung.

## 2. Role: Kader
Kader bertugas memantau dan membina sekelompok warga yang telah ditugaskan kepadanya. Kader hanya dapat melihat data dari warga binaannya saja.

* **Dashboard** (`/kader`): Menampilkan ringkasan kondisi kesehatan khusus untuk warga yang berada di bawah binaan Kader tersebut.
* **Warga Saya** (`/kader/warga`): Menampilkan daftar warga binaan yang ditugaskan kepada Kader, lengkap dengan informasi detail profil masing-masing warga.
* **Riwayat Data Kesehatan** (`/kader/kesehatan`): Menampilkan riwayat hasil pengukuran Tekanan Darah dan skor GAD-7 secara spesifik hanya untuk warga binaan milik Kader tersebut. Dilengkapi fitur pencarian dan export data.
* **Reproduksi** (`/kader/reproduksi`): Menu untuk memantau catatan siklus menstruasi dan keterangan kesehatan reproduksi dari warga binaannya.
* **Rekomendasi** (`/kader/rekomendasi`): Menampilkan daftar referensi konten edukasi kesehatan (Materi, Video, dll) yang tersedia di sistem untuk kemudian dapat diinformasikan kepada warga.
* **Chat** (`/chat`): Fitur untuk berkomunikasi dengan Warga binaannya (untuk memberikan konsultasi) atau berkomunikasi dengan Admin (jika ada pelaporan/kendala).

## 3. Role: Warga
Warga adalah pengguna utama yang ditargetkan untuk menggunakan aplikasi secara rutin untuk memonitor dan melaporkan kondisi kesehatannya.

* **Home** (`/warga`): Halaman beranda utama warga yang menampilkan status kesehatan terakhir, visualisasi tren (grafik) Tekanan Darah & GAD-7 personal, shortcut menu laporan, dan informasi profil Kader pendampingnya.
* **Input TD** (`/warga/input-td`): Form untuk mencatat hasil pengukuran tekanan darah (Sistolik & Diastolik) harian atau sesuai jadwal yang ditentukan.
* **Kuesioner GAD7** (`/warga/input-gad`): Form asesmen mandiri untuk mendeteksi tingkat kecemasan (Generalized Anxiety Disorder 7) yang diisi secara berkala.
* **Reproduksi** (`/warga/reproduksi`): Form bagi warga (perempuan) untuk mencatat tanggal menstruasi dan berbagai keluhan atau kondisi yang berkaitan dengan kesehatan reproduksinya.
* **Rekomendasi** (`/warga/rekomendasi`): Kumpulan konten edukasi kesehatan (Video, Materi/Artikel, Gambar, Rekomendasi Olahraga) yang disarankan berdasarkan kondisi kesehatan terkini pengguna.
* **Chat** (`/chat`): Fitur konsultasi jarak jauh dengan Kader pendampingnya untuk mempermudah tanya-jawab seputar kesehatan secara personal.
