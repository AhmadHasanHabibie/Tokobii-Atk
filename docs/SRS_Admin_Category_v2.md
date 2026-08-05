# SOFTWARE REQUIREMENT SPECIFICATION
## Modul: Admin Category
### Website Toko Alat Tulis Kantor (ATK)

---

| Informasi Dokumen | Detail |
|---|---|
| **Kode Dokumen** | SRS-ADM-CAT-V2 |
| **Versi** | 1.0.0 |
| **Status** | Draft – Menunggu Persetujuan |
| **Milestone** | 2 |
| **Step** | 4 |
| **Tanggal** | 04 Agustus 2026 |
| **Framework** | Laravel 10 |
| **Domain** | Master Data |

---

## Daftar Isi

1. [Executive Summary](#1-executive-summary)
2. [Business Objectives](#2-business-objectives)
3. [User Objectives](#3-user-objectives)
4. [Module Scope](#4-module-scope)
5. [Functional Requirements](#5-functional-requirements)
6. [Business Rules](#6-business-rules)
7. [Validation Rules](#7-validation-rules)
8. [Security Requirements](#8-security-requirements)
9. [Performance Requirements](#9-performance-requirements)
10. [Error Handling](#10-error-handling)
11. [Acceptance Criteria](#11-acceptance-criteria)
12. [Definition of Done](#12-definition-of-done)
13. [Future Enhancement](#13-future-enhancement)
14. [Conclusion](#14-conclusion)

---

## 1. Executive Summary

Modul **Admin Category** merupakan komponen master data yang bertanggung jawab atas pengelolaan klasifikasi produk pada Website Toko Alat Tulis Kantor (ATK). Modul ini memungkinkan Admin untuk membuat, memperbarui, menonaktifkan, dan menghapus kategori produk secara terstruktur dan aman.

Kategori berfungsi sebagai tulang punggung organisasi produk dalam sistem. Tanpa kategori yang terkelola dengan baik, navigasi pelanggan dan pengelolaan produk oleh Admin akan menjadi tidak efisien. Oleh karena itu, modul ini merupakan salah satu modul paling kritis dalam domain Master Data dan menjadi prasyarat implementasi Modul Produk.

Dokumen ini merupakan spesifikasi resmi yang mengikat tim pengembang, tim QA, dan seluruh pemangku kepentingan teknis selama fase implementasi berlangsung. Seluruh keputusan implementasi harus merujuk kepada dokumen ini sebagai sumber kebenaran tunggal (*single source of truth*).

---

## 2. Business Objectives

### 2.1 Tujuan Bisnis

| ID | Tujuan | Ukuran Keberhasilan |
|---|---|---|
| BO-001 | Menyediakan sistem klasifikasi produk yang terstruktur | Seluruh produk memiliki kategori yang valid |
| BO-002 | Meningkatkan efisiensi pengelolaan produk oleh Admin | Admin dapat menemukan dan memfilter produk berdasarkan kategori dalam < 5 detik |
| BO-003 | Memudahkan navigasi pelanggan di halaman publik | Pelanggan dapat menelusuri produk berdasarkan kategori tanpa kebingungan |
| BO-004 | Menjamin integritas dan konsistensi data master | Tidak ada kategori duplikat atau data rusak di database |
| BO-005 | Menyediakan jejak audit atas seluruh perubahan data | Setiap perubahan kategori dapat dilacak hingga ke Admin dan timestamp yang bertanggung jawab |
| BO-006 | Mendukung skalabilitas operasional toko | Sistem tetap responsif saat jumlah kategori dan produk bertumbuh |

### 2.2 Masalah Bisnis yang Diselesaikan

| Masalah | Dampak Sebelum Sistem | Solusi Melalui Modul Ini |
|---|---|---|
| Produk tidak terklasifikasi | Admin sulit mengelola produk dalam jumlah besar | Sistem kategori terstruktur dengan validasi ketat |
| Tidak ada standar penamaan | Laporan dan filter tidak akurat | Aturan validasi nama dan slug yang konsisten |
| Tidak ada jejak audit | Perubahan data tidak dapat dilacak | Activity log otomatis pada setiap operasi |
| Risiko penghapusan data kritis | Data produk yang terhubung bisa rusak | Soft delete wajib; force delete dengan validasi berlapis |

---

## 3. User Objectives

Aktor utama dalam modul ini adalah **Admin**. Tidak ada aktor lain yang memiliki akses ke fitur manajemen kategori dalam panel administrasi.

| ID | Tujuan Admin |
|---|---|
| UO-001 | Membuat kategori baru dengan data yang valid |
| UO-002 | Melihat seluruh daftar kategori dalam tampilan yang terorganisir |
| UO-003 | Melihat detail lengkap satu kategori, termasuk jumlah produk terhubung |
| UO-004 | Mengedit data kategori yang sudah ada |
| UO-005 | Mengaktifkan atau menonaktifkan kategori tanpa menghapus data |
| UO-006 | Menghapus kategori secara aman melalui mekanisme soft delete |
| UO-007 | Memulihkan kategori yang telah dihapus dari Trash |
| UO-008 | Menghapus kategori secara permanen dari Trash dengan konfirmasi berlapis |
| UO-009 | Mencari kategori berdasarkan nama atau slug dengan cepat |
| UO-010 | Memfilter daftar kategori berdasarkan status |
| UO-011 | Mengurutkan daftar kategori berdasarkan berbagai kolom |
| UO-012 | Melihat riwayat aktivitas yang dilakukan pada setiap kategori |

---

## 4. Module Scope

### 4.1 Fitur yang Tercakup (In Scope)

| No. | Fitur |
|---|---|
| 1 | Pembuatan kategori baru (Create) |
| 2 | Pengeditan kategori (Update) |
| 3 | Tampilan daftar kategori dengan paginasi (Read – List) |
| 4 | Tampilan detail kategori (Read – Detail) |
| 5 | Soft delete kategori |
| 6 | Restore kategori dari Trash |
| 7 | Force delete (hapus permanen) dari Trash |
| 8 | Aktivasi dan deaktivasi kategori |
| 9 | Pencarian berdasarkan nama dan slug |
| 10 | Filter berdasarkan status |
| 11 | Pengurutan (sorting) kolom |
| 12 | Auto-generate slug dari nama |
| 13 | Validasi keunikan nama dan slug |
| 14 | Pengunggahan dan pengelolaan thumbnail |
| 15 | Penghitung produk aktif per kategori |
| 16 | Halaman Trash (daftar kategori terhapus) |
| 17 | Activity log otomatis setiap operasi |

### 4.2 Fitur yang Tidak Tercakup (Out of Scope)

| No. | Fitur | Keterangan |
|---|---|---|
| 1 | Nested / sub-kategori | Direncanakan untuk versi berikutnya |
| 2 | Import / Export Excel | Direncanakan untuk versi berikutnya |
| 3 | Category Analytics | Direncanakan untuk versi berikutnya |
| 4 | SEO Metadata per kategori | Direncanakan untuk versi berikutnya |
| 5 | Halaman publik kategori (Customer-facing) | Diatur oleh modul terpisah |
| 6 | API endpoint kategori | Tidak termasuk dalam arsitektur V1 |
| 7 | Bulk delete permanen | Tidak diizinkan demi keamanan data |

### 4.3 Dependensi Modul

| Modul | Jenis Dependensi | Keterangan |
|---|---|---|
| Authentication Module | Wajib | Seluruh route modul ini memerlukan sesi Admin yang valid |
| Product Module | Downstream | Produk mengacu pada kategori sebagai foreign key |
| Activity Log Module | Wajib | Setiap operasi harus dicatat secara otomatis |
| File Storage | Kondisional | Diperlukan hanya jika fitur thumbnail digunakan |

---

## 5. Functional Requirements

> Seluruh requirement berikut hanya berlaku untuk **Aktor: Admin** dan diakses melalui panel administrasi yang terautentikasi.

### 5.1 Daftar Kategori

| FR ID | Nama | Deskripsi | Aktor | Prioritas |
|---|---|---|---|---|
| FR-ADM-CAT-001 | Tampilkan Daftar Kategori | Sistem menampilkan semua kategori yang belum dihapus (non-trashed) dalam format tabel dengan kolom: Nama, Slug, Status, Jumlah Produk, Tanggal Dibuat. | Admin | Must Have |
| FR-ADM-CAT-002 | Paginasi Daftar | Sistem membagi daftar kategori ke dalam halaman dengan jumlah item per halaman yang dapat dikonfigurasi (default: 10). Navigasi antar halaman tersedia. | Admin | Must Have |
| FR-ADM-CAT-003 | Sorting Kolom | Admin dapat mengurutkan daftar berdasarkan kolom Nama, Status, Jumlah Produk, dan Tanggal Dibuat secara ascending atau descending. Kolom aktif ditandai indikator visual. | Admin | Must Have |
| FR-ADM-CAT-004 | Pencarian Berdasarkan Nama | Admin dapat mencari kategori menggunakan kata kunci yang dicocokkan dengan field nama secara case-insensitive. | Admin | Must Have |
| FR-ADM-CAT-005 | Pencarian Berdasarkan Slug | Admin dapat mencari kategori menggunakan kata kunci yang dicocokkan dengan field slug. | Admin | Should Have |
| FR-ADM-CAT-006 | Filter Berdasarkan Status | Admin dapat memfilter daftar kategori berdasarkan status: Semua, Aktif, Tidak Aktif. Filter dapat dikombinasikan dengan pencarian. | Admin | Must Have |
| FR-ADM-CAT-007 | Tampilkan Jumlah Produk Aktif | Kolom "Jumlah Produk" pada daftar menampilkan count produk aktif yang terhubung ke setiap kategori secara real-time. Produk yang di-soft delete tidak dihitung. | Admin | Must Have |
| FR-ADM-CAT-008 | Indikator Status Visual | Setiap baris daftar menampilkan badge visual berbeda untuk status Aktif (hijau) dan Tidak Aktif (abu-abu). | Admin | Must Have |
| FR-ADM-CAT-009 | Tombol Aksi Per Baris | Setiap baris pada daftar memiliki tombol aksi: Detail, Edit, Ubah Status, Hapus. Tombol tersusun konsisten pada seluruh baris. | Admin | Must Have |
| FR-ADM-CAT-010 | Empty State | Jika tidak ada kategori yang ditemukan (daftar kosong atau pencarian tidak menghasilkan data), sistem menampilkan pesan informatif yang berbeda antara "belum ada data" dan "tidak ada hasil pencarian". | Admin | Must Have |
| FR-ADM-CAT-011 | Reset Filter dan Pencarian | Sistem menyediakan tombol untuk mereset semua filter dan pencarian aktif ke kondisi default tanpa reload halaman penuh. Parameter URL juga direset. | Admin | Must Have |
| FR-ADM-CAT-012 | Pertahankan Filter Saat Paginasi | Parameter pencarian dan filter aktif dipertahankan dalam query parameter URL saat Admin berpindah halaman melalui paginasi. | Admin | Must Have |

### 5.2 Detail Kategori

| FR ID | Nama | Deskripsi | Aktor | Prioritas |
|---|---|---|---|---|
| FR-ADM-CAT-013 | Halaman Detail Kategori | Sistem menampilkan halaman detail dengan informasi lengkap: Nama, Slug, Deskripsi, Thumbnail, Status, Jumlah Produk, Tanggal Dibuat, Tanggal Diperbarui. | Admin | Must Have |
| FR-ADM-CAT-014 | Daftar Produk dalam Kategori | Halaman detail menampilkan daftar produk aktif yang terhubung, terpaginasi (maksimum 5 item per halaman). | Admin | Should Have |
| FR-ADM-CAT-015 | Riwayat Aktivitas pada Detail | Halaman detail menampilkan 10 entri aktivitas terakhir yang berkaitan dengan kategori tersebut: aksi, pelaku, dan timestamp. | Admin | Should Have |
| FR-ADM-CAT-016 | Pratinjau Thumbnail | Sistem menampilkan thumbnail kategori pada halaman detail. Jika thumbnail tidak ada, gambar placeholder default ditampilkan dan tidak menimbulkan broken image. | Admin | Must Have |

### 5.3 Pembuatan Kategori

| FR ID | Nama | Deskripsi | Aktor | Prioritas |
|---|---|---|---|---|
| FR-ADM-CAT-017 | Formulir Buat Kategori | Sistem menyediakan formulir pembuatan dengan field: Nama Kategori (wajib), Slug (auto-generated, dapat diedit), Deskripsi (opsional), Thumbnail (opsional), Status (default: Aktif). | Admin | Must Have |
| FR-ADM-CAT-018 | Auto-generate Slug | Saat Admin mengetik pada field Nama, sistem secara otomatis mengisi field Slug dengan format: huruf kecil, spasi diganti tanda hubung, karakter non-alfanumerik dihapus. | Admin | Must Have |
| FR-ADM-CAT-019 | Validasi Slug Real-time | Sistem memvalidasi keunikan slug secara real-time melalui server request saat Admin mengisi atau mengubah field Slug. Umpan balik diberikan tanpa reload halaman. | Admin | Must Have |
| FR-ADM-CAT-020 | Pratinjau Thumbnail Sebelum Simpan | Setelah Admin memilih file thumbnail, pratinjau gambar ditampilkan secara langsung dalam area preview sebelum formulir dikirim. | Admin | Should Have |
| FR-ADM-CAT-021 | Simpan Kategori Baru | Sistem memproses penyimpanan data kategori baru setelah seluruh validasi terpenuhi. Setelah berhasil, Admin diarahkan ke halaman daftar kategori dengan flash message sukses. | Admin | Must Have |
| FR-ADM-CAT-022 | Activity Log Pembuatan | Sistem mencatat entri activity log secara otomatis saat kategori berhasil dibuat: user ID, nama kategori baru, dan timestamp. | Admin | Must Have |

### 5.4 Pengeditan Kategori

| FR ID | Nama | Deskripsi | Aktor | Prioritas |
|---|---|---|---|---|
| FR-ADM-CAT-023 | Formulir Edit Kategori | Sistem menampilkan formulir edit yang sudah terisi dengan data kategori saat ini. Semua field dapat diubah kecuali ID dan tanggal pembuatan. | Admin | Must Have |
| FR-ADM-CAT-024 | Simpan Pembaruan Kategori | Sistem memproses pembaruan data setelah validasi terpenuhi. Kolom `updated_at` diperbarui otomatis. Notifikasi sukses ditampilkan. | Admin | Must Have |
| FR-ADM-CAT-025 | Pembaruan Thumbnail | Admin dapat mengganti thumbnail dengan file baru. Sistem menghapus file lama dari penyimpanan setelah penggantian berhasil. | Admin | Should Have |
| FR-ADM-CAT-026 | Hapus Thumbnail Tanpa Penggantian | Admin dapat menghapus thumbnail yang ada tanpa menggantinya. Sistem kembali menggunakan placeholder default. | Admin | Should Have |
| FR-ADM-CAT-027 | Konfirmasi Tinggalkan Halaman Edit | Jika Admin mencoba meninggalkan halaman edit tanpa menyimpan, sistem menampilkan dialog konfirmasi bahwa perubahan akan hilang. | Admin | Should Have |
| FR-ADM-CAT-028 | Activity Log Pengeditan | Sistem mencatat entri activity log saat data kategori diperbarui, termasuk: field yang berubah, nilai sebelum, nilai sesudah, user ID, dan timestamp. | Admin | Must Have |

### 5.5 Manajemen Status

| FR ID | Nama | Deskripsi | Aktor | Prioritas |
|---|---|---|---|---|
| FR-ADM-CAT-029 | Aktivasi Kategori | Sistem mengubah status kategori menjadi Aktif. Kategori segera tampil di halaman pelanggan. Notifikasi sukses ditampilkan. | Admin | Must Have |
| FR-ADM-CAT-030 | Deaktivasi Kategori | Sistem mengubah status kategori menjadi Tidak Aktif. Kategori tidak lagi tampil di halaman pelanggan. Produk dalam kategori tidak terpengaruh. | Admin | Must Have |
| FR-ADM-CAT-031 | Konfirmasi Ubah Status | Sebelum mengeksekusi perubahan status, sistem menampilkan dialog konfirmasi yang menyebutkan nama kategori dan status tujuan. | Admin | Must Have |
| FR-ADM-CAT-032 | Toggle Status dari Daftar | Admin dapat mengubah status kategori langsung dari halaman daftar tanpa harus membuka halaman edit. | Admin | Should Have |

### 5.6 Soft Delete, Restore, dan Force Delete

| FR ID | Nama | Deskripsi | Aktor | Prioritas |
|---|---|---|---|---|
| FR-ADM-CAT-033 | Soft Delete Kategori | Sistem menandai kategori sebagai terhapus dengan mengisi kolom `deleted_at`. Data tidak dihapus dari database. Kategori hilang dari daftar utama dan halaman pelanggan. | Admin | Must Have |
| FR-ADM-CAT-034 | Konfirmasi Soft Delete | Sebelum soft delete dieksekusi, sistem menampilkan dialog konfirmasi yang menyebutkan nama kategori dan jumlah produk aktif yang terhubung (jika ada). | Admin | Must Have |
| FR-ADM-CAT-035 | Blokir Soft Delete – Produk Aktif | Sistem menolak soft delete jika kategori masih memiliki produk aktif. Pesan error menginformasikan jumlah produk yang menghalangi penghapusan. | Admin | Must Have |
| FR-ADM-CAT-036 | Halaman Trash | Sistem menyediakan halaman Trash yang menampilkan daftar kategori yang telah di-soft delete, lengkap dengan paginasi dan pencarian. | Admin | Must Have |
| FR-ADM-CAT-037 | Restore Kategori | Sistem memulihkan kategori dari Trash ke daftar aktif. Status setelah restore default: Tidak Aktif. Admin harus mengaktifkan secara manual. | Admin | Must Have |
| FR-ADM-CAT-038 | Force Delete Kategori | Sistem menghapus data kategori secara permanen dari database. Hanya tersedia di halaman Trash. Memerlukan konfirmasi dua langkah. | Admin | Must Have |
| FR-ADM-CAT-039 | Blokir Force Delete – Produk Terhubung | Sistem menolak force delete jika kategori masih memiliki riwayat produk terhubung, termasuk produk yang sudah di-soft delete. | Admin | Must Have |
| FR-ADM-CAT-040 | Activity Log Soft Delete, Restore, Force Delete | Setiap aksi soft delete, restore, dan force delete dicatat di activity log dengan detail: user ID, nama kategori, aksi yang dilakukan, dan timestamp. | Admin | Must Have |

### 5.7 Notifikasi Sistem

| FR ID | Nama | Deskripsi | Aktor | Prioritas |
|---|---|---|---|---|
| FR-ADM-CAT-041 | Flash Message Sukses | Sistem menampilkan flash message sukses setelah setiap operasi berhasil (buat, edit, hapus, restore, ubah status). | Admin | Must Have |
| FR-ADM-CAT-042 | Pesan Error Validasi Per-Field | Sistem menampilkan pesan error yang spesifik di bawah setiap field yang gagal validasi. Pesan dalam Bahasa Indonesia. | Admin | Must Have |
| FR-ADM-CAT-043 | Pesan Error Sistem yang Aman | Jika terjadi kegagalan sistem, Admin menerima pesan generik yang ramah. Detail teknis tidak ditampilkan ke antarmuka dan hanya dicatat di server log. | Admin | Must Have |

---

## 6. Business Rules

| BR ID | Aturan | Alasan |
|---|---|---|
| BR-ADM-CAT-001 | Nama kategori harus unik di seluruh sistem, bersifat case-insensitive. | Mencegah duplikasi data yang membingungkan Admin dan pelanggan. |
| BR-ADM-CAT-002 | Slug harus unik di seluruh sistem. Slug kategori yang sudah di-soft delete tidak dapat digunakan oleh kategori aktif. | Slug adalah URL identifier; konflik slug menyebabkan broken routing. |
| BR-ADM-CAT-003 | Slug hanya boleh terdiri dari huruf kecil (a–z), angka (0–9), dan tanda hubung (-). Tidak boleh diawali atau diakhiri dengan tanda hubung. | Memastikan slug valid dan aman digunakan sebagai URL path. |
| BR-ADM-CAT-004 | Nama kategori wajib diisi. String kosong atau hanya spasi tidak diizinkan. | Kategori tanpa nama tidak memiliki identitas dan tidak dapat digunakan. |
| BR-ADM-CAT-005 | Nama kategori minimal 3 karakter dan maksimal 100 karakter. | Nama terlalu singkat tidak deskriptif; nama terlalu panjang merusak antarmuka. |
| BR-ADM-CAT-006 | Deskripsi bersifat opsional. Jika diisi, maksimal 1000 karakter. | Tidak semua kategori memerlukan penjelasan panjang. |
| BR-ADM-CAT-007 | Soft delete adalah satu-satunya mekanisme penghapusan standar. Penghapusan permanen hanya diizinkan dari Trash. | Melindungi data historis dari kehilangan yang tidak dapat dipulihkan. |
| BR-ADM-CAT-008 | Kategori yang memiliki produk aktif tidak dapat di-soft delete. | Menghapus kategori yang masih digunakan akan mengakibatkan produk tanpa kategori (orphaned). |
| BR-ADM-CAT-009 | Force delete hanya diizinkan jika kategori tidak memiliki produk yang terhubung sama sekali, termasuk produk yang sudah di-soft delete. | Menjaga integritas referensial data historis. |
| BR-ADM-CAT-010 | Penghapusan permanen memerlukan konfirmasi dua langkah sebelum dieksekusi. | Mencegah penghapusan data permanen yang tidak disengaja. |
| BR-ADM-CAT-011 | Kategori yang di-soft delete tidak tampil di daftar utama maupun halaman publik. | Menghindari navigasi ke konten yang tidak valid. |
| BR-ADM-CAT-012 | Kategori dengan status Tidak Aktif tidak tampil di halaman publik. | Memungkinkan Admin menyembunyikan kategori sementara tanpa menghapusnya. |
| BR-ADM-CAT-013 | Menonaktifkan kategori tidak mengubah status produk yang terhubung. | Pengelolaan status produk adalah tanggung jawab Modul Produk. |
| BR-ADM-CAT-014 | Status default kategori baru adalah Aktif. | Mempercepat alur kerja Admin dalam menambahkan kategori baru. |
| BR-ADM-CAT-015 | Kategori yang dipulihkan dari Trash otomatis berstatus Tidak Aktif. | Admin harus mengaktifkan secara eksplisit agar kategori tidak muncul di halaman publik secara tidak sengaja. |
| BR-ADM-CAT-016 | Slug dibangkitkan otomatis dari nama jika Admin tidak mengisinya secara manual. | Setiap kategori harus selalu memiliki slug yang valid. |
| BR-ADM-CAT-017 | Thumbnail bersifat opsional. Jika tidak diunggah, sistem menggunakan gambar placeholder default. | Tidak semua kategori memerlukan representasi visual khusus. |
| BR-ADM-CAT-018 | Thumbnail hanya menerima format JPG, JPEG, PNG, atau WEBP dengan ukuran maksimum 2 MB. | Keamanan server dan konsistensi performa halaman. |
| BR-ADM-CAT-019 | File thumbnail lama harus dihapus dari storage saat diganti dengan file baru. | Mencegah penumpukan file tidak terpakai yang membuang kapasitas penyimpanan. |
| BR-ADM-CAT-020 | Seluruh aksi CRUD, perubahan status, soft delete, restore, dan force delete wajib dicatat dalam activity log. | Akuntabilitas dan keterlacakan untuk keperluan audit operasional. |
| BR-ADM-CAT-021 | Jumlah produk yang ditampilkan per kategori hanya menghitung produk aktif (bukan yang di-soft delete). | Informasi jumlah produk harus mencerminkan kondisi operasional yang aktual. |
| BR-ADM-CAT-022 | Pencarian kategori bersifat case-insensitive. | Mengurangi risiko Admin tidak menemukan data yang sebenarnya ada karena perbedaan huruf besar/kecil. |
| BR-ADM-CAT-023 | Filter dan parameter pencarian aktif harus dipertahankan dalam query parameter URL saat Admin bernavigasi antar halaman paginasi. | Mencegah Admin kehilangan konteks kerja saat bernavigasi. |
| BR-ADM-CAT-024 | Seluruh daftar kategori harus selalu ditampilkan menggunakan paginasi. Query tanpa paginasi tidak diizinkan pada halaman daftar. | Mencegah degradasi performa saat data bertumbuh besar. |
| BR-ADM-CAT-025 | Setiap pembaruan data pada kategori harus memperbarui kolom `updated_at` secara otomatis menggunakan timezone server. | Memungkinkan pelacakan kapan terakhir kali data dimodifikasi secara akurat. |
| BR-ADM-CAT-026 | Admin yang sedang mengubah slug harus mendapatkan peringatan bahwa perubahan slug akan mempengaruhi URL yang sudah ada. | Mencegah broken link secara tidak disengaja pada halaman pelanggan. |
| BR-ADM-CAT-027 | Seluruh route modul ini hanya dapat diakses oleh pengguna dengan peran Admin yang terautentikasi. | Keamanan data master harus dijaga dari akses tidak sah. |

---

## 7. Validation Rules

### 7.1 Tabel Validasi Field

| Field | Status | Aturan Validasi | Pesan Error | Alasan |
|---|---|---|---|---|
| **Nama Kategori** | Wajib | Tidak boleh kosong (`required`) | "Nama kategori wajib diisi." | Kategori harus memiliki identitas. |
| | | Minimal 3 karakter (`min:3`) | "Nama kategori minimal 3 karakter." | Nama terlalu singkat tidak deskriptif. |
| | | Maksimal 100 karakter (`max:100`) | "Nama kategori maksimal 100 karakter." | Batas wajar untuk antarmuka. |
| | | Unik case-insensitive (`unique`) | "Nama kategori sudah digunakan." | Mencegah duplikasi. |
| | | Unik kecuali dirinya saat edit (`unique,{id}`) | "Nama kategori sudah digunakan." | Mengizinkan edit tanpa ubah nama. |
| **Slug** | Wajib (auto) | Tidak boleh kosong (`required`) | "Slug wajib diisi." | Slug adalah URL identifier kritis. |
| | | Format valid (`regex: ^[a-z0-9]+(?:-[a-z0-9]+)*$`) | "Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung. Tidak boleh diawali atau diakhiri tanda hubung." | Slug harus valid sebagai URL path. |
| | | Maksimal 120 karakter (`max:120`) | "Slug maksimal 120 karakter." | Batas wajar untuk URL yang bersih. |
| | | Unik (`unique`) | "Slug sudah digunakan." | Slug sebagai identifier harus unik. |
| | | Unik kecuali dirinya saat edit (`unique,{id}`) | "Slug sudah digunakan." | Mengizinkan edit tanpa ubah slug. |
| **Deskripsi** | Opsional | Maksimal 1000 karakter jika diisi (`max:1000`) | "Deskripsi maksimal 1000 karakter." | Mencegah input teks berlebihan. |
| | | Tidak boleh mengandung skrip berbahaya | "Deskripsi mengandung karakter yang tidak diizinkan." | Proteksi dari serangan XSS. |
| **Status** | Wajib | Harus salah satu: `active` atau `inactive` (`in:active,inactive`) | "Nilai status tidak valid." | Memastikan hanya nilai enum yang valid tersimpan. |
| **Thumbnail** | Opsional | Jika diunggah: harus berupa file gambar (`image`) | "File harus berupa gambar." | Validasi tipe file dasar. |
| | | Format: JPG, JPEG, PNG, WEBP (`mimes:jpg,jpeg,png,webp`) | "Format tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP." | Keamanan dan kompatibilitas. |
| | | Maksimal 2 MB (`max:2048`) | "Ukuran thumbnail maksimal 2 MB." | Menjaga performa halaman dan kapasitas storage. |

### 7.2 Validasi Lintas Field (Cross-Field)

| Skenario | Aturan | Pesan Error |
|---|---|---|
| Soft delete kategori dengan produk aktif | Validasi `products_count > 0` sebelum eksekusi | "Kategori tidak dapat dihapus. Masih terdapat {n} produk aktif yang terhubung." |
| Force delete kategori dengan produk terhubung | Validasi `products_count_including_deleted > 0` | "Kategori tidak dapat dihapus permanen. Masih terdapat riwayat produk yang terhubung." |
| Restore dengan konflik slug | Validasi keunikan slug saat restore | "Kategori tidak dapat dipulihkan. Slug sudah digunakan oleh kategori lain." |

---

## 8. Security Requirements

### 8.1 Autentikasi

| ID | Requirement |
|---|---|
| SEC-001 | Seluruh route modul kategori dilindungi middleware `auth`. Pengguna yang belum login diarahkan ke halaman login. |
| SEC-002 | Setiap request ke route modul ini harus memvalidasi bahwa sesi Admin masih aktif dan belum kedaluwarsa. |

### 8.2 Otorisasi dan Pembatasan Peran

| ID | Requirement |
|---|---|
| SEC-003 | Hanya pengguna dengan peran `admin` yang dapat mengakses seluruh fitur modul ini. Peran lain menerima respons HTTP 403 Forbidden. |
| SEC-004 | Route grup modul ini menggunakan middleware role validation yang memverifikasi peran pengguna dari sesi aktif. |
| SEC-005 | Setiap action (store, update, destroy, restore, forceDelete) divalidasi otorisasinya melalui Laravel Policy atau Gate sebelum logika dieksekusi. |

### 8.3 Middleware

| ID | Requirement |
|---|---|
| SEC-006 | Seluruh route dalam modul ini terbungkus dalam grup middleware `auth` dan `role:admin`. |
| SEC-007 | Middleware harus dieksekusi sebelum controller action dijalankan. Tidak ada controller action yang dapat diakses tanpa melewati middleware. |

### 8.4 Proteksi CSRF

| ID | Requirement |
|---|---|
| SEC-008 | Semua form (create, edit) harus menyertakan token CSRF. |
| SEC-009 | Server memvalidasi token CSRF pada setiap request POST, PUT/PATCH, dan DELETE. Request dengan token tidak valid menerima respons HTTP 419. |

### 8.5 Session Validation

| ID | Requirement |
|---|---|
| SEC-010 | Setiap request memvalidasi keberadaan sesi Admin yang valid. Sesi yang kedaluwarsa mengarahkan pengguna ke halaman login. |
| SEC-011 | Perubahan pada data sesi (misalnya peran pengguna berubah di tengah sesi) harus menyebabkan sesi diinvalidasi. |

### 8.6 Mass Assignment Protection

| ID | Requirement |
|---|---|
| SEC-012 | Model Category mendefinisikan property `$fillable` secara eksplisit. Hanya field yang terdaftar yang dapat diisi melalui mass assignment. |
| SEC-013 | Field `id`, `created_at`, `updated_at`, dan `deleted_at` tidak dapat diubah melalui mass assignment. |
| SEC-014 | Seluruh input dari request harus melewati Form Request Validation sebelum diteruskan ke model atau service. |

### 8.7 Activity Log dan Audit Trail

| ID | Requirement |
|---|---|
| SEC-015 | Setiap operasi CRUD, perubahan status, soft delete, restore, dan force delete dicatat secara otomatis di tabel activity log. |
| SEC-016 | Setiap entri log harus mencatat: user ID, nama pengguna, IP address, nama aksi, data yang terpengaruh, dan timestamp. |
| SEC-017 | Untuk operasi update, log mencatat nilai field sebelum dan sesudah perubahan (before/after audit trail). |
| SEC-018 | Data activity log tidak dapat diedit atau dihapus melalui antarmuka panel Admin. |

### 8.8 Keamanan Input dan Output

| ID | Requirement |
|---|---|
| SEC-019 | Semua output di Blade template menggunakan sintaks `{{ }}` untuk auto-escaping. Penggunaan `{!! !!}` dilarang kecuali dengan sanitasi eksplisit. |
| SEC-020 | File upload divalidasi MIME type di sisi server, bukan hanya ekstensi file. |
| SEC-021 | Seluruh query database menggunakan Eloquent ORM atau Query Builder dengan parameter binding. Raw query tanpa binding dilarang. |

---

## 9. Performance Requirements

### 9.1 Target Waktu Respons

| Operasi | Target | Catatan |
|---|---|---|
| Memuat halaman daftar (10 item) | < 1 detik | Dengan paginasi dan indeks yang tepat |
| Memuat halaman daftar (50 item) | < 2 detik | Batas atas yang dapat diterima |
| Eksekusi pencarian | < 1 detik | Query memanfaatkan indeks pada kolom `name` dan `slug` |
| Memuat halaman detail kategori | < 1 detik | Dengan eager loading relasi produk |
| Operasi Create / Update | < 2 detik | Termasuk validasi dan penyimpanan |
| Operasi Soft Delete / Restore | < 1 detik | Operasi single-record |
| Memuat halaman Trash | < 2 detik | Dengan paginasi |

### 9.2 Strategi Indeks Database

| Kolom | Tipe Indeks | Alasan |
|---|---|---|
| `name` | INDEX | Mempercepat pencarian dan pengecekan keunikan |
| `slug` | UNIQUE INDEX | Mempercepat lookup slug dan routing |
| `status` | INDEX | Mempercepat filter berdasarkan status |
| `deleted_at` | INDEX | Mempercepat query soft delete |
| `created_at` | INDEX | Mempercepat sorting dan filter tanggal |

### 9.3 Strategi Optimasi Query

| Strategi | Detail |
|---|---|
| **Eager Loading** | Relasi `products` hanya dimuat menggunakan `with()` saat dibutuhkan (halaman detail). Halaman daftar menggunakan `withCount('activeProducts')`. |
| **Select Spesifik** | Query daftar menggunakan `select()` dengan hanya kolom yang diperlukan, bukan `SELECT *`. |
| **Paginasi Database** | Menggunakan `paginate()` bukan `get()` untuk semua query daftar. |
| **Query Scope** | Logika query yang berulang (scope aktif, scope pencarian) dienkapsulasi dalam Eloquent Scope untuk konsistensi dan keterbacaan. |
| **Hindari N+1** | Semua relasi yang ditampilkan dalam daftar dimuat menggunakan eager loading. |

### 9.4 Skalabilitas

| Aspek | Target |
|---|---|
| Jumlah kategori | Performa stabil hingga 500 kategori |
| Konkurensi Admin | Minimal 5 Admin mengakses modul secara bersamaan |
| Pertumbuhan data | Performa tidak degradasi saat data tumbuh 10× lipat dengan indeks yang tepat |

---

## 10. Error Handling

| ID | Skenario | Penyebab | Perilaku Sistem | Umpan Balik Admin | Log |
|---|---|---|---|---|---|
| ERR-001 | Duplikasi nama kategori | Admin menyimpan kategori dengan nama yang sudah ada | Sistem menolak penyimpanan; form dikembalikan dengan data | Pesan error di field Nama: "Nama kategori sudah digunakan." | Tidak dicatat (validasi gagal) |
| ERR-002 | Duplikasi slug | Admin menyimpan slug yang sudah digunakan | Sistem menolak; form dikembalikan | Pesan error di field Slug: "Slug sudah digunakan." | Tidak dicatat |
| ERR-003 | Kategori tidak ditemukan | URL dengan ID tidak valid atau sudah force delete | Sistem menampilkan halaman 404 custom | Halaman 404 dengan tombol kembali ke daftar | Dicatat di error log server |
| ERR-004 | Validasi input gagal | Field tidak memenuhi aturan validasi | Sistem menolak; form dikembalikan dengan error per-field | Pesan error spesifik di bawah setiap field yang bermasalah | Tidak dicatat |
| ERR-005 | Akses ditolak | Pengguna non-Admin mengakses URL modul | Sistem mengembalikan HTTP 403 | Halaman 403 custom dengan pesan "Anda tidak memiliki akses." | Dicatat di security log |
| ERR-006 | Soft delete diblokir | Kategori masih punya produk aktif | Sistem menolak; tidak mengeksekusi delete | Flash message error: "Kategori tidak dapat dihapus. Masih ada {n} produk aktif." | Dicatat di activity log sebagai aksi gagal |
| ERR-007 | Force delete diblokir | Kategori masih punya riwayat produk | Sistem menolak | Flash message error yang informatif | Dicatat di activity log sebagai aksi gagal |
| ERR-008 | Restore slug konflik | Slug kategori yang dipulihkan sudah dipakai kategori lain | Sistem menolak restore | Flash message: "Gagal dipulihkan. Slug sudah digunakan." | Dicatat di activity log |
| ERR-009 | Upload thumbnail gagal | Format/ukuran tidak valid atau error server | Sistem menolak penyimpanan; form dikembalikan | Pesan error di field Thumbnail | Error upload dicatat di Laravel log |
| ERR-010 | Token CSRF tidak valid | Request tanpa token atau token kedaluwarsa | Framework mengembalikan HTTP 419 | Halaman 419 dengan instruksi refresh | Tidak perlu dicatat khusus |
| ERR-011 | Hasil pencarian kosong | Kata kunci tidak cocok dengan data | Sistem menampilkan empty state | "Tidak ada kategori yang cocok dengan '{keyword}'." | Tidak dicatat |
| ERR-012 | Kegagalan database | Error koneksi atau query | Exception ditangkap; transaksi di-rollback | Flash message generik: "Terjadi kesalahan sistem. Coba lagi." | Error detail dicatat di storage/logs |
| ERR-013 | Exception tidak terduga | Error tak terprediksi di server | Global exception handler menangkap error | Halaman 500 custom yang informatif | Full stack trace dicatat di Laravel log |

---

## 11. Acceptance Criteria

| ID | Given | When | Then |
|---|---|---|---|
| AC-001 | Admin telah login dan berada di halaman buat kategori | Admin mengisi Nama dan semua field valid, lalu klik "Simpan" | Sistem menyimpan kategori, menampilkan flash sukses, dan mengarahkan Admin ke daftar kategori. |
| AC-002 | Admin berada di halaman buat kategori | Admin mengosongkan field Nama dan klik "Simpan" | Sistem menolak dan menampilkan pesan error "Nama kategori wajib diisi." di bawah field Nama. |
| AC-003 | Admin berada di halaman buat kategori | Admin mengetik "Alat Tulis Kelas" pada field Nama | Field Slug otomatis terisi dengan nilai `alat-tulis-kelas`. |
| AC-004 | Admin berada di halaman buat kategori | Admin menginput nama kategori yang sudah ada di database | Sistem menolak penyimpanan dan menampilkan error duplikasi nama. |
| AC-005 | Admin berada di halaman buat kategori | Admin mengunggah file thumbnail berformat PDF | Sistem menolak dan menampilkan error "Format tidak didukung." |
| AC-006 | Admin berada di halaman edit kategori "Pena" | Admin mengubah nama menjadi "Pena Kantor" dan klik "Simpan" | Sistem memperbarui data, `updated_at` diperbarui, notifikasi sukses ditampilkan. |
| AC-007 | Terdapat kategori "Stabilo" berstatus Aktif | Admin mengklik tombol "Nonaktifkan" dan mengonfirmasi dialog | Status berubah menjadi Tidak Aktif, notifikasi sukses muncul, dan kategori tidak tampil di halaman pelanggan. |
| AC-008 | Terdapat kategori "Penggaris" berstatus Tidak Aktif | Admin mengklik "Aktifkan" dan mengonfirmasi | Status berubah menjadi Aktif dan kategori kembali tampil di halaman pelanggan. |
| AC-009 | Terdapat 20 kategori berbagai nama | Admin mengetik "tulis" pada kolom pencarian | Sistem menampilkan hanya kategori yang namanya mengandung "tulis" (case-insensitive). |
| AC-010 | Terdapat kategori Aktif dan Tidak Aktif | Admin memilih filter "Tidak Aktif" | Sistem hanya menampilkan kategori berstatus Tidak Aktif. |
| AC-011 | Admin aktif menggunakan filter | Admin mengklik tombol "Reset" | Filter dan pencarian direset; seluruh kategori ditampilkan kembali. |
| AC-012 | Terdapat kategori "Tip-X" tanpa produk aktif | Admin mengklik "Hapus" dan mengonfirmasi dialog | Sistem melakukan soft delete; kategori hilang dari daftar utama dan muncul di halaman Trash. |
| AC-013 | Terdapat kategori "Kertas" dengan 8 produk aktif | Admin mengklik "Hapus" pada kategori tersebut | Setelah konfirmasi, sistem menolak dengan pesan "Kategori tidak dapat dihapus. Masih terdapat 8 produk aktif." |
| AC-014 | Terdapat kategori "Tip-X" di halaman Trash | Admin mengklik "Pulihkan" dan mengonfirmasi | Kategori kembali ke daftar aktif dengan status Tidak Aktif; notifikasi sukses ditampilkan. |
| AC-015 | Terdapat kategori "Stempel" di Trash tanpa produk terhubung | Admin mengklik "Hapus Permanen" dan mengonfirmasi dua kali | Sistem menghapus data secara permanen; kategori hilang dari Trash; notifikasi sukses ditampilkan. |
| AC-016 | Terdapat kategori "Buku" di Trash dengan riwayat 3 produk (termasuk yang dihapus) | Admin mengklik "Hapus Permanen" | Sistem menolak dengan pesan "Kategori tidak dapat dihapus permanen. Masih terdapat riwayat produk terhubung." |
| AC-017 | Admin berhasil membuat kategori "Spidol" | Operasi selesai | Sistem mencatat entri di activity log: user ID, aksi "create_category", nama "Spidol", dan timestamp. |
| AC-018 | Pengguna berstatus pelanggan mencoba mengakses `/admin/categories` | Request dikirim | Sistem mengembalikan HTTP 403 dan menampilkan halaman error 403. |
| AC-019 | Admin membuka halaman detail kategori yang memiliki 12 produk aktif | Halaman dimuat | Sistem menampilkan angka "12" pada counter produk dan daftar produk terpaginasi. |
| AC-020 | Admin membuka daftar kategori dengan 30 data | Halaman pertama dimuat | Sistem menampilkan 10 data per halaman dengan navigasi paginasi yang berfungsi. |

---

## 12. Definition of Done

Modul Admin Category dinyatakan **selesai** apabila seluruh kriteria berikut terpenuhi.

### 12.1 Fungsional

- [ ] Semua 43 Functional Requirements (FR-ADM-CAT-001 s/d 043) telah diimplementasikan.
- [ ] Semua 27 Business Rules telah diverifikasi berfungsi.
- [ ] Semua Validation Rules diterapkan dan diverifikasi untuk setiap field.
- [ ] Fitur Create, Edit, View List, View Detail, Activate, Deactivate, Soft Delete, Restore, Force Delete berfungsi benar.
- [ ] Fitur Search, Filter, Sorting, Pagination berfungsi dan mempertahankan state saat navigasi.
- [ ] Auto-generate slug berfungsi dan dapat diedit manual.
- [ ] Product counter menampilkan angka akurat dan real-time.
- [ ] Halaman Trash berfungsi dengan paginasi dan pencarian.
- [ ] Activity log mencatat semua operasi yang ditentukan.

### 12.2 Kualitas Kode

- [ ] Kode mengikuti standar yang ditetapkan dalam Development Bible dan Folder Architecture Standard.
- [ ] Form Request Validation digunakan untuk semua validasi input.
- [ ] Tidak ada query N+1 pada halaman daftar dan detail.
- [ ] Semua query menggunakan Eloquent ORM atau Query Builder dengan parameter binding.

### 12.3 Keamanan

- [ ] Seluruh route dilindungi middleware `auth` dan `role:admin`.
- [ ] CSRF protection aktif di semua form.
- [ ] Mass assignment protection diterapkan di model.
- [ ] Output di Blade menggunakan auto-escaping.
- [ ] Upload file divalidasi MIME type di sisi server.

### 12.4 Pengujian

- [ ] Semua 20 Acceptance Criteria (AC-001 s/d AC-020) diuji dan lulus.
- [ ] Tidak ada bug dengan kategori Critical atau High yang masih terbuka.
- [ ] Pengujian keamanan dasar telah dilakukan (uji akses tanpa login, uji akses dengan peran salah).
- [ ] Halaman daftar diuji memenuhi target performa < 2 detik.

### 12.5 Dokumentasi dan Review

- [ ] Dokumen SRS ini telah disetujui oleh Project Owner.
- [ ] Kode telah melewati proses code review dan disetujui.
- [ ] Kode telah di-merge ke branch utama sesuai Development Workflow Standard.
- [ ] Modul telah diuji di environment staging sebelum production.

---

## 13. Future Enhancement

Fitur-fitur berikut berada di luar cakupan Versi 1 dan dapat direncanakan untuk milestone atau iterasi berikutnya.

| ID | Fitur | Deskripsi Singkat | Nilai Bisnis |
|---|---|---|---|
| FE-001 | Nested / Sub-kategori | Kategori berhierarkis (induk → anak) | Klasifikasi produk yang lebih granular |
| FE-002 | Category Tree tanpa batas | Hierarki kategori dengan kedalaman tidak terbatas | Skalabilitas untuk ekspansi produk jangka panjang |
| FE-003 | Category Banner Image | Gambar banner berukuran besar per kategori | Meningkatkan daya tarik visual halaman kategori |
| FE-004 | Category Icon | Ikon SVG atau font icon per kategori | Navigasi pelanggan yang lebih intuitif |
| FE-005 | Import via Excel/CSV | Unggah data kategori secara massal | Efisiensi saat migrasi atau penambahan data besar |
| FE-006 | Export ke Excel/CSV | Ekspor data kategori untuk pelaporan | Kemudahan akses data untuk manajemen |
| FE-007 | Category Analytics | Statistik per kategori: produk, pesanan, pendapatan | Insight bisnis untuk pengambilan keputusan |
| FE-008 | SEO Metadata per Kategori | Meta title, meta description, dan keyword per kategori | Meningkatkan visibilitas di mesin pencari |
| FE-009 | Manual Sort Order | Urutan tampilan kategori diatur secara manual (drag-and-drop) | Kontrol penuh atas navigasi halaman pelanggan |
| FE-010 | Bulk Actions | Aksi massal: nonaktifkan, aktifkan, hapus banyak kategori sekaligus | Efisiensi pengelolaan skala besar |
| FE-011 | Color Tag | Label warna per kategori untuk identifikasi visual cepat di panel admin | Mempercepat identifikasi kategori |
| FE-012 | Advanced Reporting | Laporan tren kategori per periode waktu | Mendukung perencanaan stok dan strategi bisnis |

---

## 14. Conclusion

### 14.1 Ringkasan

Dokumen ini mendefinisikan seluruh persyaratan yang diperlukan untuk implementasi **Admin Category Module** pada Website Toko Alat Tulis Kantor (ATK). Dokumen mencakup **43 Functional Requirements**, **27 Business Rules**, validasi field yang komprehensif, 21 security requirement, target performa yang terukur, 13 skenario error handling, dan **20 Acceptance Criteria** yang dapat diuji.

### 14.2 Signifikansi Modul

Modul Category adalah **fondasi hierarki produk** dalam sistem ini. Kualitas implementasinya secara langsung mempengaruhi:

1. **Kemudahan kerja Admin** dalam mengelola produk berskala besar secara efisien.
2. **Pengalaman pelanggan** dalam menemukan produk yang mereka butuhkan.
3. **Integritas seluruh sistem**, khususnya Modul Produk yang bergantung pada modul ini.
4. **Skalabilitas jangka panjang** untuk pertumbuhan bisnis.

### 14.3 Tindak Lanjut

Setelah dokumen ini disetujui oleh Project Owner:

- Tim pengembang dapat memulai fase implementasi sesuai Development Workflow Standard.
- Tim QA dapat menyusun test case berdasarkan Acceptance Criteria yang telah didefinisikan.
- Setiap perubahan terhadap persyaratan dalam dokumen ini harus diproses melalui mekanisme **Change Request** resmi.

---

> **Versi**: 1.0.0 | **Status**: Draft | **Tanggal**: 04 Agustus 2026
>
> *Dokumen ini bersifat konfidensial dan merupakan milik resmi proyek Website Toko Alat Tulis Kantor (ATK). Distribusi di luar tim proyek memerlukan persetujuan Project Owner.*
