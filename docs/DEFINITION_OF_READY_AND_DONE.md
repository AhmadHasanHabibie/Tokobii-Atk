# Definition of Ready (DoR) & Definition of Done (DoD)

## Website Toko Alat Tulis Kantor (ATK)

| Informasi | Standar |
| --- | --- |
| Framework | Laravel 10 |
| Target | Production ready |
| Metode | Incremental Development |
| Fungsi dokumen | Quality gate wajib sebelum task dimulai dan sebelum task/fitur/rilis dinyatakan selesai |
| Dokumen acuan | Project Scope, Development Bible, Folder Architecture, dan Development Workflow Standard |

## 1. Executive Summary

Definition of Ready (DoR) menetapkan bukti minimum bahwa sebuah pekerjaan sudah cukup jelas dan aman untuk dimulai. Definition of Done (DoD) menetapkan bukti minimum bahwa pekerjaan telah memenuhi requirement, standar kualitas, pengujian, review, dan dokumentasi sebelum dianggap selesai. Keduanya mencegah tim memulai pekerjaan berdasarkan asumsi atau menutup pekerjaan hanya karena kode telah ditulis.

DoR dan DoD berlaku untuk dokumentasi, desain, implementasi, test, perbaikan bug, refactor, serta rilis. Checklist diterapkan secara proporsional: task dokumentasi tidak memerlukan migration atau wireframe, sedangkan task yang mengubah order, stok, payment, akses, atau production memerlukan kontrol lebih ketat. Butir yang tidak relevan harus ditandai **N/A** dengan alasan, bukan diabaikan.

## 2. Definition of Ready

Task berstatus **Ready** hanya apabila seluruh syarat yang relevan berikut terpenuhi dan tidak ada blocker kritis.

### 2.1 Requirement dan Scope

- [ ] Tujuan bisnis dan masalah yang diselesaikan telah dijelaskan.
- [ ] Scope task jelas, termasuk apa yang termasuk dan tidak termasuk.
- [ ] Aktor/stakeholder yang terdampak telah diidentifikasi.
- [ ] Tidak ada ambiguity material pada istilah, hasil, ownership, atau perilaku.
- [ ] Task memiliki owner pelaksana dan approver yang jelas.
- [ ] Keterkaitan dengan requirement/SRS, backlog, atau milestone dapat ditelusuri.

Jika requirement masih memiliki dua atau lebih interpretasi yang dapat menghasilkan perilaku berbeda, task belum Ready. Klarifikasi harus dilakukan sebelum desain atau coding.

### 2.2 Business Rules

- [ ] Business Rules yang relevan tersedia dan ditautkan pada task.
- [ ] Aturan telah disetujui pihak bisnis/teknis yang berwenang.
- [ ] Status, transisi, batas nilai, pengecualian, dan kondisi gagal dijelaskan bila berlaku.
- [ ] Hak akses, ownership data, audit, dan implikasi operasional telah dipahami.
- [ ] Tidak ada konflik dengan aturan bisnis, scope, atau dokumen resmi yang sudah ada.

Task yang memengaruhi stok, order, QRIS, Pick-Up, rating, atau keamanan tidak boleh Ready tanpa aturan bisnis eksplisit.

### 2.3 Flow

- [ ] Alur normal tersedia dan dapat dipahami oleh pelaksana.
- [ ] Alur gagal, alternatif, pembatalan, atau edge case penting telah diidentifikasi.
- [ ] Tanggung jawab setiap aktor dalam alur jelas.
- [ ] Input, output, dan titik keputusan utama tidak saling bertentangan.
- [ ] Flow telah direview bila mengubah proses operasional Customer, Owner, atau Admin.

### 2.4 Database dan Data Impact

Bagian ini wajib hanya bila task membuat atau mengubah data persisten.

- [ ] ERD tersedia dan final untuk dampak task tersebut.
- [ ] Entitas, relationship, cardinality, ownership, lifecycle, dan source of truth jelas.
- [ ] Database Dictionary menjelaskan arti data, constraint, validasi, serta status yang relevan.
- [ ] Strategi primary key, foreign key, index, timestamp, soft delete, dan transaksi telah ditinjau bila berlaku.
- [ ] Strategi data migration, backward compatibility, backup, rollback, dan dampak data existing telah dianalisis.
- [ ] Tidak ada konflik desain data yang belum diputuskan.

Migration tidak boleh dimulai jika ERD dan Database Dictionary yang relevan belum final/disetujui.

### 2.5 UI dan UX Impact

Bagian ini wajib bila task mengubah antarmuka atau perjalanan pengguna.

- [ ] Wireframe atau pola UI yang disetujui tersedia.
- [ ] Halaman, komponen, field, navigasi, empty state, loading state, error state, dan aksesibilitas utama telah diidentifikasi.
- [ ] Perbedaan tampilan/hak akses antar Guest, Customer, Owner, dan Admin jelas.
- [ ] Copy/instruksi yang berkaitan dengan bisnis atau keamanan telah disetujui bila material.
- [ ] UI tidak bertentangan dengan UI Rules, scope, dan flow bisnis.

### 2.6 Dependency dan Environment

- [ ] Dependency teknis, package, layanan, credential, konfigurasi, atau data uji yang diperlukan telah tersedia atau memiliki rencana penyediaan yang disetujui.
- [ ] Tidak ada blocker dari task lain, keputusan arsitektur, akses environment, atau stakeholder.
- [ ] Dependency baru telah melewati evaluasi compatibility, maintenance, security, biaya, dan approval.
- [ ] Environment development/testing yang diperlukan dapat digunakan tanpa membahayakan production.

### 2.7 Acceptance Criteria

- [ ] Acceptance criteria tertulis dengan hasil yang dapat diamati/dibuktikan.
- [ ] Kriteria mencakup skenario berhasil, gagal, dan authorization yang relevan.
- [ ] Kriteria dapat diuji melalui unit, feature, manual, atau acceptance test yang ditetapkan.
- [ ] Kriteria disetujui oleh owner bisnis/teknis yang sesuai.

### 2.8 Risiko dan Rencana Pengujian

- [ ] Risiko bisnis, data, keamanan, performa, operasional, dan regresi telah dinilai.
- [ ] Mitigasi atau acceptance risk memiliki owner dan keputusan yang jelas.
- [ ] Strategi testing telah dipilih: unit, feature, manual, regression, security, atau performance sesuai risiko.
- [ ] Kebutuhan logging, Activity Log, Stock History, Login History, dan monitoring telah ditentukan bila relevan.

### 2.9 Kriteria keputusan Ready

Task dapat dipindahkan dari **Not Started** ke **Ready** bila checklist yang relevan lengkap, output dapat ditelusuri, dan approver menyatakan tidak ada blocker kritis. Jika satu input penting belum ada, task tetap **Not Started** atau **Blocked**, bukan **In Development**.

## 3. Definition of Done

Task/fitur hanya dinyatakan **Done** bila seluruh acceptance criteria terpenuhi serta seluruh butir relevan di bawah telah diverifikasi.

### 3.1 Requirement dan Business Rules

- [ ] Semua requirement dalam scope task terpenuhi.
- [ ] Seluruh Business Rules diterapkan, termasuk skenario gagal dan batasan akses.
- [ ] Tidak ada perilaku di luar scope yang ditambahkan tanpa change request/approval.
- [ ] Acceptance criteria telah divalidasi dengan bukti hasil test atau acceptance review.

### 3.2 Coding Standard dan Architecture

- [ ] Kode mengikuti PSR-12, Laravel convention, Development Bible, dan Folder Architecture Standard.
- [ ] Naming class, method, variable, route, view, file, folder, dan namespace konsisten.
- [ ] Controller tetap tipis; aturan bisnis ditempatkan pada service/domain yang sesuai.
- [ ] Service layer, repository, action, policy, job, event, atau observer digunakan hanya jika relevan dan sesuai tanggung jawabnya.
- [ ] Tidak ada duplicate code, dead code, commented-out code, debug artifact, magic value tanpa konteks, atau abstraction tanpa nilai.
- [ ] Perubahan tidak melanggar batas domain maupun struktur folder resmi.

### 3.3 Validation dan Data Integrity

- [ ] Form Request digunakan untuk input HTTP yang bermakna, kecuali pengecualian sangat sederhana telah disetujui.
- [ ] Validasi server-side lengkap untuk tipe, format, batas nilai, status, file, dan ownership yang relevan.
- [ ] Perubahan data tidak merusak integritas, relationship, atau lifecycle data.
- [ ] Migration, bila ada, aman, direview, konsisten dengan ERD/Database Dictionary, dan memiliki pertimbangan rollback/backup.
- [ ] Operasi data lintas entitas yang atomik menggunakan transaksi yang sesuai.
- [ ] Aturan kritis—misalnya stok tidak negatif dan perubahan stok tercatat—terjaga.

### 3.4 Security

- [ ] Authentication dan authorization benar untuk seluruh aktor yang terdampak.
- [ ] Middleware, policy, ownership data, email verification, role, dan Face Verification diterapkan bila relevan.
- [ ] CSRF, validasi input, mass assignment protection, output escaping, dan query parameter binding tidak diabaikan.
- [ ] Upload file tervalidasi, disimpan pada disk/path yang tepat, dan berkas privat tidak dapat diakses bebas.
- [ ] Secret, password, token, data biometrik, dan data pribadi tidak diekspos dalam kode, log, error, atau repository.
- [ ] Rate limiter, session, Activity Log, Login History, atau Security Event diterapkan bila feature menyentuh area tersebut.
- [ ] Tidak ada celah umum yang diketahui hasil dari review perubahan ini; risiko residual didokumentasikan dan diterima secara eksplisit bila ada.

### 3.5 Performance

- [ ] Daftar data menggunakan pagination bila ukuran data dapat bertumbuh.
- [ ] Eager loading digunakan pada relasi yang dirender; tidak ada N+1 query yang diketahui.
- [ ] Query mengambil data secukupnya, filter/sort terukur, dan implikasi indeks telah ditinjau.
- [ ] Gambar/unggahan memakai ukuran, thumbnail, kompresi, atau akses storage yang sesuai bila relevan.
- [ ] Cache, queue, lazy loading, atau optimasi lain hanya digunakan bila memberikan nilai dan memiliki invalidasi/monitoring yang jelas.
- [ ] Target performa atau hasil pengukuran relevan dicatat untuk task berisiko tinggi.

### 3.6 Error Handling dan Logging

- [ ] Pesan error untuk pengguna jelas, aman, dan memberikan tindakan lanjutan yang sesuai.
- [ ] Exception tidak disembunyikan; kegagalan penting dicatat dengan context aman.
- [ ] Activity Log, Stock History, Login History, Failed Login History, atau audit trail diperbarui bila diwajibkan oleh proses.
- [ ] Log tidak menyimpan password, token, secret, atau data pribadi berlebihan.

### 3.7 Testing

- [ ] Unit test ditambahkan/diperbarui bila logika dapat diisolasi.
- [ ] Feature test ditambahkan/diperbarui untuk alur HTTP, validation, authorization, dan integrasi yang relevan.
- [ ] Manual testing lulus untuk user journey dan UI yang terdampak.
- [ ] Skenario berhasil, gagal, edge case, akses tanpa izin, dan regresi yang relevan telah diuji.
- [ ] Semua test yang diwajibkan lulus; kegagalan test yang tidak terkait tidak diabaikan tanpa dicatat dan ditindaklanjuti.

### 3.8 Documentation, Review, dan Approval

- [ ] Requirement, Business Rule, flow, SRS, data/UI design, manual, changelog, atau deployment note diperbarui sesuai dampak.
- [ ] Pull request/work item mencantumkan ringkasan, risiko, test, dependency, dan keputusan material.
- [ ] Self-review dan review oleh pihak yang berwenang selesai; seluruh temuan kritis ditutup.
- [ ] Acceptance/approval bisnis diperoleh bila fitur mengubah proses Customer, Owner, atau Admin.
- [ ] Tidak ada blocker atau defect kritis yang terbuka.

### 3.9 Kriteria keputusan Done

Task hanya boleh dipindahkan ke **Approved** atau **Released** setelah review menyatakan seluruh butir relevan selesai, bukti test tersedia, dan approver memberikan persetujuan. "Kode sudah berjalan di lokal" bukan Definition of Done.

## 4. Acceptance Criteria Standard

Acceptance criteria memakai format **Given – When – Then** dan harus bersifat spesifik, dapat diuji, serta terkait dengan satu outcome bisnis. Kriteria tidak boleh hanya berbunyi "fitur berjalan dengan baik".

### 4.1 Template

| Elemen | Isi |
| --- | --- |
| Given | Kondisi awal, aktor, data, role, atau precondition. |
| When | Aksi/peristiwa yang dilakukan pengguna atau sistem. |
| Then | Hasil yang dapat diverifikasi, termasuk data, status, akses, pesan, atau audit bila relevan. |
| And | Kondisi tambahan yang harus tetap benar, termasuk keamanan/performa bila relevan. |

### 4.2 Contoh Login

- **Given** Customer memiliki akun aktif dan email terverifikasi, **When** Customer memasukkan kredensial yang benar, **Then** Customer berhasil masuk dan hanya dapat mengakses fitur Customer.
- **Given** pengguna memasukkan kredensial yang salah berulang kali, **When** batas rate limiter tercapai, **Then** sistem menolak percobaan berikutnya sesuai kebijakan dan mencatat kejadian keamanan tanpa menyimpan password.

### 4.3 Contoh Produk

- **Given** Admin atau Owner memiliki hak akses produk, **When** pengguna menyimpan produk dengan data valid, **Then** produk tersimpan sesuai aturan dan aktivitas penting dapat diaudit.
- **Given** produk berstatus nonaktif, **When** Customer mencoba menambahkannya ke cart atau checkout, **Then** sistem menolak transaksi produk tersebut dan menampilkan informasi yang aman.

### 4.4 Contoh Checkout

- **Given** Customer telah terverifikasi, memiliki cart valid, dan stok tersedia, **When** Customer melakukan checkout dengan metode Pick-Up, **Then** sistem membuat order dengan nomor order dan kode Pick-Up yang unik serta status awal yang sesuai.
- **Given** stok item tidak mencukupi pada saat checkout, **When** Customer mengonfirmasi pesanan, **Then** sistem tidak membuat pesanan parsial atau stok negatif dan memberi informasi yang dapat ditindaklanjuti.

### 4.5 Contoh Pembayaran

- **Given** Customer memiliki order QRIS yang menunggu pembayaran, **When** Customer mengunggah bukti dengan format dan ukuran valid, **Then** bukti disimpan privat dan order dapat masuk ke antrean verifikasi Owner.
- **Given** Owner berwenang memeriksa bukti QRIS, **When** Owner menyetujui pembayaran valid, **Then** status pembayaran/order diperbarui sesuai Business Rule dan tindakan tercatat dalam audit log.

### 4.6 Contoh Rating

- **Given** Customer memiliki order berstatus Completed yang memuat suatu produk, **When** Customer mengirimkan satu rating valid untuk produk tersebut, **Then** rating tersimpan dan ditautkan ke pembelian yang tepat.
- **Given** Customer belum membeli produk pada order Completed atau telah memberi rating pada produk itu di order yang sama, **When** Customer mencoba memberi rating, **Then** sistem menolak tindakan tersebut tanpa mengubah data rating sebelumnya.

## 5. Review Checklist

Reviewer dan pembuat perubahan menggunakan checklist berikut sesuai relevansi:

- [ ] Requirement, scope, actor, dan acceptance criteria sesuai dengan artefak yang disetujui.
- [ ] Business Rules, status, exception, dan aturan audit dipatuhi.
- [ ] Folder, namespace, nama file, class, route, view, method, dan variable mengikuti standar.
- [ ] Tidak ada duplicate code, god class/controller, hardcode merugikan, dead code, atau dependency tidak perlu.
- [ ] Service digunakan untuk business process; repository/action/policy/job/event dipakai secara tepat dan tidak berlebihan.
- [ ] Form Request dan server-side validation lengkap.
- [ ] Authorization, ownership resource, role, email/Face Verification, session, rate limiting, dan file access diperiksa bila relevan.
- [ ] CSRF, XSS, SQL injection, mass assignment, secret exposure, dan error disclosure diperiksa.
- [ ] Transaction, data integrity, migration safety, Stock History, dan Activity Log diperiksa bila data transaksi berubah.
- [ ] Pagination, eager loading, N+1, query, index implication, image/media, cache, dan respons halaman diperiksa bila relevan.
- [ ] Error handling serta logging aman dan dapat ditindaklanjuti.
- [ ] Unit/feature/manual/regression test relevan lulus dan bukti hasil tersedia.
- [ ] Dokumentasi, changelog, manual, dan catatan deployment diperbarui.
- [ ] Temuan review ditutup, disetujui, atau memiliki risiko residual yang diterima secara eksplisit.

## 6. Quality Gate

Status task dan syarat transisinya adalah sebagai berikut:

| Status | Makna | Syarat masuk | Syarat keluar |
| --- | --- | --- | --- |
| Not Started | Belum siap dikerjakan atau belum diprioritaskan. | Task tercatat di backlog/plan. | DoR relevan lengkap dan tidak ada blocker kritis. |
| Ready | Input cukup jelas untuk memulai pekerjaan. | Requirement, rule, flow, dependency, risiko, dan acceptance criteria siap. | Pelaksana mengambil task dan memulai aktivitas yang disetujui. |
| In Development | Desain/implementasi/test awal sedang dikerjakan. | Task Ready dan owner ditetapkan. | Perubahan selesai secara internal, self-review/test awal lulus, lalu diajukan review. |
| Code Review | Artefak implementasi sedang diperiksa. | PR/work item berisi ringkasan, test, risiko, dan dokumentasi relevan. | Temuan kritis diperbaiki, perubahan memenuhi review checklist. |
| Testing | Validasi fungsional, nonfungsional, dan acceptance sedang dilakukan. | Review teknis memenuhi syarat atau izin test diberikan sesuai proses. | Semua test wajib lulus, defect kritis ditutup, bukti test tersedia. |
| Approved | Hasil memenuhi DoD dan disetujui untuk rilis/penjadwalan. | Test/review/dokumentasi/approval relevan selesai. | Release gate terpenuhi dan rilis dijadwalkan atau dilakukan. |
| Released | Perubahan sudah berada pada environment rilis yang disetujui. | Deployment, konfigurasi, backup/rollback, dan health check selesai. | Monitoring pascarilis stabil; task dapat ditutup. |
| Blocked | Task tidak dapat aman dilanjutkan. | Terdapat blocker material. | Blocker diselesaikan dan state kembali ke status sesuai kondisi. |

Status bukan pengganti catatan kualitas. Task dapat kembali ke status sebelumnya bila temuan baru menunjukkan input, desain, atau implementasi belum memadai.

## 7. Approval Workflow

Alur approval wajib mengikuti urutan berikut:

1. **Requirement:** Business Analyst/Product Owner mendokumentasikan kebutuhan, scope, dan acceptance criteria.
2. **Review requirement:** Stakeholder bisnis, Admin/Owner yang relevan, dan Technical Lead memeriksa kejelasan, risiko, serta kelayakan.
3. **Requirement approval:** Pihak berwenang menyetujui requirement dan Business Rules; task dapat masuk Ready setelah input lain lengkap.
4. **Development:** Developer/Codex bekerja hanya pada scope yang siap dan mengikuti standar arsitektur.
5. **Technical review:** Reviewer memeriksa implementasi, test, keamanan, performa, dokumentasi, dan dampak regresi.
6. **Testing:** QA/developer/stakeholder menjalankan skenario test serta acceptance yang disepakati.
7. **Final approval:** Technical Lead dan pemilik keputusan bisnis menyetujui hasil bila DoD terpenuhi.
8. **Release approval:** Pihak release/operasional menyetujui jadwal dan kesiapan deployment untuk perubahan yang akan diproduksikan.

Persetujuan tidak bersifat seremonial: approver wajib dapat meninjau bukti requirement, hasil test, risiko, dan perubahan yang disetujui. Untuk perubahan kecil berisiko rendah, peran dapat dirangkap oleh orang yang berwenang, tetapi self-approval terhadap perubahan sensitif sebaiknya dihindari.

## 8. Blocker Criteria

Task tidak boleh dimulai, harus dihentikan, atau harus dikembalikan ke tahap sebelumnya jika salah satu kondisi berikut terjadi:

- Requirement, scope, acceptance criteria, atau owner keputusan belum jelas.
- Business Rules belum tersedia, belum disetujui, atau saling bertentangan.
- Flow tidak final atau konflik desain/operasional belum diselesaikan.
- ERD/Database Dictionary belum final untuk task yang mengubah data; desain relationship/constraint masih diperdebatkan.
- Wireframe/pola UI belum tersedia untuk task yang mengubah pengalaman pengguna secara material.
- Ada dependency, credential, environment, akses, atau keputusan stack yang belum tersedia.
- Scope, arsitektur, Business Rules, atau security policy berubah tanpa change request dan approval.
- Risiko kritis terhadap data, keamanan, operasional, atau compliance belum dimitigasi/diputuskan.
- Test wajib gagal, terdapat defect kritis, atau hasil acceptance tidak memenuhi kriteria.
- Tidak ada rencana rollback/backup untuk perubahan yang berisiko terhadap production/data.

Blocker harus dicatat dengan dampak, owner, tindakan penyelesaian, dan tanggal peninjauan. Task tidak boleh dipaksa lanjut hanya untuk memenuhi target waktu.

## 9. Quality Metrics

Metrik berikut digunakan untuk menilai konsistensi proses dan kualitas hasil. Metrik adalah alat perbaikan, bukan alasan untuk menutup masalah.

| Area | Indikator |
| --- | --- |
| Completion quality | Persentase task yang selesai dengan seluruh DoD relevan terpenuhi dan tanpa reopen karena kelalaian proses. |
| Defect quality | Tidak ada bug kritis terbuka pada alur inti; jumlah defect pascarilis dan waktu penyelesaiannya dipantau. |
| Requirement quality | Persentase task yang memasuki development dengan DoR lengkap; jumlah perubahan akibat requirement ambigu ditinjau. |
| Documentation | Requirement, rule, flow, test, dan catatan rilis tersedia serta sinkron dengan perilaku yang dirilis. |
| Architecture compliance | Tidak ada pelanggaran terbuka terhadap Development Bible atau Folder Architecture tanpa pengecualian disetujui. |
| Security | Semua perubahan sensitif menjalani review authorization/validation/logging; tidak ada secret atau data sensitif terpapar. |
| Performance | Tidak ada query tidak efisien/N+1 yang diketahui pada jalur kritis; daftar besar memakai pagination dan target respons dievaluasi. |
| Testing | Semua test wajib lulus; cakupan test dan kualitas skenario dinilai berdasarkan risiko, bukan hanya angka coverage. |
| Review | Semua perubahan berisiko sedang/tinggi memiliki bukti review dan seluruh temuan kritis telah ditutup. |
| Delivery health | Jumlah task Blocked, waktu di setiap quality gate, rework, dan technical debt ditinjau pada milestone. |

Target numerik untuk metrik ditetapkan setelah baseline pengerjaan tersedia. Pada tahap awal, kepatuhan terhadap checklist dan tidak adanya defect kritis lebih penting daripada mengejar angka coverage atau kecepatan semata.

## 10. Conclusion

DoR dan DoD ini adalah standar kualitas wajib bagi Website Toko ATK. DoR memastikan tim hanya memulai pekerjaan yang dapat dipahami dan dikerjakan dengan aman. DoD memastikan setiap hasil telah memenuhi requirement, aturan bisnis, arsitektur, keamanan, performa, test, review, dan dokumentasi yang relevan.

Dokumen ini digunakan bersama Development Workflow Standard sebagai gerbang kerja resmi. Tidak ada kode, migration, database, controller, model, route, UI, atau wireframe yang dibuat oleh dokumen ini; dokumen ini menentukan syarat kapan artefak tersebut kelak boleh dibuat dan kapan hasilnya dapat diterima.
