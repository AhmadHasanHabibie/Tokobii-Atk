# Vision Project — Website Toko Alat Tulis Kantor (ATK)

**Dokumen:** Vision Project  
**Produk:** Sistem Penjualan Internal Toko ATK  
**Platform:** Web Application  
**Target teknologi:** Laravel 10  
**Status:** Pondasi perencanaan pengembangan

## 1. Vision Statement

Membangun sistem penjualan web untuk toko alat tulis kantor (ATK) skala UMKM yang aman, mudah digunakan, dan andal, sehingga pelanggan dapat menemukan serta memesan produk secara daring, sementara pemilik toko dapat mengelola katalog, persediaan, pembayaran, dan pesanan Pick-Up secara terpusat.

Sistem ini ditujukan sebagai kanal penjualan digital milik satu toko, bukan marketplace. Pengalaman belanja harus sederhana bagi pelanggan dan efisien bagi tim operasional, tanpa mengorbankan keamanan data, akurasi stok, maupun kesiapan pengembangan jangka panjang.

## 2. Project Overview

Website Toko ATK adalah aplikasi web internal yang mendigitalisasi proses penjualan toko dari katalog hingga pengambilan barang di lokasi toko. Pelanggan dapat melihat produk, mencari kebutuhan ATK, membuat akun, memasukkan produk ke keranjang, melakukan checkout, memilih pembayaran COD atau QRIS, dan mengambil barang yang telah disiapkan menggunakan kode Pick-Up.

Di sisi operasional, Admin dan Owner memperoleh sarana untuk mengelola produk, kategori, harga, SKU, stok, gambar, pesanan, pembayaran, laporan, dan aktivitas keamanan. Sistem menjadi satu sumber data operasional yang menggantikan pencatatan tersebar dan membantu toko mengambil keputusan berdasarkan informasi yang lebih akurat.

## 3. Background Project

Toko ATK saat ini melayani penjualan secara offline. Pola tersebut efektif untuk pelanggan yang datang langsung, tetapi membatasi jangkauan informasi produk, membuat proses pencarian barang kurang praktis, serta meningkatkan ketergantungan pada komunikasi manual untuk ketersediaan dan pemesanan.

Digitalisasi melalui kanal web memungkinkan toko tetap mempertahankan proses pengambilan barang langsung (Pick-Up), sambil memberi pelanggan kemudahan berbelanja dari mana saja. Pendekatan ini sesuai untuk UMKM karena mengurangi kompleksitas logistik pengiriman pada tahap awal dan memusatkan perhatian pada katalog, stok, pesanan, serta layanan di toko.

## 4. Existing Problems

- Informasi produk, harga, dan ketersediaan belum tersedia secara mandiri bagi pelanggan.
- Pelanggan perlu datang atau menghubungi toko untuk mengetahui barang yang tersedia.
- Pencatatan stok dan perubahan stok berisiko tidak konsisten jika dilakukan secara manual.
- Konfirmasi pesanan dan pembayaran QRIS dapat membutuhkan komunikasi berulang.
- Riwayat transaksi, aktivitas operasional, dan jejak keamanan sulit ditelusuri secara terpusat.
- Pemilik toko belum memiliki dashboard dan laporan yang konsisten untuk memantau operasional.
- Pertumbuhan jumlah produk, pelanggan, dan transaksi dapat meningkatkan beban kerja tanpa sistem yang terstruktur.

## 5. Proposed Solution

Solusi yang diusulkan adalah aplikasi web Laravel 10 dengan katalog produk publik, autentikasi pelanggan, proses keranjang dan checkout, serta panel operasional berbasis peran. Model pemenuhan pesanan dibatasi pada Pick-Up di toko.

Untuk pembayaran, pelanggan dapat memilih COD atau QRIS. Pesanan QRIS mewajibkan unggahan bukti pembayaran dan menunggu verifikasi Owner. Setelah pembayaran atau pesanan dikonfirmasi, operasional menyiapkan barang hingga status berubah menjadi **Siap Diambil**. Pelanggan mengambil pesanan dengan kode Pick-Up, lalu pesanan ditandai selesai.

Sistem juga menyediakan pengelolaan stok yang dapat diaudit, laporan dasar, serta kontrol keamanan untuk akun operasional. Semua aturan bisnis kritis diterapkan pada lapisan aplikasi agar data transaksi tetap valid dan dapat ditelusuri.

## 6. Project Objectives

- Menyediakan katalog ATK daring yang informatif, mudah dicari, dan mudah digunakan.
- Memungkinkan pelanggan melakukan pemesanan mandiri dengan alur Pick-Up yang jelas.
- Memusatkan pengelolaan produk, harga, stok, pesanan, dan pembayaran.
- Meminimalkan kesalahan operasional melalui validasi, status pesanan, dan riwayat perubahan.
- Menyediakan fondasi yang aman, terukur, dan mudah dipelihara untuk pertumbuhan usaha.

## 7. Business Objectives

- Memperluas akses pelanggan terhadap katalog toko di luar jam kunjungan fisik.
- Meningkatkan peluang penjualan melalui proses pemesanan yang praktis.
- Mengurangi waktu staf dalam menjawab pertanyaan berulang tentang produk dan ketersediaan.
- Meningkatkan ketepatan pengelolaan stok dan kesiapan barang sebelum pelanggan datang.
- Memberikan visibilitas terhadap performa penjualan, produk, dan operasional toko.
- Membangun kepercayaan pelanggan melalui proses pembayaran dan pengambilan yang transparan.

## 8. Technical Objectives

- Menggunakan Laravel 10 dengan praktik pengembangan yang selaras dengan ekosistemnya.
- Menjaga waktu respons target dashboard dan daftar produk di bawah 2 detik pada beban operasional yang wajar.
- Menggunakan pagination, eager loading, indeks data yang relevan, dan pencegahan N+1 query.
- Menyediakan kontrol akses berbasis peran untuk Guest, Customer, Owner, dan Admin.
- Menerapkan verifikasi email, pembatasan percobaan akses, pengelolaan sesi, serta pencatatan aktivitas keamanan.
- Menjaga struktur kode modular, dapat diuji, dan siap dikembangkan tanpa mengubah inti sistem secara berisiko.

## 9. Target Users

| Pengguna | Kebutuhan utama |
| --- | --- |
| Guest | Melihat landing page, katalog, pencarian, detail produk, login, dan registrasi. |
| Customer | Berbelanja, membayar, mengunggah bukti QRIS, melacak pesanan, mengambil barang, serta memberi rating. |
| Owner | Mengelola operasional harian, produk, stok, pesanan, konfirmasi pembayaran, dashboard, dan laporan. |
| Admin | Mengelola keseluruhan sistem, pengguna, keamanan, audit, statistik, dan akun Owner. |

## 10. Stakeholder Identification

| Stakeholder | Peran dan kepentingan |
| --- | --- |
| Pemilik toko | Sponsor bisnis, pengambil keputusan, dan pengguna informasi operasional. |
| Admin sistem | Menjaga konfigurasi, data utama, akun, keamanan, dan audit sistem. |
| Owner/operasional | Memproses pesanan, mengelola stok serta katalog, dan melayani pengambilan barang. |
| Customer | Menggunakan katalog dan layanan pemesanan untuk membeli kebutuhan ATK. |
| Tim pengembang | Menerjemahkan kebutuhan bisnis menjadi sistem yang aman dan dapat dipelihara. |
| Penyedia pembayaran/QRIS | Mendukung metode pembayaran QRIS sesuai proses bisnis yang ditetapkan. |

## 11. Success Criteria

Proyek dinilai berhasil apabila:

- Pelanggan dapat menyelesaikan alur katalog sampai Pick-Up tanpa bantuan manual pada skenario normal.
- Pesanan memiliki status yang jelas, kode Pick-Up, dan riwayat yang dapat ditelusuri.
- Produk nonaktif tidak dapat ditambahkan atau dibeli, dan stok tidak pernah menjadi negatif.
- Setiap perubahan stok terekam dalam Stock History.
- Aktivitas penting dan kejadian keamanan terekam dalam Activity Log serta riwayat terkait.
- Hanya pelanggan dengan email terverifikasi yang dapat menggunakan fitur transaksi.
- Rating hanya tersedia setelah pesanan berstatus Completed dan mengikuti batasan satu produk per pesanan.
- Admin dan Owner melewati Face Verification sesuai kebijakan keamanan.
- Daftar produk dan dashboard memenuhi target performa dalam kondisi penggunaan yang disepakati.
- Pemilik toko dapat menggunakan laporan untuk memantau aktivitas dan penjualan tanpa rekap manual utama.

## 12. Project Scope Summary

### In Scope

- Katalog produk publik, pencarian, kategori, detail produk, thumbnail, dan galeri.
- Registrasi, login, verifikasi email, serta manajemen sesi pelanggan.
- Keranjang, checkout, pembayaran COD/QRIS, unggahan bukti pembayaran, dan Pick-Up.
- Pengelolaan produk, kategori, harga, SKU, status produk, stok, dan riwayat stok.
- Manajemen pesanan, verifikasi pembayaran, kode Pick-Up, dan perubahan status pesanan.
- Rating produk sesuai aturan transaksi yang telah selesai.
- Dashboard, statistik, laporan, manajemen customer, dan manajemen akun Owner sesuai peran.
- Security Center, Activity Log, Login History, IP History, Device History, serta Failed Login History.

### Out of Scope untuk Tahap Awal

- Marketplace multi-penjual atau multi-toko.
- Pengiriman kurir, perhitungan ongkir, dan pelacakan pengiriman.
- Metode pemenuhan selain Pick-Up.
- Integrasi ERP, akuntansi penuh, POS fisik, atau aplikasi mobile native.
- Promosi tingkat lanjut, program loyalitas, dan integrasi pihak ketiga yang belum dibutuhkan operasional inti.

## 13. High-Level Features

1. **Katalog dan pencarian produk** — daftar produk terstruktur berdasarkan kategori, status aktif, harga, gambar, dan ketersediaan.
2. **Akun pelanggan** — registrasi, login, verifikasi email, serta riwayat pesanan.
3. **Keranjang dan checkout** — validasi item, harga, stok, metode pembayaran, dan Pick-Up.
4. **Pembayaran QRIS dan COD** — unggah bukti pembayaran QRIS dan proses verifikasi oleh Owner.
5. **Manajemen pesanan** — status pesanan dari dibuat hingga completed, termasuk kode Pick-Up.
6. **Manajemen produk dan inventori** — produk, kategori, SKU, harga, gambar, stok, dan Stock History.
7. **Rating produk** — tersedia hanya untuk pembelian pada pesanan completed sesuai aturan bisnis.
8. **Dashboard dan laporan** — ringkasan operasional dan informasi yang relevan untuk Admin/Owner.
9. **Keamanan dan audit** — kontrol peran, face verification, rate limiting, aktivitas, serta riwayat akses.

## 14. Expected Benefits

- Pelanggan memperoleh pengalaman belanja yang lebih cepat dan transparan.
- Toko memiliki etalase digital yang dapat diakses kapan pun.
- Barang dapat disiapkan sebelum pelanggan tiba, sehingga waktu layanan di toko berkurang.
- Stok, pesanan, dan pembayaran dikelola melalui proses yang lebih tertib serta dapat diaudit.
- Pemilik memperoleh dasar data untuk mengevaluasi produk dan operasional.
- Sistem menciptakan fondasi untuk pengembangan fitur bisnis berikutnya tanpa harus membangun ulang dari awal.

## 15. Risks and Mitigation

| Risiko | Dampak | Mitigasi |
| --- | --- | --- |
| Ketidakakuratan stok | Pesanan tidak dapat dipenuhi dan kepercayaan pelanggan menurun. | Validasi stok pada proses transaksi, pencatatan Stock History, dan prosedur stok opname. |
| Bukti QRIS tidak valid atau lambat diverifikasi | Pesanan tertunda. | Status pembayaran yang jelas, antrean verifikasi, pembatasan format unggahan, dan audit tindakan verifikator. |
| Akses tidak sah ke akun operasional | Risiko perubahan data dan kebocoran informasi. | Role middleware, password hash, face verification, rate limiter, session management, serta login/activity history. |
| Pertumbuhan data mengurangi performa | Pengalaman pengguna dan operasional melambat. | Pagination, eager loading, indeks yang tepat, optimasi query, thumbnail gambar, dan pemantauan performa. |
| Perubahan kebutuhan bisnis | Pengembangan menjadi mahal atau sulit. | Arsitektur modular, service layer, dokumentasi aturan bisnis, dan pengembangan iteratif. |
| Ketergantungan pada proses manual Pick-Up | Kesalahan penyerahan barang. | Kode Pick-Up unik, status pesanan terkontrol, dan prosedur konfirmasi saat barang diserahkan. |

## 16. Future Scalability

Sistem dirancang agar perluasan fitur dapat dilakukan bertahap tanpa mengganggu alur inti. Arah pengembangan yang memungkinkan meliputi notifikasi transaksi, integrasi payment gateway, pengiriman, promosi, kupon, pelanggan loyalti, ekspor laporan, integrasi POS, aplikasi mobile, beberapa cabang toko, dan analitik yang lebih mendalam.

Skalabilitas tidak hanya berarti menambah server. Struktur domain yang jelas, pemisahan tanggung jawab, data yang dapat diaudit, serta kontrak antar-modul yang stabil menjadi dasar agar sistem dapat menampung pertumbuhan produk, transaksi, pengguna, dan kebutuhan integrasi.

## 17. Project Development Philosophy

Pengembangan mengikuti prinsip Laravel Best Practice, Clean Architecture secara pragmatis, modular structure, service layer, reusable code, Clean Code, SOLID bila relevan, dan standar PSR-12. Prinsip tersebut diterapkan untuk menghasilkan solusi yang proporsional bagi UMKM: cukup kuat untuk kebutuhan nyata, tetapi tidak berlebihan dalam kompleksitas.

Setiap fitur harus berangkat dari nilai bisnis dan aturan yang dapat diuji. Logika inti transaksi, stok, pembayaran, dan otorisasi tidak ditempatkan secara tersebar. Perubahan dilakukan secara bertahap, terdokumentasi, dan disertai pengujian yang sesuai risiko agar sistem tetap mudah dipahami dan dipelihara.

## 18. Technology Direction

- **Backend:** Laravel 10 sebagai kerangka kerja utama aplikasi web.
- **Bahasa dan standar:** PHP modern, PSR-12, serta praktik pengujian Laravel.
- **Arsitektur aplikasi:** pemisahan domain/modul, service layer untuk proses bisnis, request validation, policy/middleware untuk otorisasi, dan komponen yang dapat digunakan ulang.
- **Data:** basis data relasional dengan relasi, constraint, dan indeks yang dirancang berdasarkan kebutuhan transaksi serta pelaporan.
- **Performa:** query efisien, eager loading, pagination, caching selektif bila diperlukan, dan penyimpanan gambar dengan varian thumbnail.
- **Keamanan:** autentikasi Laravel, verifikasi email, password hashing, proteksi CSRF, validasi input, rate limiting, session management, audit trail, dan pengamanan unggahan berkas.
- **Operasional:** logging terstruktur, pemantauan error, pencadangan data, serta strategi rilis yang dapat dipulihkan.

Pemilihan detail implementasi dan layanan pihak ketiga akan divalidasi pada fase analisis kebutuhan dan desain teknis berikutnya, dengan mempertimbangkan biaya, keamanan, kemudahan operasional, dan kebutuhan toko.

## 19. Conclusion

Website Toko ATK merupakan investasi digital untuk memindahkan proses penjualan inti dari pola offline ke pengalaman web yang terkelola. Fokus tahap awal adalah katalog, pemesanan, pembayaran COD/QRIS, dan Pick-Up, dengan pengelolaan stok serta keamanan sebagai fondasi utama.

Dokumen ini menjadi acuan bersama bagi pemilik toko, pengguna operasional, dan tim pengembang sebelum masuk ke tahap penggalian kebutuhan detail, perancangan proses, desain arsitektur, dan implementasi. Tidak ada keputusan teknis rinci, kode, migration, controller, database, atau desain UI yang ditetapkan melalui dokumen Vision Project ini.
