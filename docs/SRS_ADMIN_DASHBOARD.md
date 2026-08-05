# Software Requirement Specification (SRS)

## Modul Dashboard Admin — Website Toko Alat Tulis Kantor (ATK)

| Informasi | Keterangan |
| --- | --- |
| Dokumen | SRS Admin Dashboard |
| Role utama | Admin |
| Framework arah pengembangan | Laravel 10 |
| Target respons | Halaman dashboard selesai dimuat kurang dari 2 detik pada kondisi data normal yang disepakati |
| Status | Draft SRS Milestone 2 — menunggu penutupan dependency foundation yang relevan |
| Ruang lingkup | Kebutuhan dashboard administrasi; tidak mencakup spesifikasi detail modul sumber data |

## 1. Executive Summary

Dashboard Admin adalah halaman utama yang ditampilkan setelah Admin berhasil login dan memenuhi Face Verification sesuai kebijakan keamanan yang disahkan. Dashboard berfungsi sebagai pusat monitoring tingkat tinggi: memberikan ringkasan kondisi katalog, pengguna, pesanan, pendapatan, stok, aktivitas, dan kejadian yang memerlukan perhatian Admin.

Dashboard tidak menggantikan modul operasional seperti Product, Stock, Order, Report, atau Security Center. Fungsinya adalah menyajikan indikator yang ringkas, akurat, aman, dan dapat ditindaklanjuti melalui navigasi atau Quick Action ke modul terkait. Detail data/transaksi tetap dikelola di modul sumber yang berwenang.

> **Dependency kritis:** definisi status order, status pembayaran, pengakuan pendapatan, pembatalan, no-show, serta perubahan/reservasi stok harus mengikuti *Order, Payment & Stock Lifecycle Matrix* yang belum disahkan pada Foundation Review. Requirement dashboard yang memakai status/pendapatan berlaku setelah matriks tersebut disetujui dan harus direvalidasi bila definisinya berubah.

## 2. Business Objective

Dashboard diperlukan karena Admin membutuhkan satu titik pemantauan untuk mengenali kondisi toko tanpa membuka banyak modul dan melakukan rekap manual. Tanpa dashboard, data produk, stok, order, pembayaran, user, aktivitas, dan keamanan tersebar sehingga keputusan administratif menjadi lambat serta risiko kejadian penting terlewat meningkat.

Tujuan bisnis Dashboard Admin adalah:

- Memberikan visibilitas cepat terhadap kesehatan operasional toko.
- Membantu Admin menemukan prioritas: stok menipis, pembayaran menunggu verifikasi, order baru, kejadian keamanan, dan perubahan penting.
- Mengurangi waktu pencarian data dan kebutuhan rekap manual untuk pemantauan harian/bulanan.
- Mendukung keputusan berbasis data mengenai produk, persediaan, Customer, Owner, dan penjualan.
- Memperkuat akuntabilitas karena indikator dashboard dapat ditelusuri kembali ke modul sumber dan audit yang relevan.

## 3. User Objective

Ketika membuka Dashboard, Admin harus dapat:

- Memastikan sistem menampilkan data pada periode dan konteks yang benar.
- Memahami kondisi inti toko dalam satu halaman ringkas tanpa membaca seluruh detail transaksi.
- Mengetahui apakah ada tindakan segera yang harus dilakukan.
- Berpindah secara cepat dan aman ke modul sumber untuk menangani masalah atau melanjutkan pekerjaan.
- Memantau tren penjualan dan produk terjual untuk mendukung keputusan administrasi/pemilik toko.
- Mengenali kondisi kosong atau gangguan data tanpa menganggapnya sebagai angka nol yang menyesatkan.

## 4. Functional Requirement

| ID | Kebutuhan fungsional |
| --- | --- |
| FR-ADM-DASH-001 | Sistem harus menampilkan Dashboard sebagai halaman awal Admin setelah autentikasi berhasil dan Face Verification Admin valid sesuai kebijakan keamanan. |
| FR-ADM-DASH-002 | Sistem harus menolak akses Dashboard dari Guest, Customer, dan Owner serta mengarahkan pengguna ke respons yang aman sesuai hak aksesnya. |
| FR-ADM-DASH-003 | Dashboard harus menampilkan identitas Admin yang sedang aktif tanpa menampilkan informasi sensitif yang tidak diperlukan. |
| FR-ADM-DASH-004 | Dashboard harus menampilkan total produk aktif sebagai indikator katalog yang dapat dijual. |
| FR-ADM-DASH-005 | Dashboard harus menampilkan total kategori aktif yang digunakan untuk klasifikasi katalog. |
| FR-ADM-DASH-006 | Dashboard harus menampilkan total Customer aktif/terdaftar sesuai definisi status user yang disetujui. |
| FR-ADM-DASH-007 | Dashboard harus menampilkan total Owner aktif yang dapat menjalankan operasional sistem. |
| FR-ADM-DASH-008 | Dashboard harus menampilkan total order pada periode ringkasan yang dipilih atau ditetapkan sistem. |
| FR-ADM-DASH-009 | Dashboard harus menampilkan jumlah order menurut status sebagai ringkasan proses operasional. |
| FR-ADM-DASH-010 | Dashboard harus menampilkan pendapatan hari ini berdasarkan definisi pengakuan pendapatan yang disetujui. |
| FR-ADM-DASH-011 | Dashboard harus menampilkan pendapatan bulan berjalan berdasarkan definisi pengakuan pendapatan yang disetujui. |
| FR-ADM-DASH-012 | Dashboard harus menampilkan daftar produk terlaris berdasarkan jumlah unit terjual pada periode yang jelas. |
| FR-ADM-DASH-013 | Dashboard harus menampilkan daftar produk dengan stok menipis sesuai ambang stok yang ditetapkan kebijakan inventori. |
| FR-ADM-DASH-014 | Dashboard harus menampilkan pesanan terbaru yang boleh dilihat Admin, dengan informasi ringkas dan tautan aman ke detail order. |
| FR-ADM-DASH-015 | Dashboard harus menampilkan aktivitas terbaru yang relevan bagi Admin dari Activity Log sesuai kebijakan retensi dan privasi. |
| FR-ADM-DASH-016 | Dashboard harus menampilkan grafik penjualan per periode dengan satuan, rentang waktu, dan definisi pendapatan yang jelas. |
| FR-ADM-DASH-017 | Dashboard harus menampilkan grafik jumlah produk/unit terjual per periode. |
| FR-ADM-DASH-018 | Dashboard harus menampilkan indikator jumlah bukti QRIS yang menunggu verifikasi oleh Owner atau pihak berwenang. |
| FR-ADM-DASH-019 | Dashboard harus menampilkan indikator Customer baru pada periode yang ditetapkan. |
| FR-ADM-DASH-020 | Dashboard harus menampilkan indikator login gagal berulang atau kejadian keamanan yang memenuhi ambang notifikasi. |
| FR-ADM-DASH-021 | Dashboard harus menampilkan indikator aktivitas mencurigakan yang telah diklasifikasikan oleh Security Center atau aturan keamanan. |
| FR-ADM-DASH-022 | Dashboard harus menyediakan Quick Action untuk menuju proses tambah produk sesuai authorization. |
| FR-ADM-DASH-023 | Dashboard harus menyediakan Quick Action untuk menuju proses tambah kategori sesuai authorization. |
| FR-ADM-DASH-024 | Dashboard harus menyediakan Quick Action untuk melihat daftar/antrean order sesuai authorization. |
| FR-ADM-DASH-025 | Dashboard harus menyediakan Quick Action untuk menuju proses tambah Owner sesuai authorization. |
| FR-ADM-DASH-026 | Dashboard harus menyediakan Quick Action untuk membuka laporan sesuai authorization. |
| FR-ADM-DASH-027 | Dashboard harus menyediakan tautan dari widget/alert ke modul sumber yang relevan tanpa melewati authorization pada modul tersebut. |
| FR-ADM-DASH-028 | Dashboard harus menyediakan informasi waktu pembaruan data atau kondisi cache yang relevan agar Admin dapat memahami kesegaran informasi. |
| FR-ADM-DASH-029 | Dashboard harus mendukung pemilihan rentang waktu untuk widget/grafik yang memang dirancang periodik, tanpa memengaruhi definisi data widget lain secara ambigu. |
| FR-ADM-DASH-030 | Dashboard harus menampilkan keadaan kosong yang informatif ketika belum ada produk, transaksi, aktivitas, atau data grafik. |
| FR-ADM-DASH-031 | Dashboard harus menampilkan keadaan gagal yang aman dan ramah pengguna bila sebagian data tidak dapat dimuat, tanpa membuka detail internal sistem. |
| FR-ADM-DASH-032 | Dashboard harus memungkinkan widget berat/rincian yang tidak kritis dimuat secara terukur tanpa menghambat indikator inti. |
| FR-ADM-DASH-033 | Dashboard harus membatasi jumlah item pada daftar ringkas dan mengarahkan Admin ke modul sumber untuk melihat seluruh data. |
| FR-ADM-DASH-034 | Dashboard harus mencatat akses atau tindakan administratif dari Dashboard bila diwajibkan oleh kebijakan Activity Log. |
| FR-ADM-DASH-035 | Dashboard harus menjaga konsistensi angka antara widget dan modul sumber pada waktu/rentang data yang sama, dengan memperhatikan cache yang dinyatakan. |
| FR-ADM-DASH-036 | Dashboard harus menyajikan nilai uang, tanggal, waktu, angka, dan status menggunakan format yang konsisten dengan konfigurasi aplikasi dan Bahasa Indonesia. |
| FR-ADM-DASH-037 | Dashboard harus menjaga konteks filter/rentang waktu yang dipilih selama sesi halaman sesuai kebijakan UX, tanpa menampilkan data lintas hak akses. |
| FR-ADM-DASH-038 | Dashboard harus menampilkan tindakan tindak lanjut yang relevan untuk notifikasi prioritas, atau menyatakan dengan jelas bila notifikasi hanya informatif. |

## 5. Dashboard Widget

Widget adalah ringkasan informasi; detail tetap tersedia pada modul sumber. Frekuensi pembaruan adalah kebutuhan bisnis/teknis yang harus disesuaikan dengan hasil NFR dan strategi cache, bukan janji pembaruan real-time tanpa batas.

| Widget | Tujuan | Sumber data konseptual | Frekuensi pembaruan | Manfaat bisnis |
| --- | --- | --- | --- | --- |
| Total Produk Aktif | Mengukur jumlah produk yang tersedia dalam katalog aktif. | Data produk berstatus aktif, tidak termasuk data yang dihapus sesuai lifecycle. | Saat halaman dimuat; dapat menggunakan cache singkat yang diinformasikan. | Memastikan Admin mengetahui ukuran katalog yang dapat dijual. |
| Total Kategori Aktif | Mengukur cakupan klasifikasi katalog. | Data kategori aktif sesuai kebijakan kategori. | Saat halaman dimuat; cache singkat bila sesuai. | Membantu memantau keteraturan katalog. |
| Total Customer | Mengetahui basis Customer yang terdaftar/aktif. | Data Customer sesuai definisi status user. | Saat halaman dimuat; cache singkat bila sesuai. | Mendukung evaluasi pertumbuhan pelanggan. |
| Total Owner | Memantau akun operasional yang aktif. | Data user dengan role Owner dan status yang diizinkan. | Saat halaman dimuat. | Membantu kontrol akses serta kapasitas operasional. |
| Total Order | Melihat volume order pada konteks periode yang jelas. | Data order sesuai filter/rentang dan lifecycle yang disetujui. | Saat halaman dimuat; cache pendek bila aman. | Memberi gambaran beban transaksi. |
| Ringkasan Status Order | Mengetahui jumlah order per status yang memerlukan perhatian. | Status order dari lifecycle matrix yang disetujui. | Saat halaman dimuat; prioritas tinggi untuk data yang actionable. | Membantu menentukan antrean kerja dan mendeteksi order menggantung. |
| Pendapatan Hari Ini | Menampilkan nilai pendapatan yang diakui pada tanggal berjalan. | Data order/payment yang memenuhi definisi pengakuan pendapatan. | Saat halaman dimuat; cache sangat singkat atau query agregasi efisien. | Memantau performa harian. |
| Pendapatan Bulan Ini | Menampilkan nilai pendapatan yang diakui pada bulan berjalan. | Data order/payment yang memenuhi definisi pengakuan pendapatan. | Saat halaman dimuat; cache pendek bila konsisten. | Membantu evaluasi pencapaian bulanan. |
| Produk Terlaris | Mengidentifikasi produk/unit dengan penjualan tertinggi pada rentang yang dipilih. | Item order yang sah untuk penghitungan unit terjual. | Saat filter periode berubah atau halaman dimuat; cache per periode bila aman. | Mendukung keputusan katalog dan stok. |
| Produk Stok Menipis | Menandai produk yang perlu perhatian persediaan. | Stok tersedia dan ambang stok yang disahkan pada modul Stock. | Saat halaman dimuat; tidak boleh memakai cache usang yang menutupi risiko signifikan. | Mengurangi risiko kehabisan barang dan gagal memenuhi order. |
| Pesanan Terbaru | Menampilkan order terbaru dalam jumlah terbatas. | Order yang dapat dilihat Admin, diurutkan berdasarkan waktu pembuatan/perubahan sesuai definisi. | Saat halaman dimuat. | Mempercepat pemantauan transaksi baru. |
| Aktivitas Terbaru | Menampilkan tindakan/audit terbaru yang relevan. | Activity Log yang dapat diakses Admin sesuai privacy policy. | Saat halaman dimuat; dapat menggunakan batas item. | Mendukung pengawasan serta investigasi awal. |
| Grafik Penjualan | Menampilkan tren nilai pendapatan per hari/minggu/bulan dalam rentang yang dipilih. | Agregasi pendapatan yang diakui per interval waktu. | Saat filter berubah atau halaman dimuat; cache per periode. | Membantu Admin/pemilik membaca tren penjualan. |
| Grafik Produk Terjual | Menampilkan tren jumlah unit terjual per interval. | Agregasi item order yang sah untuk perhitungan. | Saat filter berubah atau halaman dimuat; cache per periode. | Membantu perencanaan stok/katalog. |
| Bukti QRIS Menunggu Verifikasi | Menandai antrean pembayaran QRIS yang membutuhkan tindakan Owner. | Pembayaran/bukti dengan status menunggu verifikasi. | Saat halaman dimuat; data harus cukup segar untuk tindakan operasional. | Mengurangi keterlambatan pemrosesan order. |
| Customer Baru | Menampilkan jumlah Customer baru pada periode ringkas. | Akun Customer yang berhasil dibuat sesuai definisi status. | Saat halaman dimuat; cache pendek bila sesuai. | Memantau akuisisi pelanggan. |
| Alert Keamanan | Menandai gagal login berulang atau aktivitas mencurigakan yang lolos ambang. | Security Center, failed login history, dan security event. | Saat halaman dimuat; prioritas tinggi dan dapat diperbarui lebih cepat. | Mempercepat deteksi serta respons insiden. |

## 6. Quick Action

Quick Action mempercepat akses ke proses yang sering dilakukan Admin. Quick Action bukan bypass terhadap permission, Form Request, approval, atau Business Rules pada modul tujuan.

| Quick Action | Tujuan | Kondisi akses | Hasil |
| --- | --- | --- | --- |
| Tambah Produk | Memulai proses penambahan produk. | Admin terautentikasi, berwenang, dan memenuhi keamanan sesi. | Admin diarahkan ke proses Product yang relevan. |
| Tambah Kategori | Memulai proses penambahan kategori. | Authorization Admin valid. | Admin diarahkan ke proses Category yang relevan. |
| Lihat Pesanan | Membuka daftar/antrean order untuk monitoring. | Authorization Order Admin valid. | Admin diarahkan ke daftar order dengan konteks aman. |
| Tambah Owner | Memulai pembuatan akun Owner. | Authorization Owner management valid; tindakan sensitif dapat memerlukan kontrol tambahan. | Admin diarahkan ke proses Owner yang relevan. |
| Lihat Laporan | Membuka laporan administrasi. | Authorization Report Admin valid. | Admin diarahkan ke modul Report. |
| Buka Security Center | Meninjau kejadian keamanan dari alert. | Authorization Security Center valid. | Admin diarahkan ke area keamanan yang relevan. |

## 7. Dashboard Notification

Notifikasi dashboard adalah indikator prioritas, bukan pengganti Security Center, modul Order, atau SOP operasional. Notifikasi memiliki klasifikasi prioritas dan tautan ke sumber data bila tindakan lanjutan diperlukan.

| Notifikasi | Pemicu bisnis/teknis | Prioritas awal | Tindakan yang diharapkan |
| --- | --- | --- | --- |
| Stok menipis | Stok tersedia mencapai atau berada di bawah ambang yang disetujui. | Tinggi | Tinjau modul Stock dan lakukan tindakan sesuai SOP. |
| Bukti pembayaran menunggu verifikasi | Bukti QRIS berada pada status menunggu verifikasi. | Tinggi | Tinjau antrean/Owner yang bertanggung jawab; jangan menyetujui tanpa proses verifikasi. |
| Login gagal berulang | Percobaan gagal dari akun/IP/perangkat memenuhi ambang keamanan. | Tinggi | Tinjau Security Center dan lakukan respons sesuai SOP. |
| Aktivitas mencurigakan | Security event/rule mendeteksi pola yang perlu ditinjau. | Tinggi/Kritis sesuai klasifikasi | Investigasi melalui Security Center; eskalasi bila diperlukan. |
| Customer baru | Customer baru berhasil terdaftar sesuai definisi. | Informasional | Pantau pertumbuhan; tidak memerlukan tindakan langsung. |
| Order baru | Order dibuat dan masuk status yang dapat ditindaklanjuti. | Sedang/Tinggi sesuai lifecycle | Tinjau daftar order dan pastikan Owner menerima proses operasional. |
| Order melewati batas waktu | Order/payment/ambil barang melewati batas waktu yang disetujui. | Tinggi | Ikuti SOP expire/cancel/no-show setelah lifecycle matrix tersedia. |

Ambang, kanal pemberitahuan di luar dashboard, deduplikasi, pengakuan telah dibaca, dan eskalasi akan didefinisikan pada SRS Security Center/Order/Stock. Dashboard hanya menampilkan ringkasan yang sesuai akses Admin.

## 8. Business Rules

| ID | Aturan bisnis |
| --- | --- |
| BR-ADM-DASH-001 | Hanya pengguna dengan role Admin dan sesi valid yang dapat mengakses Dashboard Admin. |
| BR-ADM-DASH-002 | Dashboard hanya ditampilkan setelah Face Verification Admin valid sesuai kebijakan keamanan final; kegagalan atau kedaluwarsa verifikasi tidak boleh memberikan akses dashboard. |
| BR-ADM-DASH-003 | Semua data dashboard harus berasal dari sumber data sistem yang sah dan mengikuti filter, status, serta lifecycle yang telah disetujui. |
| BR-ADM-DASH-004 | Produk nonaktif tidak dihitung sebagai Total Produk Aktif dan tidak diperlakukan sebagai produk yang dapat dijual. |
| BR-ADM-DASH-005 | Data yang telah dihapus secara soft delete tidak ditampilkan atau dihitung secara default, kecuali widget secara eksplisit dirancang untuk tujuan audit dan menjelaskan konteksnya. |
| BR-ADM-DASH-006 | Total Customer dan Total Owner mengikuti definisi status akun aktif/terdaftar yang disetujui pada modul User Management. |
| BR-ADM-DASH-007 | Total Order, ringkasan status, dan daftar order hanya menghitung/menampilkan order sesuai definisi status dan rentang waktu yang jelas. |
| BR-ADM-DASH-008 | Pendapatan hari ini dan bulan ini hanya menghitung order/payment yang memenuhi definisi pengakuan pendapatan pada lifecycle matrix yang disetujui. |
| BR-ADM-DASH-009 | Order yang dibatalkan, ditolak, kedaluwarsa, atau belum memenuhi syarat pengakuan pendapatan tidak boleh dihitung sebagai pendapatan, kecuali lifecycle matrix secara eksplisit menetapkan sebaliknya. |
| BR-ADM-DASH-010 | Produk terlaris dihitung dari unit item order yang sah pada periode yang dipilih; definisi “sah” mengikuti lifecycle matrix. |
| BR-ADM-DASH-011 | Produk stok menipis ditentukan oleh stok tersedia dan ambang stok yang sah; sistem tidak boleh menggunakan angka stok negatif sebagai indikator normal. |
| BR-ADM-DASH-012 | Bukti QRIS menunggu verifikasi hanya menampilkan item yang benar-benar berada pada status menunggu tindakan, bukan bukti yang sudah disetujui, ditolak, atau kedaluwarsa. |
| BR-ADM-DASH-013 | Daftar pesanan terbaru dan aktivitas terbaru dibatasi jumlahnya; Admin harus diarahkan ke modul sumber untuk melihat data lengkap. |
| BR-ADM-DASH-014 | Detail pribadi Customer, bukti pembayaran, data autentikasi, dan informasi keamanan sensitif tidak boleh ditampilkan lebih luas daripada yang diperlukan oleh widget. |
| BR-ADM-DASH-015 | Dashboard tidak boleh menjadi satu-satunya tempat untuk mengubah status transaksi atau melakukan tindakan yang harus mengikuti flow modul sumber. |
| BR-ADM-DASH-016 | Quick Action dan tautan widget harus memeriksa authorization pada tujuan; akses dashboard tidak otomatis memberi akses tanpa batas pada setiap tindakan. |
| BR-ADM-DASH-017 | Jika cache digunakan, dashboard harus menampilkan informasi pembaruan yang cukup dan cache harus diinvalidadi/berakhir sesuai tingkat sensitivitas data. |
| BR-ADM-DASH-018 | Data alert keamanan dan audit hanya dapat dilihat Admin yang berwenang serta harus mengikuti data classification dan retention policy. |
| BR-ADM-DASH-019 | Dashboard harus membedakan nilai nol yang valid dari data yang gagal dimuat atau belum tersedia. |
| BR-ADM-DASH-020 | Setiap format waktu/tanggal mengikuti zona waktu aplikasi yang disetujui; periode “hari ini” dan “bulan ini” tidak boleh ambigu. |
| BR-ADM-DASH-021 | Perubahan data sumber harus tercermin pada dashboard sesuai strategi pembaruan; dashboard tidak boleh mengklaim real-time bila menggunakan cache/refresh periodik. |
| BR-ADM-DASH-022 | Indikator keamanan dengan prioritas tinggi tidak boleh disembunyikan hanya karena tidak ada data bisnis lain pada dashboard. |
| BR-ADM-DASH-023 | Akses dashboard dan tindakan administratif dari dashboard dicatat bila diwajibkan oleh Activity Log/Security policy. |
| BR-ADM-DASH-024 | Widget/grafik harus menampilkan unit, periode, dan label yang cukup agar Admin tidak salah menafsirkan metrik. |
| BR-ADM-DASH-025 | Dashboard tidak boleh memuat seluruh riwayat order, aktivitas, atau log ke dalam satu halaman; pembatasan, agregasi, dan pagination pada modul sumber wajib diterapkan. |

## 9. Validation Rules

Dashboard tidak memiliki form transaksi utama, tetapi wajib memvalidasi konteks akses, input filter, dan kondisi data agar informasi tidak menyesatkan.

| Kondisi | Perilaku yang diwajibkan |
| --- | --- |
| Rentang waktu tidak valid | Sistem menolak atau menormalkan input sesuai batas yang disetujui, menampilkan pesan aman, dan tidak menjalankan query rentang tak terbatas. |
| Rentang waktu terlalu luas | Sistem membatasi sesuai kebijakan atau mengarahkan Admin ke modul Report; dashboard tidak melakukan agregasi berat tanpa kontrol. |
| Tidak ada produk/kategori/Customer/Owner | Widget menampilkan empty state dan nilai yang jelas, bukan error. |
| Belum ada order/pendapatan | Widget dan grafik menampilkan keadaan belum ada transaksi; nilai nol dibedakan dari kegagalan data. |
| Tidak ada produk stok menipis | Widget menampilkan pesan bahwa tidak ada item yang memerlukan perhatian. |
| Tidak ada aktivitas/log yang dapat ditampilkan | Widget menampilkan empty state yang tidak membuka informasi keamanan tersembunyi. |
| Data grafik tidak lengkap | Grafik menampilkan keterangan data belum tersedia/tidak lengkap dan tidak menggambar tren palsu. |
| Parameter filter dimanipulasi | Sistem memvalidasi tipe, enum, rentang, dan authorization di server; data lintas akses tidak ditampilkan. |
| Cache belum tersedia/kedaluwarsa | Sistem menggunakan fallback aman atau menampilkan indikator data belum dapat diperbarui; tidak menampilkan nilai lama sebagai nilai terbaru tanpa penanda. |

## 10. Security Requirement

| ID | Kebutuhan keamanan |
| --- | --- |
| SR-ADM-DASH-001 | Dashboard harus berada di balik autentikasi Laravel berbasis sesi yang valid. |
| SR-ADM-DASH-002 | Dashboard harus dilindungi middleware role Admin; Guest, Customer, dan Owner tidak boleh dapat mengaksesnya melalui URL langsung. |
| SR-ADM-DASH-003 | Face Verification Admin harus diperiksa sesuai kebijakan final sebelum akses Dashboard atau tindakan sensitif diberikan. |
| SR-ADM-DASH-004 | Setiap tautan/Quick Action harus menjalankan authorization pada modul tujuan, termasuk pemeriksaan policy/ownership yang relevan. |
| SR-ADM-DASH-005 | Semua input filter/rentang waktu harus divalidasi server-side; query harus menggunakan parameter binding melalui mekanisme Laravel yang aman. |
| SR-ADM-DASH-006 | Form tindakan dari dashboard, bila ada, harus memakai proteksi CSRF Laravel dan tidak boleh mengubah data melalui GET. |
| SR-ADM-DASH-007 | Dashboard tidak boleh merender password, token, secret, data biometrik mentah, bukti QRIS, atau detail log sensitif secara tidak perlu. |
| SR-ADM-DASH-008 | Output harus menggunakan escaping Blade secara default dan konten dinamis harus aman dari XSS. |
| SR-ADM-DASH-009 | Akses dan tindakan penting harus dapat masuk ke Activity Log/Login History sesuai Security Policy. |
| SR-ADM-DASH-010 | Sesi kedaluwarsa, logout, perubahan password, atau pencabutan akses harus mengakhiri akses dashboard sesuai kebijakan session management. |

## 11. Performance Requirement

| ID | Kebutuhan performa dan strategi |
| --- | --- |
| PR-ADM-DASH-001 | Dashboard harus selesai dimuat dalam waktu kurang dari 2 detik pada kondisi data normal, environment target, dan skenario uji yang disetujui. |
| PR-ADM-DASH-002 | Indikator inti harus diprioritaskan; widget berat/non-kritis dapat dimuat terukur setelah konten inti bila hasil pengukuran membenarkannya. |
| PR-ADM-DASH-003 | Perhitungan total dan grafik harus memakai query agregasi efisien, bukan memuat seluruh record ke memori aplikasi. |
| PR-ADM-DASH-004 | Relasi yang dibutuhkan oleh daftar ringkas harus memakai eager loading yang tepat untuk mencegah N+1 query. |
| PR-ADM-DASH-005 | Daftar ringkas pesanan, aktivitas, dan produk stok menipis harus dibatasi; data lengkap dibuka melalui modul sumber dengan pagination. |
| PR-ADM-DASH-006 | Indeks data untuk filter, status, tanggal, dan relasi dashboard harus ditetapkan pada desain data berdasarkan query aktual. |
| PR-ADM-DASH-007 | Cache boleh digunakan untuk metrik agregat dan grafik yang tidak memerlukan konsistensi instan; key, TTL, invalidasi, dan indikator kesegaran harus ditentukan. |
| PR-ADM-DASH-008 | Alert kritis dan stok menipis tidak boleh bergantung pada cache yang terlalu lama sehingga menghambat tindakan operasional. |
| PR-ADM-DASH-009 | Semua query dashboard harus diukur/ditinjau pada data representatif sebelum acceptance performance disetujui. |
| PR-ADM-DASH-010 | Dashboard harus kompatibel dengan route/config/view cache dan autoloader optimization pada deployment production sesuai prosedur Laravel. |

## 12. Error Handling

| Kondisi error | Perilaku sistem |
| --- | --- |
| Database atau layanan data tidak dapat diakses | Tampilkan pesan umum yang aman; catat error dengan context aman; jangan tampilkan SQL, stack trace, credential, atau data internal. |
| Satu widget gagal dimuat | Indikator inti lain tetap ditampilkan bila aman; widget gagal menunjukkan state error/retry yang sesuai dan error dicatat. |
| Query agregasi/grafik gagal | Tampilkan state tidak dapat memuat data, bukan grafik/nilai palsu; catat kegagalan untuk investigasi. |
| Sesi habis atau akses dicabut | Hentikan akses dashboard dan arahkan Admin ke autentikasi/penanganan sesi sesuai kebijakan. |
| Face Verification belum valid/kedaluwarsa | Tolak akses atau tindakan yang memerlukannya dan arahkan ke proses verifikasi tanpa membuka data dashboard. |
| Authorization gagal pada Quick Action | Jangan buka modul tujuan; tampilkan respons akses ditolak yang aman dan catat bila kebijakan mewajibkan. |
| Filter tidak valid | Kembalikan ke nilai aman/default yang disetujui atau tampilkan pesan validasi; jangan jalankan query berisiko. |
| Cache/service pendukung bermasalah | Gunakan fallback yang tidak merusak kebenaran data atau tampilkan informasi data sementara tidak tersedia. |
| Tidak ada data | Tampilkan empty state yang informatif; kondisi ini bukan exception atau error sistem. |

## 13. Future Enhancement

- Insight berbantuan AI yang hanya memberi rekomendasi dan selalu dapat ditelusuri ke data sumber.
- Forecast penjualan dan kebutuhan stok setelah data historis serta definisi pendapatan stabil.
- Grafik interaktif dengan filter/segmentasi lebih dalam untuk periode, kategori, atau produk.
- Dashboard per cabang setelah multi-branch resmi menjadi scope dan model data cabang tersedia.
- KPI Owner dan SLA operasional, seperti waktu verifikasi QRIS atau waktu siap Pick-Up.
- Scheduled report dan notifikasi proaktif melalui kanal yang disetujui, misalnya email atau WhatsApp resmi.
- Drill-down analitik yang lebih luas, export dashboard, serta benchmark target bisnis.
- Monitoring kesehatan integrasi, queue, dan infrastruktur ketika skala operasional meningkat.

Semua future enhancement harus melalui Change Request, analisis privasi/security/performance, dan tidak boleh mengubah definisi metrik existing tanpa versioning dokumentasi.

## 14. Acceptance Criteria

| ID | Given | When | Then |
| --- | --- | --- | --- |
| AC-ADM-DASH-001 | Admin memiliki sesi valid dan Face Verification valid. | Admin menyelesaikan login. | Sistem menampilkan Dashboard Admin sebagai halaman awal. |
| AC-ADM-DASH-002 | Pengguna adalah Customer, Owner, atau Guest. | Pengguna mencoba membuka URL Dashboard Admin. | Sistem menolak akses dan tidak menampilkan data dashboard. |
| AC-ADM-DASH-003 | Data produk aktif tersedia. | Admin membuka dashboard. | Sistem menampilkan total produk aktif yang konsisten dengan modul Product pada konteks waktu sama. |
| AC-ADM-DASH-004 | Tidak ada produk/kategori/Customer/Owner yang memenuhi widget. | Admin membuka dashboard. | Widget menampilkan empty state atau nol yang jelas tanpa error. |
| AC-ADM-DASH-005 | Order berada pada beberapa status sah. | Admin membuka dashboard. | Sistem menampilkan ringkasan jumlah per status sesuai lifecycle matrix yang disetujui. |
| AC-ADM-DASH-006 | Ada order/payment yang memenuhi pengakuan pendapatan hari ini. | Admin membuka dashboard. | Sistem menampilkan pendapatan hari ini sesuai definisi matriks lifecycle. |
| AC-ADM-DASH-007 | Ada order dibatalkan atau belum memenuhi pengakuan pendapatan. | Admin membuka dashboard. | Order tersebut tidak menambah nilai pendapatan kecuali lifecycle matrix menetapkan sebaliknya. |
| AC-ADM-DASH-008 | Produk memiliki stok pada atau di bawah ambang sah. | Admin membuka dashboard. | Sistem menampilkan produk tersebut pada widget stok menipis dan menyediakan tautan ke modul sumber yang berwenang. |
| AC-ADM-DASH-009 | Ada bukti QRIS menunggu verifikasi. | Admin membuka dashboard. | Sistem menampilkan jumlah/alert antrean verifikasi tanpa membuka bukti pembayaran secara langsung. |
| AC-ADM-DASH-010 | Ada aktivitas audit terbaru yang dapat dilihat Admin. | Admin membuka dashboard. | Sistem menampilkan jumlah item ringkas yang dibatasi dan tautan aman ke detail Security Center. |
| AC-ADM-DASH-011 | Ada percobaan login gagal yang melewati ambang keamanan. | Admin membuka dashboard. | Sistem menampilkan alert sesuai klasifikasi dan menyediakan arah tindak lanjut ke Security Center. |
| AC-ADM-DASH-012 | Admin memilih rentang waktu valid. | Dashboard memuat grafik periodik. | Grafik penjualan dan unit terjual memakai rentang, label, dan data agregat yang sesuai. |
| AC-ADM-DASH-013 | Admin memilih rentang waktu tidak valid atau terlalu luas. | Admin menerapkan filter. | Sistem menolak/menormalkan input secara aman tanpa menjalankan query tak terkendali. |
| AC-ADM-DASH-014 | Salah satu sumber data widget gagal diakses. | Admin membuka dashboard. | Widget terkait menunjukkan state gagal yang aman, error dicatat, dan widget lain tetap tersedia bila aman. |
| AC-ADM-DASH-015 | Admin menggunakan Quick Action Tambah Produk. | Admin memilih aksi tersebut. | Sistem mengarahkan ke modul Product dan tetap memeriksa authorization pada tujuan. |
| AC-ADM-DASH-016 | Sesi Admin berakhir atau Face Verification tidak lagi valid. | Admin mencoba membuka/menyegarkan dashboard. | Sistem menghentikan akses dan mengarahkan ke proses autentikasi/verifikasi yang sesuai tanpa mengekspos data. |
| AC-ADM-DASH-017 | Data normal representatif dan environment target tersedia. | Dashboard dimuat sesuai skenario uji performa. | Indikator inti dashboard selesai dimuat dalam waktu kurang dari 2 detik. |

## 15. Definition of Done

Dashboard Admin dianggap selesai hanya apabila:

- Semua Functional Requirement dan Business Rules pada dokumen ini telah diterapkan atau memiliki pengecualian Change Request yang disetujui.
- Dependency kritis lifecycle order–payment–stock dan keputusan Face Verification telah disahkan serta requirement dashboard yang terdampak direvalidasi.
- Implementasi mengikuti Development Bible, Folder Architecture, naming convention, service layer, Form Request, middleware, dan policy yang relevan.
- Akses hanya tersedia bagi Admin dengan autentikasi, session, role, Face Verification sesuai kebijakan, CSRF pada aksi, validasi input, output escaping, dan audit yang sesuai.
- Metrik/daftar dashboard terbukti konsisten dengan modul sumber pada konteks periode yang sama.
- Query menggunakan agregasi, eager loading, pembatasan item, pagination pada modul sumber, dan strategi cache yang aman; tidak terdapat N+1 query yang diketahui.
- Target kurang dari 2 detik divalidasi pada data dan environment uji yang disepakati.
- Empty state, error state, session expiry, authorization failure, dan kegagalan widget telah diuji.
- Unit/feature/manual test yang relevan lulus, termasuk jalur berhasil, gagal, dan akses tidak sah.
- Security, performance, code, dokumentasi, dan acceptance review telah selesai tanpa temuan kritis terbuka.
- Dokumentasi SRS, Business Rules, changelog, dan panduan operasional yang terdampak telah diperbarui.

## 16. Conclusion

Dashboard Admin menyediakan pusat monitoring yang aman dan ringkas bagi Super Administrator untuk mengawasi katalog, pengguna, pesanan, pendapatan, stok, aktivitas, dan keamanan. Dashboard diarahkan untuk mempercepat identifikasi prioritas serta navigasi ke modul sumber, bukan menggantikan proses operasional atau kontrol detail pada modul tersebut.

Dokumen ini siap menjadi acuan spesifikasi Dashboard Admin setelah dependency foundation yang disebutkan disahkan. Implementasi hanya dapat dimulai ketika DoR dipenuhi, khususnya setelah definisi lifecycle order–payment–stock, kebijakan Face Verification, desain data, dan rancangan UI pada langkah yang tepat telah final.
