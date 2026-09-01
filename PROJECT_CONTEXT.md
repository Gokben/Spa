# Sofitel Spa Yazılımı — Proje Hafızası

Son güncelleme: 1 Eylül 2026

## Projenin amacı

Sofitel Spa operasyonlarını tek arayüzden yönetmek için masaüstü uygulaması hissi veren, tarayıcı tabanlı bir SPA yönetim yazılımı geliştiriliyor. Arayüz Sofitel Spa yeşil-altın kimliğini ve Vox/Windows tarzı açılır pencereleri kullanıyor.

## Adresler ve depo

- Yerel proje: `E:\kirpi\spa-web`
- Yerel uygulama: `http://127.0.0.1:8090/`
- Canlı uygulama: `https://krpsoft.com.tr/spa/`
- GitHub: `https://github.com/Gokben/Spa.git`
- Aktif geliştirme dalı: `codex/add-agustos-index`
- Bu görev öncesindeki son Git commit: `2966c21`
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

Masaüstü görev çubuğundaki Başlat düğmesi menüyü açıp kapatır; bir ekran seçildiğinde menü otomatik kapanır.

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

Yerelde alan testi için beş örnek personel vardır. İsim bazlı rezervasyon görünümünü test etmek için aktif üç örnek personelin mesleği Terapist olarak atanmıştır. Bu kişiler canlıya gönderilmemiştir ve açık talep olmadan gönderilmemelidir.

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

## Ön Kasa

Vox ERP Ön Kasa modülü Laravel yapısına uyarlanarak Ön Kasa menüsüne bağlanmıştır. Pencerede dört sekme vardır:

1. Günlük İşlemler
2. Yeni İşlem
3. Gün Sonu
4. Kategoriler

- Devreden kasa, toplam gelir, toplam gider ve mevcut bakiye özetleri gösterilir.
- Gelir ve gider kayıtlarında tarih, açıklama, tutar, ödeme türü, kategori ve belge/fatura numarası tutulur.
- Ödeme türleri Nakit, Kredi Kartı, Havale/EFT ve Oda Hesabı seçenekleridir.
- Gelir ve gider kategorileri ayrı tanımlanır; kullanılan kategoriler silinemez, pasifleştirilebilir.
- Gün sonu kaydı beklenen bakiye, sayılan bakiye ve farkı saklar; aynı tarih yeniden kaydedildiğinde kayıt güncellenir.

## Rezervasyon

Vox ERP randevu modülü SPA'ya uyarlanarak Rezervasyon menüsüne bağlanmıştır. Üstteki Takvim / Yeni Rezervasyon / Rezervasyon Listesi sekme şeridi kaldırılmıştır; pencere doğrudan rezervasyon takvimini gösterir. Yeni düğmesi ve çizelge etkileşimleri kayıt formunu açar. Araç çubuğundaki görünüm ve Yenile düğmeleri görünmez; günlük takvim ilk açılışta ve kayıt işlemleri sonrasında otomatik yenilenir.

- Rezervasyon takvimi doğrudan günlük görünümde açılır; Günlük, Haftalık ve Aylık görünüm düğmeleri araç çubuğundan kaldırılmıştır.
- Günlük görünüm rezervasyonları 08:00–22:00 saat çizelgesi üzerinde süreleri oranında gösterir; çakışan rezervasyonlar yan yana yerleşir. Ardışık saat blokları, geçişleri kolay izlemek için dönüşümlü açık tonlarla ayrılır.
- Günlük çizelgede her saat 60 piksel, her yarım saat 30 piksel sabit ölçüyle hesaplanır. Rezervasyon kartının üst ve alt kenarı kendi başlangıç/bitiş saat çizgilerine tam oturur.
- Günlük görünümde mesleği Terapist olan aktif personeller ad-soyad başlıklı ayrı sütunlarda gösterilir; rezervasyon kartı bağlı olduğu terapistin sütununa yerleşir.
- Terapist sütunundaki boş saate sağ tıklayıp Yeni seçildiğinde tarih, yarım saatlik zaman aralığı ve terapist otomatik olarak forma aktarılır.
- Terapist sütunundaki boş saat aralığına çift tıklanınca yeni rezervasyon formu doğrudan açılır; tarih, yarım saatlik zaman aralığı ve terapist otomatik doldurulur.
- Günlük çizelgedeki mevcut rezervasyon kartları terapist sütunları arasında sürüklenip bırakılabilir. Taşıma sırasında tarih ve saat korunur, yalnızca terapist değişir; hedef terapistte saat çakışması varsa sunucu değişikliği reddeder ve kart eski yerinde kalır.
- Günlük çizelgenin üstündeki rezervasyon/terapist sayısını ve artı düğmesini gösteren yeşil bilgi şeridi kaldırılmıştır.
- Haftalık görünüm pazartesiden pazara yedi günlük planı gösterir.
- Aylık görünümde rezervasyonlar gün ve başlangıç saatiyle gösterilir.
- Tarih başlığında önceki/sonraki dönem düğmeleri bulunur; hafta veya gün ay sınırını geçtiğinde API gerekli tarih aralığını birlikte getirir. `Bugün` düğmesi kullanıcı isteğiyle kaldırılmıştır.
- Günlük çizelgedeki boş saate veya haftalık/aylık gün alanına sağ tıklanınca Yeni, Düzenle ve Sil seçenekli bağlam menüsü açılır. Boş alanda yalnızca Yeni aktiftir; mevcut rezervasyonda Düzenle ve Sil de etkinleşir. Kullanıcının seçimi olmadan işlem yapılmaz; Sil ayrıca onay ister. Günlük çizelgeden Yeni seçildiğinde tarih ve yarım saatlik zaman aralığı otomatik doldurulur.
- Rezervasyon penceresi varsayılan olarak Günlük görünümle açılır; saatler 08:00–22:00 arasında sol tarafta dikey eksende gösterilir.
- Rezervasyon penceresi ilk açılışta otomatik olarak tam ekran olur.
- Rezervasyon üye kaydına ve aktif personele bağlanabilir; üye seçilmeden misafir kaydı da açılabilir.
- Hizmet, tarih, başlangıç/bitiş saati, personel, durum, telefon ve not bilgileri tutulur.
- Durumlar Planlandı, Onaylandı, Tamamlandı, İptal ve Gelmedi seçenekleridir.
- Aynı personelin çakışan saatlerde iki aktif rezervasyonuna sunucu tarafında izin verilmez.
- Takvim günündeki artı düğmesi seçilen tarihle yeni rezervasyon formunu açar.
- Rezervasyon giriş formu Türkçe Genel / Detay düzenindedir. Genel bölümde tarih-saat-terapist özeti, aranabilir hizmet şablonları, seçilen hizmet özeti, ek hizmet alanı ve üye/misafir seçimi; Detay bölümünde telefon, durum ve not bulunur.
- Rezervasyon girişi takvimin üzerinde ayrı bir pencere olarak açılır. Pencere Sofitel yeşil-altın renk standardını kullanır; sağ üstteki X formu kaydetmeden kapatır ve günlük rezervasyon takvimine geri döner.
- Hizmet şablonu seçimi hizmet adını doldurur ve bitiş saatini şablon süresine göre hesaplar. Üye satırı seçimi ad-soyad ve telefonu mevcut rezervasyon alanlarına aktarır.

## Kurulum

Kurulum penceresinde beş sekme bulunur:

1. Mesai Tanımları
2. Çalışma Saatleri
3. Meslekler
4. Çalışma Grupları
5. Kategoriler

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

### Kategoriler

- Kategori ekleme, düzenleme ve silme desteklenir.
- Başlangıç örneği olarak `Masaj` kategorisi eklenir.
- Kurulumda tanımlanan kategoriler Stok Kartındaki çoklu kategori seçim alanına otomatik gelir.
- Bir stok kartına sıfır, bir veya birden fazla kategori bağlanabilir.
- Eski stok kartlarındaki tek metin kategori değerleri migration sırasında kategori tanımına ve stok ilişkisine dönüştürülür.

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
- `categories`
- `category_stock_item`
- `stock_items`
- `stock_movements`
- `cash_settings`
- `cash_categories`
- `cash_transactions`
- `cash_closings`
- `reservations`

`employees` tablosunda nullable `occupation_id` ve `work_group_id` alanları vardır. Her ikisi de ilgili tanım silindiğinde `NULL` olacak dış anahtarlarla bağlıdır.

## Önemli API uçları

- `/api/members`
- `/api/employees`
- `/api/employees/{employee}/photo`
- `/api/work-shifts`
- `/api/business-hours`
- `/api/occupations`
- `/api/work-groups`
- `/api/categories`
- `/api/employee-schedules`
- `/api/stock-items`
- `/api/stock-movements`
- `/api/cash`
- `/api/cash/transactions`
- `/api/cash/categories`
- `/api/cash/closing`
- `/api/reservations`

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
- `2026_08_29_010000_create_cash_module_tables`
- `2026_08_29_020000_create_reservations_table`
- `2026_08_31_000000_create_categories_table`

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
- Vox ERP stok modülü yerelde ve canlıda çalışmaktadır; giriş/çıkış miktar hesabı doğrulanmıştır.
- Vox ERP Ön Kasa modülü yerelde ve canlıda çalışmaktadır; gelir, gider, bakiye ve gün sonu fark hesabı doğrulanmıştır.
- Vox ERP rezervasyon modülü yerelde ve canlıda çalışmaktadır; kayıt ve personel saat çakışması doğrulanmıştır. Günlük, haftalık ve aylık takvim, dikey saat çizelgesi, sağ tıkla yeni kayıt ve otomatik tam ekran davranışları canlıda doğrulanmıştır.
- Terapist bazlı isim sütunları, sağ tıklanan veya çift tıklanan terapist ve saatin forma otomatik aktarılması canlıda çalışmaktadır.
- Başlat düğmesinin menüyü açıp kapatma davranışı ve açık menü yanında tam ekran pencerenin sağa kayarak daralması canlıda doğrulanmıştır.
- Rezervasyon penceresindeki geniş üçlü sekme şeridi, Yenile düğmesi ve Günlük / Haftalık / Aylık görünüm düğmeleri canlıda kaldırılmıştır; takvim günlük görünümde açılır.
- Türkçe Genel / Detay sekmeli, hizmet şablonu ve üye/misafir seçimli rezervasyon giriş düzeni canlıda doğrulanmıştır.
- Günlük rezervasyon çizelgesinde dönüşümlü saat blokları ile 11 piksel, kalın ve mavi saat etiketleri canlıda görünmektedir.
- Ayrı pencere şeklindeki yeşil-altın rezervasyon giriş ekranı ve X ile günlük takvime dönüş davranışı yerelde ve canlıda doğrulanmıştır.
- 31 Ağustos 2026 tarihli `0287d9f` dağıtımında yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env`, canlı MySQL verileri ve test kayıtları değiştirilmemiştir.
- Kurulumdaki Kategoriler sekmesi, örnek `Masaj` kaydı ve Stok Kartındaki çoklu kategori seçimi yerelde ve canlıda doğrulanmıştır.
- Stok Kartındaki çoklu kategori seçenekleri yan yana kutular yerine, tek alana tıklanınca dikey açılan seçim listesine dönüştürülmüştür. Seçilen kategori adları alan üzerinde özetlenir; davranış yerelde ve canlıda doğrulanmıştır.
- Stok Kartındaki açılır kategori listesinin seçenekleri yerelde listenin sol kenarına hizalanmıştır; henüz canlıya alınmamıştır.
- Rezervasyonların terapistler arasında sürükle-bırakla taşınması ve günlük çizelgedeki yeşil bilgi şeridinin kaldırılması yerelde ve canlıda doğrulanmıştır.
- 31 Ağustos 2026 tarihli `7a4a06e` dağıtımında yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env`, canlı MySQL verileri ve test kayıtları değiştirilmemiştir.
- Günlük rezervasyon kartlarının saat çizgilerine sabit dakika/piksel hesabıyla tam oturması yerelde ve canlıda doğrulanmıştır.
- 1 Eylül 2026 tarihli `db10dfe` dağıtımında yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env`, canlı MySQL verileri ve rezervasyon kayıtları değiştirilmemiştir.
- Rezervasyon tarih başlığındaki `Bugün` düğmesi ve araç çubuğundaki `Yeni` düğmesi yerelde ve canlıda kaldırılmıştır. Yeni rezervasyon açma işlemi sağ tık ve boş saat aralığına çift tıklama üzerinden çalışmaya devam eder.
- 1 Eylül 2026 tarihli `5ab43fc` dağıtımında yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env`, canlı MySQL verileri ve rezervasyon kayıtları değiştirilmemiştir.
- 31 Ağustos 2026 dağıtımında yalnızca `app/Http/Controllers/ReservationController.php` ve `resources/views/spa.blade.php` güncellenmiş, migration uygulanmamış ve canlı MySQL verileri değiştirilmemiştir.

## Yeni bir Codex görevi başlatırken

Şu komut yeterlidir:

> `E:\kirpi\spa-web\AGENTS.md` ve `E:\kirpi\spa-web\PROJECT_CONTEXT.md` dosyalarını tamamen oku, mevcut git durumunu kontrol et ve SPA projesine kaldığımız yerden devam et.
