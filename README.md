# Dokumentasi API Tani Sehat - Role Warga (Untuk Flutter)

Dokumentasi ini berisi daftar dan detail endpoint API yang digunakan untuk membangun aplikasi mobile (Android/iOS) menggunakan Flutter khusus untuk **Role Warga**. 

**Penting:**
- Semua request (kecuali login) wajib menggunakan header:
  ```json
  {
    "Accept": "application/json",
    "Authorization": "Bearer <TOKEN_ANDA>"
  }
  ```
- Jika Anda menguji menggunakan emulator Android ke server lokal Laravel (`php artisan serve`), gunakan Base URL: `http://10.0.2.2:8000/api`.

---

## 1. Autentikasi (Auth)

### 1.1 Login
- **Endpoint:** `POST /login`
- **Deskripsi:** Endpoint untuk mendapatkan akses token.
- **Body (JSON):**
  ```json
  {
    "nik": "1234567890123456",
    "password": "password123"
  }
  ```
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Login berhasil",
    "data": {
      "token": "1|abcdefghijklmnopqrstuvwxyz...",
      "user": {
        "id": 3,
        "nik": "1234567890123456",
        "nama_lengkap": "Budi Santoso",
        "role": "warga"
      }
    }
  }
  ```

### 1.2 Get Data User Saat Ini
- **Endpoint:** `GET /me`
- **Deskripsi:** Mendapatkan detail informasi user yang sedang login beserta status kesehatannya.
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": {
      "id": 3,
      "nik": "1234567890123456",
      "nama_lengkap": "Budi Santoso",
      "role": "warga",
      "status_kesehatan": {
        "tekanan_darah": "120/80",
        "kategori_td": "normal",
        "skor_gad": 5,
        "kategori_gad": "ringan"
      }
    }
  }
  ```

### 1.3 Logout
- **Endpoint:** `POST /logout`
- **Deskripsi:** Menghapus token sesi saat ini.
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Logout berhasil"
  }
  ```

---

## 2. Fitur Chat (Konsultasi)

Fitur ini digunakan oleh warga untuk berkomunikasi dengan Kader atau Admin.

### 2.1 Mendapatkan Daftar Kontak (Kader & Admin)
Warga dapat memulai chat dengan Kader atau Admin.
- **Endpoint Admin:** `GET /admins`
- **Endpoint Kader:** `GET /kaders`
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "nama_lengkap": "Admin Utama",
        "role": "admin"
      },
      {
        "id": 2,
        "nama_lengkap": "Siti Kader",
        "role": "kader"
      }
    ]
  }
  ```

### 2.2 Memulai Chat Baru (Start Conversation)
- **Endpoint:** `POST /messages/start`
- **Deskripsi:** Membuat ruang chat baru atau mengembalikan ID ruang chat jika sudah pernah berinteraksi.
- **Body (JSON):**
  ```json
  {
    "receiver_id": 2
  }
  ```
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": {
      "id": 15,
      "user1_id": 3,
      "user2_id": 2,
      "updated_at": "2026-05-03T10:00:00.000000Z"
    }
  }
  ```

### 2.3 Daftar Percakapan (Inbox)
- **Endpoint:** `GET /messages`
- **Deskripsi:** Mengambil semua daftar percakapan (list chat) milik user.
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 15,
        "unread_count": 2,
        "partner": {
          "id": 2,
          "nama_lengkap": "Siti Kader",
          "role": "kader"
        },
        "latest_detail": {
          "message": "Halo, jangan lupa cek tekanan darah ya pak.",
          "created_at": "2026-05-03T10:05:00.000000Z"
        }
      }
    ]
  }
  ```

### 2.4 Detail Pesan dalam Percakapan (Isi Chat)
- **Endpoint:** `GET /messages/{conversation_id}`
- **Deskripsi:** Mengambil riwayat pesan di dalam satu ruang chat.
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": {
      "id": 15,
      "details": [
        {
          "id": 101,
          "sender_id": 2,
          "message": "Halo, jangan lupa cek tekanan darah ya pak.",
          "is_read": true,
          "created_at": "2026-05-03T10:05:00.000000Z"
        },
        {
          "id": 102,
          "sender_id": 3,
          "message": "Baik bu kader, terima kasih pengingatnya.",
          "is_read": false,
          "created_at": "2026-05-03T10:07:00.000000Z"
        }
      ]
    }
  }
  ```

### 2.5 Mengirim Pesan
- **Endpoint:** `POST /messages/{conversation_id}/send`
- **Body (JSON):**
  ```json
  {
    "message": "Halo bu kader, saya sudah isi datanya."
  }
  ```
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Pesan terkirim",
    "data": {
      "id": 103,
      "message": "Halo bu kader, saya sudah isi datanya."
    }
  }
  ```

### 2.6 Menghapus Pesan Milik Sendiri
- **Endpoint:** `DELETE /messages/detail/{message_id}`
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Pesan berhasil dihapus"
  }
  ```

---

## 3. Data Kesehatan (Tekanan Darah & GAD-7)

### 3.1 Cek Jadwal (Apakah sudah waktunya mengisi?)
- **Endpoint TD:** `GET /status-kesehatan/cek-jadwal?jenis=td`
- **Endpoint GAD-7:** `GET /status-kesehatan/cek-jadwal?jenis=gad7`
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "harus_isi": true,
    "message": "Saatnya mengisi data tekanan darah Anda."
  }
  ```

### 3.2 Mengambil Daftar Kuesioner GAD-7
- **Endpoint:** `GET /gad/kuesioner`
- **Deskripsi:** Mendapatkan daftar pertanyaan GAD-7 untuk ditampilkan di Flutter.
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "pertanyaan": "Merasa gugup, cemas atau tegang"
      },
      {
        "id": 2,
        "pertanyaan": "Tidak mampu menghentikan atau mengendalikan kekhawatiran"
      }
    ]
  }
  ```

### 3.3 Menyimpan Data Tekanan Darah (TD)
- **Endpoint:** `POST /tekanan-darah` ATAU `POST /status-kesehatan/td`
- **Body (JSON):**
  ```json
  {
    "systolic": 120,
    "diastolic": 80
  }
  ```
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Data tekanan darah berhasil disimpan"
  }
  ```

### 3.4 Menyimpan Data GAD-7
- **Endpoint:** `POST /gad` ATAU `POST /status-kesehatan/gad`
- **Body (JSON):**
  ```json
  {
    "skor": 15,
    "jawaban": {
      "1": 3,
      "2": 2,
      "3": 3,
      "4": 1,
      "5": 2,
      "6": 1,
      "7": 3
    }
  }
  ```
  *(Catatan: `jawaban` merupakan key-value dari `id_pertanyaan` dan `skor_jawaban` 0-3)*
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Data GAD-7 berhasil disimpan"
  }
  ```

### 3.5 Riwayat Data Kesehatan
- **Endpoint Riwayat TD:** `GET /tekanan-darah`
- **Endpoint Riwayat GAD-7:** `GET /gad`
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "systolic": 120,
        "diastolic": 80,
        "created_at": "2026-05-01T10:00:00.000000Z"
      }
    ]
  }
  ```

---

## 4. Reproduksi

### 4.1 Mengambil Data Reproduksi
- **Endpoint:** `GET /reproduksi`
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "jumlah_anak": 2,
        "penggunaan_kb": "Pil",
        "masalah_reproduksi": "Tidak ada",
        "created_at": "2026-05-01T10:00:00.000000Z"
      }
    ]
  }
  ```

### 4.2 Menambah Data Reproduksi
- **Endpoint:** `POST /reproduksi`
- **Body (JSON):**
  ```json
  {
    "jumlah_anak": 2,
    "penggunaan_kb": "Pil",
    "masalah_reproduksi": "Tidak ada"
  }
  ```
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Data berhasil disimpan"
  }
  ```

### 4.3 Menghapus Data Reproduksi
- **Endpoint:** `DELETE /reproduksi/{id}`
- **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Data reproduksi berhasil dihapus"
  }
  ```

---

## 5. Rekomendasi & Edukasi Kesehatan

Warga dapat melihat materi edukasi berdasarkan hasil kesehatan mereka. Semua API di bawah menggunakan method **GET**.

- **Semua Rekomendasi:** `GET /rekomendasi`
- **Materi Teks:** `GET /materi`
- **Video Edukasi:** `GET /video`
- **Gambar/Infografis:** `GET /gambar`
- **Rekomendasi Olahraga:** `GET /olahraga`

**Contoh Response Sukses `GET /rekomendasi`:**
```json
{
  "success": true,
  "data": {
    "video": [
      {
        "id": 1,
        "judul": "Cara Mengatasi Hipertensi",
        "link_embed": "https://www.youtube.com/embed/xxxxx",
        "kategori_td": "hipertensi",
        "kategori_gad": "semua"
      }
    ],
    "olahraga": [
      {
        "id": 2,
        "nama_olahraga": "Jalan Santai",
        "kategori_td": "hipertensi",
        "kategori_gad": "ringan"
      }
    ],
    "materi": [],
    "gambar": []
  }
}
```

---

## Contoh Implementasi Http Request di Flutter (Dart)

Berikut adalah contoh cara memanggil API Login dan mengambil daftar Chat menggunakan package `http` di Flutter.

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  String? token;

  // 1. Contoh Login
  Future<void> login(String nik, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
      body: jsonEncode({
        'nik': nik,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      token = data['data']['token'];
      print('Login Berhasil! Token: $token');
    } else {
      print('Login Gagal: ${response.body}');
    }
  }

  // 2. Contoh Mengambil List Chat Warga
  Future<void> fetchInbox() async {
    final response = await http.get(
      Uri.parse('$baseUrl/messages'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token', // Sertakan token di sini
      },
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      List chats = data['data'];
      for (var chat in chats) {
        print("Chat dari: ${chat['partner']['nama_lengkap']}");
        print("Pesan terakhir: ${chat['latest_detail']['message']}");
      }
    } else {
      print('Gagal mengambil pesan');
    }
  }
}
```
