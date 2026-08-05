# Project Scope Document

## Website Toko Alat Tulis Kantor (ATK)

| Informasi | Keterangan |
| --- | --- |
| Nama proyek | Website Toko Alat Tulis Kantor |
| Jenis sistem | Website penjualan internal UMKM untuk satu toko |
| Target pengguna | Admin, Owner, Customer, dan Guest |
| Framework | Laravel 10 |
| Antarmuka | Blade + Bootstrap |
| Basis data | MySQL |
| Metode pemenuhan | Pick-Up saja |
| Status dokumen | Acuan scope sebelum Software Requirement Specification (SRS) |

## 1. Project Purpose

Proyek ini bertujuan mendigitalisasi operasional toko ATK agar pelanggan dapat memperoleh informasi produk dan membuat pesanan secara daring, sementara toko dapat mengelola katalog, stok, pembayaran, pesanan, serta laporan melalui satu sistem terpusat.

Versi pertama berfokus pada proses penjualan inti satu toko: pelanggan memesan barang untuk diambil sendiri, memilih COD atau QRIS, dan menerima barang setelah pesanan diproses oleh operasional toko. Scope ini dirancang untuk memberikan manfaat bisnis nyata dengan kompleksitas yang sesuai untuk UMKM.

## 2. Project Goals

- Menyediakan katalog produk ATK daring yang mudah diakses dan dicari.
- Memungkinkan Customer melakukan pesanan Pick-Up secara mandiri dan terstruktur.
- Meningkatkan akurasi pengelolaan produk, harga, SKU, stok, dan perubahan stok.
- Menyediakan alur pembayaran COD dan QRIS yang dapat diverifikasi.
- Memberikan visibilitas status pesanan kepada Customer dan operasional.
- Menyediakan dashboard serta laporan untuk mendukung pemantauan toko.
- Menerapkan kontrol akses, audit aktivitas, dan riwayat keamanan untuk fungsi yang sensitif.
- Menyediakan fondasi aplikasi Laravel 10 yang mudah dipelihara dan dikembangkan setelah versi pertama stabil.

## 3. Project Deliverables

Deliverable proyek adalah aplikasi web Laravel 10 beserta konfigurasi, dokumentasi, dan pengujian yang diperlukan untuk menjalankan ruang lingkup versi pertama. Deliverable fungsional mencakup:

- Modul autentikasi dan keamanan akun sesuai peran pengguna.
- Modul master data produk, kategori, stok, dan riwayat stok.
- Katalog publik, cart, checkout, profil Customer, riwayat pesanan, dan rating produk.
- Manajemen order dengan nomor order, kode Pick-Up, status, dan timeline.
- Pembayaran COD serta QRIS dengan unggah dan verifikasi bukti pembayaran.
- Dashboard, laporan penjualan/produk/customer/stok, dan Security Center.
- Dokumentasi kebutuhan, scope, aturan bisnis, panduan operasional yang relevan, serta dokumentasi teknis yang diperlukan untuk pemeliharaan.
- Pengujian fungsional untuk alur bisnis kritis dan validasi penerimaan sebelum rilis.

## 4. In Scope

Seluruh item berikut termasuk dalam versi pertama aplikasi.

### 4.1 Authentication dan Account Security

- Login, register, logout, forgot password, dan reset password.
- Email Verification untuk Customer sebelum transaksi.
- Role dan pembatasan akses bagi Guest, Customer, Owner, dan Admin.
- Face Verification untuk Admin dan Owner sebagai kontrol akses tambahan.
- Session Management untuk pengelolaan sesi akun.
- Login History, IP History, Device History, dan Failed Login History.
- Activity Log untuk aktivitas operasional dan keamanan yang penting.

### 4.2 Dashboard

- Dashboard Admin untuk ringkasan sistem, statistik, dan pemantauan operasional.
- Dashboard Owner untuk ringkasan pesanan, produk/stok, serta kegiatan operasional yang relevan.

### 4.3 Master Data dan Inventory

- CRUD produk dan kategori sesuai kewenangan peran.
- Pengelolaan nama, deskripsi, harga, SKU, status aktif/nonaktif, thumbnail, dan galeri produk.
- Pengelolaan stok produk.
- Stock History untuk seluruh perubahan stok beserta informasi penelusuran yang diperlukan.
- Validasi bahwa produk nonaktif tidak dapat dibeli dan stok tidak menjadi negatif.

### 4.4 Katalog dan Customer Experience

- Landing page untuk Guest.
- Daftar produk, kategori, pencarian, dan detail produk.
- Cart untuk Customer.
- Profile Customer dan riwayat pesanan.
- Rating produk hanya setelah pesanan Completed; satu produk dapat diberi satu rating dalam satu pesanan.

### 4.5 Order dan Pick-Up

- Checkout pesanan Customer.
- Nomor order yang dapat diidentifikasi.
- Kode Pick-Up untuk validasi serah terima barang di toko.
- Status pesanan dan timeline pesanan yang dapat dilihat sesuai peran.
- Alur operasional pesanan dari dibuat, diverifikasi bila diperlukan, diproses, siap diambil, hingga completed.
- Metode pengambilan hanya Pick-Up di lokasi toko.

### 4.6 Payment

- Pilihan pembayaran COD.
- Pilihan pembayaran QRIS.
- Unggah bukti pembayaran QRIS oleh Customer.
- Verifikasi pembayaran QRIS oleh Owner yang berwenang.
- Pencatatan status pembayaran dan status order yang konsisten.

### 4.7 Report dan Security Center

- Laporan penjualan, produk, Customer, dan stok sesuai peran.
- Security Center untuk Admin, mencakup pemantauan login, aktivitas, IP, perangkat, gagal login, dan sesi.
- Manajemen akun Owner oleh Admin dan pengelolaan Customer sesuai kewenangan Admin.

## 5. Out of Scope

Fitur berikut secara sengaja tidak dibangun pada versi pertama. Pengecualian ini menjaga fokus pada proses inti dan menghindari peningkatan kompleksitas operasional sebelum fondasi sistem stabil.

| Fitur | Alasan belum termasuk |
| --- | --- |
| Aplikasi mobile native | Website responsif mencukupi kebutuhan awal dan menghindari biaya pengembangan/pemeliharaan dua platform. |
| Multi cabang | Proyek dibatasi untuk satu toko; kebutuhan sinkronisasi cabang belum menjadi prioritas. |
| Marketplace atau integrasi marketplace | Sistem ditujukan untuk kanal penjualan internal milik satu toko, bukan agregator penjual. |
| Payment gateway otomatis (mis. Midtrans/Xendit) | QRIS tahap awal menggunakan unggah bukti dan verifikasi manual; integrasi akan dievaluasi setelah volume transaksi memerlukan otomatisasi. |
| Ongkir otomatis dan pengiriman kurir | Metode pemenuhan versi pertama hanya Pick-Up. |
| Kurir internal dan pelacakan pengiriman | Tidak relevan pada model Pick-Up Only. |
| Live chat | Pertanyaan pelanggan dapat ditangani melalui kanal operasional yang telah ada; fitur ini bukan proses transaksi inti. |
| Voucher, promo, dan loyalty program | Fokus awal adalah kestabilan katalog, stok, order, dan pembayaran. |
| Multi warehouse | Persediaan berasal dari satu lokasi toko dalam versi pertama. |
| API publik | Tidak diperlukan untuk layanan eksternal pada tahap awal dan meningkatkan kebutuhan keamanan/dukungan. |
| Multi bahasa | Target awal menggunakan Bahasa Indonesia. |
| Notifikasi WhatsApp otomatis | Memerlukan integrasi dan pengelolaan pihak ketiga; dapat dipertimbangkan setelah alur utama stabil. |
| Integrasi POS, ERP, atau akuntansi penuh | Belum diperlukan untuk mencapai tujuan digitalisasi transaksi inti. |

## 6. Assumptions

- Toko beroperasi dari satu lokasi dan seluruh pesanan diambil langsung di lokasi tersebut.
- Owner, Admin, dan Customer memiliki akses internet serta perangkat yang mendukung browser modern.
- Customer memiliki alamat email aktif dan bersedia menyelesaikan verifikasi email untuk bertransaksi.
- Admin bertanggung jawab atas pemeliharaan data akun, pengawasan keamanan, dan tata kelola sistem.
- Owner/operasional memperbarui data produk, harga, stok, dan status pesanan secara disiplin melalui dashboard.
- Data awal produk, kategori, harga, SKU, stok, serta materi gambar tersedia dan dapat disiapkan oleh toko.
- QRIS sudah tersedia dan proses validasi pembayaran dapat dilakukan oleh Owner sesuai prosedur operasional.
- Toko menetapkan jam serta prosedur Pick-Up yang akan dikomunikasikan kepada Customer.
- Kebijakan bisnis mengenai pembatalan, pengembalian, dan penanganan pesanan bermasalah akan ditetapkan sebelum rilis jika diperlukan oleh proses operasional.

## 7. Constraints

- Aplikasi dibangun menggunakan Laravel 10.
- Antarmuka web menggunakan Blade + Bootstrap.
- Basis data yang digunakan adalah MySQL.
- Sistem dibatasi untuk satu toko dan tidak mendukung multi cabang pada versi pertama.
- Metode pemenuhan pesanan adalah Pick-Up Only.
- Pembayaran hanya COD dan QRIS; QRIS tidak terintegrasi payment gateway pada versi pertama.
- Tidak ada aplikasi mobile native, fitur pengiriman, atau ongkir otomatis.
- Ketersediaan dan ketepatan data bergantung pada pembaruan yang dilakukan pengguna operasional.
- Face Verification memerlukan keputusan implementasi yang memperhatikan akurasi, privasi, persetujuan pengguna, dan kapasitas perangkat sebelum diterapkan.
- Waktu respons target untuk dashboard dan daftar produk adalah kurang dari 2 detik pada kondisi penggunaan normal yang disepakati.

## 8. Stakeholders

| Stakeholder | Kepentingan dan peran |
| --- | --- |
| Pemilik toko | Sponsor dan pengambil keputusan; memastikan sistem mendukung tujuan usaha. |
| Admin | Mengelola sistem, akun, data utama, keamanan, audit, dan akses Owner. |
| Owner/operasional | Menjalankan proses produk, stok, pesanan, verifikasi pembayaran, serta laporan. |
| Customer | Menggunakan katalog, checkout, pembayaran, Pick-Up, riwayat, dan rating. |
| Guest | Mengakses informasi toko dan katalog sebelum memiliki akun. |
| Tim pengembang | Menerjemahkan scope menjadi kebutuhan rinci, desain, implementasi, pengujian, dan dokumentasi. |

## 9. Success Criteria

Proyek dinyatakan berhasil bila:

- Semua modul dalam In Scope tersedia dan berfungsi sesuai acceptance criteria.
- Alur Customer dari katalog sampai pesanan Completed dapat dijalankan untuk COD dan QRIS.
- Stok tidak dapat bernilai negatif dan setiap perubahan stok tercatat pada Stock History.
- Produk nonaktif tidak dapat dibeli.
- Admin/Owner dapat mengelola data sesuai kewenangan, melihat dashboard/laporan, serta menjalankan proses order.
- Security Center, Activity Log, Login History, dan kontrol keamanan yang disepakati aktif untuk fungsi yang relevan.
- Tidak terdapat bug kritis yang mengganggu transaksi, stok, keamanan, atau pengambilan barang pada saat penerimaan rilis.
- Target respons daftar produk dan dashboard tercapai pada kondisi uji yang disepakati.
- Dokumentasi proyek tersedia dan struktur implementasi mudah dipelihara oleh tim berikutnya.

## 10. Acceptance Criteria

| Area | Kriteria penerimaan |
| --- | --- |
| Authentication | Pengguna dapat register, login, logout, meminta reset password, dan Customer harus terverifikasi email sebelum bertransaksi. |
| Authorization | Guest, Customer, Owner, dan Admin hanya dapat mengakses fungsi sesuai perannya; Admin/Owner memenuhi Face Verification sesuai kebijakan. |
| Produk dan stok | Peran berwenang dapat mengelola produk/kategori/stok; produk nonaktif tidak dapat dibeli; stok tidak negatif; setiap perubahan stok memiliki riwayat. |
| Katalog dan cart | Guest dapat menjelajah/mencari produk; Customer dapat menambah, mengubah, dan meninjau item cart yang valid. |
| Checkout dan payment | Customer dapat membuat pesanan Pick-Up dengan COD atau QRIS; bukti QRIS dapat diunggah dan hasil verifikasinya tercatat. |
| Order dan Pick-Up | Sistem membuat nomor order dan kode Pick-Up; status/timeline dapat ditelusuri; pesanan dapat diselesaikan setelah serah terima yang valid. |
| Rating | Rating hanya tersedia untuk produk yang dibeli pada pesanan Completed dan satu kali per produk dalam pesanan tersebut. |
| Report | Admin/Owner dapat mengakses laporan penjualan, produk, Customer, atau stok sesuai hak aksesnya. |
| Security Center | Riwayat login, aktivitas, IP, perangkat, gagal login, dan sesi tersedia bagi Admin sesuai kebijakan akses. |
| Kualitas | Tidak ada bug kritis pada alur inti; uji penerimaan bisnis disetujui stakeholder yang berwenang. |

## 11. Project Boundaries

Batas fungsional proyek dimulai ketika Guest mengakses website atau Customer menggunakan akun untuk melihat katalog, dan berakhir ketika pesanan Pick-Up berstatus Completed serta Customer dapat memberi rating sesuai ketentuan. Pengelolaan data produk, stok, pesanan, pembayaran, laporan, dan keamanan berada dalam batas aplikasi.

Proses fisik setelah Customer tiba di toko—seperti pengepakan akhir dan penyerahan barang—tetap merupakan tanggung jawab operasional toko. Sistem mendukung proses tersebut dengan status, data pesanan, dan kode Pick-Up, tetapi tidak menggantikan prosedur pelayanan fisik. Pembayaran QRIS dipastikan melalui verifikasi bukti oleh Owner, bukan konfirmasi otomatis dari bank atau payment gateway.

Di luar batas proyek adalah pengiriman barang, pengelolaan cabang lain, transaksi untuk penjual lain, serta otomasi/integrasi eksternal yang tercantum dalam Out of Scope.

## 12. Feature Prioritization

| Prioritas | Fitur | Dasar prioritas |
| --- | --- | --- |
| Must Have | Authentication, role access, email verification, Face Verification Admin/Owner | Dasar akses aman dan pemisahan kewenangan. |
| Must Have | Produk, kategori, stok, Stock History | Data inti yang menentukan ketepatan transaksi. |
| Must Have | Katalog, pencarian, cart, checkout, profile, riwayat pesanan | Pengalaman pembelian inti bagi Customer. |
| Must Have | COD, QRIS, unggah bukti, verifikasi | Mendukung seluruh metode pembayaran yang disepakati. |
| Must Have | Nomor order, kode Pick-Up, status dan timeline | Mengendalikan proses pemenuhan pesanan di toko. |
| Must Have | Dashboard dan laporan utama | Membantu pengawasan operasional sejak rilis awal. |
| Must Have | Security Center, Activity Log, Login/IP/Device/Failed Login History, session management | Mendukung audit dan mitigasi akses tidak sah. |
| Should Have | Rating produk | Menambah kepercayaan dan umpan balik setelah proses inti berhasil. |
| Should Have | Thumbnail dan galeri produk | Meningkatkan kualitas katalog dan pengalaman belanja. |
| Could Have | Penyempurnaan statistik atau filter laporan lanjutan | Berguna, tetapi tidak menghambat transaksi inti. |
| Won't Have pada V1 | Fitur dalam Out of Scope | Ditunda untuk mencegah perluasan scope awal. |

## 13. Development Phases

1. **Inisiasi dan spesifikasi.** Finalisasi dokumen scope, BRD, aturan bisnis, kebijakan operasional, dan SRS.
2. **Fondasi sistem.** Penetapan arsitektur, autentikasi, role access, verifikasi email, serta fondasi keamanan dan audit.
3. **Master data dan katalog.** Pengembangan produk, kategori, stok, riwayat stok, katalog publik, pencarian, gambar, dan dashboard awal.
4. **Transaksi Customer.** Pengembangan cart, checkout, profile, riwayat pesanan, nomor order, kode Pick-Up, status, dan timeline.
5. **Pembayaran dan pemenuhan.** Penerapan COD, QRIS, unggah bukti, verifikasi pembayaran, proses persiapan, dan penyelesaian Pick-Up.
6. **Administrasi dan pelaporan.** Penyelesaian modul Admin/Owner, laporan, Security Center, rating, dan audit trail.
7. **Quality assurance dan penerimaan.** Pengujian alur kritis, uji penerimaan pengguna, perbaikan temuan, dokumentasi, dan persiapan rilis.

Urutan ini dapat disesuaikan secara internal tanpa mengubah daftar fitur yang disetujui. Perubahan terhadap In Scope harus mengikuti strategi manajemen scope di bawah.

## 14. Future Enhancement

Fitur berikut dapat dipertimbangkan setelah versi pertama beroperasi stabil, tanpa mengubah prinsip arsitektur utama:

- Multi cabang dan manajemen persediaan antar cabang.
- Aplikasi mobile native atau progressive web app.
- REST API untuk integrasi terkontrol.
- Integrasi payment gateway dan konfirmasi pembayaran otomatis.
- Notifikasi WhatsApp, email, atau push notification otomatis.
- Dashboard analitik lanjutan dan ekspor laporan yang lebih luas.
- Barcode scanner untuk stok dan serah terima.
- Sistem supplier, purchase order, dan penerimaan barang.
- Multi warehouse dan pengelolaan lokasi stok.
- Promo, voucher, loyalty program, serta kampanye pemasaran.
- Integrasi POS atau sistem akuntansi.
- Pengiriman, ongkir, pelacakan kurir, dan opsi pemenuhan lain.
- Multi bahasa dan peningkatan aksesibilitas.

## 15. Scope Management Strategy

Dokumen ini menjadi baseline scope untuk versi pertama. Semua pihak harus menggunakan In Scope, Out of Scope, acceptance criteria, dan prioritas fitur sebagai rujukan ketika membuat keputusan pengembangan.

Setiap permintaan fitur atau perubahan setelah baseline harus dicatat sebagai change request. Change request minimal menjelaskan kebutuhan bisnis, alasan, dampak terhadap waktu, biaya, kualitas, keamanan, data, dan ketergantungan fitur. Pemilik toko atau pihak berwenang menilai permintaan tersebut bersama tim proyek.

Perubahan hanya menjadi bagian dari versi pertama apabila disetujui secara eksplisit dan baseline dokumen diperbarui. Permintaan yang tidak mendukung tujuan inti, meningkatkan risiko secara tidak proporsional, atau tidak dapat diselesaikan dalam kapasitas rilis akan ditempatkan pada backlog Future Enhancement. Pendekatan ini menjaga proyek tetap fokus, dapat diprediksi, dan siap memasuki penyusunan SRS tanpa scope creep.
