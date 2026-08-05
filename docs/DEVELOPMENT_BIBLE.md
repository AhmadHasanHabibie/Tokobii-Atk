# Development Bible

## Website Toko Alat Tulis Kantor (ATK)

| Informasi  | Standar                                                                   |
| ---------- | ------------------------------------------------------------------------- |
| Framework  | Laravel 10                                                                |
| Bahasa     | PHP 8.x; baseline yang disetujui: PHP 8.3                                 |
| Basis data | MySQL                                                                     |
| Target     | Production ready, mudah dipelihara, dan dapat dikembangkan                |
| Status     | Standar pengembangan resmi untuk seluruh proses coding                    |
| Cakupan    | Kode aplikasi, pengujian, dokumentasi, keamanan, kualitas, dan kolaborasi |

## 1. Executive Summary

Development Bible ini adalah baseline wajib bagi setiap developer, reviewer, dan agen AI yang bekerja pada Website Toko ATK. Dokumen ini menyatukan cara pengambilan keputusan teknis agar implementasi konsisten dengan tujuan bisnis: proses penjualan Pick-Up yang aman, pengelolaan stok yang akurat, serta operasional yang dapat diaudit.

Aturan di bawah berlaku untuk kode baru dan perubahan terhadap kode yang ada. Jika sebuah aturan tidak dapat dipatuhi karena kebutuhan teknis yang sah, pengecualian harus didokumentasikan pada pull request atau catatan keputusan teknis, menjelaskan alasan, dampak, mitigasi, dan rencana peninjauan ulang. Tidak ada pengecualian untuk keamanan dasar, integritas transaksi, atau perlindungan data tanpa persetujuan pihak teknis yang berwenang.

## 2. Development Philosophy

| Filosofi                    | Penerapan dan alasan                                                                                                                       |
| --------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------ |
| Clean Code                  | Kode harus jelas, kecil, bernama baik, dan fokus pada satu tanggung jawab agar mudah dibaca serta diubah dengan aman.                      |
| Maintainability First       | Pilihan implementasi mengutamakan kemudahan perawatan jangka panjang, bukan hanya kecepatan menulis kode hari ini.                         |
| Business-Driven Development | Setiap fitur dimulai dari kebutuhan, aturan, dan alur bisnis yang terdokumentasi; teknologi melayani proses bisnis, bukan sebaliknya.      |
| Security First              | Akses, input, file, sesi, dan data transaksi dianggap tidak tepercaya sampai divalidasi serta diotorisasi.                                 |
| Performance Matters         | Query, media, dan tampilan dirancang efisien sejak awal, terutama untuk daftar produk, dashboard, dan tabel administrasi.                  |
| Simplicity Over Complexity  | Pilih solusi Laravel standar dan paling sederhana yang memenuhi kebutuhan. Arsitektur rumit atau package baru harus dibuktikan manfaatnya. |
| Explicit Over Implicit      | Aturan bisnis kritis, perubahan stok, status order, transaksi data, dan hak akses harus terlihat jelas dalam kode dan dokumentasi.         |
| Testable by Design          | Logika bisnis ditempatkan pada unit yang dapat diuji tanpa ketergantungan berlebihan pada controller atau view.                            |
| Auditability                | Perubahan stok, pembayaran, status order, akses keamanan, dan tindakan penting harus dapat ditelusuri.                                     |

## 3. Architecture Principle

### 3.1 MVC sebagai fondasi

Laravel MVC digunakan sebagai struktur dasar. Model mewakili data dan relasi domain; View/Blade menyajikan data; Controller menjadi adaptor HTTP yang tipis. Controller tidak boleh menjadi tempat utama logika bisnis.

### 3.2 Service Layer

Service Layer adalah tempat use case atau proses bisnis lintas langkah, terutama yang melibatkan validasi bisnis tambahan, beberapa model, perubahan status, transaksi basis data, upload melalui abstraction, dispatch notification, atau audit. Contoh domain service: `ProductService`, `StockService`, `OrderService`, `PaymentService`, dan `SecurityService`.

Service harus memiliki tujuan bisnis yang jelas, metode yang kecil, dependency eksplisit, serta tidak bergantung pada request HTTP atau Blade. Logika yang hanya menyusun query sederhana tidak otomatis memerlukan service.

### 3.3 Repository Pattern

Repository bukan kewajiban untuk setiap model. Gunakan hanya ketika akses data memiliki query kompleks atau dipakai berulang, perlu dipisahkan dari service, membutuhkan variasi sumber data, atau harus mudah diubah/diganti dalam pengujian. Untuk CRUD sederhana dan relasi Eloquent yang jelas, gunakan Eloquent langsung di service agar tidak menciptakan lapisan abstraksi tanpa nilai.

Repository tidak boleh berisi aturan bisnis. Ia bertanggung jawab pada pengambilan dan penyimpanan data. Nama repository harus mencerminkan aggregate/domain yang ditangani, misalnya `OrderRepository`, bukan `CommonRepository`.

### 3.4 Form Request Validation

Seluruh input HTTP yang memengaruhi data atau proses bisnis menggunakan Form Request. Form Request menangani authorization request-level dan aturan validasi input. Validasi bisnis yang memerlukan data transaksi atau konsistensi lintas entitas tetap berada pada service/domain layer.

### 3.5 Policy dan Middleware

Middleware digunakan untuk kontrol request umum: autentikasi, verifikasi email, role, rate limiting, atau Face Verification. Policy digunakan untuk authorization yang bergantung pada resource dan kepemilikan, misalnya Customer hanya dapat melihat order miliknya. Jangan memakai role saja untuk menggantikan pemeriksaan kepemilikan data.

### 3.6 Transaction Boundary

Use case yang mengubah beberapa data terkait—khususnya order, payment, stok, dan audit—harus memiliki transaction boundary yang jelas di service. Notification atau proses lambat tidak boleh menyebabkan transaksi basis data terbuka lebih lama dari yang diperlukan.

## 4. Folder Standard

Struktur folder mengikuti konvensi Laravel dengan tambahan folder aplikasi yang terarah. Folder hanya dibuat bila memiliki tanggung jawab nyata.

| Lokasi                  | Fungsi                                                                                                                           |
| ----------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `app/Http/Controllers/` | Controller tipis untuk request/response; dapat dikelompokkan berdasarkan area fitur, misalnya `Admin`, `Owner`, atau `Customer`. |
| `app/Services/`         | Use case dan logika bisnis lintas langkah.                                                                                       |
| `app/Repositories/`     | Abstraksi akses data yang benar-benar kompleks atau dipakai ulang; bukan folder wajib untuk setiap model.                        |
| `app/Http/Requests/`    | Form Request untuk validasi dan authorization request-level; dikelompokkan per fitur.                                            |
| `app/Models/`           | Eloquent model, relasi, casts, scope sederhana, dan perilaku data yang dekat dengan model.                                       |
| `app/Policies/`         | Authorization berbasis resource/kepemilikan.                                                                                     |
| `app/Notifications/`    | Notifikasi domain melalui kanal yang disetujui.                                                                                  |
| `app/Traits/`           | Trait kecil, generik, dan dapat diuji; tidak dipakai sebagai tempat logika bisnis tak terstruktur.                               |
| `app/Helpers/`          | Helper murni dan stateless yang tidak cocok ditempatkan pada service/model; penggunaannya dibatasi.                              |
| `app/Exceptions/`       | Exception domain atau aplikasi yang bermakna dan dapat ditangani secara konsisten.                                               |
| `app/Support/`          | Value object, enum pendukung, atau utilitas domain yang bukan helper global.                                                     |
| `resources/views/`      | Blade view, layout, component, partial, dan halaman yang dikelompokkan menurut fitur/area.                                       |
| `resources/js/`         | JavaScript vanilla dan entry point Vite.                                                                                         |
| `resources/css/`        | CSS proyek, penyesuaian Bootstrap, dan entry point stylesheet.                                                                   |
| `routes/`               | Definisi route per konteks; route harus tipis dan tidak berisi logika bisnis.                                                    |
| `storage/`              | Log, cache, file runtime, serta file aplikasi nonpublik sesuai konfigurasi filesystem.                                           |
| `public/`               | Satu-satunya web root; aset publik hasil build dan entry point aplikasi.                                                         |
| `database/`             | Migration, seeder, factory, dan sumber daya data development/testing.                                                            |
| `tests/Feature/`        | Pengujian alur HTTP, authorization, dan integrasi fitur.                                                                         |
| `tests/Unit/`           | Pengujian unit logika yang terisolasi.                                                                                           |
| `docs/`                 | Dokumen proyek, keputusan arsitektur, requirement, SOP, dan catatan rilis.                                                       |

Tidak boleh membuat folder umum seperti `Misc`, `Utils`, `Common`, atau `Temp` untuk menghindari file tanpa kepemilikan domain. Struktur yang dipilih harus konsisten dalam satu fitur.

## 5. Feature Organization

Fitur dikelompokkan berdasarkan domain dan area pengguna, bukan sekadar tipe file. Pengelompokan yang direkomendasikan mencakup `Admin`, `Owner`, `Customer`, `Guest`, `Product`, `Category`, `Stock`, `Order`, `Payment`, `Report`, dan `Security`.

- Controller, request, view, policy, service, test, dan dokumentasi fitur harus mudah ditemukan melalui nama domain yang sama.
- Area pengguna dipakai bila sebuah halaman/proses memiliki aturan akses dan tampilan berbeda, misalnya `Admin/Product` dan `Owner/Product`.
- Domain bersama seperti `Order` atau `Stock` tidak boleh diduplikasi hanya karena dipakai lebih dari satu role; perbedaan akses ditangani oleh policy/middleware dan presenter/view yang sesuai.
- Satu fitur harus memiliki pemilik tanggung jawab yang jelas. Perubahan lintas fitur harus menyebutkan dampak serta pengujian semua domain terkait.
- Jangan memaksa modularisasi penuh bila struktur Laravel standar sudah cukup. Keterbacaan dan navigasi kode lebih penting daripada hierarki folder berlebihan.

## 6. Naming Convention

Gunakan Bahasa Inggris untuk nama teknis agar selaras dengan Laravel/PHP; Bahasa Indonesia digunakan untuk isi bisnis yang ditampilkan kepada pengguna. Nama harus spesifik, konsisten, dan menggambarkan tanggung jawab.

| Elemen       | Standar benar                                                           | Hindari                                                             |
| ------------ | ----------------------------------------------------------------------- | ------------------------------------------------------------------- |
| Controller   | `ProductController`, `Admin/OrderController`                            | `ProductCtrl`, `Controller1`, `AllController`                       |
| Service      | `OrderService`, `PaymentVerificationService`                            | `ServiceHelper`, `GeneralService`                                   |
| Repository   | `ProductRepository`, `OrderRepository`                                  | `Repository`, `DatabaseHelper`                                      |
| Form Request | `StoreProductRequest`, `UpdateOrderStatusRequest`                       | `ProductRequest`, `ValidationRequest` bila maksudnya tidak spesifik |
| Policy       | `OrderPolicy`, `ProductPolicy`                                          | `PermissionPolicy`, `UserPolicy2`                                   |
| Migration    | Nama aksi Laravel yang deskriptif, misalnya `create_products_table`     | nama generik atau singkatan tidak jelas                             |
| Seeder       | `ProductSeeder`, `AdminUserSeeder`                                      | `DataSeeder`, `SeederNew`                                           |
| Factory      | `ProductFactory`, `OrderFactory`                                        | `FactoryData`                                                       |
| Model        | `Product`, `StockHistory`, `PaymentProof`                               | `ProductsModel`, `TblProduct`                                       |
| View         | `products/index`, `orders/show`, `components/status-badge`              | `page1`, `produkbaru`, nama view campuran tidak konsisten           |
| Route name   | `admin.products.index`, `customer.orders.show`                          | `product-list-new`, `route1`                                        |
| Variable     | `$product`, `$orderStatus`, `$pickupCode`                               | `$data`, `$x`, `$result1` tanpa konteks                             |
| Method       | `verifyPaymentProof`, `adjustStock`, `markAsReadyForPickup`             | `process`, `handleData`, `doIt` tanpa makna bisnis                  |
| Table        | jamak snake_case: `products`, `stock_histories`                         | `tbl_product`, `ProductTable`                                       |
| Column       | snake_case: `pickup_code`, `verified_at`                                | `PickupCode`, `verifiedAt`                                          |
| Enum/case    | Nama domain eksplisit, misalnya `OrderStatus::ReadyForPickup`           | string status tersebar seperti `ready`, `siap`, `R`                 |
| File upload  | UUID/ULID acak dan ekstensi tervalidasi                                 | nama asli pengguna, timestamp saja, atau nama yang dapat ditebak    |
| Folder       | lowercase/kebab-case bila relevan dan konsisten dengan konvensi Laravel | `New Folder`, `Folder_Final`, campuran kapitalisasi                 |

Singkatan hanya boleh digunakan bila universal dan tidak ambigu, misalnya `SKU`, `QRIS`, `IP`, `URL`, atau `PDF`. Jangan gunakan nama teknis sebagai pengganti istilah bisnis yang bermakna.

## 7. Coding Standard

### 7.1 PSR-12 dan Laravel Convention

Kode PHP mengikuti PSR-12: indentasi empat spasi, struktur namespace/import rapi, satu class per file, format konsisten, serta tidak ada whitespace/formatting noise. Laravel convention diikuti untuk penamaan model, relasi, migration, request, route, dan view agar developer dapat memprediksi lokasi perilaku aplikasi.

### 7.2 Clean Code dan SOLID

Method harus singkat, memiliki satu tujuan, dan bernama berdasarkan hasil/tindakan bisnis. Class tidak boleh memegang terlalu banyak tanggung jawab. SOLID digunakan secara pragmatis: pisahkan tanggung jawab ketika perubahan berbeda memiliki alasan berbeda; gunakan interface/abstraction jika ada kebutuhan nyata untuk substitusi atau pengujian, bukan sebagai formalitas.

### 7.3 DRY, KISS, dan YAGNI

- **DRY:** hilangkan duplikasi aturan/algoritma yang benar-benar sama, tetapi jangan menyatukan kode yang kebetulan mirip namun konteks bisnisnya berbeda.
- **KISS:** pilih alur dan API yang paling mudah dipahami; hindari pola desain atau package berat tanpa kebutuhan.
- **YAGNI:** jangan membangun API publik, event bus, multi-branch, cache kompleks, atau abstraction masa depan sebelum ada requirement yang disetujui.

### 7.4 Aturan umum

- Jangan gunakan magic number, magic string status, atau hardcode konfigurasi. Letakkan nilai bisnis pada enum, config, atau objek domain yang sesuai.
- Gunakan type declaration dan return type bila sesuai dengan versi PHP serta konvensi proyek.
- Hilangkan dead code, commented-out code, import tidak terpakai, dan debug statement sebelum review.
- Komentar menjelaskan keputusan atau alasan yang tidak jelas dari kode, bukan mengulang apa yang sudah nyata dari nama method.
- Jangan mengakses environment variable langsung di class aplikasi; gunakan konfigurasi Laravel.

## 8. Development Rules

### 8.1 Controller Rules

Controller hanya menerima request, menggunakan Form Request, memanggil service/use case, lalu mengembalikan response atau view. Controller boleh menyiapkan data presentasi sederhana, tetapi tidak boleh memuat query kompleks, perhitungan bisnis, perubahan status lintas model, pengelolaan upload secara langsung, atau blok logika panjang.

Aturan ini menjaga controller mudah diuji, mencegah duplikasi di endpoint lain, dan memastikan logika order/stok/pembayaran tidak tersebar. Jika controller mulai memerlukan beberapa langkah bisnis, pindahkan langkah tersebut ke service yang bernama jelas.

### 8.2 Service Rules

Buat service bila proses memiliki aturan bisnis, digunakan lebih dari satu controller/job/command, melakukan perubahan pada beberapa entitas, memerlukan transaction, menangani side effect terkontrol, atau membutuhkan pengujian domain terpisah. Contoh: penyesuaian stok, checkout, verifikasi QRIS, perubahan status order, dan pencatatan kejadian keamanan.

Service bertanggung jawab atas urutan proses, validasi bisnis, transaction boundary, dan koordinasi dependency. Service tidak boleh menangani rendering Blade atau membaca request global secara langsung. Satu service tidak boleh berkembang menjadi "god service"; pecah berdasarkan use case apabila tanggung jawabnya melebar.

### 8.3 Repository Rules

Repository dibuat hanya untuk query/data access yang kompleks, berulang, atau perlu diisolasi. Service dapat menggunakan Eloquent langsung untuk operasi sederhana. Jangan membuat repository wrapper satu-banding-satu yang hanya memanggil `find`, `create`, atau `update` tanpa menambah nilai.

Repository mengembalikan data domain dengan bentuk yang jelas dan tidak membuat response HTTP. Query yang memerlukan eager loading, filter terstandar, atau penguncian data dapat dipusatkan di repository bila ini meningkatkan keterbacaan.

### 8.4 Validation Rules

Semua validasi input yang bermakna menggunakan Form Request. `validate()` langsung pada controller hanya diizinkan untuk kasus sangat sederhana, satu kali, tanpa aturan bisnis, dan perlu dijelaskan pada review; preferensi tetap Form Request.

Aturan validasi harus memeriksa tipe, batas nilai, format, kepemilikan, enum/status yang diizinkan, serta file upload. Validasi client-side hanya memperbaiki pengalaman pengguna dan tidak pernah menggantikan validasi server-side.

### 8.5 Database Rules

- Gunakan primary key standar Laravel atau identifier yang diputuskan konsisten; jangan mencampur strategi tanpa alasan.
- Foreign key digunakan pada relasi inti dengan kebijakan penghapusan eksplisit.
- Gunakan `created_at` dan `updated_at` pada data operasional kecuali ada alasan terdokumentasi untuk tidak menggunakannya.
- Soft delete dipakai pada master data yang berpotensi perlu dipulihkan atau memiliki riwayat; jangan soft delete data transaksi tanpa kebijakan lifecycle yang jelas.
- Index dibuat berdasarkan pola akses nyata: foreign key, SKU, nomor order, status, tanggal, filter, dan pencarian yang disetujui. Setiap indeks harus memiliki alasan.
- Hindari enum basis data yang sulit diubah bila status bisnis diperkirakan berkembang; enum PHP/value object dan constraint yang sesuai dapat menjadi pilihan. Keputusan final dicatat pada desain data.
- Gunakan nama tabel jamak snake_case dan nama kolom snake_case.
- Relasi Eloquent harus mencerminkan istilah bisnis dan meminimalkan ambiguitas.
- Operasi perubahan order, payment, dan stok lintas tabel wajib memakai transaksi bila konsistensinya bersifat atomik.
- Migration harus kecil, dapat diulang, reversible bila praktis, dan tidak mencampur perubahan yang tidak terkait.
- Seeder dipakai untuk data referensi/development yang terkontrol; Factory dipakai untuk data test/development yang realistis dan terisolasi.

### 8.6 Upload Rules

| Jenis          | Standar                                                                                                                   |
| -------------- | ------------------------------------------------------------------------------------------------------------------------- |
| Produk         | Mendukung thumbnail dan galeri; simpan varian optimasi; hanya tipe/dimensi yang disetujui.                                |
| Profile        | Batasi ke gambar, ukuran kecil, serta satu kebijakan penggantian/retensi yang jelas.                                      |
| Payment proof  | Disimpan sebagai berkas privat; akses melalui authorization; MIME, ukuran, ekstensi, dan malware policy harus divalidasi. |
| Report/receipt | Berkas keluaran dibuat server-side; akses berdasarkan role/kepemilikan dan retensi ditetapkan.                            |
| Storage        | Gunakan Laravel filesystem/disk terkonfigurasi; jangan menulis path absolut atau menyimpan file upload di source tree.    |
| Naming         | Gunakan UUID/ULID acak, folder domain yang konsisten, dan metadata yang diperlukan; jangan percaya nama asli dari client. |

Semua upload diuji dari sisi server. File harus diproses/diakses dengan least privilege, tidak dieksekusi sebagai script, dan dibersihkan sesuai kebijakan retensi ketika data terkait benar-benar boleh dihapus.

### 8.7 Error Handling

Gunakan exception untuk kondisi gagal yang bermakna. Try-catch dipakai bila class tersebut dapat menambahkan konteks, memulihkan kegagalan, menerjemahkan exception, atau menjamin cleanup; tidak boleh digunakan untuk menyembunyikan kesalahan.

Error pengguna harus ramah, tidak mengungkapkan stack trace, SQL, secret, atau detail keamanan. Gunakan flash message untuk hasil tindakan normal seperti berhasil disimpan atau validasi gagal. Kesalahan tak terduga dicatat melalui logging dengan context aman dan diberi correlation/reference bila diperlukan untuk investigasi.

## 9. Security Rules

- Gunakan CSRF Laravel untuk setiap form berbasis session; jangan menonaktifkan middleware CSRF secara global.
- Password menggunakan hashing Laravel; password dan token sensitif tidak boleh dicatat pada log atau disimpan plaintext.
- Terapkan rate limiter pada login, reset password, upload, dan endpoint sensitif.
- Terapkan role middleware untuk area Admin/Owner/Customer serta policy untuk kepemilikan resource.
- Email verification wajib bagi Customer sebelum transaksi; Face Verification diterapkan untuk Admin/Owner sesuai keputusan privasi dan prosedur fallback.
- Semua input tervalidasi dengan Form Request; seluruh output Blade di-escape secara default; HTML mentah hanya diizinkan setelah sanitasi yang disetujui.
- Gunakan Eloquent/query builder dengan parameter binding. Query mentah harus sangat terbatas, menggunakan binding, dan melalui review.
- Terapkan mass assignment protection; model mendefinisikan field yang aman diisi secara eksplisit. Jangan memakai unguard global pada aplikasi production.
- Session memakai HTTPS di production, cookie aman, regenerasi setelah login, timeout sesuai risiko, dan invalidasi pada logout/perubahan keamanan.
- Tindakan kritis wajib memiliki Activity Log; login, gagal login, IP, device, dan perubahan sesi dicatat sesuai kebijakan privasi/retensi.
- Secret diletakkan pada `.env` atau secret manager, tidak dalam repository, view, log, atau pesan error.
- `APP_DEBUG` wajib nonaktif di production. Folder web root hanya `public`; file privat/bukti pembayaran tidak boleh dapat diakses bebas.
- Dependency dan package harus ditinjau secara periodik untuk pembaruan keamanan; package tidak terpelihara tidak boleh menjadi ketergantungan kritis tanpa mitigasi.

## 10. Performance Rules

- Semua daftar besar wajib pagination, termasuk produk, order, customer, stok, report, dan log.
- Gunakan eager loading untuk relasi yang dirender bersama agar tidak menimbulkan N+1 query. Review query pada halaman daftar/detail utama.
- Jangan memanggil database dari Blade atau melakukan query berulang dalam loop.
- Ambil hanya kolom yang diperlukan; gunakan filter, sort, dan scope yang terukur.
- Setiap indeks database harus didasarkan pada query nyata dan diuji dampaknya terhadap baca/tulis.
- Gambar produk harus memakai thumbnail, resize proporsional, kompresi, dan ukuran tampilan yang sesuai. Galeri non-kritis dapat lazy load.
- Cache digunakan secara selektif untuk data baca yang stabil. Setiap cache wajib memiliki key, TTL, scope, dan strategi invalidasi yang jelas.
- Gunakan route cache, config cache, view cache, dan autoloader optimization pada deployment production sesuai prosedur rilis.
- Proses lambat seperti email, ekspor besar, dan pengolahan gambar berat dapat dipindahkan ke queue ketika volume membutuhkannya.
- Monitor error, respons lambat, query lambat, storage, dan kegagalan job. Optimasi dilakukan berdasarkan pengukuran, bukan asumsi.
- Target awal dashboard dan daftar produk adalah kurang dari dua detik pada kondisi penggunaan normal yang disetujui.

## 11. Logging Rules

| Catatan              | Dibuat ketika                                                                                                                                                |
| -------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Activity Log         | Terjadi perubahan penting terhadap data atau tindakan administratif: produk, kategori, harga, status, akun, order, pembayaran, dan konfigurasi yang relevan. |
| Stock History        | Setiap stok bertambah, berkurang, disesuaikan, atau dipulihkan; catat kuantitas, alasan, referensi proses, pelaku, dan waktu.                                |
| Login History        | Login berhasil, dengan user, waktu, IP, perangkat/browser yang diizinkan kebijakan, dan informasi sesi yang diperlukan.                                      |
| Failed Login History | Percobaan autentikasi yang gagal atau dibatasi, secara aman tanpa merekam password/token.                                                                    |
| Security Event       | Aktivitas berisiko: perubahan password, reset, logout sesi, akses ditolak, Face Verification, perubahan role, atau kejadian limiter.                         |
| Application Log      | Exception, kegagalan integrasi, job, atau kondisi operasional yang memerlukan investigasi.                                                                   |

Log harus bersifat append-oriented, memiliki timestamp dan actor/context yang cukup, serta tidak memuat password, token, secret, data biometrik mentah, atau payload pribadi yang tidak diperlukan. Retensi, akses, dan penghapusan log mengikuti kebutuhan audit dan prinsip minimisasi data.

## 12. UI Rules

Aturan UI ini menetapkan konsistensi implementasi Blade + Bootstrap, bukan rancangan visual final.

- Gunakan layout Blade bersama, component/partial yang dapat dipakai ulang, dan struktur halaman konsisten antar area.
- Gunakan token warna/utility Bootstrap yang konsisten; warna status tidak boleh menjadi satu-satunya indikator—sertakan label atau ikon yang dapat diakses.
- Gunakan tipografi, ukuran, spacing, grid, tabel, button, badge, alert, modal, dan toast secara konsisten; jangan membuat variasi bebas per halaman.
- Button harus menggunakan kata kerja yang jelas, memiliki state disabled/loading bila aksi berjalan, serta mencegah submit ganda bila relevan.
- Tabel harus memiliki header bermakna, pagination, empty state, dan tindakan yang tidak ambigu; data sensitif tidak ditampilkan lebih luas dari hak aksesnya.
- Form harus menunjukkan label, bantuan, error validasi, required state, dan instruksi unggah yang jelas.
- Semua halaman penting harus responsif pada layar mobile dan desktop, terutama katalog serta proses checkout.
- Sediakan loading state untuk operasi asynchronous/lama dan empty state yang menjelaskan tindakan berikutnya.
- Accessibility minimum: HTML semantik, focus state, keyboard access untuk kontrol utama, kontras memadai, dan teks alternatif gambar informatif.

Business logic, query basis data, authorization, atau perhitungan bisnis tidak boleh ditempatkan di Blade.

## 13. Git Rules

| Area         | Standar                                                                                                                                                                                     |
| ------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Branch       | `main` hanya berisi rilis stabil. Gunakan branch pendek: `feature/...`, `fix/...`, `chore/...`, `docs/...`, atau `refactor/...`. Branch `develop` opsional bila workflow tim memerlukannya. |
| Commit       | Pesan imperatif dan terarah: `feat: add pickup order workflow`, `fix: prevent negative stock`, `docs: define review checklist`. Satu commit memuat satu perubahan yang logis.               |
| Pull request | Wajib atau sangat direkomendasikan sebelum merge ke branch terlindungi. Jelaskan tujuan, aturan bisnis yang terdampak, pengujian, risiko, dependency, dan perubahan dokumentasi.            |
| Review       | Perubahan keamanan, order, payment, stok, authorization, migration, atau package harus direview oleh pihak yang memahami area tersebut.                                                     |
| Version      | Gunakan semantic versioning untuk rilis yang diberi tag: MAJOR untuk perubahan tidak kompatibel, MINOR untuk fitur kompatibel, PATCH untuk perbaikan kompatibel.                            |
| Release      | Rilis dibuat dari commit yang sudah diuji; catat versi, perubahan, risiko, langkah deploy, rollback, dan status backup.                                                                     |
| Hygiene      | Jangan commit `.env`, secret, storage runtime, vendor, node_modules, atau artefak lokal. Commit lockfile dependency.                                                                        |

## 14. Documentation Rules

Setiap fitur wajib memiliki atau memperbarui dokumentasi yang proporsional terhadap risikonya:

- **Requirement:** tujuan, aktor, acceptance criteria, dan scope fitur.
- **Business rule:** aturan validasi, status, otorisasi, dan pengecualian bisnis.
- **Flow:** uraian proses normal, gagal, dan edge case penting tanpa menyamakan flow dengan implementasi UI.
- **Controller/endpoint:** tanggung jawab request/response dan otorisasi yang relevan.
- **Data:** entitas/relasi/data lifecycle yang terdampak, tanpa menggantikan dokumen rancangan data resmi.
- **Testing:** skenario unit/feature, hasil yang diharapkan, dan data uji penting.
- **Operational note:** SOP atau implikasi bagi Admin/Owner bila terdapat perubahan proses.

Dokumentasi disimpan dalam `docs/` dan diperbarui pada pull request yang mengubah perilaku bisnis, keamanan, kontrak data, atau operasional. README hanya berisi instruksi tingkat proyek; jangan jadikan README sebagai tempat seluruh requirement fitur.

## 15. Definition of Ready

Sebuah fitur boleh mulai dikembangkan apabila seluruh syarat berikut terpenuhi:

- Tujuan bisnis, aktor, scope, dan prioritas telah disetujui.
- User story/use case dan acceptance criteria dapat dipahami serta dapat diuji.
- Aturan bisnis, status, data input/output, dan otorisasi utama telah dijelaskan.
- Dependensi, risiko keamanan, kebutuhan upload/integrasi, serta dampak data telah diidentifikasi.
- Desain UI/UX atau pola halaman yang diperlukan tersedia atau dapat diturunkan dari standar yang disetujui.
- Keputusan yang memengaruhi arsitektur, teknologi, atau privasi telah dibuat atau memiliki owner dan batas waktu jelas.
- Estimasi kerja dan strategi pengujian tersedia.
- Tidak ada blocker kritis yang membuat implementasi hanya berdasarkan asumsi berisiko.

## 16. Definition of Done

Fitur dianggap selesai hanya apabila:

- Acceptance criteria terpenuhi dan diverifikasi.
- Kode mengikuti Development Bible, lulus format/lint yang berlaku, dan tidak menyisakan debug/dead code.
- Form Request, service, authorization, transaction, dan logging diterapkan bila relevan.
- Pengujian unit/feature yang sesuai telah ditambahkan atau diperbarui dan berhasil dijalankan.
- Skenario gagal, input tidak valid, dan akses tanpa izin telah ditangani.
- Performa dan N+1 query telah ditinjau pada area yang terdampak.
- Upload, error, keamanan, dan audit telah dipertimbangkan bila fitur menyentuh area tersebut.
- Dokumentasi, catatan konfigurasi, dan migration/seed/factory (jika ada pada tahap implementasi) telah diperbarui.
- Pull request telah direview/disetujui sesuai kebijakan dan tidak memiliki temuan kritis terbuka.
- Fitur dapat dideploy tanpa langkah manual tersembunyi dan memiliki rollback/mitigasi yang sesuai risiko.

## 17. Code Review Checklist

Reviewer menggunakan checklist berikut sesuai relevansi perubahan:

- [ ] Requirement, acceptance criteria, dan aturan bisnis dipenuhi.
- [ ] Nama class, method, variable, route, view, dan file konsisten dengan standar.
- [ ] Controller tipis; business logic berada pada service/domain yang tepat.
- [ ] Form Request digunakan dan validasi server-side lengkap.
- [ ] Authorization memakai middleware dan/atau policy yang tepat.
- [ ] Tidak ada mass assignment, hardcode secret, debug output, atau data sensitif di log.
- [ ] Transaksi basis data digunakan untuk perubahan order/payment/stok yang atomik.
- [ ] Stock History, Activity Log, dan audit terkait dibuat bila proses mewajibkannya.
- [ ] Query menggunakan eager loading bila perlu; tidak ada N+1 atau query di Blade.
- [ ] Pagination, index implication, dan beban query diperiksa untuk data daftar/laporan.
- [ ] File upload aman: authorization, validasi MIME/ukuran/dimensi, storage, serta akses privat bila perlu.
- [ ] Error user-friendly dan logging memiliki context yang aman.
- [ ] Pengujian relevan tersedia dan mencakup kasus berhasil, gagal, serta akses tidak sah.
- [ ] UI mengikuti layout/component, responsive, accessibility, loading, dan empty state yang disepakati.
- [ ] Tidak ada duplikasi yang merugikan, package tidak perlu, atau abstraction tanpa alasan.
- [ ] Dokumentasi dan catatan operasional diperbarui.

## 18. Anti Pattern

Hal-hal berikut dilarang atau memerlukan pengecualian tertulis yang sangat kuat:

- Controller sangat panjang atau berisi query kompleks, perhitungan bisnis, upload, dan perubahan status lintas entitas.
- Query basis data atau authorization di Blade/view.
- Duplikasi logika bisnis pada controller, job, command, view, atau beberapa fitur.
- Hardcode role, status, path, URL, credential, angka bisnis, atau konfigurasi environment di banyak lokasi.
- Business logic di JavaScript atau Blade yang tidak divalidasi kembali oleh server.
- Membuat `Repository` atau `Service` generik yang tidak memiliki tanggung jawab domain jelas.
- Mengubah stok, order, atau pembayaran terkait tanpa transaction dan audit yang sesuai.
- Mengabaikan soft delete/lifecycle data ketika data master atau referensi masih dipakai riwayat transaksi.
- Memvalidasi hanya di frontend atau memercayai input/status dari browser.
- Menggunakan query mentah tanpa parameter binding atau mengabaikan N+1 query.
- Menyajikan bukti pembayaran/file privat secara publik tanpa authorization.
- Menangkap exception lalu mengabaikannya, mengembalikan sukses palsu, atau menyembunyikan error dari log.
- Menonaktifkan CSRF, rate limiting, middleware, atau policy untuk "mempermudah" pengembangan.
- Menambahkan package, framework frontend, API publik, atau arsitektur terdistribusi sebelum ada kebutuhan disetujui.
- Merge langsung ke branch rilis tanpa review/pengujian yang sesuai risiko.

## 19. Future Scalability

Fondasi MVC, service layer, policy, Eloquent, filesystem abstraction, dan dokumentasi domain memungkinkan pengembangan berikut tanpa mengganti arsitektur inti:

| Pengembangan               | Cara tetap selaras dengan fondasi                                                                                                                      |
| -------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| REST API                   | Service/use case yang tidak bergantung pada Blade dapat dipanggil oleh controller API; tambah versioning, token auth, rate limit, dan dokumentasi API. |
| Mobile App                 | Flutter atau klien lain menggunakan API terkontrol tanpa menduplikasi aturan order, stok, dan payment.                                                 |
| Multi Branch               | Perluas konteks data dan authorization dengan konsep cabang; pertahankan service transaksi, audit, dan reporting sebagai domain inti.                  |
| Supplier Module            | Tambah domain pengadaan yang terpisah tetapi berintegrasi melalui aturan stok dan audit yang sama.                                                     |
| Purchase Order             | Buat use case purchase order dan penerimaan barang yang mengubah stok melalui StockService/riwayat yang terkontrol.                                    |
| Payment Gateway            | Tambah adapter gateway, webhook idempoten, rekonsiliasi, dan audit tanpa mengganti order/payment domain existing.                                      |
| Barcode Scanner            | Standarkan SKU/barcode pada domain product dan hubungkan scan ke service stok/serah terima.                                                            |
| Queue/cache/object storage | Ganti atau tambah adapter infrastruktur untuk pekerjaan asinkron, cache, dan media tanpa mengubah aturan bisnis.                                       |

Skalabilitas bukan alasan untuk membangun semua fitur sejak awal. Setiap pengembangan baru harus melalui scope, requirement, security review, dan pengujian sesuai Development Bible ini.

## 20. Conclusion

Development Bible ini menetapkan cara kerja resmi untuk membangun Website Toko ATK secara konsisten, aman, dan berkelanjutan. Prioritasnya adalah kejelasan aturan bisnis, controller yang tipis, service yang dapat diuji, akses yang ketat, data transaksi yang konsisten, audit yang dapat ditelusuri, dan optimasi berdasarkan pengukuran.

Seluruh implementasi setelah dokumen ini harus mematuhi standar yang ditetapkan atau memiliki pengecualian tertulis dan disetujui. Dokumen ini tidak berisi source code, rancangan database, migration, controller, model, route, atau UI implementatif; ia menjadi pedoman sebelum dan selama tahap coding.
