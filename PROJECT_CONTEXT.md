# Sofitel Spa Yazılımı — Proje Hafızası

Son güncelleme: 29 Ağustos 2026

## Projenin amacı

Sofitel Spa operasyonlarını tek arayüzden yönetmek için masaüstü uygulaması hissi veren, tarayıcı tabanlı bir SPA yönetim yazılımı geliştiriliyor. Arayüz Sofitel Spa yeşil-altın kimliğini ve Vox/Windows tarzı açılır pencereleri kullanıyor.

## Adresler ve depo

- Yerel proje: `E:\kirpi\spa-web`
- Yerel uygulama: `http://127.0.0.1:8090/`
- Canlı uygulama: `https://krpsoft.com.tr/spa/`
- GitHub: `https://github.com/Gokben/Spa.git`
- Aktif geliştirme dalı: `codex/add-agustos-index`
- Bu hafıza oluşturulurken son Git commit: `f1b770e`
- Canlı Laravel uygulama klasörü: `/home/krpsoftc/spa-app`

Kimlik bilgileri, cPanel oturum adresleri ve `.env` sırları bu dosyada tutulmaz.

## Teknoloji

- Backend: PHP ve Laravel
- Canlı veritabanı: MySQL/MariaDB, veritabanı adı `krpsoftc_spa`
- Yerel geliştirme veritabanı: SQLite
- Arayüz: Laravel Blade içinde HTML, CSS ve JavaScript
- Ana Blade görünümü: `resources/views/spa.blade.php`
- API rotaları: `routes/web.php`

## Onaylanmış ana menü

- Üyeler
- Rezervasyon
- Ön Kasa
- Stok
- Personel
  - Çalışma Programı
- Raporlar
- Kurulum

## Giriş davranışı

Giriş ekranı korunmuştur. Şimdilik kullanıcı adı ve parola zorunluluğu olmadan “Başlat” düğmesiyle uygulamaya geçilir. Bu geçici davranış kullanıcı tarafından özellikle istenmiştir.

## Üyeler

- Üye listesi API'den gelir.
- Bir üyeye tıklanınca üye kartı açılır.
- Kartta kişisel bilgiler, üyelik bilgileri ve ödeme bilgileri bulunur.
- Üye kartı üst bölümde geniş renkli “Bilgiler”, “Üyelik” ve “Muhasebe” sekmelerine ayrılmıştır.
- Görünüm Vox/Windows tarzındadır.

## Personel

- Personel menüsü personel listesini açar.
- Yeni personel kartında üstteki ID satırı kaldırılmıştır.
- Kişisel bilgilerde Ad, Soyad, Personel No, Mesleği, Çalışma Grubu, giriş/çıkış/doğum tarihleri, kan grubu, cinsiyet ve durum bulunur.
- İletişim sekmesinde Telefon 1, Cep Telefonu, E-mail, İl, İlçe ve Adres bulunur.
- Fotoğraf yükleme desteklenir.
- Gereksiz eski sekmeler kaldırılmış, Kişisel Bilgileri, İletişim Bilgileri ve Belgeler bırakılmıştır.
- Personel API'si meslek ve çalışma grubu ilişkilerini döndürür.

Yerelde alan testi için beş örnek personel vardır. Bu kişiler canlıya gönderilmemiştir ve açık talep olmadan gönderilmemelidir.

## Stok

Vox ERP stok modülü Laravel yapısına uyarlanarak Stok menüsüne bağlanmıştır. Stok penceresinde dört ana sekme vardır:

1. Stok Listesi
2. Stok Kartı
3. Stok Giriş
4. Stok Çıkış

- Stok kartlarında kod, ad, kategori, marka, birim, minimum stok, alış/satış fiyatı, KDV, açıklama ve durum tutulur.
- Liste arama ve kategori filtresi içerir; minimum seviyeye düşen kayıtlar kritik stok olarak işaretlenir.
- Giriş ve çıkış hareketlerinde tarih, miktar, belge/fatura numarası ve açıklama tutulur.
- Mevcut miktardan fazla stok çıkışı sunucu tarafında engellenir.
- Stok kartı silindiğinde ona bağlı hareketler de silinir.

## Kurulum

Kurulum penceresinde dört sekme bulunur:

1. Mesai Tanımları
2. Çalışma Saatleri
3. Meslekler
4. Çalışma Grupları

### Mesai Tanımları

- Mesai ekleme, düzenleme ve silme desteklenir.
- İlk tanımlar kullanıcının sağladığı ekran görüntüsündeki saatlerden oluşturulmuştur.

### Çalışma Saatleri

- Haftanın yedi günü için açılış, kapanış ve kapalı durumu tutulur.
- Canlı başlangıç değerleri her gün `08:00–22:00` şeklindedir.
- Çalışma programında yalnızca günün çalışma saatleri içinde kalan mesailer seçilebilir.
- Sunucu tarafı da çalışma saatleri dışındaki mesai kaydını reddeder.

### Meslekler

Ekleme, düzenleme ve silme desteklenir. Canlıda tanımlı başlangıç kayıtları:

- Direktör
- Spa Şefi
- Resepsiyonist
- Terapist
- Operasyon sorumlusu

### Çalışma Grupları

- Çalışma grubu ekleme, düzenleme ve silme desteklenir.
- Bir çalışma grubu silindiğinde personel kaydı silinmez; personelin grup alanı boşalır.

## Çalışma Programı

- Haftalık takvim görünümündedir.
- Yılın ISO hafta numarası ve haftanın tarih aralığı gösterilir.
- Önceki Hafta, Bu Hafta, Sonraki Hafta, Haftayı Kaydet ve Yazdır düğmeleri bulunur.
- Yazdır düğmesinin hemen sağında “Çalışma grubunu seçiniz” seçim kutusu bulunur.
- Grup seçilmeden program satırları gösterilmez ve haftayı kaydetmeye izin verilmez.
- Seçilen grubun aktif çalışanları programda görünür.
- Grupsuz çalışanlar için “Grupsuz Personel” seçeneği vardır.
- Seçili grubun haftası kaydedilirken diğer çalışma gruplarının programları korunur.
- Mesailer günlük açılış-kapanış aralığına göre filtrelenir.
- OFF, İZİN ve RAPORLU durumları desteklenir.
- A4 yatay yazdırma görünümü Sofitel Spa logosunu ve hafta bilgisini içerir.

## Veri modeli

Başlıca uygulama tabloları:

- `members`
- `employees`
- `work_shifts`
- `business_hours`
- `employee_schedules`
- `occupations`
- `work_groups`
- `stock_items`
- `stock_movements`

`employees` tablosunda nullable `occupation_id` ve `work_group_id` alanları vardır. Her ikisi de ilgili tanım silindiğinde `NULL` olacak dış anahtarlarla bağlıdır.

## Önemli API uçları

- `/api/members`
- `/api/employees`
- `/api/employees/{employee}/photo`
- `/api/work-shifts`
- `/api/business-hours`
- `/api/occupations`
- `/api/work-groups`
- `/api/employee-schedules`
- `/api/stock-items`
- `/api/stock-movements`

API erişimi `SpaAuthenticate` middleware katmanından geçer.

## Migrationlar

Projeye özgü migration sırası:

- `2026_08_27_000000_create_members_table`
- `2026_08_28_000000_create_work_shifts_table`
- `2026_08_28_010000_create_employees_table`
- `2026_08_28_020000_create_employee_schedules_table`
- `2026_08_28_030000_add_contact_fields_to_employees_table`
- `2026_08_28_040000_create_business_hours_table`
- `2026_08_28_050000_create_occupations_and_work_groups`
- `2026_08_29_000000_create_stock_module_tables`

## Yerel test durumu

- Yerel SQLite migrationları uygulanmıştır.
- Beş örnek personel yalnızca yereldedir.
- PHP CLI varsayılan `php.ini` yüklememektedir; SQLite işlemlerinde `pdo_sqlite`, `sqlite3` ve bazı Laravel komutlarında `mbstring` eklentilerinin açıkça yüklenmesi gerekebilir.
- PHP kurulumunda PHPUnit'in gerektirdiği DOM/XML/XMLWriter eklentileri bulunmadığı için tam PHPUnit çalıştırması mümkün olmayabilir.
- PHP sözdizimi, `git diff --check`, rota listesi, migration durumu ve tarayıcı ekran kontrolleri kullanılmıştır.

## Canlı dağıtım yöntemi

Canlı hostingde shell erişimi kapalıdır. Çalışan yöntem:

1. Değişen dosyaları depo içindeki göreli yolları korunacak şekilde ZIP paketine al.
2. cPanel Dosya Yöneticisinde `/home/krpsoftc/spa-app` klasörüne yükle.
3. Paketi aynı klasöre çıkararak yalnızca hedef dosyaların üzerine yaz.
4. Yeni migration gerekiyorsa eşdeğer SQL'i phpMyAdmin üzerinden `krpsoftc_spa` veritabanına uygula ve migration kaydını ekle.
5. Canlı adresi yenile; Kurulum, personel kartı ve ilgili API davranışını kontrol et.

Git Version Control içindeki otomatik dağıtım ekranı geçmişte “Yükleniyor” durumunda kalmıştır. Bu nedenle Dosya Yöneticisi yöntemi kullanılmıştır.

## Son durum

- Grup seçimi zorunlu çalışma programı değişikliği yerelde, GitHub'da ve canlıda bulunmaktadır.
- Canlıda meslek ve çalışma grubu altyapısı hazırdır.
- Canlıda çalışma grubu kaydı henüz kullanıcı tarafından tanımlanmadıysa çalışma programı seçiminde yalnızca “Grupsuz Personel” görünür.
- Canlıda test personeli yoktur.
- Vox ERP stok modülü yerelde tamamlanmış ve giriş/çıkış miktar hesabı tarayıcıda doğrulanmıştır; henüz canlıya alınmamıştır.

## Yeni bir Codex görevi başlatırken

Şu komut yeterlidir:

> `E:\kirpi\spa-web\AGENTS.md` ve `E:\kirpi\spa-web\PROJECT_CONTEXT.md` dosyalarını tamamen oku, mevcut git durumunu kontrol et ve SPA projesine kaldığımız yerden devam et.
