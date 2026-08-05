# Project Folder Architecture Standard

## Website Toko Alat Tulis Kantor (ATK)

| Informasi | Standar |
| --- | --- |
| Framework | Laravel 10 |
| Arsitektur | Laravel MVC dengan service layer dan pengorganisasian domain/area pengguna |
| Target | Production ready, modular, mudah dibaca, dan mudah dipelihara |
| Status | Blueprint struktur resmi sebelum implementasi fitur |
| Dokumen terkait | Development Bible dan Technology Stack Document |

## 1. Executive Summary

Dokumen ini menetapkan struktur folder resmi untuk Website Toko ATK. Struktur mempertahankan konvensi Laravel 10 yang familiar, lalu menambahkan pengelompokan berdasarkan domain bisnis dan area pengguna ketika hal tersebut meningkatkan keterbacaan. Tujuannya bukan membuat banyak folder, melainkan memastikan setiap file memiliki lokasi dan tanggung jawab yang mudah diprediksi.

Arsitektur ini menggunakan monolitik modular: satu aplikasi Laravel dengan batas domain yang jelas. Pendekatan tersebut cukup kuat untuk transaksi, stok, pembayaran, keamanan, dan laporan UMKM, sekaligus lebih sederhana untuk dikelola daripada microservices. Struktur dapat berkembang ke API, aplikasi mobile, multi-branch, atau integrasi eksternal tanpa memindahkan fondasi bisnis utama.

## 2. Architecture Overview

Prinsip organisasi yang berlaku adalah:

- Konvensi Laravel adalah default; struktur khusus hanya ditambahkan jika memberi nilai nyata.
- Pengelompokan dilakukan menurut domain bisnis (`Product`, `Stock`, `Order`, `Payment`, dan seterusnya) dan area pengguna (`Admin`, `Owner`, `Customer`, `Guest`) secara konsisten.
- Controller adalah adaptor HTTP yang tipis. Proses bisnis ditempatkan pada service, sedangkan validasi request dan authorization berada pada Form Request, middleware, serta policy.
- Model menyimpan relasi dan perilaku data yang dekat dengan entitas. Query kompleks berulang dapat dipusatkan di repository, tetapi repository tidak wajib untuk setiap model.
- Nama folder/file menunjukkan tanggung jawab; tidak ada folder generik seperti `Misc`, `Common`, `TempCode`, atau `HelperNew`.
- Satu struktur tidak boleh diduplikasi untuk setiap role bila domain dan logika bisnisnya sama. Perbedaan akses ditangani oleh authorization, sedangkan view/controller boleh dipisahkan bila pengalaman area pengguna memang berbeda.

## 3. Root Folder Structure

Struktur root standar Laravel yang digunakan adalah sebagai berikut. Pohon ini adalah blueprint organisasi, bukan daftar file implementasi yang harus dibuat sekaligus.

```text
project-root/
├── app/
├── bootstrap/
├── config/
├── database/
├── docs/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
├── .env
├── .env.example
├── artisan
├── composer.json
├── composer.lock
├── package.json
└── vite.config.js
```

| Lokasi | Fungsi dan aturan |
| --- | --- |
| `app/` | Kode aplikasi: HTTP, domain/service, model, policy, event, job, exception, dan provider. Detailnya ditetapkan pada bagian App Structure. |
| `bootstrap/` | Bootstrap aplikasi dan cache framework. Tidak digunakan untuk logika bisnis. Perubahan hanya dilakukan untuk konfigurasi aplikasi yang memang memerlukannya. |
| `config/` | Konfigurasi Laravel dan konfigurasi proyek yang tidak mengandung secret. Semua nilai environment dibaca melalui file konfigurasi, bukan langsung dari class bisnis. |
| `database/` | Migration, seeder, factory, dan sumber daya data development/testing. Bukan tempat backup database produksi. |
| `docs/` | Dokumen proyek yang ditinjau dan dikendalikan versinya bersama kode. |
| `public/` | Satu-satunya document root web; menyimpan entry point dan aset publik yang aman. |
| `resources/` | Blade view, CSS, JavaScript, dan aset sumber yang perlu diproses Vite. |
| `routes/` | Definisi route HTTP yang dikelompokkan menurut konteks akses. Route tetap deklaratif dan tipis. |
| `storage/` | Log, cache, file runtime, dan berkas aplikasi; struktur akses publik/pribadi harus dipisahkan. |
| `tests/` | Pengujian Feature, Unit, dan Browser opsional. |
| `vendor/` | Dependency Composer yang dihasilkan; tidak diedit atau dikomit. |
| `.env` | Konfigurasi rahasia dan spesifik environment; tidak pernah dikomit. |
| `.env.example` | Kontrak konfigurasi tanpa secret; wajib diperbarui bila environment baru dibutuhkan. |
| `composer.lock` | Versi dependency PHP yang terkunci; wajib dikomit. |
| `package.json`/lockfile | Dependency aset frontend; lockfile wajib dikomit. |

## 4. App Structure

### 4.1 Struktur `app/`

```text
app/
├── Actions/
├── Enums/
├── Events/
├── Exceptions/
├── Helpers/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Jobs/
├── Listeners/
├── Mail/
├── Models/
├── Notifications/
├── Observers/
├── Policies/
├── Providers/
├── Repositories/
├── Rules/
├── Services/
├── Support/
└── Traits/
```

| Folder | Fungsi | Aturan penggunaan |
| --- | --- | --- |
| `Actions/` | Operasi kecil yang sangat fokus dan dapat dipakai ulang, misalnya tindakan satu tujuan yang tidak perlu menjadi service penuh. | Opsional; jangan duplikasi service. Dipakai bila menambah kejelasan use case. |
| `Enums/` | Enum PHP untuk status dan nilai domain yang terbatas, seperti status order atau payment. | Hindari magic string status di controller/view. |
| `Events/` | Representasi kejadian domain/aplikasi yang terjadi. | Gunakan hanya bila ada side effect terpisah yang perlu didengarkan; jangan gunakan event untuk menyembunyikan alur bisnis utama. |
| `Exceptions/` | Exception domain/aplikasi yang dapat ditangani dengan pesan dan logging konsisten. | Nama harus spesifik terhadap kegagalan, bukan `CustomException`. |
| `Helpers/` | Fungsi murni, stateless, dan generik yang tidak cocok pada class lain. | Penggunaan dibatasi; helper bukan tempat business logic atau akses database. |
| `Http/Controllers/` | Controller request/response per area pengguna atau konteks fitur. | Tipis; tidak memuat proses bisnis panjang atau query kompleks. |
| `Http/Middleware/` | Kontrol lintas request seperti role, Face Verification, atau pembatasan akses. | Tidak menggantikan policy kepemilikan resource. |
| `Http/Requests/` | Form Request untuk validasi dan authorization pada input HTTP. | Kelompokkan per area/domain, bukan satu folder datar besar. |
| `Jobs/` | Pekerjaan asinkron, misalnya email, ekspor besar, atau pemrosesan media berat. | Job harus idempoten jika mungkin dan tidak menyimpan state request. |
| `Listeners/` | Penangan side effect dari event. | Pendek dan fokus; logika inti tetap eksplisit di service. |
| `Mail/` | Mailable Laravel untuk email transaksional atau operasional. | Template email berada pada view mail terkait. |
| `Models/` | Eloquent model, relasi, casts, scope sederhana, dan perilaku data dekat entitas. | Tidak menjadi god model atau tempat orkestrasi use case panjang. |
| `Notifications/` | Notifikasi untuk Customer, Owner, atau Admin melalui mail/database/channel lain. | Kelompokkan menurut penerima atau domain; jangan berisi aturan transaksi. |
| `Observers/` | Reaksi lifecycle model yang generik dan aman. | Jangan menyembunyikan perubahan stok/order/payment yang kritis; gunakan service eksplisit untuk itu. |
| `Policies/` | Authorization berbasis model/resource dan kepemilikan data. | Wajib untuk akses resource sensitif lintas role/pemilik. |
| `Providers/` | Pendaftaran binding, policy, event, dan konfigurasi aplikasi. | Tidak memuat logika bisnis atau query. |
| `Repositories/` | Query dan akses data kompleks/berulang yang perlu diisolasi. | Opsional; tidak dibuat sebagai wrapper CRUD satu-banding-satu. |
| `Rules/` | Custom validation rule yang dapat digunakan ulang. | Rule harus fokus pada satu validasi dan tidak mengubah data. |
| `Services/` | Orkestrasi use case dan aturan bisnis lintas entitas. | Tempat utama proses checkout, stok, order, payment, dan security. |
| `Support/` | Value object, DTO, builder, atau komponen pendukung domain yang tidak sesuai folder lain. | Harus bernama spesifik; bukan folder serbaguna. |
| `Traits/` | Perilaku kecil yang dipakai ulang dengan hati-hati. | Hindari trait dengan state tersembunyi atau logika domain besar. |

### 4.2 Controller Architecture

Controller dipisahkan berdasarkan area pengguna untuk menjaga route, middleware, view, dan tanggung jawab akses tetap jelas.

```text
app/Http/Controllers/
├── Admin/
│   ├── DashboardController
│   ├── ProductController
│   ├── CategoryController
│   ├── OrderController
│   ├── CustomerController
│   ├── OwnerController
│   ├── ReportController
│   └── SecurityController
├── Owner/
│   ├── DashboardController
│   ├── ProductController
│   ├── CategoryController
│   ├── OrderController
│   └── ReportController
├── Customer/
│   ├── CartController
│   ├── CheckoutController
│   ├── OrderController
│   ├── PaymentController
│   ├── ProfileController
│   └── RatingController
├── Guest/
│   ├── HomeController
│   └── ProductController
└── Auth/
    ├── LoginController
    └── RegisterController
```

Pemisahan ini membuat kebutuhan tiap role tidak tercampur: Admin menangani administrasi dan keamanan, Owner menangani operasional, Customer menangani transaksi milik sendiri, dan Guest hanya mengakses konten publik. Controller dengan nama sama pada area berbeda diperbolehkan apabila route, view, tindakan yang tersedia, atau authorization memang berbeda. Mereka tetap menggunakan service/domain bersama bila aturan bisnisnya sama.

Starter kit Laravel Breeze dapat menyediakan sebagian controller/auth flow. Dokumen ini menetapkan lokasi konseptual untuk penyesuaian proyek; implementasi harus mengikuti struktur aktual package dan tidak menduplikasi controller bawaan tanpa kebutuhan.

### 4.3 Services Architecture

Service dipisahkan per domain, bukan berdasarkan tindakan teknis umum.

```text
app/Services/
├── Catalog/
│   ├── ProductService
│   └── CategoryService
├── Inventory/
│   └── StockService
├── Order/
│   ├── CheckoutService
│   ├── OrderService
│   └── PickupService
├── Payment/
│   └── PaymentVerificationService
├── Customer/
│   ├── CartService
│   ├── ProfileService
│   └── RatingService
├── Reporting/
│   └── ReportService
└── Security/
    ├── ActivityLogService
    ├── LoginHistoryService
    └── FaceVerificationService
```

- `ProductService` dan `CategoryService` mengatur use case katalog/master data sesuai kewenangan.
- `StockService` menjadi pintu utama perubahan stok dan pencatatan Stock History.
- `CheckoutService`, `OrderService`, dan `PickupService` mengatur lifecycle pesanan, kode Pick-Up, dan konsistensi proses transaksi.
- `PaymentVerificationService` mengelola verifikasi bukti QRIS serta dampaknya terhadap status terkait.
- `CartService`, `ProfileService`, dan `RatingService` menangani use case Customer tanpa mengakses data Customer lain.
- `ReportService` menyusun kebutuhan data laporan, bukan menaruh query laporan tersebar di controller.
- Service keamanan mengoordinasikan audit/kontrol yang tidak cocok sebagai observer tersembunyi.

Tidak semua folder/service harus dibuat di awal. Folder dibuat pada saat feature/domain memerlukannya. Nama folder berbasis domain di atas lebih diutamakan daripada pemisahan `Admin/Owner/Customer` di service, karena business rule inti harus dapat dipakai ulang lintas role dengan authorization yang tepat.

### 4.4 Requests Architecture

Form Request dipisahkan berdasarkan area dan domain untuk mencegah satu folder besar yang sulit dinavigasi.

```text
app/Http/Requests/
├── Admin/
│   ├── Product/
│   │   ├── StoreProductRequest
│   │   ├── UpdateProductRequest
│   │   └── DeleteProductRequest
│   ├── Category/
│   │   ├── StoreCategoryRequest
│   │   └── UpdateCategoryRequest
│   └── Owner/
│       └── StoreOwnerRequest
├── Owner/
│   ├── Product/
│   ├── Category/
│   ├── Order/
│   └── Payment/
├── Customer/
│   ├── Cart/
│   ├── Checkout/
│   ├── Payment/
│   ├── Profile/
│   └── Rating/
└── Auth/
```

Request yang memiliki aturan input identik dan berada pada domain yang sama boleh dipakai ulang lintas role bila authorization-nya tidak menjadi ambigu. Namun, jangan memakai request umum yang mencampur field dan rule berbeda hanya demi mengurangi jumlah file. Nama request harus menjelaskan aksi dan resource.

### 4.5 Repositories Architecture

Repository diletakkan berdasarkan domain jika diperlukan, misalnya `Repositories/Order/OrderRepository`. Gunakan ketika query order laporan/daftar bersifat kompleks dan dipakai berulang, perlu eager loading/filter standar, membutuhkan query locking, atau harus dipisahkan dari service agar lebih teruji.

Repository tidak digunakan untuk CRUD Eloquent sederhana, relasi sederhana, atau sebagai lapisan wajib di atas setiap model. Laravel Eloquent sudah menyediakan abstraction yang kuat; repository yang hanya meneruskan method Eloquent menambah file dan biaya pemeliharaan tanpa manfaat. Repository tidak mengatur status bisnis, audit, atau response HTTP.

### 4.6 Policies Architecture

Policy dipusatkan pada resource domain dan dapat diberi namespace area hanya jika benar-benar ada perbedaan kebijakan yang tidak dapat dijelaskan dalam satu policy.

```text
app/Policies/
├── ProductPolicy
├── OrderPolicy
├── PaymentPolicy
├── RatingPolicy
├── StockHistoryPolicy
├── UserPolicy
└── SecurityEventPolicy
```

Strategi authorization: middleware memeriksa konteks umum seperti login, role, email verification, atau Face Verification; policy memeriksa hak tindakan terhadap resource tertentu. Customer hanya mengakses cart/profile/order/payment/rating miliknya. Owner mengakses domain operasional yang diberikan. Admin mengawasi dan mengelola sistem sesuai matriks permission. Semua aksi sensitif default-nya ditolak sampai diizinkan secara eksplisit.

### 4.7 Notifications, Mail, Jobs, Events, dan Listeners

```text
app/
├── Notifications/
│   ├── Customer/
│   ├── Owner/
│   └── Admin/
├── Mail/
│   ├── Customer/
│   └── Admin/
├── Jobs/
│   ├── Export/
│   ├── Media/
│   └── Notification/
├── Events/
│   └── Order/
└── Listeners/
    └── Order/
```

Notification dipisahkan menurut penerima atau domain agar pesan, channel, dan authorization komunikasi jelas. Customer dapat menerima perubahan status order; Owner dapat menerima order/bukti pembayaran yang memerlukan tindakan; Admin dapat menerima kejadian keamanan. Mail menyimpan mailable, sedangkan template berada pada `resources/views/mail/`.

Jobs digunakan untuk pekerjaan yang mahal atau dapat berjalan setelah response, seperti ekspor besar, resize gambar, atau pengiriman email. Events/listeners hanya dipakai untuk side effect yang benar-benar terpisah; alur inti perubahan order, pembayaran, dan stok tetap eksplisit di service agar mudah ditelusuri.

## 5. Resources Structure

```text
resources/
├── css/
├── js/
└── views/
    ├── layouts/
    ├── admin/
    ├── owner/
    ├── customer/
    ├── guest/
    ├── components/
    ├── partials/
    ├── auth/
    ├── errors/
    ├── mail/
    └── pdf/
```

| Folder | Fungsi dan aturan |
| --- | --- |
| `resources/css/` | Entry point stylesheet Vite dan penyesuaian Bootstrap yang terorganisasi. Jangan menyimpan CSS inline besar di Blade. |
| `resources/js/` | Entry point Vite dan JavaScript vanilla per kebutuhan interaksi. Tidak menyimpan business rule yang harus diputuskan server. |
| `views/layouts/` | Layout dasar terpisah untuk area publik, Customer, Owner, dan Admin bila struktur navigasi/otorisasinya berbeda. |
| `views/admin/` | Halaman khusus administrasi: dashboard, produk, customer, owner, report, dan Security Center. |
| `views/owner/` | Halaman operasional Owner: dashboard, produk, kategori, order, payment, dan report yang diizinkan. |
| `views/customer/` | Halaman transaksi milik Customer: cart, checkout, order, payment proof, profile, dan rating. |
| `views/guest/` | Landing page, katalog, pencarian, dan detail produk publik. |
| `views/components/` | Blade component reusable yang memiliki API/purpose jelas, seperti badge status, table wrapper, atau form control. |
| `views/partials/` | Fragmen tampilan kecil yang tidak perlu menjadi component mandiri, misalnya navigation atau filter panel. |
| `views/auth/` | Template autentikasi yang disesuaikan dari Breeze tanpa mencampurnya dengan halaman area bisnis. |
| `views/errors/` | Halaman error yang ramah pengguna dan aman tanpa detail internal. |
| `views/mail/` | Template email transaksional/keamanan yang diorganisasikan per penerima/domain. |
| `views/pdf/` | Template dokumen PDF seperti receipt, invoice, atau report. Berbeda dari halaman web biasa. |

View mengikuti pola nama resource/action seperti `admin/products/index`, `customer/orders/show`, dan `guest/products/show`. Logic presentasi kecil boleh digunakan; query, authorization, transaksi, dan aturan bisnis tidak boleh berada di Blade.

## 6. Routes Structure

Route dipisahkan berdasarkan konteks akses agar middleware, prefix, dan name prefix tidak tercampur. Blueprint file adalah:

```text
routes/
├── web.php
├── guest.php
├── auth.php
├── customer.php
├── owner.php
└── admin.php
```

| File | Tanggung jawab |
| --- | --- |
| `web.php` | Entry route web dan pendaftaran file route lain. Tidak memuat daftar route bisnis panjang. |
| `guest.php` | Landing page, katalog, pencarian, dan detail produk yang dapat diakses tanpa login. |
| `auth.php` | Route autentikasi dari/selaras dengan Laravel Breeze: login, register, verifikasi email, password reset, dan logout. |
| `customer.php` | Cart, checkout, profile, order, payment proof, dan rating Customer. |
| `owner.php` | Dashboard dan modul operasional Owner. |
| `admin.php` | Dashboard administrasi, master data, user management, report, dan Security Center. |

Standar grouping:

- Route publik memakai middleware web standar dan name prefix yang konsisten, misalnya `guest.` jika diperlukan.
- Route Customer memerlukan `auth` dan `verified`, serta prefix/name seperti `customer` dan `customer.`.
- Route Owner memerlukan `auth`, role Owner, dan Face Verification sesuai kebijakan; gunakan prefix/name `owner` dan `owner.`.
- Route Admin memerlukan `auth`, role Admin, dan Face Verification sesuai kebijakan; gunakan prefix/name `admin` dan `admin.`.
- Gunakan route name berbasis resource/action, misalnya `admin.products.index`, bukan URL literal atau nama ambigu.
- Route model binding, policy, dan throttle diterapkan sesuai feature. Closure route hanya untuk kebutuhan sangat sederhana/nonbisnis; prefer controller untuk halaman/proses aplikasi.

Pendaftaran beberapa file route dilakukan melalui konfigurasi provider/bootstrap Laravel pada tahap implementasi. Setiap file harus tetap dapat ditelusuri dari satu titik pendaftaran yang terdokumentasi; jangan mendaftarkan file route secara tersembunyi dari banyak lokasi.

## 7. Storage Structure

`storage/` merupakan area runtime, bukan folder source code. Berkas publik dan privat harus dipisahkan secara ketat melalui Laravel filesystem disk serta authorization.

```text
storage/app/
├── public/
│   ├── products/
│   │   ├── thumbnails/
│   │   └── gallery/
│   └── profiles/
└── private/
    ├── payments/
    ├── receipts/
    ├── invoices/
    ├── reports/
    ├── temp/
    └── backups/
```

| Lokasi | Aturan |
| --- | --- |
| `products/thumbnails/` | Varian kecil yang dioptimalkan untuk daftar produk; dapat disajikan publik bila produk publik. |
| `products/gallery/` | Foto galeri produk; validasi tipe, dimensi, dan ukuran wajib. |
| `profiles/` | Foto profil bila fitur disetujui; akses mengikuti kebijakan profil dan privasi. |
| `private/payments/` | Bukti pembayaran QRIS; wajib privat dan hanya dapat diakses pihak berwenang melalui aplikasi. |
| `private/receipts/` dan `private/invoices/` | Dokumen transaksi; akses berdasarkan kepemilikan/role dan retensi yang ditetapkan. |
| `private/reports/` | Hasil laporan ekspor; privat kecuali secara eksplisit dibagikan. |
| `private/temp/` | Berkas sementara proses ekspor/media; harus memiliki lifecycle cleanup. |
| `private/backups/` | Hanya lokasi sementara bila diperlukan; backup produksi utama sebaiknya berada di storage terpisah yang aman, tidak di web server saja. |
| `storage/logs/` | Log aplikasi; tidak boleh disajikan melalui web. |
| `storage/framework/` | Cache/session/view runtime Laravel; dikelola framework dan tidak dikomit. |

Nama berkas menggunakan UUID/ULID atau identitas acak dengan ekstensi hasil validasi. Jangan memakai nama asli pengguna sebagai path/identifier. Semua path dibangun melalui filesystem abstraction; controller/view tidak membangun path absolut secara manual.

## 8. Public Structure

`public/` hanya menyimpan entry point dan aset yang aman untuk diakses langsung dari browser.

```text
public/
├── build/
├── css/
├── js/
├── images/
├── icons/
├── logo/
├── fonts/
└── vendor/
```

| Lokasi | Aturan |
| --- | --- |
| `build/` | Aset hasil Vite; dihasilkan dari source `resources/` sesuai strategi deploy. |
| `css/` dan `js/` | Hanya aset statis publik bila memang diperlukan; source utama tetap berada di `resources/`. Hindari duplikasi aset hasil build. |
| `images/` | Gambar statis umum yang bukan upload pengguna, misalnya ilustrasi/placeholder. |
| `icons/` | Ikon statis yang disetujui proyek. |
| `logo/` | Identitas visual toko yang boleh ditampilkan publik. |
| `fonts/` | Font self-hosted yang telah diperiksa lisensi dan ukuran. |
| `vendor/` | Aset publik package pihak ketiga bila benar-benar diperlukan; prefer bundling Vite agar dependency terkelola. |

Tidak boleh menyimpan `.env`, log, source PHP, bukti pembayaran, backup, atau file upload privat di `public/`. Server production harus mengarahkan web root hanya ke folder ini.

## 9. Documentation Structure

Semua dokumen proyek disimpan di `docs/`, ditulis Markdown bila memungkinkan, dan diperbarui bersama perubahan perilaku bisnis/teknis.

```text
docs/
├── business-requirements/
├── srs/
├── business-rules/
├── architecture/
├── database/
├── prompts/
├── checklists/
├── deployment/
├── manual-book/
├── api/
├── changelog/
└── reviews/
```

| Folder | Isi yang diharapkan |
| --- | --- |
| `business-requirements/` | Vision, BRD, scope, stakeholder analysis, dan keputusan kebutuhan bisnis. |
| `srs/` | Software Requirement Specification, use case, acceptance criteria, dan traceability. |
| `business-rules/` | Aturan stok, order, payment, rating, Pick-Up, dan kebijakan operasional. |
| `architecture/` | Development Bible, folder architecture, keputusan arsitektur, security/performance standard. |
| `database/` | ERD, data dictionary, strategi index, lifecycle, dan keputusan rancangan data; bukan dump rahasia production. |
| `prompts/` | Prompt atau instruksi AI yang telah disetujui dan relevan bagi proyek. |
| `checklists/` | Checklist review, readiness, release, security, dan acceptance. |
| `deployment/` | Environment, SOP deploy, backup, rollback, monitoring, dan incident response. |
| `manual-book/` | Panduan Admin, Owner, Customer, serta SOP operasional. |
| `api/` | Kontrak/versioning/dokumentasi API saat API memang dibangun. |
| `changelog/` | Riwayat perubahan rilis yang dapat dibaca stakeholder. |
| `reviews/` | Hasil review, keputusan teknis, audit, serta tindak lanjut yang relevan. |

Dokumen Milestone 1 yang sudah ada dapat tetap berada langsung dalam `docs/` sampai dilakukan relokasi terencana. Jangan memindahkan atau mengganti nama dokumen yang sudah menjadi referensi tanpa memperbarui tautan dan catatan perubahan.

## 10. Testing Structure

```text
tests/
├── Feature/
│   ├── Admin/
│   ├── Owner/
│   ├── Customer/
│   ├── Guest/
│   ├── Auth/
│   └── Security/
├── Unit/
│   ├── Services/
│   ├── Models/
│   ├── Rules/
│   └── Support/
└── Browser/
    └── (opsional)
```

| Area | Tujuan |
| --- | --- |
| `Feature/` | Menguji request HTTP, route, middleware, Form Request, policy, database integration, dan alur per area pengguna. Contoh fokus: checkout, QRIS, Pick-Up, role access, dan stok. |
| `Unit/` | Menguji service, rule, enum/value object, dan perilaku model yang dapat diisolasi. Unit test tidak bergantung pada HTTP bila tidak diperlukan. |
| `Browser/` | Opsional untuk alur browser end-to-end kritis seperti login, checkout, dan upload. Dipakai bila tool/browser testing telah disetujui dan lingkungan CI mendukung. |

Nama test menjelaskan perilaku yang diverifikasi, bukan detail implementasi. Test yang menyentuh domain stok/order/payment harus mencakup skenario gagal, otorisasi, dan konsistensi transaksi selain happy path.

## 11. Naming Convention

| Elemen | Benar | Hindari |
| --- | --- | --- |
| Folder | lowercase atau struktur namespace konsisten: `Customer/Checkout`, `resources/views/customer/orders` | `NewFolder`, `customer order`, `Other` |
| Namespace | `App\Services\Order`, `App\Http\Requests\Customer\Checkout` | namespace tidak cocok path atau `App\Common` tanpa makna |
| Controller | `Admin/ProductController`, `Customer/CheckoutController` | `ProductCtrl`, `ManageController`, `ControllerNew` |
| View | `admin/products/index`, `customer/orders/show` | `page1`, `produkfinal`, nama berkapitalisasi acak |
| Migration | nama aksi deskriptif Laravel: `create_products_table` | `migration_product_final` |
| Seeder | `ProductSeeder`, `AdminUserSeeder` | `SeedData`, `NewSeeder` |
| Factory | `ProductFactory`, `OrderFactory` | `DataFactory` |
| Resource | Nama domain eksplisit, misalnya `OrderResource` bila API dibangun | `DataResource`, `ApiResource1` |
| Route | `admin.products.index`, `customer.checkout.store` | `route1`, `product-new` |
| Service | `StockService`, `PaymentVerificationService` | `CommonService`, `HelperService` |
| Repository | `OrderRepository` bila beralasan | `BaseRepository` yang mengaburkan query domain |
| Request | `StoreProductRequest`, `UploadPaymentProofRequest` | `ProductRequest`, `FormRequestNew` |
| Policy | `OrderPolicy`, `RatingPolicy` | `PermissionPolicy2` |
| Mail | `OrderReadyForPickupMail` | `MailNotification`, `SendMail` |
| Notification | `OrderReadyForPickupNotification` | `StatusNotification` tanpa konteks |
| Helper | `MoneyFormatter` bila fungsi murni memang diperlukan | `GlobalHelper`, `Utils` |
| Trait | `HasPickupCode` jika perilakunya kecil dan jelas | `CommonTrait`, `AllTrait` |
| Enum | `OrderStatus`, `PaymentStatus` | `StatusEnum`, string status tersebar |

Class PHP memakai PascalCase, method/variable memakai camelCase, tabel/kolom memakai snake_case, route/view memakai dot/slash convention Laravel, dan folder mengikuti casing yang konsisten dengan namespace. Nama harus memprioritaskan istilah bisnis yang dapat dipahami developer baru.

## 12. Scalability

Struktur ini memungkinkan pertumbuhan tanpa merombak fondasi karena domain bisnis sudah dipisahkan dari adaptor HTTP/view:

| Pengembangan masa depan | Dampak pada struktur |
| --- | --- |
| Multi Branch | Tambahkan domain/module `Branch` dan perluas service/policy/reporting yang relevan tanpa memindahkan Product, Order, atau Stock inti. |
| Supplier | Tambahkan `Supplier` pada domain master data, service, request, policy, view, dan test sesuai pola yang sama. |
| Purchase Order | Tambahkan domain `Procurement` atau `PurchaseOrder`; integrasikan perubahan persediaan melalui `StockService`, bukan duplikasi logika stok. |
| REST API | Tambahkan controller/request/resource pada namespace `Http/Controllers/Api` dan route API terpisah; gunakan service/domain yang sama. |
| Flutter | Flutter mengonsumsi API yang memanggil service existing; aturan order/payment tidak ditulis ulang pada aplikasi mobile. |
| React/Vue | Dapat menggantikan/menambah lapisan presentation secara bertahap; service, policy, model, dan route/API tetap menjadi fondasi backend. |
| Payment Gateway | Tambahkan domain/adapter gateway dan webhook handler di Payment; pertahankan `PaymentVerificationService` sebagai use case yang terkontrol. |
| Barcode Scanner | Tambahkan scanner adapter/UI dan perluas domain product/stock; gunakan SKU/Barcode serta StockService yang sama. |

Skalabilitas tidak berarti semua folder masa depan dibuat sekarang. Folder/domain ditambahkan ketika requirement disetujui, mengikuti pola nama, ownership, test, dokumentasi, dan authorization yang telah ditetapkan.

## 13. Conclusion

Folder Architecture Standard ini menjadi blueprint resmi organisasi proyek Laravel 10 Website Toko ATK. Struktur mengutamakan konvensi Laravel, batas domain yang jelas, controller tipis, service yang dapat diuji, route dan view terpisah per area pengguna, serta pemisahan data publik dan privat.

Setiap developer dan agen AI wajib menempatkan file baru sesuai standar ini atau mendokumentasikan alasan pengecualian. Blueprint ini tidak membuat source code, migration, controller, model, maupun basis data; implementasi baru dilakukan pada tahap coding setelah requirement dan desain teknis yang relevan disetujui.
