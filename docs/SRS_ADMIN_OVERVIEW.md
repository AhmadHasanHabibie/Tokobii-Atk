# Software Requirement Specification (SRS)

## Role Admin Overview — Website Toko Alat Tulis Kantor (ATK)

| Informasi | Keterangan |
| --- | --- |
| Dokumen | SRS Role Admin Overview |
| Proyek | Website Toko Alat Tulis Kantor |
| Framework arah pengembangan | Laravel 10 |
| Role yang dibahas | Admin |
| Status | Draft SRS Milestone 2 — Overview; belum membahas spesifikasi tiap modul |
| Ketergantungan | Project Scope, Stakeholder Analysis, Development Bible, DoR/DoD, dan Foundation Review |

## 1. Executive Summary

Admin adalah Super Administrator pada Website Toko ATK. Role ini memiliki kewenangan tertinggi untuk mengelola administrasi sistem, data master, akun pengguna, pengawasan operasional, laporan, keamanan, dan audit. Admin memastikan sistem digunakan sesuai kebijakan toko serta data yang dipakai oleh Owner dan Customer tetap akurat, terlindungi, dan dapat ditelusuri.

SRS ini mendefinisikan gambaran umum Role Admin sebagai fondasi seluruh spesifikasi modul Admin pada langkah berikutnya. Dokumen ini tidak menetapkan detail layar, route, database, migration, model, controller, maupun implementasi teknis. Detail fungsional Dashboard, Product, Category, Stock, Order, Customer, Owner, Report, Security Center, Profile, dan Settings akan dibuat dalam SRS modul tersendiri.

> Status pondasi proyek saat dokumen ini dibuat adalah **Ready with Revision**. Kebutuhan Admin yang berkaitan dengan Face Verification, lifecycle teknologi, serta lifecycle order–payment–stock harus divalidasi kembali setelah keputusan fondasi terkait disetujui. Dokumen ini bukan izin untuk langsung mengimplementasikan modul Admin.

## 2. Deskripsi Role Admin

Admin adalah pengguna internal yang diberi mandat oleh pemilik toko untuk mengelola sistem pada tingkat administrasi tertinggi. Admin dapat berupa pemilik toko, staf tepercaya, atau pihak yang secara resmi ditugaskan menjaga data dan operasional digital toko. Admin bukan sekadar pengguna dashboard; ia adalah penjaga tata kelola sistem.

Admin dibutuhkan karena sistem mengelola data dan proses yang saling bergantung: produk, kategori, stok, order, pembayaran, akun, laporan, riwayat aktivitas, serta catatan keamanan. Tanpa role dengan kewenangan administratif yang jelas, perubahan data, pengelolaan akun Owner, investigasi insiden, dan pengawasan proses tidak dapat dilakukan secara terpusat atau akuntabel.

Dalam organisasi toko, posisi Admin berada di atas Owner dari sisi administrasi aplikasi dan Security Center. Owner memimpin atau menjalankan operasional toko sehari-hari, sedangkan Admin mengatur akses, memelihara tata kelola data, dan memantau keamanan sistem. Kewenangan Admin tetap dibatasi oleh kebijakan bisnis pemilik toko, prinsip least privilege, audit trail, dan prosedur perubahan yang disetujui.

## 3. Tujuan Role Admin

### 3.1 Tujuan bisnis

- Menjaga agar sistem mendukung proses penjualan dan operasional toko secara tertib.
- Menjamin data master, pengguna, dan informasi operasional dikelola melalui sumber data yang terpusat.
- Memastikan Owner dapat menjalankan proses produk, stok, order, dan pembayaran dengan akses yang benar.
- Menyediakan informasi dan laporan yang dapat dipercaya bagi pemilik toko untuk pengambilan keputusan.
- Mengurangi risiko kesalahan operasional, akses tidak sah, serta kehilangan keterlacakan data.
- Menjaga kepercayaan Customer melalui informasi katalog, pesanan, dan penanganan data yang aman.

### 3.2 Tujuan operasional dan teknis

- Mengelola hak akses sesuai role dan memastikan akun berwenang saja yang dapat memakai fungsi sensitif.
- Memantau Activity Log, Login History, IP History, Device History, Failed Login History, dan sesi yang relevan.
- Memelihara data produk, kategori, harga, SKU, status, media, dan stok sesuai kebijakan sistem.
- Mengawasi status order/pembayaran serta menjaga proses operasional dapat ditelusuri.
- Memastikan perubahan penting direkam pada audit trail dan perubahan stok direkam pada Stock History.
- Menggunakan dashboard dan laporan untuk mendeteksi anomali operasional secara tepat waktu.
- Menjalankan Face Verification sesuai kebijakan keamanan yang nantinya disahkan.

## 4. Tanggung Jawab Admin

| Area tanggung jawab | Tanggung jawab Admin |
| --- | --- |
| Administrasi sistem | Menjaga konfigurasi operasional, struktur akses, dan data administratif sesuai kebijakan toko. |
| Master data | Mengelola atau mengawasi produk, kategori, harga, SKU, status produk, thumbnail, galeri, serta informasi lain yang menjadi sumber katalog. |
| Inventori | Mengelola atau mengawasi stok dan memastikan setiap perubahan dapat ditelusuri melalui Stock History. |
| User management | Mengelola Customer sesuai kebijakan, membuat/mengelola akun Owner, dan menjaga status akses pengguna. |
| Order dan pembayaran | Mengawasi order, status, timeline, kode Pick-Up, dan informasi pembayaran; melakukan intervensi administratif hanya sesuai kebijakan. |
| Pelaporan | Mengakses statistik dan laporan penjualan, produk, Customer, serta stok untuk monitoring dan evaluasi. |
| Security Center | Meninjau aktivitas, riwayat login, IP, perangkat, gagal login, dan sesi; menindaklanjuti kejadian mencurigakan melalui prosedur yang sah. |
| Audit dan kepatuhan | Memastikan tindakan material dapat diaudit, menjaga kerahasiaan data, dan mendukung investigasi masalah. |
| Kolaborasi operasional | Memberi Owner akses serta data yang diperlukan, menyampaikan kebijakan/perubahan sistem, dan membantu penyelesaian insiden yang berdampak pada layanan. |

Admin tidak boleh menganggap hak akses tinggi sebagai alasan untuk melewati aturan bisnis. Tindakan yang mengubah data transaksi, akun, stok, akses, atau status penting harus mengikuti proses yang jelas dan dapat dicatat.

## 5. Hak Akses Admin

Admin memiliki hak akses tingkat tinggi pada modul berikut. Hak akses rinci per tindakan akan ditetapkan pada SRS modul dan matriks role-permission.

| Area akses | Hak akses tingkat tinggi |
| --- | --- |
| Dashboard | Mengakses ringkasan statistik, kondisi operasional, dan indikator yang relevan bagi administrasi. |
| Product | Mengelola produk, harga, SKU, status aktif/nonaktif, thumbnail, galeri, dan informasi katalog sesuai kebijakan. |
| Category | Mengelola kategori dan struktur klasifikasi produk. |
| Stock | Mengelola atau mengawasi stok serta melihat Stock History dan referensi perubahan. |
| Order | Melihat, memantau, dan menjalankan tindakan administratif terhadap order sesuai kebijakan yang disetujui. |
| Customer | Melihat dan mengelola data/status Customer sesuai prinsip privasi serta kebijakan usaha. |
| Owner | Membuat, mengubah, menonaktifkan, atau mengelola akun Owner sesuai prosedur keamanan. |
| Report | Mengakses laporan penjualan, produk, Customer, stok, serta statistik yang relevan. |
| Security Center | Mengakses Activity Log, Login History, IP History, Device History, Failed Login History, serta Session Management. |
| Profile | Mengelola profil dan kredensial miliknya sendiri sesuai kebijakan keamanan. |
| Settings | Mengelola pengaturan sistem yang secara eksplisit ditetapkan sebagai kewenangan Admin. |

Admin dapat melihat lebih luas daripada Owner, tetapi akses terhadap data pribadi, bukti pembayaran, audit, dan pengaturan keamanan tetap harus mengikuti kebutuhan tugas, logging, serta prinsip akses minimum. Akses Admin terhadap fungsi yang sangat sensitif dapat memerlukan re-authentication atau Face Verification sesuai keputusan keamanan final.

## 6. Batasan Admin

- Admin tidak boleh mengubah Project Scope, Business Rules, Technology Stack, Development Bible, atau Folder Architecture tanpa Change Request dan approval yang berwenang.
- Admin tidak boleh menghapus, memodifikasi, atau menutup jejak audit untuk menyembunyikan tindakan; audit trail dan Stock History harus diperlakukan sebagai catatan akuntabilitas.
- Admin tidak boleh mengubah stok menjadi negatif, memproses produk nonaktif sebagai produk yang dapat dibeli, atau melewati validasi transaksi.
- Admin tidak boleh mengakses, mengungkapkan, atau menggunakan data Customer, bukti QRIS, riwayat keamanan, atau data biometrik di luar kebutuhan operasional yang sah.
- Admin tidak boleh membagikan akun, password, token, atau sesi; setiap individu wajib menggunakan akun yang dapat ditelusuri.
- Admin tidak boleh menyetujui dirinya sendiri untuk tindakan keamanan atau perubahan berisiko tinggi bila kebijakan mewajibkan pemisahan tugas.
- Admin tidak boleh melakukan perubahan langsung pada data production di luar fitur/prosedur yang disetujui tanpa audit, backup, dan otorisasi yang sesuai.
- Admin tidak menggantikan peran Owner dalam verifikasi operasional sehari-hari kecuali kebijakan toko secara eksplisit memberikan delegasi yang tercatat.

## 7. Hubungan Admin dengan Stakeholder

| Stakeholder | Hubungan Admin |
| --- | --- |
| Owner | Admin membuat dan mengelola akses Owner, menjaga data/master data dan keamanan sistem, serta memantau operasional. Owner memberi masukan tentang kebutuhan operasional, memproses order/pembayaran, dan melaporkan anomali yang membutuhkan tindakan administratif. |
| Customer | Admin menjaga keamanan akun, integritas data, dan ketersediaan layanan. Interaksi langsung dibatasi pada pengelolaan yang sah, penanganan isu akun, atau kebutuhan layanan; privasi Customer wajib dijaga. |
| Guest | Admin memastikan informasi publik, katalog, dan proses register/login berfungsi serta aman. Admin tidak memperoleh alasan untuk mengumpulkan data Guest di luar yang diperlukan sistem. |
| Sistem | Admin mengelola akses dan data melalui modul resmi, memantau audit/security event, serta menggunakan laporan untuk mengawasi kualitas operasional. Sistem memberi validasi, pembatasan, log, dan status untuk mencegah tindakan yang tidak sah. |
| Pemilik toko | Admin memberikan informasi administrasi, laporan, risiko, dan rekomendasi; pemilik toko atau Product Owner tetap memegang keputusan kebijakan bisnis utama. |

## 8. Kebutuhan Admin

### 8.1 Kebutuhan fungsional tingkat tinggi

- Dashboard dengan ringkasan yang relevan, dapat dipahami, dan tidak membebani proses operasional.
- Pengelolaan master data yang memiliki validasi, status, pencarian, filter, pagination, serta jejak perubahan.
- Pengelolaan user dan Owner dengan kontrol status akun, role, dan mekanisme keamanan yang dapat ditelusuri.
- Akses ke order, stok, laporan, serta status yang konsisten untuk pengawasan dan investigasi.
- Security Center yang menyajikan login, aktivitas, IP, perangkat, gagal login, serta sesi dalam bentuk yang dapat ditindaklanjuti.
- Mekanisme audit dan pemberitahuan yang membantu Admin mengenali tindakan berisiko tanpa menghasilkan kebisingan berlebihan.

### 8.2 Kebutuhan nonfungsional

- Akses aman melalui autentikasi, role middleware, policy, session management, rate limiting, dan Face Verification sesuai keputusan final.
- Kinerja dashboard/tabel sesuai target proyek, dengan pagination dan query efisien.
- Pesan error yang ramah pengguna tanpa membuka detail sistem atau data sensitif.
- Data dan dokumen privat hanya dapat diakses oleh pihak berwenang dan dicatat bila perlu.
- Antarmuka responsif serta mudah digunakan pada perangkat yang digunakan operasional toko.
- Dokumentasi/SOP yang menjelaskan proses administrasi, insiden keamanan, dan eskalasi masalah.

## 9. Risiko dan Mitigasi

| Risiko | Dampak | Mitigasi |
| --- | --- | --- |
| Salah mengubah harga, produk, status, atau stok | Katalog/pesanan salah, kerugian, dan komplain Customer. | Form Request, validasi bisnis, konfirmasi tindakan kritis, Stock History, Activity Log, review, dan SOP. |
| Salah memberikan atau mencabut akses Owner/Customer | Akses tidak sah atau operasional terhenti. | RBAC, approval untuk tindakan sensitif, audit, session management, dan prosedur pemulihan akses. |
| Mengabaikan kejadian keamanan | Insiden terlambat ditangani dan dampaknya membesar. | Security Center, alert/prosedur eskalasi, review berkala, dan owner insiden yang jelas. |
| Penyalahgunaan hak akses Admin | Kebocoran data atau perubahan data yang tidak sah. | Least privilege, akun individual, Face Verification/re-authentication sesuai keputusan, audit trail, dan pemisahan tugas untuk tindakan tertentu. |
| Data laporan tidak akurat | Keputusan bisnis keliru. | Data source terpusat, validasi proses, rekonsiliasi/monitoring, dan penelusuran audit. |
| Kehilangan data atau kegagalan perubahan | Gangguan layanan dan kesulitan pemulihan. | Backup/restore yang diuji, kontrol perubahan, deployment gate, logging, dan rencana rollback. |
| Kebijakan biometrik belum final | Fitur keamanan tidak dapat diterapkan dengan aman atau melanggar privasi. | Menjadikan keputusan Face Verification sebagai blocker sampai feasibility/privacy/security decision disahkan. |

## 10. Target Keberhasilan

Role Admin dinilai berhasil apabila:

- Admin dapat mengelola data dan akses sesuai kewenangan tanpa bergantung pada proses manual utama.
- Produk, kategori, stok, user, dan Owner dapat dikelola melalui proses yang tervalidasi serta dapat diaudit.
- Activity Log, Stock History, dan riwayat keamanan menyediakan informasi yang cukup untuk menelusuri kejadian penting.
- Owner memperoleh akses yang tepat untuk operasional dan Customer tidak dapat mengakses data/fitur yang bukan miliknya.
- Admin dapat menemukan dan menindaklanjuti anomali keamanan atau operasional sesuai SOP.
- Dashboard dan laporan memberikan informasi yang cukup untuk monitoring tanpa melebihi target performa yang disepakati.
- Tidak terdapat akses tidak sah, kebocoran data, atau perubahan administratif kritis yang tidak dapat ditelusuri pada proses yang telah dirilis.
- Seluruh modul Admin yang diimplementasikan memenuhi SRS modul, Business Rules, DoR/DoD, review, dan acceptance criteria masing-masing.

## 11. Ringkasan Modul Admin

Modul berikut berada dalam cakupan Role Admin dan akan dibahas secara terpisah pada langkah SRS berikutnya. Daftar ini adalah inventaris modul, bukan spesifikasi detail.

| Modul | Tujuan tingkat tinggi |
| --- | --- |
| Dashboard | Menyajikan ringkasan statistik, status, dan perhatian operasional. |
| Product | Mengelola katalog produk dan atribut bisnisnya. |
| Category | Mengelola klasifikasi produk. |
| Stock | Mengelola/meninjau stok dan Stock History. |
| Order | Mengawasi serta menjalankan tindakan administratif atas siklus order. |
| Customer | Mengelola informasi/status Customer sesuai kebijakan dan privasi. |
| Owner | Membuat dan mengelola akun Owner serta hak akses terkait. |
| Report | Mengakses laporan penjualan, produk, Customer, dan stok. |
| Security Center | Mengawasi activity, login, IP, device, failed login, dan session. |
| Profile | Mengelola profil serta keamanan akun Admin. |
| Settings | Mengelola pengaturan aplikasi yang ditetapkan sebagai kewenangan Admin. |

Spesifikasi tiap modul harus menjelaskan requirement fungsional, Business Rules, role permission, data impact, nonfunctional requirement, error/empty/loading state, audit, acceptance criteria, dan skenario test sebelum implementasi dimulai.

## 12. Kesimpulan

Admin adalah pusat administrasi, pengawasan, dan keamanan Website Toko ATK. Kewenangan tinggi Admin diperlukan untuk menjaga data, akses, dan operasi sistem; karena itu role ini harus dibangun dengan batasan kuat, audit trail, kontrol akses minimum, serta prosedur eskalasi yang jelas.

Dokumen SRS Overview ini menjadi dasar untuk SRS modul Admin berikutnya. Sebelum modul yang menyentuh transaksi, biometrik, atau data privat dinyatakan Ready, semua keputusan fondasi yang masih berstatus revisi wajib harus ditutup dan dokumen ini divalidasi ulang terhadap baseline yang telah dibekukan.
