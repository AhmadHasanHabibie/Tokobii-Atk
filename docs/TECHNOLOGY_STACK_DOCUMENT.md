# Technology Stack Document

## Website Toko Alat Tulis Kantor (ATK)

| Informasi | Keputusan |
| --- | --- |
| Jenis sistem | Website penjualan internal UMKM, satu toko |
| Framework utama | Laravel 10 |
| Target | Production ready dengan arsitektur yang mudah dipelihara |
| Pemenuhan pesanan | Pick-Up Only |
| Pembayaran | COD dan QRIS manual dengan unggah bukti |
| Dokumen terkait | Vision Project, BRD, Project Scope, Stakeholder Analysis |
| Status | Standar teknis awal sebelum SRS dan desain teknis rinci |

## 1. Executive Summary

Stack teknologi proyek dipilih untuk menghasilkan aplikasi web yang stabil, aman, ekonomis, dan mudah dipelihara oleh tim UMKM. Laravel 10 menjadi fondasi backend; Blade, Bootstrap 5, HTML5, CSS3, JavaScript vanilla, dan Vite digunakan untuk antarmuka; MySQL digunakan untuk data relasional transaksi. Pilihan ini memanfaatkan ekosistem Laravel secara utuh tanpa menambah kompleksitas frontend atau infrastruktur yang belum diperlukan.

Rekomendasi baseline adalah PHP 8.3 dan Composer 2.x stable. Laravel 10 mensyaratkan setidaknya PHP 8.1 dan Composer 2.2, sehingga baseline tersebut tetap kompatibel sambil menyediakan lingkungan PHP yang lebih modern. Versi patch dari seluruh dependency harus dikunci melalui `composer.lock` dan diperbarui secara terkontrol setelah pengujian kompatibilitas.

> Catatan siklus hidup: Laravel 10 merupakan keputusan scope proyek. Sebelum rilis production jangka panjang, tim harus membuat rencana upgrade framework ke versi Laravel yang masih didukung. Keputusan ini tidak mengubah scope bisnis versi pertama.

## 2. Technology Overview

| Lapisan | Teknologi standar | Alasan utama |
| --- | --- | --- |
| Backend | PHP 8.3, Laravel 10, Composer 2.x stable | Konvensi kuat, ekosistem matang, keamanan dan tooling Laravel. |
| Frontend | Blade, Bootstrap 5, HTML5, CSS3, JavaScript vanilla, Vite | Ringan, cepat dipelihara, dan sesuai aplikasi web server-rendered. |
| Data | MySQL 8.0+, InnoDB, utf8mb4 | Andal untuk data transaksi relasional serta dukungan foreign key dan indeks. |
| Auth | Laravel Breeze, email verification, session-based authentication | Starter kit resmi dan selaras dengan pola Laravel. |
| PDF/export | `barryvdh/laravel-dompdf`, `maatwebsite/excel` | Kebutuhan invoice/receipt/report dan ekspor Excel/CSV. |
| Gambar | Intervention Image v3 atau layanan image processor yang kompatibel | Thumbnail, resize, kompresi, dan validasi media. |
| Email | Laravel Mail melalui SMTP | Konfigurasi umum, portabel, dan mudah ditelusuri. |
| Version control | Git dan GitHub | Riwayat perubahan, kolaborasi, code review, serta rilis. |

## 3. Backend Stack

### 3.1 Laravel 10

Laravel 10 adalah framework aplikasi utama. Framework ini menyediakan struktur untuk routing, middleware, validasi, autentikasi, otorisasi, ORM, filesystem, mail, cache, queue, logging, testing, dan konfigurasi berbasis environment. Kemampuan tersebut cocok untuk kebutuhan toko: transaksi order, perubahan stok yang dapat diaudit, kontrol peran, dan proses administratif.

Laravel dipilih karena:

- Konvensi proyek yang kuat sehingga kode lebih konsisten dan mudah dirawat.
- Dukungan bawaan untuk keamanan web umum, validasi, hashing password, CSRF, rate limiting, serta session.
- Ekosistem package dan dokumentasi yang luas untuk kebutuhan PDF, ekspor, gambar, dan deployment.
- Dukungan pengujian serta struktur yang memungkinkan service layer dan modularisasi domain bisnis.
- Cocok untuk aplikasi monolitik modular pada tahap UMKM; tidak memerlukan arsitektur microservices pada versi pertama.

### 3.2 PHP

**Versi rekomendasi: PHP 8.3.x.** Laravel 10 membutuhkan PHP 8.1 atau lebih baru. PHP 8.3 dipilih sebagai baseline karena kompatibel dengan Laravel 10, menyediakan fitur dan perbaikan modern, serta lebih layak sebagai versi produksi dibanding memakai batas minimum. Versi PHP lokal, staging, dan production harus sama pada versi minor sejauh praktis untuk mencegah perbedaan perilaku.

Ekstensi server minimum harus memenuhi persyaratan Laravel 10, termasuk Ctype, cURL, DOM, Fileinfo, Filter, Hash, Mbstring, OpenSSL, PCRE, PDO, Session, Tokenizer, dan XML. Kebutuhan tambahan seperti driver gambar harus ditentukan setelah pilihan pemrosesan gambar disahkan.

### 3.3 Composer

**Versi rekomendasi: Composer 2.x stable, minimal 2.2.0.** Composer mengelola dependency PHP, autoloading, dan versi package. Hanya dependency stabil yang kompatibel dengan PHP/Laravel yang dipilih boleh digunakan. File `composer.lock` wajib tersimpan pada repository agar instalasi development, staging, dan production reproducible.

Package baru harus dievaluasi berdasarkan kompatibilitas, reputasi pemeliharaan, lisensi, kebutuhan keamanan, dampak performa, dan kebutuhan bisnis. Package tidak boleh ditambahkan hanya untuk fungsi yang dapat disediakan secara sederhana oleh Laravel atau kode proyek yang teruji.

## 4. Frontend Stack

| Teknologi | Peran | Alasan pemilihan |
| --- | --- | --- |
| Blade | Template server-side Laravel | Terintegrasi langsung dengan routing, session, validation error, middleware, dan data backend; tidak membutuhkan API terpisah pada V1. |
| Bootstrap 5 | Sistem komponen dan layout CSS | Mempercepat antarmuka konsisten yang responsif dengan kurva belajar rendah. |
| HTML5 | Struktur semantik halaman | Mendukung aksesibilitas, formulir, dan browser modern. |
| CSS3 | Penyesuaian visual proyek | Memungkinkan branding dan responsivitas tanpa ketergantungan UI framework tambahan. |
| JavaScript vanilla | Interaksi ringan di browser | Menghindari kompleksitas framework SPA untuk kebutuhan seperti preview unggahan, konfirmasi, dan interaksi sederhana. |
| Vite | Build tool aset | Menyediakan hot module replacement saat development dan bundling aset yang efisien saat production. |

Frontend menggunakan pendekatan server-rendered. Vue.js atau React tidak menjadi bagian V1 karena kebutuhan transaksi dapat dipenuhi dengan Blade + Bootstrap, sementara pengurangan kompleksitas build, state management, API, dan debugging lebih bernilai bagi UMKM. JavaScript pihak ketiga harus diminimalkan dan disematkan hanya dari sumber tepercaya.

## 5. Database Stack

**DBMS standar: MySQL 8.0 atau versi MySQL yang didukung penyedia hosting dan kompatibel dengan Laravel 10.** MySQL cocok untuk model data relasional seperti pengguna, produk, kategori, stok, order, pembayaran, rating, serta audit.

| Aspek | Standar teknis |
| --- | --- |
| Storage engine | InnoDB untuk seluruh tabel transaksi dan master data karena mendukung transaksi ACID, foreign key, serta pemulihan yang lebih andal. |
| Charset | `utf8mb4` untuk mendukung karakter Unicode secara lengkap. |
| Collation | `utf8mb4_unicode_ci` sebagai baseline kompatibel; collation yang lebih baru boleh dipilih bila tersedia konsisten pada seluruh environment dan telah diuji. |
| Index strategy | Indeks dibuat berdasarkan pola filter, pencarian, relasi, pengurutan, dan laporan; terutama identifier order, SKU, foreign key, status, tanggal transaksi, dan kolom pencarian yang disetujui pada desain data. Hindari indeks berlebihan karena memperlambat penulisan. |
| Foreign key strategy | Gunakan foreign key pada relasi inti untuk menjaga integritas referensial. Kebijakan penghapusan harus eksplisit; data transaksi/audit umumnya tidak dihapus secara sembarang dan perubahan kritis harus dapat dilacak. |
| Transaksi | Proses yang mengubah order, pembayaran, atau stok harus memperhatikan atomicity dan konsistensi agar stok tidak negatif atau status tidak parsial. |
| Backup | Backup otomatis terjadwal, enkripsi penyimpanan backup bila tersedia, retensi yang disetujui, serta uji pemulihan berkala. |

Dokumen ini hanya menetapkan standar stack dan strategi data; rancangan tabel, kolom, migration, dan skema bukan bagian dari dokumen ini.

## 6. Authentication Stack

**Rekomendasi: Laravel Breeze dengan autentikasi berbasis session.** Laravel Breeze adalah starter kit autentikasi yang ringkas dan mengikuti konvensi Laravel. Pilihan ini sesuai untuk Blade, memudahkan maintenance, serta tidak membawa kompleksitas fitur yang belum dibutuhkan.

| Fitur | Kebijakan penggunaan |
| --- | --- |
| Login | Diperlukan untuk Customer, Owner, dan Admin sesuai hak akses. |
| Register | Tersedia untuk Customer; akun Owner dibuat/dikelola Admin. |
| Email Verification | Wajib bagi Customer sebelum menjalankan transaksi. |
| Forgot/Reset Password | Tersedia melalui mekanisme reset password Laravel berbasis email. |
| Middleware | Memastikan halaman/fungsi terautentikasi hanya diakses pengguna yang sesuai. |
| Session | Menggunakan session Laravel yang aman; konfigurasi cookie, masa berlaku, invalidasi, dan regenerasi sesi diterapkan sesuai environment. |

Kredensial tidak boleh disimpan dalam bentuk teks biasa. Password menggunakan hashing bawaan Laravel. Pesan autentikasi harus cukup informatif untuk pengguna tetapi tidak membuka informasi yang membantu enumerasi akun atau serangan.

## 7. Authorization Strategy

Authorization memakai Role-Based Access Control (RBAC) dengan empat keadaan pengguna: Guest, Customer, Owner, dan Admin.

| Peran | Ruang otorisasi utama |
| --- | --- |
| Guest | Hanya konten publik: landing page, katalog, pencarian, detail produk, login, dan register. |
| Customer | Data profil dan transaksi milik sendiri: cart, checkout, pembayaran, bukti QRIS, riwayat/timeline order, dan rating yang memenuhi aturan. |
| Owner | Operasional toko: produk, kategori, stok, Stock History sesuai kebijakan, verifikasi QRIS, perubahan status order, dashboard, dan laporan operasional. |
| Admin | Administrasi penuh: data utama, akun Customer/Owner, laporan, dashboard, Security Center, audit, serta pengawasan seluruh order. |

Middleware menjadi gerbang pada level request untuk memastikan pengguna sudah login, memiliki role yang tepat, email telah terverifikasi bila diperlukan, serta memenuhi Face Verification pada fungsi atau sesi Admin/Owner. Untuk aturan yang bergantung pada kepemilikan data—misalnya Customer hanya melihat order miliknya—gunakan policy/authorization check pada level resource. Kombinasi middleware dan policy mencegah akses lintas pengguna meskipun URL dimanipulasi.

Permission granular dan matriks role-permission final akan disahkan pada SRS. Prinsip yang wajib dipatuhi adalah *least privilege*, *deny by default*, dan audit untuk tindakan berisiko tinggi.

## 8. Face Verification

Face Verification hanya ditujukan bagi Admin dan Owner sebagai kontrol tambahan, bukan sebagai pengganti password, session, atau authorization. Implementasi wajib memperhatikan persetujuan pengguna, dasar pemrosesan data biometrik, retensi data minimum, akses terbatas, enkripsi, penghapusan aman, dan prosedur fallback apabila verifikasi gagal. Teknologi pengenalan wajah tidak boleh dipakai hanya untuk menentukan identitas tanpa proteksi liveness/anti-spoofing yang memadai.

| Alternatif | Kelebihan | Keterbatasan | Kesesuaian |
| --- | --- | --- | --- |
| Face-api.js | Berjalan di browser, integrasi JavaScript relatif mudah, dapat mengurangi pengiriman citra ke server. | Akurasi dan liveness perlu divalidasi; performa bergantung perangkat/browser; pemeliharaan model dan keamanan browser perlu perhatian. | Layak untuk prototipe atau verifikasi sederhana dengan persetujuan yang kuat. |
| InsightFace | Kualitas pengenalan wajah tinggi dan ekosistem model luas. | Umumnya memerlukan layanan Python/AI terpisah, GPU/CPU yang memadai, MLOps, serta keahlian operasional tambahan. | Kuat secara teknis tetapi berlebihan untuk V1 UMKM. |
| DeepFace | API Python yang memudahkan eksperimen dengan beberapa model backend. | Memerlukan service terpisah, pengelolaan dependency model, serta validasi keamanan/performa. | Baik untuk proof of concept, tetapi bukan pilihan default V1. |

**Rekomendasi V1:** lakukan studi kelayakan dan penilaian privasi terlebih dahulu. Bila Face Verification tetap merupakan persyaratan wajib, pilih pendekatan browser-side berbasis Face-api.js hanya untuk verifikasi tambahan yang sederhana, dengan persetujuan eksplisit, data biometrik minimal, liveness challenge, dan fallback review manual. Jangan menyimpan gambar mentah lebih lama dari yang diperlukan. InsightFace atau DeepFace dipertimbangkan pada fase lanjutan hanya bila kebutuhan akurasi, volume, dan sumber daya operasional membenarkan service AI terpisah.

## 9. QRIS

QRIS pada V1 tidak memakai payment gateway. Alur yang distandarkan adalah: Customer melihat QR pembayaran, melakukan scan melalui aplikasi pembayaran miliknya, mengunggah bukti pembayaran, lalu Owner memverifikasi bukti dan memperbarui status sesuai prosedur order.

Pendekatan ini dipilih karena:

- Mengurangi biaya dan kompleksitas integrasi gateway pada tahap awal.
- Sesuai dengan operasi UMKM yang masih dapat melakukan verifikasi pembayaran secara manual.
- Memungkinkan toko meluncurkan proses QRIS tanpa mengandalkan webhook, rekonsiliasi API, atau kontrak gateway.
- Memberi Owner kontrol atas validasi transaksi sebelum barang disiapkan.

Konsekuensinya, sistem harus mengatur status pesanan/pembayaran dengan jelas, membatasi tipe dan ukuran unggahan bukti, menyimpan bukti secara aman, mencatat tindakan verifikasi, dan menampilkan instruksi yang mudah dipahami. Pendekatan manual dapat menimbulkan keterlambatan atau risiko bukti palsu; proses verifikasi serta Activity Log menjadi kontrol wajib. Integrasi payment gateway diposisikan sebagai future enhancement.

## 10. PDF dan Export Stack

| Kebutuhan | Rekomendasi | Alasan dan batasan |
| --- | --- | --- |
| Receipt, invoice, report PDF | `barryvdh/laravel-dompdf` | Integrasi Laravel luas dan cukup untuk dokumen bisnis berbasis HTML sederhana. Layout PDF harus diuji untuk Bahasa Indonesia, ukuran kertas, serta halaman panjang. |
| Export Excel | `maatwebsite/excel` | Standar de facto ekosistem Laravel untuk ekspor spreadsheet dan kebutuhan laporan tabular. |
| Export CSV | `maatwebsite/excel` atau respons CSV native Laravel | CSV sederhana dapat dihasilkan secara efisien; pilih mekanisme berdasarkan volume data dan kebutuhan formatting. |

PDF adalah format keluaran, bukan sistem penyimpanan data utama. Pembuatan laporan besar sebaiknya menggunakan chunking/queue apabila volume data telah memengaruhi respons web. Package dan versi final harus diuji terhadap PHP 8.3 serta Laravel 10 sebelum dikunci.

## 11. Image Processing

Strategi gambar berlaku untuk thumbnail dan galeri produk:

- Unggah melalui validasi server-side terhadap autentikasi, izin, MIME type, ekstensi, ukuran berkas, dimensi, dan jumlah gambar.
- Simpan berkas pada filesystem Laravel dengan nama yang tidak dapat ditebak; jangan gunakan nama asli pengguna sebagai identifier publik.
- Pisahkan aset publik yang aman dari berkas privat seperti bukti pembayaran QRIS bila kebijakan akses mengharuskannya.
- Buat varian thumbnail dan ukuran tampilan produk saat unggah; gunakan ukuran yang sesuai pada daftar produk untuk mengurangi bandwidth.
- Resize dengan proporsi terjaga dan lakukan kompresi yang mempertahankan kualitas bisnis yang memadai.
- Gunakan format modern bila dukungan hosting/browser telah divalidasi, sambil menjaga fallback yang sesuai.
- Batasi ukuran file dan terapkan kuota/retensi bila diperlukan agar storage terprediksi.
- Hindari mengeksekusi atau menyajikan file unggahan sebagai skrip; akses file privat harus melalui otorisasi aplikasi.

**Rekomendasi library:** Intervention Image v3 dengan driver yang tersedia di server, setelah konfirmasi kompatibilitas environment. Jika pemrosesan gambar menjadi berat, pertimbangkan queue atau image CDN pada fase lanjutan, bukan pada fondasi V1.

## 12. Email Stack

Email menggunakan Laravel Mail melalui SMTP. SMTP digunakan untuk verifikasi email, reset password, dan pemberitahuan transaksional yang disetujui pada scope proyek.

Konfigurasi umum harus mencakup host SMTP, port, jenis enkripsi TLS/SSL sesuai penyedia, username, password atau API credential khusus SMTP, alamat pengirim, dan nama pengirim. Semua secret hanya disimpan pada environment variable/secret manager, tidak pernah dikomit ke repository. Environment local menggunakan akun/sandbox terpisah dari production.

Pengiriman email perlu logging yang memadai tanpa membocorkan token atau isi sensitif. Bila volume bertambah, mail dapat dipindahkan ke queue untuk menjaga respons checkout. Deliverability, SPF/DKIM/DMARC, serta limit pengiriman bergantung pada domain dan penyedia email yang dipilih saat deployment.

## 13. Security Stack

| Kontrol | Penerapan standar |
| --- | --- |
| CSRF | Gunakan proteksi CSRF Laravel pada form berbasis session; jangan menonaktifkannya tanpa alasan yang disetujui. |
| Password hash | Gunakan hashing bawaan Laravel dengan algoritma yang didukung environment; jangan membuat algoritma sendiri atau menyimpan password plaintext. |
| Session | Cookie aman (`secure` di HTTPS), `httpOnly`, `sameSite` yang sesuai, regenerasi session setelah login, logout/invalidation, dan pengelolaan sesi. |
| Rate limiter | Batasi login, reset password, unggahan, dan endpoint sensitif untuk mengurangi brute force maupun penyalahgunaan. |
| Middleware dan policy | Terapkan authentication, verified email, role, Face Verification, serta pemeriksaan kepemilikan resource. |
| Validation | Semua input divalidasi server-side, termasuk data form, identifier, status bisnis, dan file upload. Client-side validation hanya pelengkap UX. |
| XSS protection | Escape output Blade secara default, sanitasi konten yang diizinkan, hindari injeksi HTML/JavaScript mentah, dan terapkan header keamanan yang sesuai hosting. |
| SQL injection protection | Gunakan query builder/Eloquent dengan parameter binding; hindari query mentah. Jika query mentah benar-benar perlu, gunakan binding dan review. |
| Audit | Activity Log, Login/IP/Device/Failed Login History, serta Stock History untuk tindakan penting. |
| Transport dan operasi | HTTPS/SSL wajib di production, `APP_DEBUG=false`, secret dipisahkan dari kode, akses server dibatasi, backup diuji, dan dependency diperbarui terkontrol. |

Security review perlu dilakukan sebelum production, terutama pada akses Admin/Owner, unggahan bukti pembayaran/gambar, akses file privat, Face Verification, dan perubahan stok/order.

## 14. Performance Strategy

| Strategi | Penerapan |
| --- | --- |
| Pagination | Wajib pada daftar produk, tabel administrasi, riwayat, laporan, dan log untuk menghindari pemuatan data berlebihan. |
| Eager loading | Gunakan pada relasi yang diperlukan oleh daftar/detail untuk menghindari N+1 query. |
| Database index | Rancang indeks berdasarkan query nyata: identifier, relasi, status, tanggal, pencarian, dan filter yang sering digunakan. Pantau dampak indeks. |
| Cache | Gunakan cache selektif untuk data yang sering dibaca tetapi jarang berubah, dengan strategi invalidasi saat data berubah. Jangan cache data transaksi sensitif secara tidak aman. |
| Image optimization | Gunakan thumbnail, resize, kompresi, dan aset yang sesuai tampilan. |
| Query optimization | Ambil kolom yang perlu, batasi relasi, gunakan pagination, ukur query lambat, dan hindari logika query di view. |
| Route/config/view cache | Aktifkan cache route, config, dan view pada deployment production setelah environment tervalidasi. |
| Lazy loading | Gunakan hanya untuk konten non-kritis di sisi browser, seperti galeri gambar; jangan menunda informasi yang diperlukan untuk checkout. |
| Queue | Siapkan sebagai opsi untuk email, ekspor laporan besar, dan pemrosesan gambar berat ketika beban meningkat. |
| Observability | Catat error dan ukur waktu respons, query lambat, penggunaan storage, serta kegagalan proses kritis. |

Target awal adalah respons dashboard dan product list di bawah 2 detik pada kondisi penggunaan normal yang disepakati. Target tersebut harus diuji dengan data representatif dan ditinjau ulang jika volume transaksi berubah.

## 15. Version Control

Git adalah standar pengelolaan kode sumber dan GitHub adalah layanan repository/kolaborasi yang direkomendasikan.

| Praktik | Standar |
| --- | --- |
| Branch strategy | `main` selalu merepresentasikan rilis stabil. Pengembangan dilakukan pada branch pendek dengan pola `feature/...`, `fix/...`, `chore/...`, atau `docs/...`; integrasi staging dapat menggunakan `develop` bila alur tim memerlukannya. |
| Commit convention | Gunakan pesan imperatif, ringkas, dan terarah, misalnya `feat: add product stock validation`, `fix: prevent inactive product checkout`, atau `docs: add technology stack`. Hindari commit campuran yang tidak terkait. |
| Pull request | Direkomendasikan untuk perubahan ke branch terlindungi. PR menjelaskan tujuan, dampak, pengujian, keamanan, dan screenshot bila perubahan UI di kemudian hari. |
| Review | Perubahan transaksi, akses, keamanan, migrasi, atau dependency harus ditinjau setidaknya oleh pihak yang memahami area terkait. |
| Repository hygiene | Jangan commit `.env`, secret, file upload, dependency vendor, atau artefak lokal. Commit `composer.lock` dan lockfile frontend. |

## 16. Development Environment

| Alat | Fungsi |
| --- | --- |
| Visual Studio Code | Editor utama untuk kode, konfigurasi, dokumentasi, linting, dan debugging. |
| Codex | Pendamping pengembangan untuk analisis, dokumentasi, implementasi, review, dan verifikasi sesuai instruksi tim. |
| Git | Kontrol versi lokal dan pengelolaan riwayat perubahan. |
| GitHub | Repository remote, kolaborasi, pull request, issue, dan otomasi bila diperlukan. |
| Composer 2.x | Dependency manager PHP dan autoloading Laravel. |
| Node.js LTS | Runtime untuk Vite dan asset tooling; gunakan versi LTS yang konsisten antar anggota tim. |
| npm | Pengelola dependency frontend untuk Bootstrap, Vite, dan aset terkait. |
| Laragon atau XAMPP | Server development lokal pada Windows. Laragon direkomendasikan karena praktis untuk PHP/MySQL; tim memilih satu baseline dan mendokumentasikan versinya. |
| MySQL 8.0+ | Basis data lokal yang sedekat mungkin dengan production. |
| Google Chrome | Pengujian browser, responsive view, DevTools, dan inspeksi network/performance. |

File `.env.example` menjadi kontrak konfigurasi non-rahasia. Setiap developer membuat `.env` lokal sendiri dan tidak membagikan credential production. Docker/Laravel Sail dapat dipertimbangkan bila tim membutuhkan konsistensi lintas mesin, tetapi bukan keharusan untuk V1 yang telah memilih Laragon/XAMPP sebagai lingkungan lokal utama.

## 17. Deployment Environment

| Komponen | Standar/rekomendasi |
| --- | --- |
| Hosting | Shared hosting yang memenuhi PHP 8.3, MySQL, SSL, cron, dan permission storage dapat digunakan pada beban awal. VPS direkomendasikan ketika perlu kontrol server, queue worker, Redis, monitoring, atau skalabilitas lebih baik. |
| Web server | Apache atau Nginx dengan document root diarahkan hanya ke folder `public` Laravel. |
| Domain dan DNS | Domain produksi dikelola oleh pihak berwenang; akses DNS dibatasi dan didokumentasikan. |
| SSL | HTTPS wajib; sertifikat harus diperbarui otomatis atau dipantau masa berlakunya. |
| Environment | Production memakai `APP_ENV=production` dan `APP_DEBUG=false`; secret dikelola di luar repository. |
| Backup | Backup database dan file penting terjadwal, disimpan di lokasi terpisah, memiliki retensi, serta diuji pemulihannya. |
| Monitoring | Pantau uptime, error aplikasi, kapasitas disk, database, mail, storage, dan masa berlaku SSL. |
| Storage | Storage aplikasi memiliki permission minimum; bukti pembayaran dan data privat tidak disajikan bebas sebagai aset publik. |
| SMTP | Gunakan penyedia SMTP terpercaya dengan credential khusus production dan pengaturan reputasi domain. |
| Deployment | Gunakan prosedur terdokumentasi: backup, install dependency tanpa dev dependency, build aset, clear/cache konfigurasi yang sesuai, health check, dan rencana rollback. |

Pilihan shared hosting atau VPS ditentukan oleh hasil estimasi trafik, kemampuan teknis pemeliharaan, anggaran, serta kebutuhan queue/monitoring. Kebutuhan saat ini dapat dimulai dari shared hosting yang memenuhi persyaratan, lalu ditingkatkan ke VPS tanpa mengubah arsitektur aplikasi inti.

## 18. Future Technology Roadmap

| Arah teknologi | Nilai dan prasyarat |
| --- | --- |
| REST API | Mendukung integrasi eksternal atau aplikasi klien; memerlukan versioning, token authentication, rate limit, dan dokumentasi API. |
| Flutter/mobile app | Memberi pengalaman mobile native setelah proses web stabil; dapat menggunakan API yang terdefinisi dengan baik. |
| Vue.js atau React | Dipertimbangkan jika interaktivitas antarmuka meningkat secara nyata; tidak menggantikan Blade tanpa alasan bisnis yang jelas. |
| Payment gateway | Mengotomasi konfirmasi pembayaran QRIS/kanal lain; memerlukan webhook, rekonsiliasi, keamanan secret, dan penanganan status idempoten. |
| WhatsApp notification | Memberi notifikasi status pesanan; memerlukan penyedia resmi, consent, template, dan kontrol biaya. |
| Multi branch | Mendukung toko/cabang tambahan; memerlukan pengembangan domain inventori, akses, dan pelaporan per cabang. |
| Barcode scanner | Mempercepat penerimaan, stok opname, dan serah terima; perlu standardisasi SKU/barcode. |
| Supplier dan Purchase Order | Mendigitalisasi pengadaan; dapat dikembangkan di atas data produk/stok yang sudah stabil. |
| Redis/queue | Membantu cache, job asinkron, dan skalabilitas ketika beban meningkat. |
| Object storage/CDN | Membantu skalabilitas dan kinerja media saat volume gambar/berkas tumbuh. |

Penambahan teknologi dilakukan hanya melalui evaluasi kebutuhan bisnis, biaya total kepemilikan, keamanan, kemampuan operasional, dan dampak terhadap arsitektur. Tidak semua teknologi masa depan perlu dibangun; daftar ini adalah arah yang kompatibel dengan baseline yang dipilih.

## 19. Technology Decision Summary

| Keputusan | Standar yang disetujui | Dasar keputusan |
| --- | --- | --- |
| Backend | Laravel 10 + PHP 8.3 + Composer 2.x stable | Kompatibilitas Laravel, kemudahan pemeliharaan, dan ekosistem PHP. |
| Frontend | Blade + Bootstrap 5 + Vite + JavaScript vanilla | Cepat, ringan, konsisten, dan cukup untuk kebutuhan V1. |
| Database | MySQL 8.0+, InnoDB, utf8mb4 | Kuat untuk transaksi relasional dan mudah dioperasikan. |
| Authentication | Laravel Breeze + session + email verification | Selaras dengan Laravel dan mencakup kebutuhan akun inti. |
| Authorization | RBAC melalui middleware dan policy | Memisahkan akses Admin, Owner, Customer, serta Guest secara aman. |
| Face Verification | Studi kelayakan; Face-api.js browser-side untuk V1 bila wajib | Paling proporsional untuk UMKM, dengan kewajiban privasi/liveness/fallback. |
| QRIS | Bukti bayar + verifikasi Owner, tanpa gateway | Kompleksitas dan biaya awal lebih rendah. |
| Dokumen | Dompdf + Laravel Excel | Mendukung PDF, Excel, dan CSV untuk kebutuhan operasional. |
| Gambar | Intervention Image v3 + Laravel filesystem | Memenuhi thumbnail/galeri dengan pola integrasi yang umum. |
| Deployment | Shared hosting sesuai syarat atau VPS saat kebutuhan meningkat | Memberi jalur biaya awal efisien dan peningkatan bertahap. |

## 20. Conclusion

Technology Stack ini menetapkan standar teknis yang proporsional untuk Website Toko ATK: Laravel 10 sebagai backend monolitik modular, Blade + Bootstrap 5 sebagai frontend server-rendered, MySQL sebagai penyimpanan relasional, serta tooling Laravel untuk keamanan, email, berkas, laporan, dan pengelolaan aplikasi.

Stack tersebut mendukung target UMKM—mudah dikelola, aman, berkinerja baik, dan siap tumbuh—tanpa memasukkan integrasi atau arsitektur yang belum dibutuhkan oleh versi pertama. Keputusan detail seperti versi patch package, penyedia SMTP/hosting, konfigurasi server, serta rancangan data harus divalidasi pada tahap SRS dan desain teknis sebelum implementasi production.

## Referensi Teknis

- [Laravel 10 — Server Requirements dan Deployment](https://laravel.com/docs/10.x/deployment)
- [Laravel 10 — Upgrade Guide (PHP dan Composer minimum)](https://laravel.com/docs/10.x/upgrade)
- [Composer — Command-line interface](https://getcomposer.org/doc/03-cli.md)
