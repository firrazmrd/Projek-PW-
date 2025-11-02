# Projek-PW-

# 🧩 Struktur Database: `artikel_projek`

Database ini digunakan untuk mengelola sistem artikel sederhana yang memiliki fitur **user**, **artikel**, **like**, dan **komentar**.  
Setiap tabel saling terhubung lewat **relasi (foreign key)** untuk menjaga konsistensi data.

---

## 🧑‍💻 1. Tabel `users`
Menyimpan data pengguna (baik admin maupun user biasa).

| Kolom | Tipe Data | Keterangan |
|-------|------------|------------|
| `id` | `INT` | Primary key (auto increment) |
| `name` | `VARCHAR(150)` | Nama pengguna |
| `email` | `VARCHAR(150)` | Email unik (tidak boleh duplikat) |
| `password_hash` | `VARCHAR(255)` | Password yang sudah di-hash |
| `role` | `ENUM('user','admin')` | Menentukan peran pengguna (default: `user`) |
| `created_at` | `DATETIME` | Waktu pembuatan akun |

📎 **Catatan:**  
Admin bisa menulis artikel, sementara user biasa hanya bisa membaca, memberi like, atau berkomentar.

---

## 📰 2. Tabel `articles`
Menyimpan artikel yang dibuat oleh pengguna (biasanya admin atau penulis).

| Kolom | Tipe Data | Keterangan |
|--------|------------|------------|
| `id` | `INT` | Primary key |
| `title` | `VARCHAR(255)` | Judul artikel |
| `slug` | `VARCHAR(255)` | URL-friendly version dari judul (unik) |
| `content` | `MEDIUMTEXT` | Isi artikel |
| `image` | `VARCHAR(255)` | Lokasi gambar artikel (opsional) |
| `author_id` | `INT` | Mengacu ke `users.id` |
| `created_at` | `DATETIME` | Waktu pembuatan artikel |
| `updated_at` | `DATETIME` | Otomatis diperbarui setiap update |

🔗 **Relasi:**  
- `author_id` → `users(id)`  
  Jika user dihapus, nilai `author_id` akan menjadi `NULL`.

---

## ❤️ 3. Tabel `likes`
Menyimpan data pengguna yang memberi “like” pada artikel.

| Kolom | Tipe Data | Keterangan |
|--------|------------|------------|
| `id` | `INT` | Primary key |
| `article_id` | `INT` | Artikel yang disukai |
| `user_id` | `INT` | User yang memberi like |
| `created_at` | `DATETIME` | Waktu like dibuat |

🔗 **Relasi dan Index:**
- `article_id` → `articles(id)` (**ON DELETE CASCADE**)  
  Jika artikel dihapus, semua like terkait juga ikut terhapus.
- `user_id` → `users(id)` (**ON DELETE CASCADE**)  
  Jika user dihapus, like miliknya juga dihapus.
- Unik kombinasi `(article_id, user_id)` untuk mencegah user yang sama memberi like dua kali.

---

## 💬 4. Tabel `comments`
Menyimpan komentar pengguna di setiap artikel.

| Kolom | Tipe Data | Keterangan |
|--------|------------|------------|
| `id` | `INT` | Primary key |
| `article_id` | `INT` | Artikel yang dikomentari |
| `user_id` | `INT` | User yang berkomentar (bisa `NULL` jika anonim) |
| `content` | `TEXT` | Isi komentar |
| `created_at` | `DATETIME` | Waktu komentar dibuat |
| `is_approved` | `TINYINT(1)` | Status komentar (1 = disetujui, 0 = belum) |

🔗 **Relasi:**
- `article_id` → `articles(id)` (**ON DELETE CASCADE**)  
  Komentar ikut terhapus jika artikel dihapus.  
- `user_id` → `users(id)` (**ON DELETE SET NULL**)  
  Jika user dihapus, komentar tetap ada, tapi `user_id` menjadi `NULL`.

---

## ⚙️ 5. Engine & Charset
Semua tabel menggunakan:
- **Engine:** `InnoDB` → mendukung *foreign key* dan *transactions*  
- **Charset:** `utf8mb4_unicode_ci` → mendukung karakter multi-bahasa dan emoji

---

## 🧠 Ringkasan Relasi
#users (1) ────< articles (N)
#users (1) ────< likes (N) >──── articles (1)
#users (1) ────< comments (N) >──── articles (1)


