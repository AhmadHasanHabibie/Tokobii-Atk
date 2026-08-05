# Stakeholder Analysis Document

## Website Toko Alat Tulis Kantor (ATK)

| Informasi | Keterangan |
| --- | --- |
| Nama proyek | Website Toko Alat Tulis Kantor |
| Target usaha | UMKM |
| Framework arah pengembangan | Laravel 10 |
| Model penjualan | Satu toko, Pick-Up Only |
| Metode pembayaran | COD dan QRIS dengan unggah bukti pembayaran |
| Status dokumen | Acuan stakeholder sebelum penyusunan SRS |

## 1. Executive Summary

Dokumen ini mengidentifikasi stakeholder utama yang menggunakan, mengelola, atau dipengaruhi oleh Website Toko ATK: Admin, Owner, Customer, dan Guest. Analisis ini menetapkan kebutuhan, tanggung jawab, hak akses, tingkat pengaruh, kepentingan, risiko, serta pola hubungan antarpihak agar kebutuhan sistem dan otorisasi pada tahap SRS dapat dibangun secara tepat.

Admin dan Owner adalah stakeholder internal yang menjalankan serta mengendalikan operasional toko. Customer dan Guest adalah stakeholder eksternal yang menggunakan kanal penjualan. Keberhasilan sistem bergantung pada keseimbangan antara kemudahan belanja Customer, ketelitian operasional Owner, kontrol dan keamanan Admin, serta akses informasi yang jelas bagi Guest.

## 2. Stakeholder Identification

| Kelompok | Stakeholder | Posisi dalam proyek | Prioritas |
| --- | --- | --- | --- |
| Internal | Admin | Pengelola sistem, data, pengguna, keamanan, dan audit. | Critical |
| Internal | Owner | Pengelola operasional toko, produk, stok, pesanan, pembayaran, dan laporan. | Critical |
| External | Customer | Pengguna terdaftar yang melakukan pembelian dan Pick-Up. | High |
| External | Guest | Pengunjung publik yang menjelajahi informasi dan berpotensi menjadi Customer. | Medium |

## 3. Stakeholder Description

### 3.1 Admin

Admin adalah pengguna internal dengan kewenangan tertinggi untuk administrasi aplikasi. Admin menjaga kualitas data utama, akun pengguna operasional, pengawasan keamanan, dan keterlacakan aktivitas. Admin bukan pengganti Owner dalam aktivitas pemenuhan pesanan sehari-hari, tetapi dapat memiliki akses pengawasan dan intervensi sesuai kebijakan toko.

### 3.2 Owner

Owner adalah pemilik atau pihak operasional yang bertanggung jawab atas kelancaran aktivitas toko. Owner menggunakan sistem untuk mengelola produk, kategori, stok, pesanan, verifikasi pembayaran QRIS, pembaruan status pesanan, dashboard, dan laporan. Dalam konteks bisnis, Owner memiliki kepentingan langsung terhadap akurasi persediaan serta kepuasan Customer.

### 3.3 Customer

Customer adalah pengguna eksternal yang sudah terdaftar dan memiliki email terverifikasi. Customer menggunakan website untuk melihat produk, memasukkan barang ke cart, checkout, memilih COD atau QRIS, mengunggah bukti QRIS, melacak pesanan, mengambil barang di toko, serta memberi rating atas pembelian yang telah selesai.

### 3.4 Guest

Guest adalah pengunjung yang belum login atau belum memiliki akun. Guest dapat mengenal toko melalui landing page, melihat dan mencari katalog, membaca detail produk, lalu melakukan login atau register. Guest tidak dapat melakukan checkout, melihat data pribadi Customer lain, maupun menjalankan fungsi operasional.

## 4. Stakeholder Objectives

| Stakeholder | Tujuan penggunaan atau keterlibatan |
| --- | --- |
| Admin | Menjaga sistem dapat digunakan secara aman, data terkelola, akses terkendali, dan aktivitas dapat diaudit. |
| Owner | Mengelola operasional dengan efisien, memastikan pesanan dipenuhi, stok akurat, pembayaran valid, dan penjualan dapat dipantau. |
| Customer | Menemukan produk, memesan dengan mudah, membayar secara jelas, dan mengambil barang tanpa menunggu lama. |
| Guest | Mendapatkan informasi produk dan toko sebelum memutuskan mendaftar atau berbelanja. |

## 5. Stakeholder Responsibilities

| Stakeholder | Tanggung jawab utama |
| --- | --- |
| Admin | Mengelola akun Owner dan Customer sesuai kebijakan; menjaga master data dan akses; memantau Security Center; meninjau audit; memastikan tata kelola penggunaan sistem. |
| Owner | Memelihara produk/kategori/stok; memeriksa bukti QRIS; memproses serta memperbarui order; menyiapkan barang; memastikan serah terima Pick-Up; meninjau laporan. |
| Customer | Menjaga keamanan akun; memberikan data yang benar; memilih produk secara tepat; mengunggah bukti QRIS yang valid; membawa kode Pick-Up; memberi rating secara jujur. |
| Guest | Menggunakan fitur publik secara wajar; membuat akun dengan data yang benar bila ingin melakukan transaksi. |

## 6. Stakeholder Needs

| Stakeholder | Kebutuhan agar pekerjaan/tujuan terbantu |
| --- | --- |
| Admin | Dashboard administrasi yang jelas, pengelolaan pengguna/data, hak akses tegas, Security Center, riwayat aktivitas dan akses, serta informasi audit yang mudah ditelusuri. |
| Owner | Pengelolaan produk dan stok yang sederhana, daftar order berdasarkan status, bukti pembayaran yang mudah diperiksa, kode Pick-Up, dashboard operasional, dan laporan yang relevan. |
| Customer | Katalog lengkap, harga dan stok yang akurat, cart/checkout sederhana, status pesanan transparan, instruksi pembayaran/Pick-Up, serta riwayat transaksi. |
| Guest | Landing page yang informatif, katalog dan pencarian yang mudah, detail produk yang jelas, serta proses login/registrasi yang mudah dipahami. |

## 7. Stakeholder Authority

| Stakeholder | Tingkat otoritas | Batas otoritas |
| --- | --- | --- |
| Admin | Tertinggi pada administrasi sistem dan keamanan. | Tidak menggunakan kewenangan untuk mengabaikan prosedur transaksi tanpa kebijakan yang sah; tindakan kritis harus dapat diaudit. |
| Owner | Tinggi pada operasional toko dan pemenuhan pesanan. | Tidak mengelola Security Center tingkat Admin atau membuat akun Owner kecuali diberikan kebijakan baru. |
| Customer | Terbatas pada data dan transaksi miliknya sendiri. | Tidak mengakses data Customer lain, data operasional, atau mengubah status internal pesanan. |
| Guest | Sangat terbatas pada informasi publik. | Tidak melakukan transaksi atau mengakses data/proses terautentikasi. |

## 8. Stakeholder Access Rights

| Stakeholder | Hak akses |
| --- | --- |
| Admin | Dashboard; CRUD Product dan Category; pengelolaan harga, SKU, status, thumbnail, galeri, stok, dan Stock History; pengawasan Order; Customer; pembuatan/pengelolaan Owner; Report; Security Center; Activity Log; Login, IP, Device, Failed Login History; Session Management. |
| Owner | Dashboard; CRUD Product dan Category; harga, SKU, gambar, stok, dan Stock History sesuai kebijakan; daftar dan detail Order; konfirmasi QRIS; pembaruan status Order; Report operasional. |
| Customer | Product; Cart; Checkout Pick-Up; pembayaran COD/QRIS; unggah bukti QRIS; status/timeline dan riwayat Order miliknya; Profile; Rating produk yang memenuhi syarat. |
| Guest | Landing Page; Product, pencarian, kategori, dan detail produk; Login; Register; akses informasi publik lain yang disetujui. |

Hak akses harus mengikuti prinsip *least privilege*: setiap stakeholder hanya memperoleh akses minimum yang diperlukan untuk menjalankan tanggung jawabnya. Detail permission granular akan ditetapkan pada SRS dan rancangan authorization.

## 9. Stakeholder Relationship

| Hubungan | Bentuk interaksi dan ketergantungan |
| --- | --- |
| Admin ↔ Owner | Admin menyediakan akun, akses, kontrol data, dan pengawasan keamanan; Owner menjalankan operasional. Keduanya perlu menyelaraskan kebijakan stok, produk, serta penanganan insiden. |
| Admin ↔ Customer | Admin mengelola status akun dan keamanan sistem; Customer bergantung pada data serta akses yang dikelola dengan benar. Interaksi harus menjaga privasi Customer. |
| Owner ↔ Customer | Owner menyiapkan pesanan, memverifikasi QRIS, memperbarui status, dan menyerahkan barang; Customer memberikan pesanan, bukti pembayaran bila QRIS, serta kode Pick-Up. Hubungan ini paling langsung memengaruhi kualitas layanan. |
| Customer ↔ Guest | Guest dapat beralih menjadi Customer melalui registrasi dan verifikasi email. Customer memiliki akses transaksi yang tidak dimiliki Guest. |
| Guest ↔ Sistem | Guest mengonsumsi informasi publik dan menjadi sumber calon pembeli; sistem perlu memberi informasi yang cepat dan aman tanpa membuka data privat. |
| Owner ↔ Sistem | Owner memasukkan dan menggunakan data operasional inti; sistem memberi validasi, status, riwayat, dan laporan untuk mengurangi kesalahan. |
| Admin ↔ Sistem | Admin menjaga konfigurasi operasional, hak akses, data penting, dan audit; sistem harus memberi kontrol yang kuat dan jejak tindakan yang dapat ditelusuri. |

## 10. Stakeholder Matrix

| Stakeholder | Influence | Interest | Responsibility | Authority | Posisi dan tindakan pengelolaan |
| --- | --- | --- | --- | --- | --- |
| Admin | Tinggi | Tinggi | Tinggi | Tinggi | Kelola secara dekat; libatkan pada keputusan keamanan, akses, kualitas data, dan penerimaan administrasi. |
| Owner | Tinggi | Tinggi | Tinggi | Tinggi pada operasional | Kelola secara dekat; validasi alur order, stok, QRIS, Pick-Up, dan laporan bersama Owner. |
| Customer | Sedang | Tinggi | Sedang pada transaksi sendiri | Rendah | Penuhi kebutuhan utama; uji kemudahan katalog, checkout, status pesanan, dan pengambilan barang. |
| Guest | Rendah | Sedang | Rendah | Sangat rendah | Jaga agar tetap terinformasi; optimalkan kejelasan katalog dan konversi menuju registrasi. |

**Interpretasi posisi:** Admin dan Owner berada pada kuadran pengaruh serta kepentingan tinggi sehingga menjadi pengambil keputusan dan validator utama. Customer berkepentingan tinggi terhadap kualitas layanan tetapi tidak menetapkan kebijakan internal; masukan mereka penting dalam pengujian penerimaan. Guest memiliki pengaruh langsung paling rendah, tetapi pengalaman publik tetap berpengaruh pada pertumbuhan calon Customer.

## 11. Stakeholder Risks

| Stakeholder | Risiko | Dampak |
| --- | --- | --- |
| Admin | Salah menghapus/menonaktifkan produk, mengubah stok atau hak akses secara keliru. | Gangguan katalog, pesanan, kontrol akses, dan kepercayaan pengguna. |
| Admin | Mengabaikan aktivitas atau login mencurigakan. | Insiden keamanan terlambat ditangani. |
| Owner | Salah menyetujui atau menolak bukti QRIS. | Kerugian finansial, pesanan tertunda, atau komplain Customer. |
| Owner | Salah memperbarui stok atau status pesanan. | Barang tidak tersedia, pesanan salah diproses, atau serah terima keliru. |
| Customer | Salah memilih produk/jumlah, salah unggah bukti, atau tidak membawa kode Pick-Up. | Penundaan proses, kebutuhan klarifikasi, atau pesanan tidak dapat diserahkan tepat waktu. |
| Customer | Akun digunakan pihak lain. | Kebocoran data pesanan atau transaksi tidak sah. |
| Guest | Menganggap produk dapat dibeli tanpa registrasi atau checkout. | Friksi konversi dan kebingungan pengguna. |
| Guest | Penyalahgunaan form publik atau percobaan login berulang. | Beban sistem dan risiko keamanan. |

## 12. Risk Mitigation

| Stakeholder | Mitigasi |
| --- | --- |
| Admin | Konfirmasi untuk tindakan kritis, pembatasan hak akses, Activity Log, Stock History, prosedur review, dan pelatihan administrasi. |
| Owner | Status pembayaran/order yang jelas, panduan verifikasi QRIS, daftar kerja berdasarkan prioritas, Stock History, validasi perubahan, dan prosedur serah terima dengan kode Pick-Up. |
| Customer | Instruksi checkout dan QRIS yang jelas, validasi input/unggahan, status pesanan transparan, email verification, reset password aman, dan panduan Pick-Up. |
| Guest | CTA registrasi/login yang jelas, pembatasan akses transaksi, informasi produk yang memadai, rate limiting, serta validasi form publik. |

Mitigasi teknis dan prosedural yang lebih rinci akan diputuskan dalam SRS, desain keamanan, serta SOP operasional. Seluruh aktivitas penting harus mendukung kebutuhan audit yang telah ditetapkan pada dokumen proyek sebelumnya.

## 13. Stakeholder Expectations

| Stakeholder | Ekspektasi terhadap sistem |
| --- | --- |
| Admin | Sistem aman, stabil, data mudah dikelola, tindakan dapat ditelusuri, dan akses dapat dikendalikan tanpa menghambat operasional. |
| Owner | Proses kerja lebih cepat daripada pencatatan manual, stok dan pesanan akurat, QRIS mudah diverifikasi, serta laporan membantu pengambilan keputusan. |
| Customer | Produk mudah ditemukan, informasi harga/stok dapat dipercaya, checkout sederhana, status pesanan jelas, dan Pick-Up cepat. |
| Guest | Informasi toko dan produk mudah diperoleh, performa halaman baik, serta transisi menuju registrasi tidak membingungkan. |

## 14. Communication Overview

| Komunikasi | Pihak | Tujuan | Mekanisme/frekuensi |
| --- | --- | --- | --- |
| Keputusan bisnis dan prioritas | Pemilik toko, Admin, Owner, tim proyek | Menetapkan kebijakan dan menyetujui perubahan penting. | Rapat tinjauan pada setiap milestone atau saat ada change request. |
| Validasi alur operasional | Owner, Admin, tim proyek | Mengonfirmasi produk, stok, QRIS, order, Pick-Up, dan laporan. | Sesi review kebutuhan dan uji penerimaan per modul. |
| Status keamanan dan insiden | Admin, Owner, pemilik toko | Meninjau temuan Security Center dan menentukan tindak lanjut. | Segera untuk insiden; tinjauan berkala untuk aktivitas rutin. |
| Status pesanan | Sistem, Customer, Owner | Memberikan kepastian proses transaksi dan tindakan berikutnya. | Melalui status/timeline pesanan dalam sistem. |
| Informasi katalog dan onboarding | Sistem, Guest/Customer | Memberikan informasi produk serta arahan registrasi/transaksi. | Melalui halaman publik dan pesan sistem yang relevan. |
| Masukan layanan | Customer, Owner/Admin | Memahami kendala dan kualitas pengalaman belanja. | Rating produk, catatan operasional, dan evaluasi berkala. |

Komunikasi yang berkaitan dengan perubahan scope, kebijakan akses, atau proses transaksi harus didokumentasikan dan disetujui oleh pihak berwenang. Informasi pribadi Customer dan detail keamanan tidak boleh disebarkan di luar kebutuhan operasional yang sah.

## 15. Conclusion

Admin, Owner, Customer, dan Guest memiliki kebutuhan yang berbeda tetapi saling bergantung dalam keberhasilan Website Toko ATK. Admin dan Owner adalah stakeholder paling kritis karena keduanya menentukan kualitas data, keamanan, dan kelancaran proses operasional. Customer adalah penerima manfaat utama dari layanan digital, sedangkan Guest menjadi pintu pertumbuhan pelanggan baru.

Dokumen ini menjadi acuan untuk penyusunan SRS, use case, activity flow, desain basis data, serta role permission pada tahap berikutnya. Keputusan implementasi harus mempertahankan prinsip akses minimum, akuntabilitas aktivitas, perlindungan data, dan pengalaman transaksi Pick-Up yang mudah dipahami.
