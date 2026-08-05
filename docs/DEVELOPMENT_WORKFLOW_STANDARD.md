# Development Workflow Standard

## Website Toko Alat Tulis Kantor (ATK)

| Informasi | Standar |
| --- | --- |
| Framework | Laravel 10 |
| Target | Production ready |
| Metode pengembangan | Incremental Development |
| Pendekatan | Requirement-Driven Development |
| Status | Standar alur kerja resmi dari perencanaan sampai deployment |
| Dokumen terkait | Vision, BRD, Scope, Stakeholder, Technology Stack, Development Bible, dan Folder Architecture |

## 1. Executive Summary

Development Workflow Standard ini menetapkan urutan kerja wajib agar Website Toko ATK dibangun berdasarkan kebutuhan yang telah disetujui, bukan langsung dari asumsi atau kode. Setiap tahap menghasilkan artefak yang menjadi input tahap berikutnya. Dengan demikian, keputusan bisnis, desain, implementasi, pengujian, dan deployment dapat ditelusuri serta dievaluasi secara objektif.

Proyek memakai Incremental Development: fitur dikembangkan dalam bagian kecil yang bernilai bisnis, divalidasi, lalu ditambahkan secara bertahap. Namun, incremental bukan berarti bebas menambah fitur tanpa kendali. Setiap increment harus tetap mematuhi scope, business rule, standar arsitektur, Definition of Ready, dan Definition of Done.

## 2. Development Lifecycle

Urutan lifecycle berikut adalah baseline proyek. Tahap tidak boleh dilompati; pekerjaan dapat tumpang tindih secara terbatas hanya bila input dan keputusan yang dibutuhkan sudah disetujui.

| Urutan | Tahap | Tujuan | Alasan urutan |
| --- | --- | --- | --- |
| 1 | Vision Project | Menetapkan arah, masalah, nilai, dan tujuan besar sistem. | Semua keputusan berikut harus memiliki orientasi bisnis yang sama. |
| 2 | Business Requirement Document (BRD) | Menerjemahkan visi menjadi kebutuhan dan manfaat bisnis. | Requirement perlu dipahami sebelum solusi teknis ditentukan. |
| 3 | Project Scope | Menetapkan fitur V1, batasan, asumsi, dan out of scope. | Mencegah scope creep serta memastikan fokus rilis. |
| 4 | Stakeholder Analysis | Mengidentifikasi peran, kepentingan, akses, kebutuhan, dan risiko stakeholder. | Menjadi dasar role, authorization, use case, dan komunikasi. |
| 5 | Technology Stack | Menetapkan teknologi, environment, keamanan, dan arah deployment. | Menghindari pilihan tool/dependency yang tidak konsisten saat coding. |
| 6 | Development Bible | Menetapkan standar coding, arsitektur, kualitas, keamanan, dan review. | Menjaga konsistensi implementasi seluruh tim. |
| 7 | Folder Architecture | Menetapkan organisasi file dan batas domain proyek. | File baru memiliki lokasi yang jelas sejak implementasi dimulai. |
| 8 | Development Workflow | Menetapkan proses kerja, quality gate, perubahan, dan governance. | Semua pihak memiliki urutan kerja serta jalur approval yang sama. |
| 9 | Definition of Done | Mengunci kriteria kapan artefak/fitur benar-benar selesai. | Mencegah fitur dianggap selesai hanya karena kode telah ditulis. |
| 10 | Milestone Review | Memeriksa kelengkapan dan konsistensi artefak milestone. | Kesalahan arah diperbaiki sebelum biaya perubahan meningkat. |
| 11 | Software Requirement Specification (SRS) | Merinci kebutuhan sistem yang dapat dibangun dan diuji. | Menjadi kontrak teknis-fungsional sebelum desain rinci. |
| 12 | Business Rules | Menetapkan aturan transaksi, stok, pembayaran, status, rating, dan pengecualian. | Aturan ini mengendalikan perilaku sistem dan tidak boleh tersirat. |
| 13 | Functional Requirement | Menetapkan kemampuan sistem per aktor/modul. | Menentukan fitur yang harus disediakan. |
| 14 | Non-Functional Requirement | Menetapkan target keamanan, performa, reliabilitas, usability, dan maintainability. | Kualitas tidak boleh ditambahkan belakangan tanpa target. |
| 15 | UML | Memodelkan use case dan alur/interaksi yang disepakati. | Memvalidasi pemahaman proses sebelum struktur data dan kode. |
| 16 | ERD | Merancang entitas, relasi, integritas, dan lifecycle data. | Basis data harus mendukung aturan dan alur yang sudah final. |
| 17 | Database Dictionary | Mendefinisikan arti data, tipe, aturan, dan ownership secara rinci. | Menghindari perbedaan interpretasi data antara developer dan stakeholder. |
| 18 | UI Wireframe | Menyusun struktur pengalaman pengguna dan informasi per halaman. | Antarmuka dibangun dari requirement/flow, bukan dari tebakan implementasi. |
| 19 | Laravel Development | Mengimplementasikan increment sesuai desain yang telah disetujui. | Coding baru aman dimulai setelah requirement, data, dan UI cukup jelas. |
| 20 | Testing | Memverifikasi fungsi, aturan bisnis, akses, keamanan, dan kualitas. | Temuan harus diselesaikan sebelum optimasi/rilis. |
| 21 | Optimization | Mengukur dan memperbaiki performa, query, media, serta operasional. | Optimasi dilakukan atas hasil pengukuran dan setelah fungsi benar. |
| 22 | Deployment | Merilis artefak yang telah diterima ke environment target. | Production hanya menerima rilis yang teruji dan dapat dipulihkan. |

Definition of Done berlaku pada setiap tahap/fitur; Milestone Review adalah quality gate formal sebelum proyek berpindah ke kumpulan artefak besar berikutnya. SRS hingga Database Dictionary dan Wireframe merupakan pekerjaan tahap setelah Milestone 1, bukan izin untuk mulai membuat implementasi saat ini.

## 3. Development Workflow

### 3.1 Siklus kerja incremental

Setiap increment mengambil satu domain atau bagian domain yang dapat memberikan nilai dan dapat diuji, misalnya Product, Category, Stock, Cart, Checkout, Payment, atau Order. Prioritas awal mengikuti dependensi bisnis: autentikasi dan master data mendukung katalog; katalog/stok mendukung cart; cart mendukung checkout; checkout mendukung payment/order/Pick-Up.

Siklus standar suatu increment adalah:

1. Pilih item backlog yang sudah prioritas dan memenuhi Definition of Ready.
2. Konfirmasi requirement, business rule, aktor, scope, dan acceptance criteria.
3. Analisis dampak pada data, UI, authorization, keamanan, performa, dokumentasi, dan fitur lain.
4. Finalisasi desain artefak yang dipersyaratkan: SRS, flow, ERD/dictionary bila ada dampak data, serta wireframe bila ada dampak UI.
5. Rencanakan pekerjaan backend dan frontend sesuai Development Bible serta Folder Architecture.
6. Implementasikan dalam branch terpisah dengan commit kecil dan terarah.
7. Tulis atau perbarui pengujian; jalankan test/lint/quality check yang relevan.
8. Lakukan self-review, pull request, dan peer/technical review.
9. Lakukan acceptance review dengan stakeholder yang berwenang bila perilaku bisnis berubah.
10. Perbarui dokumentasi, changelog, dan backlog; kemudian tandai increment selesai bila memenuhi Definition of Done.

### 3.2 Quality gates

| Gate | Syarat untuk melanjutkan |
| --- | --- |
| Requirement Gate | Scope, aktor, business value, business rule, acceptance criteria, dan owner keputusan telah jelas. |
| Design Gate | Dampak data, akses, UI, keamanan, performa, dan integrasi telah dianalisis; ERD/dictionary final bila data berubah. |
| Development Gate | Branch/work item siap, desain disetujui, dependensi tersedia, dan tidak ada blocker kritis. |
| Review Gate | Kode, test, dokumentasi, dan review checklist memenuhi standar; temuan kritis ditutup. |
| Acceptance Gate | Stakeholder berwenang menyetujui hasil terhadap acceptance criteria. |
| Release Gate | Test selesai, deployment plan/rollback/backup siap, konfigurasi production tervalidasi, dan tidak ada risiko kritis terbuka. |

## 4. Feature Workflow

Workflow berikut berlaku untuk setiap modul, termasuk Product, Category, Stock, Cart, Checkout, Order, Payment, Report, dan Security Center. Tidak ada coding langsung tanpa requirement yang dapat diuji.

| Langkah | Fokus | Output minimum |
| --- | --- | --- |
| 1. Requirement | Tujuan, aktor, value, scope, dan acceptance criteria. | Requirement/entry SRS yang disetujui. |
| 2. Business Rule | Validasi, status, izin, batasan, pengecualian, serta audit. | Daftar business rule yang eksplisit. |
| 3. Flow | Alur normal, gagal, alternatif, dan tanggung jawab aktor. | Flow naratif/use case yang direview. |
| 4. Database Impact | Entitas/relasi, perubahan data, integritas, index, lifecycle, dan migrasi potensial. | Dampak ERD + database dictionary final bila ada perubahan data. |
| 5. UI Impact | Halaman, field, informasi, state loading/error/empty, serta aksesibilitas. | Wireframe/pola UI yang disetujui bila ada perubahan tampilan. |
| 6. Backend Planning | Controller adapter, request, service, policy, model/repository/job/notification, transaction, log, dan test. | Rencana teknis backend sesuai Folder Architecture. |
| 7. Frontend Planning | Blade layout/component, Bootstrap, asset/interaksi ringan, validasi UX, dan state. | Rencana frontend sesuai UI Rules. |
| 8. Development | Implementasi bertahap pada branch, sesuai rencana dan standar. | Kode serta perubahan konfigurasi/data yang diperlukan. |
| 9. Testing | Unit, feature, authorization, negative path, manual acceptance, dan regression. | Hasil test yang lulus atau daftar temuan. |
| 10. Review | Self-review, code review, security/performance/document review. | PR/review record dan temuan tertutup. |
| 11. Approval | Persetujuan teknis dan bisnis terhadap acceptance criteria. | Keputusan approve atau perbaikan terarah. |
| 12. Documentation Update | Requirement, rule, flow, changelog, manual, dan deployment note. | Dokumentasi yang sinkron dengan perilaku final. |

### 4.1 Contoh penerapan ringkas per domain

- **Product:** pastikan status aktif/nonaktif, SKU, gambar, harga, akses Admin/Owner, serta dampak katalog; stok tidak dicampur sebagai perubahan tanpa Stock History.
- **Order:** definisikan lifecycle, nomor order, kode Pick-Up, hak Customer/Owner/Admin, dan semua transisi yang diizinkan sebelum desain data atau controller dibuat.
- **Payment:** bedakan COD dan QRIS; QRIS memerlukan bukti, akses file privat, verifikasi Owner, audit, dan dampak status order yang eksplisit.
- **Stock:** setiap sumber perubahan, alasan, actor, kuantitas, referensi order, aturan stok tidak negatif, dan transaksi atomik harus terdokumentasi.

## 5. Micro Step Template

Template berikut dipakai untuk setiap step kerja kecil, baik dokumentasi, desain, implementasi, test, maupun deployment. Satu step harus cukup kecil untuk ditinjau dan mempunyai hasil yang dapat diverifikasi.

| Elemen | Isi yang wajib diisi |
| --- | --- |
| Nomor Step | Identifier berurutan yang unik di work item/milestone. |
| Nama Step | Nama tindakan yang spesifik, misalnya “Finalisasi aturan stok checkout”. |
| Tujuan | Nilai/keputusan yang ingin dicapai oleh step tersebut. |
| Input | Dokumen, keputusan, data, dependency, atau hasil step sebelumnya. |
| Aktivitas | Pekerjaan yang dilakukan, batas tanggung jawab, dan pihak terlibat. |
| Output | Artefak nyata: dokumen, keputusan, perubahan ter-review, hasil test, atau catatan rilis. |
| Checklist | Daftar pemeriksaan spesifik untuk memastikan aktivitas/aturan penting tidak terlewat. |
| Definition of Done | Kriteria objektif yang menyatakan step selesai dan diterima. |
| Next Step | Step lanjutan yang dapat dimulai setelah output diterima. |
| Owner/Approver | Pihak yang melakukan pekerjaan dan pihak yang berwenang menyetujui, bila berbeda. |
| Risiko/Dependency | Risiko, asumsi, atau blocker yang perlu dicatat dan ditindaklanjuti. |

### Template penggunaan

| Elemen | Template |
| --- | --- |
| Nomor Step | `[Milestone]-[Feature]-[Nomor]` |
| Nama Step | `[Kata kerja] [artefak/proses] untuk [fitur]` |
| Tujuan | `Menetapkan/memverifikasi … agar …` |
| Input | `Dokumen/keputusan/hasil test …` |
| Aktivitas | `Analisis, desain, implementasi, atau review yang terukur` |
| Output | `Tautan/nama artefak atau hasil verifikasi` |
| Checklist | `Butir yang relevan dengan requirement, rule, security, performance, dan dokumentasi` |
| Definition of Done | `Output lengkap, direview, disetujui, dan tidak ada temuan kritis` |
| Next Step | `Step berikutnya yang memakai output tersebut` |

## 6. Review Workflow

Setiap perubahan artefak atau implementasi wajib menjalani review proporsional terhadap risiko. Perubahan dokumentasi sederhana dapat melalui self-review; perubahan stok, payment, order, security, akses, dependency, konfigurasi, atau deployment memerlukan review teknis oleh pihak yang berwenang.

### 6.1 Urutan review

1. Pembuat perubahan melakukan self-review terhadap requirement dan checklist.
2. Pembuat memastikan test/validasi yang relevan telah dijalankan serta dokumentasi diperbarui.
3. Reviewer memeriksa kesesuaian bisnis, arsitektur, kualitas, keamanan, performa, dan dampak regresi.
4. Temuan dicatat dengan tingkat prioritas; temuan kritis harus diperbaiki sebelum approval.
5. Reviewer menyetujui, meminta perubahan, atau menolak dengan alasan yang dapat ditindaklanjuti.
6. Bila perubahan memengaruhi proses bisnis, stakeholder bisnis melakukan acceptance review.
7. Keputusan review dan perubahan penting didokumentasikan pada PR/work item/changelog sesuai konteks.

### 6.2 Checklist review minimum

- [ ] Requirement dan scope yang disetujui telah dipenuhi.
- [ ] Business rule, status, exception, dan acceptance criteria diterapkan/tercatat dengan benar.
- [ ] Naming, folder, namespace, dan arsitektur sesuai Development Bible serta Folder Architecture.
- [ ] Authorization, ownership data, validation, session, file access, audit, dan error handling aman.
- [ ] Tidak ada dependency baru, perubahan arsitektur, atau perubahan business rule tanpa persetujuan.
- [ ] Query, eager loading, pagination, index implication, media, dan cache ditinjau untuk dampak performa.
- [ ] Pengujian yang relevan lulus, termasuk jalur gagal dan akses tidak sah.
- [ ] Tidak ada duplicate code, debug artifact, secret, dead code, atau hardcode yang merugikan.
- [ ] Dokumentasi requirement, rule, flow, test, manual, atau changelog diperbarui sesuai dampak.

## 7. AI (Codex) Workflow

Codex digunakan sebagai pendamping kerja terkontrol, bukan pengganti owner keputusan bisnis atau teknis. Semua output AI harus ditinjau manusia/penanggung jawab proyek sesuai risiko perubahan.

### 7.1 Penggunaan yang diizinkan

- Menyusun atau menyempurnakan draft dokumentasi, requirement, checklist, test case, dan catatan review.
- Membantu memahami struktur proyek dan menganalisis dampak perubahan terhadap standar yang sudah disetujui.
- Membuat draft implementasi setelah requirement, desain, dan scope telah disetujui.
- Membantu refactor yang mempertahankan perilaku serta memperbaiki konsistensi/naming/duplikasi.
- Membantu menulis atau meninjau test, melakukan analisis error, dan merangkum hasil verifikasi.
- Membantu audit terhadap Development Bible, Folder Architecture, keamanan dasar, performa, dan dokumentasi.

### 7.2 Batasan wajib bagi Codex

- Tidak mengubah arsitektur, stack teknologi, folder standard, atau pola inti tanpa persetujuan eksplisit.
- Tidak menambah, menghapus, atau memperbarui dependency sembarangan; setiap dependency harus melalui evaluasi dan approval.
- Tidak mengubah Business Rules, Project Scope, prioritas, status, atau acceptance criteria tanpa keputusan stakeholder.
- Tidak membuat migration sebelum ERD dan Database Dictionary final disetujui.
- Tidak membuat controller sebelum desain data final dan requirement/backend plan tersedia.
- Tidak melakukan deployment, perubahan production, penghapusan data, atau tindakan eksternal tanpa otorisasi yang jelas.
- Tidak menyimpan, mengekspos, atau menyalin secret, credential, token, data biometrik, atau data pribadi di luar kebutuhan yang sah.

### 7.3 Proses penggunaan Codex

1. Berikan konteks feature, link dokumen/aturan, scope, dan output yang diharapkan.
2. Minta Codex melakukan inspeksi atau membuat rencana/draft yang terbatas pada scope tersebut.
3. Periksa rekomendasi terhadap requirement, Business Rules, Development Bible, dan Folder Architecture.
4. Izinkan implementasi hanya setelah design gate terpenuhi.
5. Minta Codex menjalankan verifikasi yang relevan, merangkum perubahan, risiko, dan hasil test.
6. Lakukan review manusia sebelum merge atau deployment.

## 8. Change Management

Permintaan fitur baru, perubahan aturan, perubahan prioritas, atau perubahan desain setelah baseline tidak boleh langsung masuk ke versi yang sedang dikerjakan. Semua perubahan mengikuti proses berikut:

1. **Analisis kebutuhan:** catat masalah, tujuan bisnis, stakeholder terdampak, dan alasan perubahan.
2. **Analisis dampak:** nilai dampak terhadap scope, waktu, biaya, data, keamanan, performa, UX, testing, dokumentasi, dan dependency.
3. **Backlog:** masukkan sebagai item backlog dengan prioritas awal dan hubungan terhadap requirement yang ada.
4. **Approval:** pemilik toko/stakeholder berwenang dan technical lead memutuskan menerima, menolak, menunda, atau meminta analisis tambahan.
5. **Penjadwalan:** perubahan yang disetujui masuk ke future version atau increment/sprint berikutnya setelah Definition of Ready terpenuhi.
6. **Baseline update:** jika perubahan mengubah scope atau aturan resmi, perbarui dokumen terkait dan changelog keputusan sebelum coding.

Perubahan hanya dapat dimasukkan ke rilis berjalan jika bersifat defect/security critical, tidak dapat ditunda, dan mendapat persetujuan eksplisit beserta analisis risiko/rollback. Prosedur ini mencegah scope creep serta melindungi stabilitas rilis.

## 9. Versioning Strategy

Gunakan semantic versioning untuk release tag dan changelog.

| Versi | Kapan digunakan | Contoh |
| --- | --- | --- |
| `v1.0.0` | Rilis awal yang memenuhi scope V1 dan siap digunakan. | Rilis pertama katalog, transaksi Pick-Up, COD/QRIS manual, stok, laporan, dan keamanan yang disepakati. |
| `v1.1.0` | Fitur baru yang kompatibel dan disetujui setelah V1. | Penyempurnaan laporan atau fitur notifikasi yang tidak merusak perilaku existing. |
| `v1.2.0` | Fitur kompatibel tambahan berikutnya. | Peningkatan modul yang tetap kompatibel dengan data/proses yang ada. |
| `v1.0.1` | Perbaikan bug kompatibel tanpa fitur baru. | Perbaikan validasi, tampilan, atau query yang tidak mengubah kontrak bisnis. |
| `v2.0.0` | Perubahan besar/tidak kompatibel. | Perubahan alur bisnis inti, kontrak API, model multi-branch besar, atau perubahan yang membutuhkan migrasi/adopsi khusus. |

**Major** menaik ketika ada perubahan tidak kompatibel atau keputusan arsitektur/proses yang menuntut transisi. **Minor** menaik ketika ada fitur baru yang kompatibel. **Patch** menaik ketika memperbaiki bug, keamanan, atau performa tanpa mengubah kontrak fitur yang sudah disetujui. Setiap rilis harus memiliki changelog, catatan dampak, hasil test, langkah deployment, dan rencana rollback sesuai risiko.

## 10. Risk Management

| Risiko | Pencegahan melalui workflow | Respons bila terjadi |
| --- | --- | --- |
| Scope creep | Scope baseline, backlog, change management, dan approval sebelum perubahan. | Hentikan penambahan langsung; analisis dampak lalu jadwalkan ulang atau pindahkan ke future version. |
| Duplicate code | Development Bible, folder/domain standard, service layer, dan code review. | Refactor terarah dengan regression test sebelum menambah fitur lanjutan. |
| Bug fungsional | Requirement jelas, business rule, acceptance criteria, test negative path, dan review. | Catat defect, nilai severity, perbaiki di branch terkontrol, lalu regression test. |
| Struktur berantakan | Folder Architecture, naming convention, PR review, dan batas tanggung jawab class. | Reorganisasi bertahap pada work item refactor; jangan menumpuk pengecualian. |
| Performa menurun | NFR, performance review, pagination, eager loading, index strategy, dan pengukuran. | Profiling area lambat, optimasi berbasis bukti, dan uji regresi. |
| Technical debt | Definition of Done, review, dokumentasi, backlog debt, dan rilis incremental kecil. | Catat debt secara eksplisit, prioritaskan berdasarkan risiko/biaya, dan selesaikan sebelum menghambat domain inti. |
| Perubahan data tidak aman | Design gate, ERD/dictionary final, migration review, backup/rollback, dan test. | Hentikan rilis jika integritas data terancam; pulihkan dari backup/rollback sesuai SOP. |
| Insiden keamanan | Security rule, authorization review, dependency review, logging, dan release gate. | Contain, investigasi melalui audit log, perbaiki, putar credential bila perlu, dan dokumentasikan insiden. |

Risiko dicatat pada work item/milestone bila memiliki dampak terhadap jadwal, keamanan, data, atau operasional. Risiko terbuka dengan severity tinggi menjadi blocker untuk gate berikutnya sampai terdapat mitigasi yang disetujui.

## 11. Governance Rules

Aturan governance berikut bersifat wajib:

1. Tidak boleh coding fitur sebelum requirement, scope, business rule, dan acceptance criteria yang relevan selesai serta memenuhi Definition of Ready.
2. Tidak boleh membuat migration sebelum ERD dan Database Dictionary untuk perubahan tersebut final dan disetujui.
3. Tidak boleh membuat controller sebelum desain data final dan backend planning tersedia; controller harus mengikuti service/request/policy yang telah direncanakan.
4. Tidak boleh membuat UI implementatif sebelum wireframe atau pola UI yang relevan disetujui.
5. Tidak boleh mengubah Business Rules, Scope, Technology Stack, Development Bible, atau Folder Architecture tanpa dokumentasi change request dan approval yang sesuai.
6. Tidak boleh menambah dependency, mengubah konfigurasi sensitif, atau memperluas akses tanpa analisis keamanan/maintenance dan approval.
7. Tidak boleh merge perubahan yang belum direview atau belum memenuhi kualitas/test yang diwajibkan oleh tingkat risikonya.
8. Tidak boleh deployment sebelum test, review, acceptance yang relevan, backup, rollback plan, konfigurasi production, dan release gate selesai.
9. Tidak boleh memperlakukan output AI sebagai keputusan final; output Codex tetap tunduk pada review dan governance manusia.
10. Setiap perubahan material pada proses, data, akses, atau operasional wajib memperbarui dokumentasi terkait pada commit/PR yang sama atau sebelum rilis.

Pelanggaran governance harus dicatat sebagai temuan proses. Pekerjaan dapat dihentikan atau dikembalikan ke tahap sebelumnya jika input wajib belum lengkap atau risiko tidak dapat diterima.

## 12. Conclusion

Development Workflow Standard ini memastikan proyek berjalan dari keputusan bisnis yang jelas menuju implementasi Laravel yang teruji dan dapat dideploy dengan aman. Urutan dokumen, design gate, feature workflow, review, change management, dan governance dibangun untuk mengurangi revisi besar, defect, scope creep, serta technical debt.

Semua developer, reviewer, dan Codex wajib menggunakan dokumen ini bersama Development Bible dan Folder Architecture. Tidak ada implementasi, migration, controller, UI, atau deployment yang dibentuk oleh dokumen ini; dokumen ini adalah standar proses resmi yang mengatur kapan dan bagaimana pekerjaan tersebut kelak dapat dilakukan.
