# Software Requirement Specification (SRS)

## Modul Product Admin — Website Toko Alat Tulis Kantor (ATK)

| Informasi | Keterangan |
| --- | --- |
| Dokumen | SRS Modul Product Admin |
| Role utama | Admin |
| Framework arah pengembangan | Laravel 10 |
| Target respons | Daftar produk kurang dari 2 detik pada kondisi data normal dan skenario uji yang disetujui |
| Status | Draft SRS Milestone 2 — membutuhkan validasi dampak data dan inventory sebelum implementasi |
| Batas | Mengatur produk oleh Admin; tidak membahas modul Product Owner, Category, Stock, Supplier, atau UI final secara detail |

## 1. Executive Summary

Modul Product Admin adalah pusat pengelolaan informasi produk ATK yang menjadi sumber katalog bagi Guest dan Customer serta referensi bagi stok, cart, checkout, order, laporan, dan audit. Admin dapat membuat, melihat, mencari, memfilter, mengurutkan, memperbarui, menonaktifkan, menghapus secara lunak, serta memulihkan data produk sesuai kebijakan sistem.

Modul ini memastikan produk memiliki informasi bisnis yang konsisten: nama, SKU, kategori, harga, deskripsi, thumbnail, galeri, status, dan stok awal. Perubahan produk yang penting harus tervalidasi, terotorisasi, dan dapat diaudit. Pengelolaan stok awal harus menggunakan proses inventori yang tercatat pada Stock History; modul Product tidak boleh menjadi jalur perubahan stok tanpa jejak.

> **Dependency:** sebelum implementasi, ERD/Database Dictionary produk dan *Order, Payment & Stock Lifecycle Matrix* harus final. Definisi stok awal, perubahan stok, soft delete, dan pemulihan produk wajib diselaraskan dengan modul Stock serta data transaksi yang telah ada.

## 2. Business Objective

Produk adalah inti penjualan toko. Data produk yang tidak lengkap, salah harga, SKU ganda, kategori tidak tepat, media buruk, atau status aktif yang keliru akan langsung memengaruhi pengalaman Customer, ketepatan stok, proses checkout, dan laporan.

Modul Product Admin diperlukan untuk:

- Menyediakan satu sumber data produk yang konsisten dan terkontrol.
- Menjaga informasi katalog yang tampil kepada Guest/Customer tetap akurat dan layak dijual.
- Mendukung identifikasi produk melalui SKU serta klasifikasi melalui kategori.
- Menjaga perubahan harga, status, media, dan stok awal agar dapat ditelusuri.
- Mengurangi kesalahan manual dalam pengelolaan katalog dan persediaan.
- Menjaga riwayat transaksi tetap utuh melalui soft delete dan pembatasan penghapusan permanen.

## 3. User Objective

Admin menggunakan Modul Product untuk:

- Menambah produk baru dengan data katalog lengkap dan stok awal yang valid.
- Memperbarui informasi produk tanpa mengganggu data transaksi atau riwayat stok.
- Menemukan produk dengan cepat menggunakan pencarian SKU/nama, filter, sort, dan pagination.
- Menentukan produk mana yang aktif untuk dijual atau nonaktif untuk dihentikan dari katalog.
- Mengelola thumbnail serta galeri dengan aman dan efisien.
- Menangani produk yang tidak lagi digunakan melalui soft delete atau restore sesuai kebijakan.
- Mengetahui dampak penting perubahan produk melalui informasi detail dan Activity Log.

## 4. Functional Requirement

| ID | Kebutuhan fungsional |
| --- | --- |
| FR-ADM-PROD-001 | Sistem harus membatasi akses Modul Product Admin hanya kepada Admin dengan sesi dan otorisasi valid. |
| FR-ADM-PROD-002 | Sistem harus menampilkan daftar produk untuk Admin dalam bentuk paginasi. |
| FR-ADM-PROD-003 | Daftar produk harus menampilkan informasi ringkas minimal nama, SKU, kategori, harga, status, stok tersedia, thumbnail, dan waktu pembaruan yang relevan. |
| FR-ADM-PROD-004 | Admin harus dapat membuka detail produk yang menampilkan informasi katalog, media, status, stok ringkas, serta metadata/audit yang diizinkan. |
| FR-ADM-PROD-005 | Admin harus dapat membuat produk baru dengan field wajib yang tervalidasi. |
| FR-ADM-PROD-006 | Sistem harus menghasilkan atau menerima SKU sesuai kebijakan SKU yang disetujui dan memvalidasi keunikannya. |
| FR-ADM-PROD-007 | Admin harus dapat mengubah nama produk tanpa mengubah identitas riwayat transaksi yang sudah ada. |
| FR-ADM-PROD-008 | Admin harus dapat memilih kategori aktif yang sah ketika membuat atau memperbarui produk. |
| FR-ADM-PROD-009 | Admin harus dapat memasukkan dan memperbarui deskripsi produk sesuai batas validasi. |
| FR-ADM-PROD-010 | Admin harus dapat menetapkan harga produk yang valid dan menggunakan format mata uang yang konsisten. |
| FR-ADM-PROD-011 | Admin harus dapat mengunggah satu thumbnail produk pada saat membuat produk. |
| FR-ADM-PROD-012 | Admin harus dapat mengganti thumbnail produk melalui proses unggah yang tervalidasi. |
| FR-ADM-PROD-013 | Admin harus dapat menambahkan nol sampai jumlah maksimum galeri produk yang diizinkan. |
| FR-ADM-PROD-014 | Admin harus dapat melihat galeri produk pada detail/edit produk sesuai authorization. |
| FR-ADM-PROD-015 | Admin harus dapat menghapus satu gambar galeri tanpa menghapus gambar galeri lain. |
| FR-ADM-PROD-016 | Admin harus dapat mengatur urutan tampilan gambar galeri sesuai batas yang ditetapkan. |
| FR-ADM-PROD-017 | Sistem harus memvalidasi tipe, ekstensi, ukuran, dimensi, dan integritas file thumbnail/galeri sebelum menyimpannya. |
| FR-ADM-PROD-018 | Sistem harus membuat atau menyediakan varian thumbnail yang dioptimalkan untuk daftar produk tanpa mengubah file sumber secara tidak dapat dipulihkan. |
| FR-ADM-PROD-019 | Admin harus dapat mengatur status produk aktif atau nonaktif. |
| FR-ADM-PROD-020 | Sistem harus mengeluarkan produk nonaktif dari katalog dan proses pembelian Customer/Guest sesuai Business Rules. |
| FR-ADM-PROD-021 | Admin harus dapat memasukkan stok awal saat membuat produk baru. |
| FR-ADM-PROD-022 | Sistem harus memproses stok awal melalui proses inventori yang membuat Stock History dengan referensi produk, alasan, actor, kuantitas, dan waktu. |
| FR-ADM-PROD-023 | Sistem harus menolak stok awal negatif dan tidak membuat perubahan inventori parsial. |
| FR-ADM-PROD-024 | Admin harus dapat melihat stok tersedia produk sebagai informasi ringkas; penyesuaian stok setelah produk dibuat mengikuti modul/proses Stock. |
| FR-ADM-PROD-025 | Admin harus dapat mencari produk berdasarkan nama produk atau SKU. |
| FR-ADM-PROD-026 | Sistem harus mendukung pencarian yang tidak peka huruf besar-kecil sesuai kemampuan collation/data yang disetujui. |
| FR-ADM-PROD-027 | Admin harus dapat memfilter daftar produk minimal berdasarkan kategori, status aktif/nonaktif, status soft delete, dan kondisi stok yang disetujui. |
| FR-ADM-PROD-028 | Admin harus dapat mengurutkan daftar produk minimal berdasarkan nama, SKU, harga, stok, tanggal dibuat, dan tanggal diperbarui. |
| FR-ADM-PROD-029 | Sistem harus menjaga filter, pencarian, sort, dan halaman daftar secara konsisten selama navigasi daftar produk. |
| FR-ADM-PROD-030 | Sistem harus menampilkan kondisi kosong yang jelas ketika pencarian/filter tidak menghasilkan produk. |
| FR-ADM-PROD-031 | Admin harus dapat memperbarui data produk yang masih aktif maupun nonaktif, sesuai validasi dan kebijakan transaksi. |
| FR-ADM-PROD-032 | Sistem harus meminta konfirmasi untuk tindakan yang dapat mengubah ketersediaan katalog atau lifecycle produk secara material, seperti nonaktif, soft delete, atau restore. |
| FR-ADM-PROD-033 | Admin harus dapat melakukan soft delete terhadap produk sesuai kebijakan lifecycle data. |
| FR-ADM-PROD-034 | Sistem harus mengeluarkan produk yang di-soft delete dari daftar/katalog default dan mencegah pembelian produk tersebut. |
| FR-ADM-PROD-035 | Admin harus dapat melihat daftar produk yang di-soft delete melalui filter/area yang berwenang. |
| FR-ADM-PROD-036 | Admin harus dapat me-restore produk yang di-soft delete apabila kategori, SKU, media wajib, status, dan constraint lain memungkinkan. |
| FR-ADM-PROD-037 | Sistem harus menolak restore jika SKU berkonflik atau data wajib/constraint produk tidak lagi valid, serta menjelaskan tindakan perbaikan secara aman. |
| FR-ADM-PROD-038 | Sistem tidak boleh menyediakan hard delete produk pada V1 melalui antarmuka Admin. |
| FR-ADM-PROD-039 | Sistem harus mencegah perubahan yang merusak referensi order, order item, rating, stok, atau audit yang sudah ada. |
| FR-ADM-PROD-040 | Sistem harus mencatat pembuatan, perubahan penting, perubahan status, soft delete, restore, dan pengelolaan media pada Activity Log sesuai kebijakan audit. |
| FR-ADM-PROD-041 | Sistem harus mencatat actor, waktu, dan perubahan stok awal pada Stock History. |
| FR-ADM-PROD-042 | Sistem harus menjaga agar data produk yang ditampilkan kepada Guest/Customer hanya berasal dari produk aktif dan valid. |
| FR-ADM-PROD-043 | Admin harus dapat membatalkan proses tambah/edit sebelum disimpan tanpa mengubah data produk yang tersimpan. |
| FR-ADM-PROD-044 | Sistem harus menampilkan hasil berhasil/gagal yang ramah pengguna setelah tindakan produk, tanpa mengungkap detail sistem. |
| FR-ADM-PROD-045 | Sistem harus mencegah pengiriman form ganda yang berpotensi membuat produk, media, atau catatan stok awal duplikat. |
| FR-ADM-PROD-046 | Sistem harus menangani kegagalan sebagian secara atomik untuk proses yang mengubah produk, media wajib, stok awal, dan audit yang harus konsisten. |
| FR-ADM-PROD-047 | Admin harus dapat melihat indikator apakah produk pernah memiliki transaksi/riwayat yang membatasi lifecycle-nya, tanpa menampilkan data Customer yang tidak diperlukan. |
| FR-ADM-PROD-048 | Sistem harus membatasi tampilan daftar/galeri dan ukuran respons agar modul tetap responsif ketika jumlah produk/media bertumbuh. |
| FR-ADM-PROD-049 | Sistem harus menyediakan tautan aman dari produk ke informasi stok/riwayat yang diizinkan tanpa memberi akses di luar hak Admin. |
| FR-ADM-PROD-050 | Sistem harus menggunakan format nilai harga, tanggal, waktu, dan status yang konsisten dengan konfigurasi aplikasi. |
| FR-ADM-PROD-051 | Sistem harus mempertahankan SKU sebagai identitas produk yang dapat ditelusuri; perubahan SKU pada produk yang memiliki riwayat transaksi harus mengikuti kebijakan identitas produk yang disetujui. |
| FR-ADM-PROD-052 | Sistem harus menampilkan peringatan sebelum perubahan SKU atau kategori pada produk yang telah memiliki transaksi, apabila perubahan tersebut dapat memengaruhi pelaporan atau interpretasi riwayat. |
| FR-ADM-PROD-053 | Admin harus dapat melihat informasi validasi field dan unggahan secara spesifik pada field yang bermasalah tanpa menghapus input valid lain secara tidak perlu. |
| FR-ADM-PROD-054 | Sistem harus memisahkan media produk publik dari berkas privat seperti bukti pembayaran; modul Product tidak boleh memberi akses ke berkas privat transaksi. |

## 5. Business Rules

| ID | Aturan bisnis |
| --- | --- |
| BR-ADM-PROD-001 | Hanya Admin yang berwenang dapat mengakses dan menjalankan tindakan pada Modul Product Admin. |
| BR-ADM-PROD-002 | Setiap produk wajib memiliki nama produk, SKU, kategori aktif, harga, thumbnail, status, dan stok awal yang valid pada saat dibuat. |
| BR-ADM-PROD-003 | Nama produk harus menggambarkan barang yang dijual dan tidak boleh hanya berisi spasi atau karakter tanpa makna. |
| BR-ADM-PROD-004 | SKU wajib unik pada seluruh produk yang masih tersimpan, termasuk produk soft deleted, untuk menjaga keterlacakan riwayat. |
| BR-ADM-PROD-005 | SKU dinormalisasi sesuai kebijakan format sebelum pemeriksaan unik; perbedaan kapitalisasi atau spasi tidak boleh menciptakan SKU berbeda yang membingungkan. |
| BR-ADM-PROD-006 | Harga jual harus lebih besar dari nol dan tidak boleh negatif, kosong, atau mengandung format numerik yang ambigu. |
| BR-ADM-PROD-007 | Kategori produk wajib ada, aktif, dan dapat digunakan; produk tidak dapat disimpan pada kategori yang soft deleted atau nonaktif. |
| BR-ADM-PROD-008 | Thumbnail produk wajib tersedia untuk produk yang dapat disimpan; produk aktif tanpa thumbnail tidak dapat dipublikasikan ke katalog. |
| BR-ADM-PROD-009 | Galeri produk bersifat opsional, dapat berisi lebih dari satu gambar, dan dibatasi maksimal delapan gambar per produk pada V1. |
| BR-ADM-PROD-010 | Thumbnail dan gambar galeri hanya menerima format JPEG, PNG, atau WebP yang valid; file non-gambar atau rusak harus ditolak. |
| BR-ADM-PROD-011 | Ukuran setiap berkas gambar maksimal 2 MB dan dimensi gambar harus berada dalam batas yang ditetapkan pada validasi media; gambar harus dapat diproses dengan aman. |
| BR-ADM-PROD-012 | Nama file dari pengguna tidak boleh dipakai sebagai identifier penyimpanan; sistem menggunakan nama unik dan path domain produk. |
| BR-ADM-PROD-013 | Media produk disimpan sebagai aset katalog sesuai filesystem policy; penghapusan/replacement media tidak boleh menghapus bukti/audit yang masih diwajibkan. |
| BR-ADM-PROD-014 | Stok awal wajib berupa bilangan bulat nol atau lebih; nilai nol diizinkan untuk produk yang belum tersedia tetapi tidak dapat dibeli sampai stok tersedia. |
| BR-ADM-PROD-015 | Pencatatan stok awal harus membuat Stock History satu kali dengan alasan “stok awal” atau nilai reason resmi yang setara. |
| BR-ADM-PROD-016 | Semua perubahan stok setelah pembuatan produk harus melalui proses Stock yang membuat Stock History; edit produk tidak boleh mengubah stok secara diam-diam. |
| BR-ADM-PROD-017 | Produk berstatus aktif dapat ditampilkan pada katalog hanya jika memenuhi data wajib dan aturan ketersediaan yang berlaku. |
| BR-ADM-PROD-018 | Produk berstatus nonaktif tidak dapat ditampilkan/dibeli oleh Guest atau Customer, tetapi tetap dapat dilihat Admin sesuai authorization. |
| BR-ADM-PROD-019 | Produk nonaktif tetap mempertahankan relasi, riwayat order, rating, stok, dan audit yang telah ada. |
| BR-ADM-PROD-020 | Produk hanya boleh soft delete; hard delete tidak tersedia pada V1 untuk menjaga konsistensi transaksi dan audit. |
| BR-ADM-PROD-021 | Produk yang soft deleted tidak dapat dicari pada daftar default, muncul di katalog, ditambahkan ke cart, atau dibeli. |
| BR-ADM-PROD-022 | Produk soft deleted dapat direstore hanya jika semua constraint data dan lifecycle terpenuhi; restore tidak otomatis menjadikannya aktif bila status terakhir atau kebijakan tidak mengizinkan. |
| BR-ADM-PROD-023 | Produk yang pernah dibeli, memiliki order aktif, memiliki rating, atau memiliki Stock History tidak boleh dihapus permanen dan tidak boleh kehilangan identitas historisnya. |
| BR-ADM-PROD-024 | Perubahan SKU pada produk yang pernah ditransaksikan dibatasi: secara default SKU harus dipertahankan; pengecualian memerlukan kebijakan resmi, audit, dan peringatan dampak. |
| BR-ADM-PROD-025 | Perubahan kategori pada produk yang pernah ditransaksikan tidak boleh mengubah snapshot/interpretasi historis pada order atau laporan periode lampau. |
| BR-ADM-PROD-026 | Pencarian nama/SKU, filter, sort, dan pagination tidak boleh membuka produk/data di luar hak akses Admin atau mengabaikan soft delete status tanpa filter eksplisit. |
| BR-ADM-PROD-027 | Daftar produk default mengecualikan produk soft deleted dan menggunakan urutan default yang konsisten; detail default ini ditetapkan pada SRS UI/listing sebelum implementasi. |
| BR-ADM-PROD-028 | Nilai harga yang ditampilkan harus menggunakan mata uang dan format aplikasi; perubahan harga berlaku prospektif dan tidak mengubah nilai item pada order yang sudah dibuat. |
| BR-ADM-PROD-029 | Kegagalan upload thumbnail wajib, penyimpanan produk, pencatatan stok awal, atau audit kritis tidak boleh menghasilkan produk aktif yang setengah tersimpan. |
| BR-ADM-PROD-030 | Pembuatan produk, edit informasi penting, status aktif/nonaktif, soft delete, restore, perubahan SKU, perubahan harga, dan perubahan media harus menghasilkan Activity Log sesuai policy. |
| BR-ADM-PROD-031 | Hanya data produk valid yang boleh menjadi sumber katalog, cart, checkout, dan laporan; sumber modul lain tidak boleh membuat bypass terhadap status produk. |
| BR-ADM-PROD-032 | Data soft deleted hanya ditampilkan untuk Admin dalam konteks pengelolaan/restore atau audit yang jelas, bukan sebagai data katalog normal. |
| BR-ADM-PROD-033 | Kondisi stok menipis dan stok habis mengikuti kebijakan modul Stock; Modul Product hanya menampilkan indikator yang berasal dari sumber inventori sah. |
| BR-ADM-PROD-034 | Berat produk tidak digunakan pada V1 karena pemenuhan hanya Pick-Up dan tidak ada ongkir; field berat tidak boleh menjadi requirement wajib Product V1. |
| BR-ADM-PROD-035 | Jika berat ditambahkan pada versi masa depan, nilainya harus berupa angka nol atau lebih dengan satuan yang konsisten dan tidak boleh mengubah scope V1 tanpa Change Request. |
| BR-ADM-PROD-036 | Produk tidak dapat dikaitkan dengan kategori baru/nonaktif secara implisit; Admin harus memilih kategori yang sah secara eksplisit. |
| BR-ADM-PROD-037 | Aksi Admin harus dijalankan melalui akun individual yang dapat ditelusuri; akun bersama tidak boleh digunakan untuk mengelola produk. |
| BR-ADM-PROD-038 | Sistem harus membedakan error validasi, konflik data seperti SKU, kegagalan media, dan kegagalan sistem agar Admin dapat mengambil tindakan yang tepat tanpa melihat informasi sensitif. |
| BR-ADM-PROD-039 | Produk yang tidak memiliki stok tersedia dapat tetap tercatat dan dikelola, tetapi tidak dapat dibeli sampai aturan inventori menyatakan tersedia. |
| BR-ADM-PROD-040 | Semua perubahan harus mengikuti RBAC, policy, Activity Log, data retention, dan workflow approval yang ditetapkan Development Bible serta DoR/DoD. |

## 6. Validation Rules

Validasi dilakukan server-side melalui Form Request pada tahap implementasi. Validasi client-side hanya untuk pengalaman pengguna dan tidak menggantikan validasi server.

| Field/konteks | Wajib | Aturan validasi |
| --- | --- | --- |
| Nama produk | Ya | Teks bersih setelah trim; 3–150 karakter; tidak boleh kosong, hanya spasi, atau memuat markup/skrip berbahaya. Nama tidak diwajibkan unik karena variasi produk dapat memiliki nama serupa, tetapi SKU wajib unik. |
| SKU | Ya | 3–64 karakter setelah normalisasi; hanya huruf kapital A–Z, angka, tanda hubung, dan garis bawah; tidak boleh memiliki spasi; unik termasuk terhadap produk soft deleted; tidak dapat kosong. |
| Kategori | Ya | Harus merujuk ke satu kategori aktif yang valid dan dapat diakses Admin; kategori soft deleted/nonaktif ditolak. |
| Harga | Ya | Nilai moneter numerik yang lebih besar dari 0; maksimal nilai mengikuti batas yang aman untuk mata uang/penyimpanan; tidak menerima nilai negatif, notasi ambigu, atau format teks. |
| Deskripsi | Ya | Teks 10–2.000 karakter setelah trim; HTML tidak diizinkan kecuali kemudian ada kebijakan sanitasi eksplisit; harus aman untuk ditampilkan. |
| Thumbnail | Ya | Satu file gambar valid JPEG/PNG/WebP; maksimal 2 MB; dimensinya memenuhi batas minimum/maksimum media yang disetujui; file dapat dibaca/proses; bukan file tersamar. |
| Gallery | Tidak | Nol sampai delapan file gambar; tiap file memenuhi aturan format, ukuran, dimensi, dan integritas yang sama dengan thumbnail; gambar duplikat dapat ditolak sesuai hash/kebijakan media. |
| Urutan gallery | Bersyarat | Harus berupa urutan integer unik dan berkesinambungan dalam batas jumlah gambar produk; tidak boleh menunjuk gambar milik produk lain. |
| Status produk | Ya | Hanya menerima nilai status resmi `active` atau `inactive` (nama teknis final mengikuti enum yang disetujui); tidak menerima status bebas dari request. |
| Stok awal | Ya | Bilangan bulat nol atau lebih; tidak menerima pecahan, nilai negatif, format nonnumerik, atau angka melewati batas aman yang disetujui. |
| Berat | Tidak untuk V1 | Tidak ditampilkan/disimpan sebagai requirement V1. Jika diaktifkan di masa depan, harus numerik nol atau lebih dengan satuan konsisten dan Change Request. |
| Search | Tidak | Panjang input dibatasi, dibersihkan, dan diperlakukan sebagai teks pencarian; tidak boleh dipakai untuk membentuk query mentah. |
| Filter | Tidak | Nilai kategori/status/kondisi stok hanya menerima enum/identifier sah; soft deleted memerlukan hak Admin dan filter eksplisit. |
| Sort dan arah sort | Tidak | Field sort hanya dari whitelist yang ditentukan; arah hanya ascending/descending; nilai lain menggunakan default aman atau ditolak. |
| Identitas produk | Bersyarat | Identifier harus produk yang ada, sesuai lifecycle, dan dapat diakses Admin; soft deleted memerlukan konteks tindakan yang benar. |

Batas dimensi gambar, nilai maksimum harga/stok, dan default pagination ditetapkan bersama desain data, media policy, serta UI specification sebelum task implementasi menjadi Ready. Nilai tersebut tidak boleh di-hardcode tersebar.

## 7. Security Requirement

| ID | Kebutuhan keamanan |
| --- | --- |
| SR-ADM-PROD-001 | Modul Product Admin harus dilindungi autentikasi sesi, middleware role Admin, dan Face Verification sesuai kebijakan keamanan final. |
| SR-ADM-PROD-002 | Setiap tindakan create, update, soft delete, restore, dan akses data soft deleted harus melalui authorization/policy yang sesuai. |
| SR-ADM-PROD-003 | Semua form yang mengubah data harus memakai proteksi CSRF dan tidak boleh melakukan perubahan melalui request GET. |
| SR-ADM-PROD-004 | Semua input harus divalidasi server-side; request tidak boleh menentukan field/model/status di luar whitelist yang diizinkan. |
| SR-ADM-PROD-005 | Model/layanan harus menerapkan mass assignment protection; field sensitif seperti status, stok, atau lifecycle tidak boleh dapat diisi bebas tanpa kontrol bisnis. |
| SR-ADM-PROD-006 | Unggahan media harus divalidasi tipe, MIME, ekstensi, ukuran, dimensi, integritas, dan akses path; file tidak boleh dieksekusi sebagai skrip. |
| SR-ADM-PROD-007 | File disimpan dengan nama unik pada filesystem yang sesuai; path dari request atau nama asli pengguna tidak boleh dipercaya. |
| SR-ADM-PROD-008 | Output produk/deskripsi harus aman dari XSS; Blade escaping default dan sanitasi konten yang disetujui wajib diterapkan. |
| SR-ADM-PROD-009 | Query pencarian/filter harus menggunakan mekanisme parameter binding Laravel; query mentah dari input pengguna dilarang. |
| SR-ADM-PROD-010 | Tindakan penting dan kegagalan terkait produk/media/stok awal harus dicatat pada Activity Log atau log aplikasi dengan context aman tanpa secret/data berlebihan. |
| SR-ADM-PROD-011 | Sistem harus mencegah akses media/path produk yang tidak sah dan tidak boleh memberi akses Modul Product terhadap file privat payment/security. |
| SR-ADM-PROD-012 | Session timeout, logout, pencabutan akses, atau Face Verification kedaluwarsa harus menghentikan tindakan produk yang belum diotorisasi ulang. |

## 8. Performance Requirement

| ID | Kebutuhan performa dan strategi |
| --- | --- |
| PR-ADM-PROD-001 | Daftar produk harus selesai dimuat kurang dari 2 detik pada kondisi data normal, environment target, serta skenario uji yang disetujui. |
| PR-ADM-PROD-002 | Daftar produk wajib memakai pagination; ukuran halaman default dan maksimum harus ditetapkan untuk mencegah pemuatan data berlebihan. |
| PR-ADM-PROD-003 | Pencarian nama/SKU harus menggunakan query terukur dan indeks/strategi pencarian yang sesuai desain data; pencarian tidak boleh memindai data tak terbatas tanpa batas. |
| PR-ADM-PROD-004 | Filter dan sort hanya menggunakan field/index yang didukung atau memiliki alasan performa yang disetujui. |
| PR-ADM-PROD-005 | Relasi yang diperlukan oleh daftar/detail, seperti kategori atau ringkasan stok, harus dimuat melalui eager loading yang tepat untuk mencegah N+1 query. |
| PR-ADM-PROD-006 | Daftar produk harus menggunakan thumbnail/varian gambar yang dioptimalkan; file galeri resolusi penuh tidak boleh dimuat pada daftar. |
| PR-ADM-PROD-007 | Galeri pada detail dapat dimuat terbatas atau lazy loading bila diperlukan, tanpa menunda data inti produk. |
| PR-ADM-PROD-008 | Query agregat atau informasi historis berat tidak boleh dijalankan per baris daftar; gunakan ringkasan, eager loading, atau modul detail sesuai kebutuhan. |
| PR-ADM-PROD-009 | Proses resize/kompresi media berat dapat dijalankan asinkron bila hasil pengukuran menuntutnya, dengan status upload yang jelas. |
| PR-ADM-PROD-010 | Semua query list/detail/restore/media harus diuji pada jumlah produk dan media yang representatif sebelum acceptance performa. |

## 9. Error Handling

| Kondisi | Perilaku sistem |
| --- | --- |
| SKU sudah digunakan | Tolak penyimpanan, tandai field SKU, jelaskan bahwa SKU harus unik tanpa membuka data produk lain yang sensitif. |
| Kategori tidak valid/nonaktif | Tolak penyimpanan dan minta Admin memilih kategori aktif yang sah. |
| Harga atau stok awal tidak valid | Tolak penyimpanan, tampilkan pesan pada field terkait, dan pertahankan input valid lain bila aman. |
| Thumbnail wajib tidak tersedia atau upload gagal | Jangan membuat produk aktif/setengah tersimpan; tampilkan pesan aman dan catat kegagalan teknis bila perlu. |
| File gambar rusak, format salah, terlalu besar, atau dimensi tidak valid | Tolak hanya file bermasalah, tampilkan alasan validasi yang dapat ditindaklanjuti, dan jangan menyimpan file tidak valid. |
| Penyimpanan media gagal | Batalkan atau kompensasikan proses sesuai batas transaksi sehingga produk/media/audit tidak inkonsisten; catat error aman. |
| Database/transaction gagal | Jangan menyimpan perubahan parsial; tampilkan pesan umum, catat exception dengan context aman, dan sediakan retry sesuai proses. |
| Produk tidak ditemukan atau sudah soft deleted | Tampilkan respons tidak ditemukan/akses tidak valid sesuai konteks tanpa membocorkan data yang tidak boleh dilihat. |
| Restore gagal karena konflik SKU/constraint | Jangan restore sebagian; jelaskan constraint yang perlu diselesaikan dan catat tindakan bila relevan. |
| Tidak berwenang/sesi/Face Verification tidak valid | Tolak tindakan, arahkan ke autentikasi/verifikasi yang sesuai, dan catat security event sesuai kebijakan. |
| Terjadi submit ganda | Sistem memproses satu tindakan sah saja atau mengembalikan hasil idempoten; tidak membuat produk/media/Stock History ganda. |
| Pencarian/filter tidak menghasilkan data | Tampilkan empty state, bukan error. |

## 10. Acceptance Criteria

| ID | Given | When | Then |
| --- | --- | --- | --- |
| AC-ADM-PROD-001 | Admin memiliki sesi, role, dan Face Verification yang valid. | Admin membuka Modul Product. | Sistem menampilkan daftar produk terpaginasikan sesuai hak akses. |
| AC-ADM-PROD-002 | Pengguna bukan Admin. | Pengguna mencoba membuka Modul Product Admin. | Sistem menolak akses dan tidak menampilkan data produk administrasi. |
| AC-ADM-PROD-003 | Admin mengisi seluruh field wajib dengan data valid, thumbnail valid, dan stok awal sah. | Admin menyimpan produk baru. | Produk tersimpan, stok awal dicatat satu kali pada Stock History, dan Activity Log dibuat sesuai policy. |
| AC-ADM-PROD-004 | SKU telah digunakan oleh produk lain, termasuk yang soft deleted. | Admin menyimpan atau memperbarui produk dengan SKU tersebut. | Sistem menolak penyimpanan dan menampilkan kesalahan SKU unik. |
| AC-ADM-PROD-005 | Admin memasukkan harga nol, negatif, atau bukan nilai moneter valid. | Admin menyimpan produk. | Sistem menolak penyimpanan dan menandai field harga. |
| AC-ADM-PROD-006 | Admin memilih kategori nonaktif atau soft deleted. | Admin menyimpan produk. | Sistem menolak penyimpanan dan meminta kategori aktif yang sah. |
| AC-ADM-PROD-007 | Admin tidak mengunggah thumbnail valid. | Admin menyimpan produk baru. | Sistem menolak produk aktif/setengah tersimpan dan menampilkan validasi thumbnail. |
| AC-ADM-PROD-008 | Admin mengunggah gambar galeri JPEG/PNG/WebP valid dalam batas ukuran/dimensi. | Admin menyimpan produk. | Sistem menyimpan gambar pada media produk dan menampilkan galeri sesuai urutan. |
| AC-ADM-PROD-009 | Admin mengunggah lebih dari delapan gambar galeri atau file tidak valid. | Admin menyimpan produk. | Sistem menolak gambar yang melanggar aturan tanpa menyimpan file tidak valid. |
| AC-ADM-PROD-010 | Admin memasukkan stok awal negatif atau pecahan. | Admin menyimpan produk. | Sistem menolak penyimpanan dan tidak membuat Stock History. |
| AC-ADM-PROD-011 | Produk memiliki stok awal nol dan status aktif. | Guest atau Customer melihat katalog/mencoba membeli produk. | Perilaku pembelian mengikuti aturan stok tersedia; sistem tidak mengizinkan stok menjadi negatif. |
| AC-ADM-PROD-012 | Produk aktif telah tersimpan. | Admin mengubah status menjadi nonaktif dan mengonfirmasi tindakan. | Produk tidak lagi tersedia di katalog/pembelian dan perubahan status dicatat. |
| AC-ADM-PROD-013 | Produk nonaktif memiliki data valid. | Admin membuka detail/daftar produk. | Produk tetap dapat dilihat dan dikelola Admin sesuai authorization. |
| AC-ADM-PROD-014 | Produk memiliki riwayat transaksi atau Stock History. | Admin mencoba menghapus produk. | Sistem hanya menyediakan soft delete dan mempertahankan referensi historis. |
| AC-ADM-PROD-015 | Produk telah soft deleted. | Admin membuka daftar produk default. | Produk tidak muncul kecuali Admin memakai filter soft deleted yang berwenang. |
| AC-ADM-PROD-016 | Produk soft deleted memenuhi semua constraint restore dan SKU tidak konflik. | Admin me-restore produk. | Produk dipulihkan tanpa otomatis berubah aktif jika status/kebijakan terakhir tidak mengizinkan, dan tindakan dicatat. |
| AC-ADM-PROD-017 | Produk soft deleted memiliki SKU yang kini berkonflik atau data wajib tidak valid. | Admin me-restore produk. | Sistem menolak restore tanpa perubahan parsial dan menampilkan informasi perbaikan aman. |
| AC-ADM-PROD-018 | Daftar produk berisi banyak produk dan kategori terkait. | Admin mencari, memfilter, mengurutkan, lalu berpindah halaman. | Sistem mempertahankan konteks pencarian/filter/sort dan tidak menimbulkan N+1 query yang diketahui. |
| AC-ADM-PROD-019 | Admin mengakses daftar produk pada data uji representatif. | Halaman daftar dimuat. | Daftar, thumbnail teroptimasi, dan data ringkas selesai dimuat kurang dari 2 detik. |
| AC-ADM-PROD-020 | Kegagalan penyimpanan database/media terjadi saat membuat produk. | Admin menyimpan produk. | Sistem tidak meninggalkan produk aktif, stok awal, atau audit dalam keadaan inkonsisten; error dicatat secara aman. |
| AC-ADM-PROD-021 | Admin mengubah harga produk yang pernah memiliki order. | Admin menyimpan perubahan harga valid. | Harga baru berlaku prospektif dan nilai item pada order yang sudah dibuat tidak berubah. |
| AC-ADM-PROD-022 | Admin mencoba mengubah SKU produk yang pernah ditransaksikan. | Admin menyimpan perubahan. | Sistem mengikuti kebijakan identitas produk: secara default menolak/perlu proses pengecualian yang diaudit sebelum SKU berubah. |

## 11. Definition of Done

Modul Product Admin dianggap selesai apabila:

- Seluruh Functional Requirement, Business Rules, validasi, dan Acceptance Criteria telah diterapkan atau memiliki Change Request yang disetujui.
- ERD, Database Dictionary, kebijakan SKU/soft delete, dan lifecycle Stock History telah final serta selaras dengan implementasi.
- Pembuatan produk, perubahan status, media, stok awal, soft delete, dan restore mengikuti transaction/error handling yang menjaga integritas data.
- Semua input menggunakan Form Request dan validasi server-side; controller, service, policy, filesystem, serta audit mengikuti Development Bible dan Folder Architecture.
- Hanya Admin berwenang yang dapat mengakses tindakan; CSRF, session, Face Verification sesuai kebijakan, mass assignment, XSS, SQL injection, dan upload security telah diuji.
- List produk memakai pagination, pencarian/filter/sort terukur, eager loading, thumbnail optimasi, indeks yang relevan, dan tidak memiliki N+1 query yang diketahui.
- Target daftar produk kurang dari 2 detik telah diuji pada environment dan data representatif yang disetujui.
- Unit test, feature test, manual test, security test, serta regression test yang relevan lulus untuk jalur berhasil/gagal/akses tanpa izin.
- Activity Log dan Stock History memiliki catatan yang lengkap untuk tindakan yang diwajibkan.
- Dokumentasi modul, Business Rules, test, changelog, manual operasional, dan keputusan exception diperbarui; review serta approval selesai tanpa temuan kritis terbuka.

## 12. Future Enhancement

- Barcode produk untuk pencarian, stok opname, dan integrasi scanner.
- Import produk dari Excel/CSV dengan validasi, preview, error report, dan audit.
- Export katalog/produk ke Excel/CSV sesuai authorization.
- QR produk untuk identifikasi cepat di toko.
- Relasi multiple supplier dan informasi pengadaan setelah modul Supplier menjadi scope resmi.
- Batch update harga, status, kategori, atau media dengan preview, approval, transaksi, dan audit kuat.
- Version History produk untuk membandingkan perubahan atribut bisnis.
- Varian produk, merek, satuan, bundling, dan aturan harga lanjutan bila kebutuhan bisnis disetujui.
- Image CDN/object storage serta pemrosesan media asinkron ketika skala media bertumbuh.

Semua pengembangan masa depan harus melalui Change Request dan tidak boleh mengubah histori transaksi, lifecycle stok, atau scope V1 tanpa analisis dampak.

## 13. Conclusion

Modul Product Admin menyediakan kontrol terpusat atas data katalog yang menjadi fondasi penjualan Website Toko ATK. Spesifikasi ini menekankan data produk valid, SKU unik, harga aman, media tervalidasi, status katalog yang jelas, stok awal yang dapat diaudit, serta lifecycle soft delete/restore yang menjaga riwayat bisnis.

Implementasi hanya dapat dimulai setelah dependency data dan lifecycle inventori final, DoR dipenuhi, serta rancangan UI/data yang relevan disetujui. Modul ini tidak menetapkan source code, database, migration, model, controller, route, atau UI final.
