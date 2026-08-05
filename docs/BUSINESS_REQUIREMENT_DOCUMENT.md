# Business Requirement Document (BRD)

## Website Toko Alat Tulis Kantor (ATK)

| Informasi | Keterangan |
| --- | --- |
| Nama proyek | Website Toko Alat Tulis Kantor |
| Jenis sistem | Website penjualan internal satu toko |
| Target usaha | UMKM |
| Framework arah pengembangan | Laravel 10 |
| Metode pemenuhan pesanan | Pick-Up di toko saja |
| Metode pembayaran | COD dan QRIS dengan unggah bukti pembayaran |
| Status dokumen | Business Requirement Document — Milestone 1, Step 2 |

## 1. Executive Summary

Website Toko Alat Tulis Kantor adalah sistem penjualan web untuk mendigitalisasi proses transaksi toko ATK yang saat ini dilakukan secara manual dan offline. Sistem menyediakan katalog daring, pemesanan mandiri, keranjang, checkout, pembayaran COD atau QRIS, proses verifikasi pembayaran, dan pengambilan barang langsung di toko melalui kode Pick-Up.

Dokumen ini mendefinisikan kebutuhan bisnis sebagai acuan bersama antara pemilik toko, pengguna operasional, dan tim pengembang. Fokusnya adalah memastikan setiap fungsi yang kelak dibangun memberi nilai nyata: informasi produk mudah diakses, stok lebih terkendali, pesanan dapat ditelusuri, dan aktivitas penting memiliki jejak audit.

## 2. Background

Toko ATK merupakan usaha skala UMKM yang saat ini mengandalkan transaksi langsung di lokasi. Pelanggan harus datang ke toko untuk mengetahui harga, stok, dan pilihan produk. Pengelolaan stok serta riwayat transaksi masih dilakukan secara manual sehingga informasi dapat terlambat, tidak konsisten, atau sulit ditelusuri kembali.

Pelanggan saat ini belum memiliki kanal untuk melihat katalog atau memesan barang sebelum datang. Di sisi internal, toko belum memiliki dashboard terpusat, riwayat perubahan stok, kontrol akses akun yang memadai, maupun catatan aktivitas operasional. Kondisi ini membatasi kualitas layanan, efisiensi kerja, dan kemampuan usaha untuk tumbuh secara terukur.

## 3. Existing Business Process

Proses bisnis saat ini berlangsung secara langsung di toko. Pelanggan datang, menanyakan produk, harga, dan ketersediaan kepada petugas. Petugas memeriksa barang secara manual, melakukan transaksi, lalu menyerahkan barang jika tersedia. Pembayaran dilakukan pada saat transaksi sesuai kebiasaan operasional toko.

Pencatatan stok dan transaksi dilakukan di luar sistem penjualan terintegrasi. Ketika stok berubah karena penjualan atau penyesuaian, alasan dan pihak yang melakukan perubahan belum selalu tercatat secara konsisten. Pemilik toko perlu mengumpulkan atau merekap informasi secara manual untuk memahami penjualan dan kondisi persediaan.

## 4. Existing Problems

| Permasalahan | Dampak bagi Owner | Dampak bagi Admin/operasional | Dampak bagi Customer |
| --- | --- | --- | --- |
| Katalog, harga, dan stok tidak tersedia daring | Peluang penjualan terbatas dan keputusan pembelian sulit dipantau. | Menjawab pertanyaan produk secara berulang. | Harus datang atau menghubungi toko hanya untuk mencari informasi. |
| Pencatatan stok manual | Risiko selisih stok dan kerugian lebih tinggi. | Sulit memastikan ketersediaan saat melayani pesanan. | Berisiko menerima informasi stok yang tidak akurat. |
| Riwayat transaksi tidak terdokumentasi baik | Sulit mengevaluasi penjualan dan produk. | Sulit menelusuri pesanan atau menyelesaikan komplain. | Tidak memiliki riwayat pesanan yang jelas. |
| Tidak ada alur pemesanan sebelum datang | Pelayanan tidak dapat dipersiapkan sebelumnya. | Persiapan barang berlangsung saat pelanggan menunggu. | Waktu tunggu di toko lebih lama. |
| Belum ada verifikasi QRIS terstruktur | Kontrol penerimaan pembayaran terbatas. | Bukti pembayaran dapat tercecer atau terlewat. | Tidak mengetahui kepastian status pembayaran. |
| Tidak ada dashboard dan laporan terpusat | Pengambilan keputusan bergantung pada rekap manual. | Sulit memantau pekerjaan dan prioritas pesanan. | Dampak tidak langsung berupa layanan yang kurang konsisten. |
| Keamanan login dan audit belum memadai | Risiko data dan operasional tidak terlindungi. | Sulit melacak tindakan atau akses yang mencurigakan. | Kepercayaan terhadap layanan digital dapat menurun. |
| Tidak ada histori perubahan stok | Sulit menemukan penyebab selisih persediaan. | Koreksi stok sulit dipertanggungjawabkan. | Pesanan dapat dibatalkan akibat stok yang tidak selaras. |

## 5. Proposed Solution

Sistem yang diusulkan menyediakan satu kanal web terpusat untuk proses penjualan dan pengelolaan operasional toko. Setiap solusi disusun untuk menjawab masalah bisnis yang telah diidentifikasi.

| Solusi | Permasalahan yang diselesaikan | Alasan bisnis |
| --- | --- | --- |
| Katalog produk publik dengan pencarian dan kategori | Informasi produk sulit diakses. | Pelanggan dapat menemukan produk, harga, dan status ketersediaan secara mandiri. |
| Akun Customer dengan verifikasi email | Transaksi dan identitas pelanggan belum terkelola. | Menjaga kualitas data akun serta memberi dasar riwayat pesanan yang terpercaya. |
| Cart dan checkout Pick-Up | Pemesanan harus dilakukan di tempat. | Barang dapat dipesan dan disiapkan sebelum pelanggan datang. |
| Pembayaran COD dan QRIS dengan unggah bukti | Pembayaran QRIS belum terkendali. | Status pembayaran dapat diverifikasi dan ditelusuri sebelum pesanan diproses. |
| Status pesanan dan kode Pick-Up | Penyerahan barang belum memiliki alur digital. | Pelanggan dan petugas memiliki kepastian tahap pesanan serta identitas pengambilan. |
| Manajemen produk, kategori, harga, SKU, dan stok | Data katalog dan persediaan tersebar/manual. | Data operasional dikelola dalam satu sumber yang konsisten. |
| Stock History | Perubahan stok tidak dapat dilacak. | Setiap perubahan dapat ditinjau untuk audit dan penanganan selisih. |
| Dashboard dan laporan | Owner tidak memiliki informasi ringkas untuk keputusan. | Kinerja penjualan dan operasional dapat dipantau lebih cepat. |
| Activity Log dan riwayat akses | Aktivitas kritis tidak dapat ditelusuri. | Memperkuat akuntabilitas serta investigasi masalah keamanan. |
| Role-based access dan Face Verification untuk Admin/Owner | Risiko akses tidak sah terhadap fungsi kritis. | Hak akses sensitif dibatasi dan dilindungi sesuai peran. |
| Rating produk pascapembelian selesai | Umpan balik produk belum terdokumentasi. | Rating berasal dari pengalaman pembelian yang valid dan membantu kepercayaan pelanggan. |

## 6. Business Objectives

- Meningkatkan akses pelanggan terhadap informasi dan pemesanan produk ATK.
- Mengurangi ketergantungan pada pencatatan dan komunikasi manual.
- Mempercepat pelayanan dengan menyiapkan pesanan sebelum pelanggan datang.
- Menjaga ketepatan stok serta akuntabilitas setiap perubahan persediaan.
- Memperoleh data penjualan dan operasional yang dapat digunakan Owner untuk mengambil keputusan.
- Meningkatkan kepercayaan pelanggan melalui proses pesanan, pembayaran, dan pengambilan yang transparan.
- Menyediakan fondasi digital yang mampu mendukung pertumbuhan usaha secara bertahap.

## 7. Project Objectives

- Menyediakan website penjualan internal satu toko dengan metode pemenuhan Pick-Up saja.
- Memungkinkan Guest melihat katalog dan Customer melakukan transaksi setelah memenuhi persyaratan akun.
- Mendukung pembayaran COD serta QRIS dengan bukti pembayaran yang diverifikasi oleh Owner.
- Mendukung pengelolaan produk, kategori, harga, SKU, gambar, status produk, dan stok berdasarkan otorisasi pengguna.
- Mencatat riwayat pesanan, perubahan stok, aktivitas penting, dan riwayat akses keamanan.
- Menyediakan dashboard dan laporan bagi peran operasional yang berwenang.
- Menerapkan aturan bisnis kritis, termasuk stok tidak negatif, produk nonaktif tidak dapat dibeli, dan rating hanya setelah pesanan selesai.

## 8. Stakeholder Analysis

| Stakeholder | Kepentingan | Tanggung jawab/keterlibatan |
| --- | --- | --- |
| Pemilik toko | Pertumbuhan penjualan, kontrol operasional, dan keamanan usaha. | Menetapkan kebijakan bisnis, menyetujui prioritas, dan menggunakan laporan. |
| Admin | Kelengkapan data, kepatuhan proses, dan keamanan sistem. | Mengelola sistem, pengguna, data utama, audit, dan Security Center. |
| Owner/operasional | Kelancaran pemenuhan pesanan dan akurasi stok. | Mengelola produk/stok, memverifikasi pembayaran, memperbarui status pesanan, dan membaca laporan. |
| Customer | Pembelian yang mudah, informasi akurat, dan pengambilan cepat. | Memilih produk, membuat pesanan, membayar sesuai metode, dan mengambil barang. |
| Guest | Akses awal terhadap informasi toko dan produk. | Menjelajahi katalog lalu dapat mendaftar atau login untuk bertransaksi. |
| Tim pengembang | Kebutuhan bisnis yang jelas dan dapat divalidasi. | Merancang serta membangun solusi sesuai dokumen yang disepakati. |

## 9. User Identification

| Aktor | Peran dan tanggung jawab |
| --- | --- |
| Guest | Mengakses landing page, melihat dan mencari produk, melihat detail produk, login, serta registrasi. Tidak dapat melakukan transaksi. |
| Customer | Mengelola profil, melihat produk, menggunakan cart, checkout, memilih COD/QRIS, mengunggah bukti QRIS, melihat riwayat pesanan, mengambil barang, dan memberi rating sesuai ketentuan. |
| Owner | Mengelola Dashboard, Product, Category, Order, dan Report. Bertanggung jawab terhadap proses produk, stok, verifikasi pembayaran, persiapan, dan status pesanan. |
| Admin | Mengelola Dashboard, Product, Category, Order, Customer, Owner, Report, dan Security Center. Admin memiliki tanggung jawab administrasi sistem serta pengawasan keamanan dan audit. |

## 10. Business Scope

Ruang lingkup bisnis tahap ini mencakup:

- Penjualan produk ATK dari satu toko melalui website.
- Landing page, katalog, pencarian, kategori, detail produk, dan informasi produk untuk Guest/Customer.
- Akun pelanggan, login, verifikasi email, profil, dan riwayat pesanan.
- Cart, checkout, Pick-Up, COD, QRIS, unggah bukti pembayaran, dan verifikasi pembayaran.
- Siklus pesanan dari dibuat, menunggu/verifikasi pembayaran bila diperlukan, diproses, siap diambil, hingga selesai.
- Kode Pick-Up sebagai sarana verifikasi saat barang diserahkan di toko.
- Pengelolaan produk, kategori, harga, SKU, status aktif/nonaktif, gambar, stok, dan Stock History.
- Rating produk setelah pesanan berstatus Completed, dengan satu rating untuk satu produk pada satu pesanan.
- Dashboard statistik, laporan penjualan, manajemen Customer dan Owner sesuai kewenangan.
- Activity Log, Login History, IP History, Device History, Failed Login History, dan Face Verification untuk Admin/Owner.

## 11. Out of Scope

Tahap ini tidak mendukung:

- Marketplace, multi-penjual, atau pengelolaan toko lain.
- Multi cabang dan transfer persediaan antar cabang.
- Pengiriman kurir, ongkir, pelacakan pengiriman, maupun pilihan pemenuhan selain Pick-Up.
- Payment gateway otomatis; QRIS diproses melalui unggah bukti pembayaran dan verifikasi operasional.
- Aplikasi mobile native untuk Android atau iOS.
- Integrasi POS fisik, ERP, akuntansi lengkap, maupun sistem pihak ketiga yang belum disepakati.
- Program loyalitas, kupon/promosi tingkat lanjut, dan fitur pemasaran otomatis.

## 12. Business Process Overview

1. **Pencarian dan pemilihan produk.** Guest atau Customer melihat katalog, melakukan pencarian, membuka detail produk, dan mempertimbangkan harga serta ketersediaan. Hanya produk aktif yang dapat ditransaksikan.
2. **Autentikasi Customer.** Untuk berbelanja, pengguna mendaftar atau login. Customer wajib memiliki email terverifikasi sebelum menggunakan fungsi transaksi.
3. **Keranjang dan checkout.** Customer menambahkan produk ke cart, meninjau item, dan melakukan checkout. Sistem memvalidasi produk serta ketersediaan stok sesuai aturan bisnis.
4. **Pemilihan pemenuhan dan pembayaran.** Metode pengambilan selalu Pick-Up. Customer memilih COD atau QRIS. Untuk QRIS, Customer mengunggah bukti pembayaran.
5. **Verifikasi dan pemrosesan pesanan.** Owner memeriksa bukti QRIS. Setelah pembayaran diverifikasi, atau setelah pesanan COD diterima untuk diproses sesuai kebijakan, pesanan berubah ke tahap Diproses. Penolakan atau kebutuhan perbaikan harus memiliki status yang jelas bagi Customer.
6. **Persiapan barang.** Owner/operasional menyiapkan barang berdasarkan pesanan. Proses ini mengandalkan data stok terkini dan perubahan stok harus tercatat pada Stock History sesuai kebijakan transaksi.
7. **Siap diambil dan serah terima.** Setelah barang siap, status pesanan menjadi Siap Diambil. Customer datang ke toko dan menunjukkan kode Pick-Up. Petugas memverifikasi kode serta pesanan sebelum menyerahkan barang.
8. **Penyelesaian dan rating.** Setelah barang diserahkan, pesanan ditandai Completed. Customer dapat memberi rating hanya atas produk yang dibeli pada pesanan tersebut; satu produk hanya dapat dinilai satu kali pada pesanan yang sama.

## 13. Functional Business Modules

| Aktor | Modul | Kebutuhan bisnis utama |
| --- | --- | --- |
| Admin | Dashboard | Memantau ringkasan statistik dan kondisi operasional. |
| Admin | Product | Mengelola produk, harga, SKU, status, thumbnail, galeri, dan stok. |
| Admin | Category | Mengelola klasifikasi produk. |
| Admin | Order | Memantau dan mengelola seluruh pesanan serta statusnya. |
| Admin | Customer | Mengelola informasi dan status Customer sesuai kebijakan. |
| Admin | Owner | Membuat dan mengelola akun Owner. |
| Admin | Report | Mengakses laporan dan statistik bisnis. |
| Admin | Security Center | Memantau activity, login, IP, device, gagal login, serta kontrol keamanan. |
| Owner | Dashboard | Memantau pesanan dan operasional harian. |
| Owner | Product | Mengelola produk, harga, SKU, gambar, dan stok. |
| Owner | Category | Mengelola kategori produk. |
| Owner | Order | Memverifikasi pembayaran, menyiapkan barang, dan memperbarui status pesanan. |
| Owner | Report | Meninjau laporan yang relevan untuk operasional. |
| Customer | Product | Melihat, mencari, dan menilai produk setelah pembelian memenuhi syarat. |
| Customer | Cart | Menyimpan item yang akan dipesan dan meninjau pesanan. |
| Customer | Checkout | Membuat pesanan Pick-Up dengan validasi data transaksi. |
| Customer | Payment | Memilih COD/QRIS dan mengunggah bukti QRIS. |
| Customer | Profile | Mengelola data profil dan melihat riwayat pesanan. |
| Guest | Landing Page | Mengakses informasi awal toko. |
| Guest | Product | Melihat katalog, pencarian, dan detail produk. |
| Guest | Login/Register | Membuat atau mengakses akun untuk menjadi Customer. |

## 14. Expected Business Benefits

| Penerima manfaat | Manfaat yang diharapkan |
| --- | --- |
| Admin | Pengelolaan data dan pengawasan keamanan lebih terpusat, aktivitas lebih mudah ditelusuri, dan administrasi sistem lebih terkendali. |
| Owner | Dapat memantau penjualan, stok, dan pesanan melalui dashboard/laporan; pengambilan keputusan tidak lagi sepenuhnya mengandalkan rekap manual. |
| Customer | Dapat melihat produk dan melakukan pemesanan sebelum datang, memperoleh status pesanan yang jelas, serta menikmati waktu pengambilan yang lebih singkat. |
| UMKM | Memiliki etalase digital, proses operasional lebih rapi, pengurangan pekerjaan manual, dan fondasi untuk pertumbuhan digital berikutnya. |

## 15. Business Risks

| Risiko bisnis | Dampak potensial |
| --- | --- |
| Kesalahan atau selisih stok | Pesanan gagal dipenuhi, pembatalan, dan turunnya kepercayaan pelanggan. |
| Bukti pembayaran QRIS palsu/tidak sesuai | Kerugian finansial atau pesanan diproses tanpa pembayaran valid. |
| Akses login tidak sah | Perubahan data, penyalahgunaan akun, atau kebocoran informasi. |
| Kehilangan atau kerusakan data | Riwayat pesanan, stok, dan laporan tidak dapat digunakan. |
| Kesalahan status pesanan atau serah terima | Barang diserahkan kepada pihak yang salah atau pelanggan menerima informasi keliru. |
| Penggunaan sistem yang belum konsisten | Data kembali tersebar dan manfaat digitalisasi tidak tercapai. |
| Kinerja sistem menurun saat data bertambah | Pelayanan Customer dan proses operasional melambat. |
| Ketergantungan pada verifikasi manual QRIS | Pesanan tertunda bila verifikasi tidak dilakukan tepat waktu. |

## 16. Risk Mitigation

| Risiko | Mitigasi bisnis |
| --- | --- |
| Kesalahan stok | Validasi stok pada transaksi, pencatatan seluruh perubahan dalam Stock History, pembatasan stok negatif, dan stok opname berkala. |
| Bukti QRIS palsu | Bukti pembayaran ditinjau Owner, status verifikasi terdokumentasi, dan tindakan verifikasi masuk Activity Log. |
| Login tidak sah | Verifikasi email, password yang aman, role-based access, Face Verification untuk Admin/Owner, rate limiter, session management, dan riwayat akses. |
| Kehilangan data | Kebijakan backup berkala, pembatasan akses data, pencatatan aktivitas, serta prosedur pemulihan yang ditetapkan sebelum operasional. |
| Kesalahan pengambilan barang | Kode Pick-Up unik, verifikasi status pesanan sebelum serah terima, dan pembaruan status hanya oleh peran berwenang. |
| Adopsi pengguna rendah | Pelatihan singkat, prosedur operasional sederhana, panduan penggunaan, dan peluncuran bertahap. |
| Penurunan performa | Pagination, pengelolaan data yang efisien, pemantauan kinerja, serta evaluasi kapasitas berkala. |
| Verifikasi QRIS terlambat | Dashboard/daftar pesanan yang memprioritaskan transaksi menunggu verifikasi dan target waktu operasional yang disepakati. |

## 17. Success Indicators

Keberhasilan sistem diukur melalui indikator berikut:

- Customer dapat melihat katalog, membuat pesanan, dan mengambil barang melalui alur Pick-Up yang mudah dipahami.
- Pencatatan manual untuk informasi produk, pesanan, dan perubahan stok berkurang secara signifikan.
- Owner dan Admin dapat melihat status pesanan, stok, serta ringkasan penjualan tanpa rekap utama di luar sistem.
- Setiap perubahan stok dan aktivitas penting dapat ditelusuri melalui riwayat yang tersedia.
- Produk nonaktif tidak dapat dipesan dan stok tidak tercatat negatif.
- Pembayaran QRIS memiliki bukti dan hasil verifikasi yang terdokumentasi.
- Waktu pelayanan saat pengambilan barang berkurang karena pesanan telah disiapkan sebelumnya.
- Customer menerima informasi status pesanan yang lebih jelas dan dapat memberi rating setelah pesanan selesai.
- Akses fungsi sensitif terbatas pada peran yang berwenang dan kejadian akses dapat diaudit.
- Dashboard dan daftar produk memenuhi target respons operasional yang disepakati, yaitu kurang dari 2 detik pada kondisi penggunaan normal.

## 18. Business Conclusion

Website Toko Alat Tulis Kantor ditujukan untuk mengubah proses penjualan UMKM yang manual menjadi proses digital yang terukur tanpa mengubah karakter utama toko sebagai layanan Pick-Up satu lokasi. Nilai bisnis utamanya adalah ketersediaan katalog daring, pengelolaan stok dan pesanan yang lebih tertib, serta informasi operasional yang dapat dipercaya.

BRD ini menjadi dasar untuk tahap analisis kebutuhan yang lebih rinci dan perancangan solusi berikutnya. Implementasi apa pun harus tetap mematuhi ruang lingkup, aturan bisnis, kontrol risiko, dan indikator keberhasilan yang ditetapkan dalam dokumen ini. Dokumen ini tidak menetapkan kode, desain basis data, migration, controller, model, route, UI, wireframe, maupun diagram.
