# Sofitel Spa Yazılımı — Proje Hafızası

Son güncelleme: 22 Eylül 2026

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
- Paketler
- Cari Kartlar

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
- Stok menüsü her açıldığında önce Stok Listesi gösterilir; kullanıcı listedeki `Yeni Kart` düğmesiyle boş stok kartını açar.
- `Stok Kartı` sekmesi boş form yerine önce ayrı Stok Kartı Listesini açar. Kullanıcı bu listeden yeni kart oluşturabilir, mevcut kartı düzenleyebilir/silebilir ve formdan kart listesine geri dönebilir.
- `Stok Giriş` sekmesi de önce Stok Giriş Listesini açar. Kullanıcı listeden yeni giriş oluşturabilir, mevcut girişi düzenleyebilir/silebilir ve formdan giriş listesine geri dönebilir; stok bakiyesini negatife düşürecek değişiklik veya silme işlemleri sunucuda engellenir.
- `Stok Çıkış` sekmesi önce Stok Çıkış Listesini açar. Kullanıcı listede arama yapabilir, yeni çıkış oluşturabilir, mevcut çıkışı düzenleyebilir/silebilir ve formdan çıkış listesine geri dönebilir; stok bakiyesini negatife düşürecek çıkışlar sunucuda engellenir.
- Stok araç çubuğunda ayrıca `Yenile` düğmesi gösterilmez; veriler pencere açılışında ve kayıt işlemlerinden sonra otomatik yüklenir.
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

## Cari Kartlar

- Sol menüde `Cari Kartlar`, `Paketler` öğesinin hemen ardından gelir.
- Cari Kartlar penceresi mevcut Sofitel yeşil-altın renk standardında Vox/Windows masaüstü penceresi olarak açılır.
- İlk görünüm cari listesidir; kod, kısa ad, ilgili kişi, cari tipi ve telefon gösterilir.
- Liste işlemlerinde turuncu fatura hareketleri, yeşil düzenle ve kırmızı sil ikonları kullanılır.
- Yeni cari kartta cari kodu, unvan/ad, kısa ad, vergi dairesi, vergi/T.C. kimlik numarası, cari tipi, telefon, e-posta, yetkili kişi, fatura adresi, firma detayı ve durum alanları bulunur. Teknik Servis, İç Servis ve Dış Servis alanları kullanıcı isteğiyle arayüzden kaldırılmıştır.
- Bir carinin hareket ekranında kayıtlar fatura bazında tarih, giriş/çıkış, fatura no, miktar, toplam, KDV'li toplam, iskontosuz tutar, iskonto, ortalama iskonto ve ödeme tipiyle gösterilir.
- Fatura satırları artı/eksi düğmesiyle açılıp kapanır; stok kartı/açıklama, miktar, iskonto oranı ve birim fiyat görünür.
- Cari kart silindiğinde ona bağlı fatura ve fatura satırları da silinir. Fatura satırındaki stok kartı silinirse satır korunur, stok bağlantısı boşalır.

## Rezervasyon

Vox ERP randevu modülü SPA'ya uyarlanarak Rezervasyon menüsüne bağlanmıştır. Üstteki Takvim / Yeni Rezervasyon / Rezervasyon Listesi sekme şeridi kaldırılmıştır; pencere doğrudan rezervasyon takvimini gösterir. Çizelge etkileşimleri kayıt formunu açar. Araç çubuğundaki görünüm ve Yenile düğmeleri görünmez; günlük takvim ilk açılışta ve kayıt işlemleri sonrasında otomatik yenilenir.

- Rezervasyon takvimi doğrudan günlük görünümde açılır; Günlük, Haftalık ve Aylık görünüm düğmeleri araç çubuğundan kaldırılmıştır.
- Günlük görünüm rezervasyonları 08:00–22:00 saat çizelgesi üzerinde süreleri oranında gösterir; çakışan rezervasyonlar yan yana yerleşir. Ardışık saat blokları, geçişleri kolay izlemek için dönüşümlü açık tonlarla ayrılır.
- Günlük çizelge 10 dakikalık hassasiyetle çalışır. On dakikalık alt bölümler normal görünümde çizilmez; fare terapist sütununda hareket ederken yalnızca en yakın 10 dakikayı gösteren mavi çizgi ve saat etiketi görünür, fare ayrılınca kaybolur. Sağ tık, çift tık ve sürükle-bırak işlemleri 10 dakikalık aralığa hizalanır; yeni rezervasyon için varsayılan süre 30 dakikadır.
- Günlük çizelgede her saat 60 piksel, her yarım saat 30 piksel sabit ölçüyle hesaplanır. Rezervasyon kartının üst ve alt kenarı kendi başlangıç/bitiş saat çizgilerine tam oturur.
- Günlük görünümde mesleği Terapist olan aktif personeller ad-soyad başlıklı ayrı sütunlarda gösterilir; rezervasyon kartı bağlı olduğu terapistin sütununa yerleşir.
- Terapist sütunundaki boş saate sağ tıklayıp Yeni seçildiğinde tarih, yarım saatlik zaman aralığı ve terapist otomatik olarak forma aktarılır.
- Terapist sütunundaki boş saat aralığına çift tıklanınca yeni rezervasyon formu doğrudan açılır; tarih, yarım saatlik zaman aralığı ve terapist otomatik doldurulur.
- Günlük çizelgedeki mevcut rezervasyon kartları terapist sütunları arasında ve saat ekseninde sürüklenip bırakılabilir. Bırakılan saat yarım saatlik aralığa yuvarlanır, rezervasyonun toplam süresi korunur; hedef terapistte saat çakışması varsa sunucu değişikliği reddeder ve kart eski yerinde kalır. Saat bazlı taşıma yerelde ve canlıda doğrulanmıştır.
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
- Rezervasyon kartları mouse-over durumunda maviye döner ve beyaz metin korunur. Yanında açılan koyu bilgi kartında mevcut hizmet, tarih, saat, terapist, misafir, üye, telefon, üyelik, doğum tarihi, durum ve not bilgileri gösterilir.
- Sol menüde `Paketler`, kullanıcı isteğiyle `Kurulum` öğesinin hemen altında yer alır. Açılan Vox tarzı paket penceresinde Klasik Rahatlama, Geleneksel Hamam, Çiftlere Özel Romantik, Arındırıcı Güzellik ve Gelin / Bekarlığa Veda paketleri; süreleri, içerikleri ve uygun misafir profilleriyle gösterilir. Paketler Laravel API ve `spa_packages` tablosunda saklanır; kullanıcı yeni paket ekleyebilir, mevcut paketi düzenleyebilir ve onay vererek silebilir. İşlem sütununda yazılımın yeşil-altın standardına uygun yeşil kalem ve kırmızı çöp kutusu SVG ikonları kullanılır. Bu CRUD ekranı yerelde ve canlıda doğrulanmıştır.
- Paketler Kurulum > Hizmetler altında tanımlanan hizmet gruplarına bağlanabilir. Yeni/düzenleme formunda hizmet grubu zorunlu seçilir; paket listesinde Hizmet Grubu sütunu ile Tüm hizmet grupları, her tanımlı grup ve Grupsuz filtreleri bulunur. Önceden var olan paketler kullanıcı sınıflandırana kadar Grupsuz kalır. Bir hizmet grubu silinirse bağlı paketler silinmez, Grupsuz duruma geçer.
- Takvim günündeki artı düğmesi seçilen tarihle yeni rezervasyon formunu açar.
- Rezervasyon giriş formu Türkçe Genel / Detay düzenindedir. Genel bölümde tarih-saat-terapist özeti, aranabilir hizmet şablonları, seçilen hizmet özeti, ek hizmet alanı ve üye/misafir seçimi; Detay bölümünde telefon, durum ve not bulunur.
- Rezervasyon girişi takvimin üzerinde ayrı bir pencere olarak açılır. Pencere Sofitel yeşil-altın renk standardını kullanır; sağ üstteki X formu kaydetmeden kapatır ve günlük rezervasyon takvimine geri döner.
- Hizmet şablonu seçimi hizmet adını doldurur ve bitiş saatini şablon süresine göre hesaplar. Üye satırı seçimi ad-soyad ve telefonu mevcut rezervasyon alanlarına aktarır.

## Kurulum

Kurulum penceresinde yedi sekme bulunur:

1. Mesai Tanımları
2. Çalışma Saatleri
3. Meslekler
4. Çalışma Grupları
5. Kategoriler
6. SMS Ayarları
7. Hizmetler

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

### SMS Ayarları

- SMS sağlayıcısı Verimor'dur ve JSON API adresi `https://sms.verimor.com.tr/v2/send.json` olarak yapılandırılmıştır.
- API kullanıcı adı, şifre, gönderici başlığı ve etkinlik durumu Kurulum ekranından yönetilir.
- API şifresi Laravel encrypted cast ile veritabanında şifreli tutulur ve API yanıtında/ekranda geri gösterilmez.
- SMS Gönder düğmesi gerçek gönderimden önce açık onay ister. Türkiye mobil numaraları `905XXXXXXXXX` biçimine dönüştürülür.
- Sağlayıcıdan gelen `401/403`, istek limiti ve bağlantı hataları ayrı kullanıcı mesajlarıyla gösterilir; yanlış API bilgisi artık bağlantı sorunu olarak raporlanmaz.
- Kampanya kimliği, hedef numara, mesaj, durum ve sağlayıcı yanıtı `sms_messages` tablosunda kaydedilir.
- Ticari ileti seçilirse Verimor'a İYS bireysel alıcı bilgisi gönderilir; varsayılan test/rezervasyon bildirimi ticari değildir.
- Bir rezervasyonun terapisti, başlangıç saati veya bitiş saati gerçekten değiştiğinde `0 (543) 548 01 22` numarasına eski/yeni terapist ve saat bilgilerini içeren otomatik, ticari olmayan SMS gönderilir. SMS hatası rezervasyon güncellemesini geri almaz; başarısız deneme `sms_messages` tablosunda `failed` olarak saklanır. Yalnızca not, durum veya başka alanların değişmesi SMS tetiklemez.
- Canlı Verimor API erişimi etkinleştirilmiş, izinli DNS olarak `krpsoft.com.tr` tanımlanmış ve API bilgileri canlı uygulamada şifreli olarak kaydedilmiştir. Gönderici başlığı boş bırakılarak yapılan 2 Eylül 2026 testinde de sağlayıcı `INVALID_SOURCE_ADDRESS` yanıtı vermiştir. Bu hesapta başlıksız gönderim kullanılamamaktadır; Verimor'da onaylı bir gönderici başlığı tanımlanmadan kampanya oluşmaz ve SMS teslim edilmez.

### Hizmetler

- `Hizmetler` sekmesi Kurulum ekranında `SMS Ayarları` sekmesinin hemen sağında yer alır.
- Hizmet grubu ekleme, düzenleme ve onaylı silme işlemleri desteklenir.
- Başlangıç kayıtları `Masaj`, `Hamam` ve `Bakım` şeklindedir.

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
- `service_groups`
- `category_stock_item`
- `stock_items`
- `stock_movements`
- `cash_settings`
- `cash_categories`
- `cash_transactions`
- `cash_closings`
- `member_payments`
- `reservations`
- `sms_settings`
- `sms_messages`
- `current_accounts`
- `current_account_invoices`
- `current_account_invoice_items`

`employees` tablosunda nullable `occupation_id` ve `work_group_id` alanları vardır. Her ikisi de ilgili tanım silindiğinde `NULL` olacak dış anahtarlarla bağlıdır.

`spa_packages` tablosundaki nullable `service_group_id`, paketleri `service_groups` tablosuna bağlar. Hizmet grubu silindiğinde paket kaydı korunur ve bağlantı `NULL` olur.

## Önemli API uçları

- `/api/members`
- `/api/members/{member}/payments`
- `/api/employees`
- `/api/employees/{employee}/photo`
- `/api/work-shifts`
- `/api/business-hours`
- `/api/occupations`
- `/api/work-groups`
- `/api/categories`
- `/api/service-groups`
- `/api/employee-schedules`
- `/api/stock-items`
- `/api/stock-movements`
- `/api/cash`
- `/api/cash/transactions`
- `/api/cash/categories`
- `/api/cash/closing`
- `/api/reservations`
- `/api/sms`
- `/api/sms/settings`
- `/api/sms/send`
- `/api/current-accounts`
- `/api/current-accounts/{current_account}`

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
- `2026_09_01_020000_create_sms_module_tables`
- `2026_09_02_000000_create_current_accounts_module_tables`
- `2026_09_21_010000_create_service_groups_table`
- `2026_09_21_020000_add_service_group_to_spa_packages_table`
- `2026_09_22_000000_create_member_payments_table`

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

- 21 Eylül 2026: Paket yeni/düzenleme formundaki Kaydet ve İptal düğmeleri Vox/Windows krem zeminini koruyacak şekilde güncellendi. Kaydet'te 18×18 piksel, 1 piksel çizgili yeşil disket; İptal'de aynı ölçüde kırmızı daire-X SVG ikonu kullanılır. İptal ikonunun çemberi ve çarpısı, Kaydet ikonu ile optik olarak eşit görünecek şekilde büyütüldü. Düğme metinleri kaldırıldı; açıklamalar yalnızca fare üzerine gelince araç ipucu olarak gösterilir ve erişilebilir adlar korunur. Yerel Yeni Paket ekranında görsel olarak doğrulandı. Canlıya aktarılmadı.
- 21 Eylül 2026: Yeni Paket ekranındaki `Paket Listesi` geri dönüş düğmesi kaldırıldı. Pencerenin sağ üstündeki X düğmesi `Paket listesine dön` erişilebilir adıyla Yeni Paket penceresini kapatıp Paketler listesini yeniden gösterir. Yerelde açma-kapama akışı doğrulandı. Canlıya aktarılmadı.
- 21 Eylül 2026: Yazılım genelindeki Kaydet ve İptal/Vazgeç düğmeleri, işlem tablolarındaki düğme ölçüsüyle birleştirildi. Düğmeler 28×23 piksel Vox/Windows krem zeminde; Kaydet için 18×18 ince yeşil disket, İptal için 18×18 ince kırmızı daire-X kullanır. Metinler kaldırıldı; düğmenin işleme özel açıklaması `title` ve `aria-label` olarak korunur. Sayfa açılışında bulunan ve sonradan dinamik üretilen düğmeler ortak dönüştürücüyle kapsanır. Yerelde Kurulum > Mesai Tanımları, Hizmetler ve Çalışma Saatleri ekranlarında görsel ve erişilebilirlik ağacında doğrulandı. Canlıya aktarılmadı.
- 21 Eylül 2026: Kurulum > Çalışma Saatleri ekranında Kaydet ikonunu saran geniş araç çubuğunun çerçevesi ve gradyan zemini kaldırıldı; Kaydet ikonu ve sağdaki durum mesajı doğrudan panel zemini üzerinde gösterilir. Yerelde görsel olarak doğrulandı. Canlıya aktarılmadı.
- 21 Eylül 2026: Kurulum tanım tablolarında düzenleme sırasında gösterilen Kaydet ve İptal düğmelerinin dış ölçüleri, Düzenle/Sil düğmeleriyle eşit olacak şekilde 28×23 piksele getirildi. Her iki ikon 18×18 piksel ve ortalanmış olarak kalır. Kurulum > Hizmetler ekranında yerelde görsel olarak doğrulandı. Canlıya aktarılmadı.
- 21 Eylül 2026: Kurulum > SMS Ayarları ekranındaki Verimor hesap girişlerinin kart dışına taşması düzeltildi. SMS kartları daralabilir hale getirildi; formun değer sütunu `minmax(0, 1fr)` kullanır ve input/textarea genişlikleri `border-box` hesabıyla kendi kartı içinde kalır. Yerelde iki kart yan yana görünümde doğrulandı. Canlıya aktarılmadı.
- 21 Eylül 2026: Yerel paket verilerinde `Öne Çıkan İçerikler` alanında `Masajlar` geçen tüm kayıtlar Masaj hizmet grubuna bağlandı. Toplam 16 eşleşmenin 3'ü önceden atanmıştı; kalan 13 paket toplu olarak güncellendi ve Paketler listesinde 16 kaydın tamamının Masaj grubunda olduğu doğrulandı. Canlı veritabanına uygulanmadı.
- 21 Eylül 2026: Yerel paket verilerinde `Öne Çıkan İçerikler` alanında `Bakım` geçen 14 kaydın tamamı Bakım hizmet grubuna bağlandı. Önceden Bakım grubunda olan eşleşme yoktu; 14 paket toplu olarak güncellendi ve Paketler listesinde doğrulandı. Canlı veritabanına uygulanmadı.
- 21 Eylül 2026: Yerel paket verilerinde `Öne Çıkan İçerikler` alanında `Üyelik` geçen 6 kayıt (Daily Use, Pool Access, 1/3/6/12 Month) mevcut `Üyelikler` hizmet grubuna bağlandı. Önceden bu gruba atanmış eşleşme yoktu; 6 paket toplu olarak güncellendi ve Paketler listesinde doğrulandı. Canlı veritabanına uygulanmadı.
- 21 Eylül 2026: Yerel paket verilerinde hizmet grubu boş olan ve Paket Türü alanında `Massage` geçen 5 kayıt (Head Massage, Indian Head Massage, Face Massage, Foot Massage, Back Massage) Masaj hizmet grubuna bağlandı. Güncelleme sonrasında `Grupsuz + Massage` koşulunda kayıt kalmadığı doğrulandı. Canlı veritabanına uygulanmadı.

- 21 Eylül 2026: Paketler hizmet gruplarına bağlandı. Paket formuna zorunlu Hizmet Grubu seçimi; listeye Hizmet Grubu sütunu ve Tüm/Masaj/Hamam/Bakım/Grupsuz filtresi eklendi. API paketlerle birlikte hizmet grubu ilişkisini döndürür ve grup kimliğini doğrular. `2026_09_21_020000_add_service_group_to_spa_packages_table` migrationı yerel SQLite'ta uygulandı; mevcut 41 paket değiştirilmeden Grupsuz bırakıldı. Paket listesi, filtre seçenekleri ve Yeni Paket formundaki grup seçimi tarayıcıda doğrulandı. Canlıya aktarılmadı.

- 21 Eylül 2026: Hizmetler listesindeki 18×18 piksel ince çizgili yeşil kalemli belge ve kırmızı çöp kutusu ikonları ortak satır işlem standardı yapıldı. Mesai tanımları, diğer Kurulum tanımları, Stok Kartları, Stok Giriş/Çıkış, Ön Kasa hareketleri ve kategorileri, Rezervasyon Listesi, Paketler ve Cari Kartlar ekranlarındaki düzenle/sil düğmeleri aynı 28×23 piksel Vox/Windows düğme ölçüsü ve ikon görünümüne geçirildi. Düğmelerin mevcut işlemleri ve erişilebilir adları korundu; Mesai Tanımları ile Hizmetler ekranlarında yerel görsel doğrulama yapıldı. Değişiklik yereldedir.

- 21 Eylül 2026: Yazılımdaki kullanıcıya açık tüm `Yenile` düğmeleri kaldırıldı. Üye listesindeki ikonlu yenileme kontrolü, Kurulum sekmelerindeki yenileme kontrolleri, Ön Kasa düğmesi ve rezervasyon ekranındaki gizli yenileme kontrolü artık arayüzde gösterilmez. Ekranların ilk açılışta ve kayıt işlemlerinden sonra otomatik veri yükleme davranışı korunmuştur. Değişiklik yereldedir.

- 21 Eylül 2026: Kurulum ekranında `SMS Ayarları` sekmesinin yanına `Hizmetler` sekmesi eklendi. `service_groups` tablosu ve listeleme/ekleme/düzenleme/silme API uçları hazırlandı; başlangıçta Masaj, Hamam ve Bakım kayıtları oluşturuldu. Yerelde geçici kayıtla CRUD akışı test edilip kayıt temizlendi; tarayıcıda üç başlangıç kaydı, Yeni Hizmet, Düzenle ve Sil kontrolleri doğrulandı. Canlıya aktarılmadı.

- 21 Eylül 2026: Paketler araç çubuğundaki `Yenile` düğmesi kaldırıldı. İki süre ve iki fiyat taşıyan 5 paket, her biri tek süre/tek fiyat içeren 10 bağımsız pakete ayrıldı; toplam paket sayısı 41'den 46'ya çıktı. Yeni/düzenleme formundaki ikinci fiyat alanı ve API yazma desteği kaldırıldı. `2026_09_21_000000_split_multi_price_spa_packages` migrationı yerelde çalıştırıldı; ikinci fiyatı dolu kayıt kalmadığı, 10 yeni satırın tutarları ve yerel Paketler ekranı doğrulandı. Canlıya aktarılmadı.

- 7 Eylül 2026: Kullanıcının canlıya alma onayından sonra spa-new-package-front.zip spa-app altında çıkarıldı. Yalnızca resources/views/spa.blade.php güncellendi. Canlı Yeni Paket formunun ortalanmış, ilk alanı odaklanmış ve liste görev düğmesi gizlenmiş şekilde bağımsız açıldığı doğrulandı. Veritabanı ve fiyatlar değiştirilmedi; önceki onay engeli çözüldü.

- 7 Eylül 2026: Yeni Paket formu yerelde ekran ortasında bağımsız ve odaklanmış açılır; paket listesi ve liste görev düğmesi form açıkken gizlenir, İptal/X ile geri gelir. Yerelde 720x490 ortalanmış pencere, ilk alan odağı ve listeye dönüş doğrulandı; PHP lint ve git diff --check geçti. spa-new-package-front.zip canlı spa-app dizinine yüklendi ancak çıkarma işlemi otomatik onay denetiminde açık canlı dağıtım onayı eksikliğiyle reddedildi. Canlıya uygulanmadı; kullanıcı onayı bekleniyor.

- 7 Eylül 2026: Paket fiyatları canlıya alındı. spa-package-prices-20260907.sql krpsoftc_spa üzerinde uygulandı; alternative_price alanı ve migration kaydı eklendi, eşleşen 36 fiyat güncellendi. spa-package-prices-20260907.zip içindeki SpaPackage modeli, SpaPackageController, paket fiyat değişikliklerini içeren Blade ve migration spa-app altına çıkarıldı. Canlı Paketler ekranında toplam 41 kayıt, 36 Euro fiyatı ve 5 çift süreli fiyat doğrulandı; eşleşmeyen 5 paketin boş fiyatları korundu. Dağıtım Blade dosyası önceki canlı deploy-20260907 sürümünden yalnızca packageRows ve packageForm değiştirilerek üretildi; bekleyen web rezervasyonu değişiklikleri bu dağıtıma dahil edilmedi.

- 7 Eylül 2026: masaj1lar.jpg görseliyle eşleşen 36 yerel paketin Euro fiyatları güncellendi. İki süreli 5 hizmet için nullable alternative_price alanı, API doğrulaması ve ikinci süre fiyatı form alanı eklendi; listede fiyatlar süre sırasıyla gösterilir. 2026_09_07_020000 migrationı yalnızca yerelde uygulandı. 41 kayıt ve eşleşmeyen 5 paket korundu; tüm fiyatlar API üzerinden, fiyat listesi ve iki fiyatlı düzenleme formu tarayıcıda doğrulandı. PHP sözdizimi ve git diff --check geçti. Canlıya aktarılmadı.

- 7 Eylül 2026: Web rezervasyonu yerelde hazır. spaweb için 3 adımlı tarih/kişi/hizmet/saat, iletişim ve özet formu; booking.php ve booking-service.php aynı reservations tablosuna planned/null employee_id ve [WEB] notuyla kayıt yazar. CSRF, IP hız limiti, session idempotency, doğrulama eklendi. ReservationController index web_requests döndürür; takvimde talep kutusu ve düzenleme dışı 15 saniye yenileme vardır. Transaction testleri ve GET/419/422 yerel kontrolleri başarılı. Migration yok. spa-web-booking-app.zip spa-app altına yüklendi ancak Extract Files otomatik denetim tarafından son onay eksikliğiyle durduruldu. Canlı değişiklik uygulanmadı; kullanıcı son onayı bekleniyor. Web paketi yerelde spa-web-booking-site.zip olarak hazır.


- 7 Eylül 2026 canlı dağıtımı tamamlandı: spa-update-20260907.zip içindeki 8 dosya spa-app altında çıkarıldı; spa-update-20260907.sql phpMyAdmin üzerinden 88 sorguyla başarıyla uygulandı. Canlıda 41 paket, ayrı Yeni Paket ekranı, EUR fiyat alanı, üye fotoğrafı seçimi ve Kapat düğmesinin kaldırılması doğrulandı. Fotoğraf POST rotası boş istekle 422/photo doğrulama hatası döndürdü; gerçek üye fotoğrafı veya test verisi yüklenmedi. Mevcut canlı kayıtlar ve .env korundu.

- Üye kartının araç çubuğundaki Kapat düğmesi kullanıcı isteğiyle kaldırıldı; pencere başlığındaki X kullanılabilir.

- Üye kartı Bilgiler sekmesine fotoğraf seçimi/önizlemesi eklendi. Kaydet ile JPG/PNG/WebP (en fazla 5 MB) yüklenir. members.photo_path özel yerel diskteki dosyayı tutar; fotoğraf oturum korumalı API üzerinden görüntülenir. Yerel migration: 2026_09_07_010000_add_photo_to_members_table. Canlıya aktarılmadı.
- Misafirler listesine VOX Hasta Kartları örneğindeki `Eylemler` sütunu eklendi. Her satırda 32×32 `Hizmetler`, `Düzenle`, `Sil` ve `Misafir Bilgi Formu` ikonları bulunur; hizmetler üyelik sekmesini, düzenle/bilgi formu misafir kartını açar, silme onay sonrası DELETE `/api/members/{member}` üzerinden kaydı ve varsa yerel fotoğrafını kaldırır. Member API testi 4 test / 13 assertion ile geçti. Yalnızca yerelde; canlıya aktarılmadı.
- Misafirler penceresi büyütüldüğünde tablo ve satırlar artık kullanılabilir yatay alanın tamamına yayılır; pencere daraldığında minimum kolon genişlikleri ve yatay kaydırma korunur. Yerelde görsel olarak doğrulandı; canlıya aktarılmadı.
- Kullanıcı arayüzündeki çift dilli İngilizce alan başlıkları kaldırıldı; misafir kartı yalnızca Türkçe etiketler kullanıyor. `Misafir No / Guest No` ifadesi `Misafir No` yapıldı, İngilizce süre gösterimleri (`3 Month` vb.) Türkçe karşılıklarına çevrildi, personel kartındaki `E-mail` `E-posta`, çalışma programındaki `OFF` ise `HAFTA TATİLİ` oldu. Yerelde görsel olarak doğrulandı; canlıya aktarılmadı.

- 7 Eylül 2026: Paketlere nullable decimal(10,2) price alanı eklendi; para birimi sabit EUR. Yeni/düzenleme formunda Fiyat (€), listede Euro biçimli fiyat gösterilir. Eski fiyatlar NULL kalır (—); negatif ve ikiden fazla ondalık basamak reddedilir. Yerel migration: 2026_09_07_000000_add_price_to_spa_packages_table. Canlıya aktarılmadı.

- 7 Eylül 2026: Paketler > Yeni Paket, listeyi gizleyip ayrı Yeni Paket penceresini açar. Kaydet sonrası liste yenilenir; İptal, Paket Listesi ve X listeye döner. Hata durumunda form korunur ve çift gönderim engellenir. Değişiklik yereldedir.

- 7 Eylül 2026: Kullanıcının masajlar.jpg listesindeki 36 hizmet/üyelik, yerel Paketler API üzerinden eklendi. Mevcut 5 paket korundu; toplam 41 kayıt var. İngilizce hizmet adları ve süre seçenekleri korundu; kategori bilgisi featured_contents alanında, kaynakta bulunmayan hedef kitle bilgisi Belirtilmemiş olarak tutuldu. Şema veya uygulama kodu değişmedi, canlıya aktarılmadı. Tekrar çalıştırılabilir aktarım: E:\kirpi\spa\import-masajlar.ps1.

- Yazılımdaki üye, personel, rezervasyon, cari kart ve SMS telefon girişleri ile telefon gösterimleri `0 (XXX) XXX XX XX` standardında ortak maske kullanır. Eski kayıtlar değiştirilmeden ekranda bu biçime dönüştürülür; yeni girişler yazılırken otomatik biçimlenir.
- 2 Eylül 2026 tarihli `dc50e0d` dağıtımında Cari Karttaki Teknik Servis / İç Servis / Dış Servis alanları kaldırılmış ve ortak telefon biçimi canlıya alınmıştır. Cari form ile üye listesindeki `0 (XXX) XXX XX XX` görünümü canlıda doğrulanmış; migration çalıştırılmamış ve canlı veriler değiştirilmemiştir.
- 2 Eylül 2026 tarihli `84e249d` dağıtımında rezervasyon terapisti veya saatleri değiştiğinde `0 (543) 548 01 22` numarasına otomatik bildirim gönderen tetikleyici canlıya alınmıştır. Migration çalıştırılmamıştır. Gönderici başlığı canlı SMS ayarlarında boşaltılmış; tek gerçek test sağlayıcı tarafından `INVALID_SOURCE_ADDRESS` ile reddedilmiş, kampanya oluşmamış ve SMS teslim edilmemiştir. İkinci gönderim yapılmamıştır.
- 3 Eylül 2026 tarihli `45cefe7` dağıtımında günlük rezervasyon çizelgesi 10 dakikalık hassasiyete geçirilmiştir. Alt bölümler yalnızca fare terapist sütununun üzerindeyken mavi çizgi ve saat etiketi olarak görünür; sağ tık, çift tık ve sürükle-bırak işlemleri 10 dakikaya hizalanır. Canlıda `09:20` göstergesi ile `09:20–09:50` yeni rezervasyon başlangıcı doğrulanmış, form kaydedilmeden kapatılmıştır. Yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env` ve canlı veriler değiştirilmemiştir.

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
- Stok Kartındaki açılır kategori listesinin seçenek satırları tüm liste genişliğini kullanacak ve onay kutusuyla birlikte en sol kenardan başlayacak şekilde yerelde ve canlıda hizalanmıştır.
- Rezervasyonların terapistler arasında sürükle-bırakla taşınması ve günlük çizelgedeki yeşil bilgi şeridinin kaldırılması yerelde ve canlıda doğrulanmıştır.
- 31 Ağustos 2026 tarihli `7a4a06e` dağıtımında yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env`, canlı MySQL verileri ve test kayıtları değiştirilmemiştir.
- Günlük rezervasyon kartlarının saat çizgilerine sabit dakika/piksel hesabıyla tam oturması yerelde ve canlıda doğrulanmıştır.
- 1 Eylül 2026 tarihli `db10dfe` dağıtımında yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env`, canlı MySQL verileri ve rezervasyon kayıtları değiştirilmemiştir.
- Rezervasyon tarih başlığındaki `Bugün` düğmesi ve araç çubuğundaki `Yeni` düğmesi yerelde ve canlıda kaldırılmıştır. Yeni rezervasyon açma işlemi sağ tık ve boş saat aralığına çift tıklama üzerinden çalışmaya devam eder.
- 1 Eylül 2026 tarihli `5ab43fc` dağıtımında yalnızca `resources/views/spa.blade.php` güncellenmiş; migration çalıştırılmamış, `.env`, canlı MySQL verileri ve rezervasyon kayıtları değiştirilmemiştir.
- 31 Ağustos 2026 dağıtımında yalnızca `app/Http/Controllers/ReservationController.php` ve `resources/views/spa.blade.php` güncellenmiş, migration uygulanmamış ve canlı MySQL verileri değiştirilmemiştir.
- 1 Eylül 2026 tarihli `e8017fa` dağıtımında canlıya alınmamış rezervasyon, paketler ve stok liste akışları birlikte aktarılmıştır. `spa_packages` tablosu ve 5 varsayılan paket canlı MySQL'e eklenmiş; Stok Giriş Listesi, Yeni Giriş, düzenleme/silme rotaları ve negatif stok koruması canlıda doğrulanmıştır. Canlı veri kayıtları ve `.env` değiştirilmemiştir.
- 1 Eylül 2026 tarihli `dd1c76e` dağıtımında Stok Çıkış sekmesi liste-merkezli akışa geçirilmiştir. Stok Çıkış Listesi, Yeni Çıkış formu ve listeye dönüş canlıda doğrulanmış; mevcut canlı veriler ve `.env` değiştirilmemiştir.
- 1 Eylül 2026 tarihli `142ce22` dağıtımında tarayıcı sekme başlığı `Yeni Hasta Kaydı` yerine `Spa` olarak değiştirilmiş ve canlıda doğrulanmıştır.
- 1 Eylül 2026 tarihli `bb3860e` dağıtımında Verimor SMS entegrasyonu canlıya alınmıştır. `sms_settings` ve `sms_messages` tabloları canlı MySQL veritabanında oluşturulmuş, migration kaydı eklenmiş ve Kurulum > SMS Ayarları ekranı devre dışı/boş hesap durumunda doğrulanmıştır. Gerçek SMS gönderilmemiş; `.env` ile mevcut canlı veriler korunmuştur.
- Verimor SMS hata yönetimi, sağlayıcının `401/403` kimlik doğrulama yanıtlarını gerçek bağlantı hatalarından ayıracak şekilde düzeltilmiştir.
- Verimor API erişimi ve canlı uygulama kimlik bilgileri doğrulanmıştır. Test SMS'i hesapta onaylı gönderici başlığı bulunmaması nedeniyle `INVALID_SOURCE_ADDRESS` ile reddedilmiştir; teslimat veya kampanya oluşmamıştır.
- Cari Kartlar modülü yerelde hazırlanmıştır. Menü sırası, cari listesi, cari kart formu, API kaydı ve fatura bazlı hareket/satır detayı tarayıcıda doğrulanmıştır. Doğrulama için oluşturulan geçici cari ve fatura kayıtları temizlenmiş; canlıya dosya, migration veya test verisi gönderilmemiştir.
- 21 Eylül 2026: Paketler tablosuna aktif hizmet grubu filtresini izleyen sade toplam satırı eklenmiştir. Satırda yalnızca görüntülenen paket adedi gösterilir; fiyatlı paket adedi, fiyat toplamı ve açıklama alanları kullanıcı isteğiyle kaldırılmıştır. Yerelde doğrulanmış; canlıya aktarılmamıştır.
- 21 Eylül 2026: Rezervasyon Girişi > Hizmet şablonu seçimi alanındaki sabit örnek hizmetler kaldırılmıştır. Liste artık rezervasyon API yanıtındaki gerçek `spa_packages` kayıtlarından oluşturulur; yalnızca dakika cinsinden randevu süresi bulunan mevcut paketler gösterilir. Paket adı, süresi, hizmet grubu ve gerçek kayıt numarası kullanılır; veritabanında bulunmayan örnek hizmetler eklenmez. Canlıya aktarılmamıştır.
- 21 Eylül 2026: Yerel paket verilerinde yanlışlıkla Bakım grubunda kalan `Pure Escape Massage & Hammam` ve `Ottoman Sultan Hammam 75 Dakika` mevcut Hamam hizmet grubuna taşındı. Yeni paket eklenmedi; Hamam grubundaki gerçek süreli hizmet sayısı 7 olarak doğrulandı. Canlı veritabanına uygulanmadı.
- 21 Eylül 2026: Rezervasyon Girişi > Hizmet şablonu tablosundaki Kod sütunu seçim kutusu sütununa dönüştürüldü. Gerçek bir paket işaretlendiğinde adı ve kayıtlı EUR fiyatı sağdaki Ek hizmetler ve ürünler tablosuna eklenir; işaret kaldırıldığında veya seçili satır kaldırma düğmesiyle silindiğinde listeden çıkar. Yeni ürün/hizmet kaydı üretilmez. Canlıya aktarılmadı.
- 21 Eylül 2026: Rezervasyon Girişi ekranındaki `Seçilen hizmet` başlığı, açıklaması ve görünen Hizmet adı alanı kaldırıldı. İlk işaretlenen gerçek paket rezervasyonun ana hizmeti olarak arka planda tutulur; ana seçim kaldırılırsa sıradaki işaretli paket devralır. Canlıya aktarılmadı.
- 21 Eylül 2026: Rezervasyon hizmet listesindeki seçim kutularının tarayıcıya ait kabartmalı/gölgeli çerçevesi kaldırıldı. Kutular düz beyaz zeminli, ince gri çerçeveli; seçili durumda yeşil zemin ve beyaz ince onay işaretli Vox stiline geçirildi. Canlıya aktarılmadı.
- 21 Eylül 2026: Rezervasyon Girişi > Ek hizmetler ve ürünler bölümündeki artı düğmesi başlığın sağına taşındı ve X düğmesi kaldırıldı. Artı düğmesi gerçek aktif stok kartlarını kod, ad, mevcut miktar ve satış fiyatıyla açar; stok kartı yanındaki seçim kutusu ürünü aşağıdaki Ürün / Hizmet listesine ekler veya çıkarır. Canlıya aktarılmadı.
- 21 Eylül 2026: Rezervasyon Girişi > Üye / misafir seçimi bölümündeki sağ taraftaki `Misafir bilgileri`, telefon açıklaması ve Ad Soyad kutusu görünümden kaldırıldı. Üye arama/listesi tam genişliğe çıkarıldı; listeden seçilen üyenin adı ve telefonu rezervasyon formundaki gizli alanlara aktarılmaya devam eder. Canlıya aktarılmadı.
- 21 Eylül 2026: Rezervasyon Girişi > Ürün / Hizmet tablosuna toplam satırı eklendi. Seçilen gerçek paketlerin hizmet adedi ve EUR toplamı gösterilir; stok ürünü seçilmişse ürün adedi ve TL toplamı aynı satırda para birimleri karıştırılmadan ayrıca gösterilir. Canlıya aktarılmadı.
- 21 Eylül 2026: Rezervasyon Girişi ekranındaki yeşil `Hizmet şablonu seçimi` başlık şeridi kaldırıldı; hizmet arama, grup filtresi ve seçim tablosu korunarak yukarı taşındı. Canlıya aktarılmadı.
- 21 Eylül 2026: Görünümden kaldırılan zorunlu Misafir Ad Soyad alanının boş kalıp hizmet seçilmiş rezervasyonun kaydını sessizce engellemesi düzeltildi. Üye seçilmemiş yeni rezervasyonda gizli misafir adı otomatik `Misafir` olur; üye seçilirse gerçek üye adı ve telefonu bu değerin üzerine yazılır. Canlıya aktarılmadı.
- 21 Eylül 2026: Üye / misafir arama ve seçim bölümünün Genel üstü ve Detay sekmesi yerleşimleri kullanıcı isteğiyle geri alındı. Bölüm ilk tasarımdaki gibi Genel sekmesinde, hizmet ve ürün seçimlerinin altında bulunur; Detay sekmesi telefon, durum ve not alanlarıyla kalır. Canlıya aktarılmadı.
- 21 Eylül 2026: Rezervasyon formunda işaretlenen gerçek hizmet ve stok kartları artık `reservation_items` tablosunda rezervasyona bağlı olarak kalıcı saklanır; düzenleme ekranı açıldığında seçimler ve toplamlar geri yüklenir. Hizmet satırının adına tıklamak da seçim kutusunu işaretler. Yerel migration uygulandı; rezervasyon kayıt ve SMS regresyon testlerinde 5 test / 20 doğrulama geçti. Canlıya aktarılmadı.
- 21 Eylül 2026: Üye Kartı > Üyelik sekmesindeki Üyelik Türü serbest metin alanı kaldırıldı; seçimler Paketler ekranındaki `Üyelikler` hizmet grubuna bağlı gerçek paketlerden yüklenir. Paket süreleri (`Günlük`, `1/3/6/12 Ay`) seçimle Süre alanına aktarılır; başlangıç tarihi seçildiğinde bitiş tarihi ay sonu taşmaları da gözetilerek otomatik hesaplanır. Yerel tarayıcıda `1 Month` için 18.07.2026 → 18.08.2026 ve 31.01.2026 → 28.02.2026 doğrulandı; kayıt yapılmadı ve canlıya aktarılmadı.
- 21 Eylül 2026: Birikmiş SPA değişikliklerinin tamamı `spa-all-20260921-1519.zip` paketiyle canlıdaki `/home/krpsoftc/spa-app` dizinine aktarıldı. `spa-all-20260921-1519.sql` başarıyla uygulandı (24 sorgu); çok fiyatlı paketler ayrıştırıldı, `Masaj`, `Hamam`, `Bakım`, `Üyelikler` hizmet grupları ve `reservation_items` tablosu oluşturuldu, paket-grup ilişkileri atandı. Canlı ana sayfa, geçici giriş ve üyeler API'si 200 yanıt verdi; 4 hizmet grubu, 45 paket ve rezervasyon uç noktası doğrulandı. `.env`, mevcut üye ve rezervasyon kayıtları korunmuştur.
- 22 Eylül 2026: SPA arayüzündeki metinler genel olarak Verdana 14 px standardına geçirildi; ikon ölçüleri korundu. Paketler ekranında hizmet grubu filtresinin yanına paket adı, hizmet grubu, süre, öne çıkan içerik ve hedef kitle içinde anlık arama yapan alan eklendi. `Sofitel Spa paketleri ve öne çıkan içerikleri` açıklaması ve ayrılan boşluk kaldırıldı. Yerel tarayıcıda gövde, arama alanı ve paket tablosunun Verdana 14 px olduğu; arama alanının görünür ve açıklama alanının kaldırılmış olduğu doğrulandı. Paket API testi 10 doğrulamayla geçti; canlıya aktarılmadı.
- 22 Eylül 2026: Üyeler, Personel, Raporlar, masaüstü Personeller ve Evraklar ekranlarındaki tüm `Listele` yazılı düğmeler kaldırıldı. Kaldırılan personel düğmesine bağlı olay kodu temizlendi; genel şablondaki `Listele` yönlendirme metni `Henüz kayıt bulunmuyor.` olarak değiştirildi. Üyeler ekranında düğmenin kaldırıldığı yerel tarayıcıda doğrulandı; Üye ve Paket API testlerinde 4 test / 20 doğrulama geçti. Canlıya aktarılmadı.
- 22 Eylül 2026: Kullanıcıya görünen `Üye` terminolojisi `Misafir` olarak değiştirildi; gerçek `Üyelik` paketi, türü ve süre kavramları korundu. Menü `Misafirler`, liste düğmesi `Yeni Misafir`, kart başlığı `Misafir Kartı`, kayıt numarası `Misafir No` oldu; rezervasyon arama/seçim metinleri ve fotoğraf açıklamaları da güncellendi. `Yeni Misafir` düğmesi boş kart açar ve ilk kayıtta üyeler API'sine POST, sonraki kayıtlarda PUT kullanır; kaydedilen kayıt açık listeye eklenir. Yerel tarayıcıda Misafirler listesi ve boş Yeni Misafir kartı açılışı doğrulandı; veri kaydı oluşturulmadı. Üye API testleri 3 test / 10 doğrulamayla geçti; canlıya aktarılmadı.
- 22 Eylül 2026: Alt görev çubuğuna Türkçe ve İngilizce bayraklı dil seçici eklendi; varsayılan dil Türkçedir. İngilizce seçildiğinde masaüstü, menüler, mevcut pencereler ve sonradan açılan dinamik ekranlardaki kullanıcı arayüzü metinleri İngilizceye çevrilir; Türkçe bayrağı tüm metinleri geri getirir. Çevrilen menü adlarının ekran açma davranışını bozmaması için yönlendirme görünür metinden bağımsız sabit `data-menu-key` değerlerine bağlandı. Her iki dil ve Misafirler ekranının İngilizce açılışı yerel tarayıcıda doğrulandı; canlıya aktarılmadı.
- 22 Eylül 2026: Misafir Kartındaki ayrı `Üyelik` sekmesi kaldırıldı. Üyelik Türü, Süresi, Başlangıç ve Bitiş alanları `Bilgiler` sekmesinin altına taşındı; gerçek üyelik paketi seçimi ve otomatik bitiş tarihi hesaplaması korunmuştur. Üyelik/hizmet eylemi artık doğrudan Bilgiler sekmesini açar. Üye API testlerinde 4 test / 13 doğrulama geçti; canlıya aktarılmadı.
- 22 Eylül 2026: Misafirler listesindeki `Hizmetler` eylemi, misafirin rezervasyonlarına bağlı hizmetleri ayrı satırlar halinde gösteren yeni hizmet geçmişi penceresine bağlandı. Liste rezervasyon numarası, tarih, saat, hizmet, terapist, durum ve kayıtlı fiyatı gösterir; aynı rezervasyondaki birden fazla hizmet ayrı satırlardır ve eski kalemsiz rezervasyonlarda ana hizmet adı geriye dönük gösterilir. Yerel DENİZ YILMAZ kaydında aynı rezervasyona bağlı 2 hizmet ayrı satır olarak doğrulandı. Ayrıca görev çubuğunda sonradan açılan pencere düğmeleri bayrakların önüne eklenerek Türkçe/İngilizce bayrakları daima saatin hemen yanında sabitlendi. Üye API testlerinde 5 test / 20 doğrulama geçti; canlıya aktarılmadı.
- 22 Eylül 2026: Misafir hizmet geçmişindeki ilk sütun `Rez No` olarak kısaltılıp 62 piksele daraltıldı, Terapist sütunu 210 piksele genişletildi ve Eylem sütununa ince çizgili düzenle/sil düğmeleri eklendi. Düzenle ilgili rezervasyonu tüm kayıtlı hizmet seçimleriyle Rezervasyon Girişi ekranında açar; silme onaydan sonra rezervasyonu ve bağlı kalemleri kaldırıp listeyi yeniler. Aynı tarihteki hizmetler aynı, farklı tarihler birbirinden farklı beş açık renk tonuyla gösterilir. Yerel listede başlıklar ve düzenleme ekranının açılışı doğrulandı; 7 test / 33 doğrulama geçti. Canlıya aktarılmadı.
- 22 Eylül 2026: Rezervasyon Girişi hizmet seçimindeki varsayılan grup filtresi `Tüm hizmet grupları` yerine `Hizmet Grupları` olarak değiştirildi. Hizmet tablosunun son sütunu `Net süre` yerine `Fiyat (€)` oldu ve her satırda paket kaydındaki gerçek Euro fiyatı gösterilir. Canlıya aktarılmadı.
- 22 Eylül 2026: Rezervasyon Girişi > Genel ekranında `Ek hizmetler ve ürünler` alanı sol tarafa taşınıp daha geniş (1.55 oran), hizmet arama/seçim tablosu sağ tarafa taşınıp daha dar (0.75 oran) hale getirildi. Seçim, toplam ve stok kartı davranışları korunmuştur. Canlıya aktarılmadı.
- 22 Eylül 2026: Rezervasyon Girişi > Misafir seçimi başlığının sağına `+` düğmesi eklendi. Düğmenin fare ipucu ve erişilebilir adı `Yeni Misafir`dir; tıklandığında boş Yeni Misafir kartını açar. Canlıya aktarılmadı.
- 22 Eylül 2026: Güncel SPA değişiklikleri `spa-live-20260922-131717.zip` paketiyle canlıdaki `/home/krpsoftc/spa-app` dizinine çıkarıldı. Yalnızca `MemberController.php`, `ReservationController.php`, `resources/views/spa.blade.php` ve `routes/web.php` güncellendi; veritabanı migration'ı uygulanmadı, `.env` ve canlı kayıtlar korunmuştur. cPanel çıkarma sonucu dört dosya için başarılı doğrulandı. Canlı sayfa yenilenip geçici giriş açıldı; Türkçe/İngilizce bayrak seçicisi, `Misafir seçildi` bildirimi ve `Hizmet Grupları` metninin yeni sürümde bulunduğu doğrulandı.
- 22 Eylül 2026: Misafir bazlı tahsilat altyapısı canlıya alındı. Misafirler > Hizmetler ekranında toplam hizmet, ödenen ve kalan borç özetleri; rezervasyon seçerek Nakit, Kredi Kartı, Havale/EFT veya Oda Hesabı yöntemiyle Euro tahsilat girişi; tahsilat geçmişi ve tahsilat silme işlemleri eklendi. Her tahsilat seçilen rezervasyon ve misafire bağlanır, aynı anda Ön Kasa'ya EUR gelir hareketi oluşturur; fazla tahsilat engellenir. Bağlı kasa hareketi doğrudan değiştirilemez/silinemez; tahsilatlı rezervasyon ve misafir de tahsilat kaldırılmadan silinemez. Ön Kasa TL bakiyesi Euro hareketlerle karıştırılmaz. Canlı MariaDB'de `cash_transactions.currency`, `member_payments` tablosu ve `2026_09_22_000000_create_member_payments_table` migration kaydı 1/1/1 olarak doğrulandı. Canlı arayüzde Ödeme Al düğmesi, özetler, hizmet listesi ve tahsilat geçmişi kontrol edildi; gerçek tahsilat/test kaydı oluşturulmadı. Yerel regresyonlarda toplam 11 test / 55 doğrulama geçti.
- 22 Eylül 2026: Rezervasyon Girişi başlık çubuğuna eksik küçült ve büyüt/geri yükle düğmeleri eklendi. Küçültme rezervasyon penceresini görev çubuğuna indirir; büyütme düğmesi pencereyi tam ekran ve önceki boyutu arasında değiştirir. Kapat düğmesi Takvime dön davranışını korur. Yerel tarayıcıda üç düğmenin görünmesi, büyüt/geri yükle ve küçültüp görev çubuğundan geri açma akışları doğrulandı. Canlıya aktarılmadı.
- 22 Eylül 2026: Kurulum penceresi daraltıldığında sekme başlıklarının sığması için pencere genişliğine bağlı responsive tipografi eklendi. Sekmeler mevcut genişliği eşit paylaşır; yazı boyutu 1100, 800 ve 600 piksel eşiklerinde sırasıyla 12, 10 ve 9 piksele iner. Geniş görünümde yazılımın 14 piksel Verdana standardı korunur. Yerel Kurulum penceresinin 840 piksel genişliğinde tüm sekmelerin taşmadan göründüğü doğrulandı. Canlıya aktarılmadı.
- 22 Eylül 2026: Misafirler listesindeki kişi ikonu `Ölçümler` olarak düzenlendi. Tıklandığında misafire bağlı ölçüm tarihçesini ve iç ayrıntı/form ekranını açar. Tanita BC-418 çıktısındaki tarih, vücut tipi, cinsiyet, yaş, boy, kilo, BMI, BMR, yağ oranı/kütlesi, yağsız kütle, toplam su, tüm vücut ve uzuv empedansları ile sağ/sol kol-bacak ve gövde bölgesel analiz alanları desteklenir; ayrıca kullanıcı tarafından girilen Yorum alanı bulunur. `member_measurements` tablosu ve CRUD API uçları eklendi; yerel SQLite migrationı uygulandı. API kayıt/listeleme/güncelleme/silme testi 1 test / 9 doğrulamayla geçti. Yerel arayüzde Ölçümler iç ekranı, boş tarihçe ve Yeni Ölçüm formundaki tüm bölümler doğrulandı. Canlıya aktarılmadı.

## Yeni bir Codex görevi başlatırken

Şu komut yeterlidir:

> `E:\kirpi\spa-web\AGENTS.md` ve `E:\kirpi\spa-web\PROJECT_CONTEXT.md` dosyalarını tamamen oku, mevcut git durumunu kontrol et ve SPA projesine kaldığımız yerden devam et.
### 2026-09-22 - Başlat menüsü ikon yenilemesi

- Başlat menüsündeki karakter tabanlı simgeler, her menü öğesine özel çizgi SVG ikonlarla değiştirildi.
- İkonlara Sofitel yeşil/altın temasına uyumlu kutu, hover ve aktif durum stilleri eklendi.
- Alt menü ikonlarının boyut ve girintileri ana menüden bağımsız düzenlendi.
- Yerel tarayıcıda Başlat menüsü açılarak hizalama ve görünüm doğrulandı.
### 2026-09-22 - Türkçe tarih biçimi standardizasyonu

- Kullanıcıya gösterilen tarihler `gg.aa.yyyy`, tarih-saat değerleri `gg.aa.yyyy ss:dd` biçiminde ortaklaştırıldı.
- Tarayıcı dili İngilizce olsa da form tarih alanlarının Türkçe görünmesi için ortak görünen alan/ISO gizli değer altyapısı eklendi; API ve veritabanı kayıt biçimi değişmedi.
- Rezervasyon web taleplerindeki ham ISO tarih ve SMS geçmişindeki tarayıcıya bağlı tarih-saat gösterimi düzeltildi.
- Dinamik açılan misafir, personel, rezervasyon, stok, kasa, tahsilat ve ölçüm formlarındaki tarih alanları da otomatik kapsama alındı.
- Yerel tarayıcıda misafir kartında doğum, başlangıç ve bitiş tarihleri görsel olarak doğrulandı.
### 2026-09-22 - Misafir kartı başlığı sadeleştirmesi

- Kayıtlı misafir kartı başlığından `Misafir Kartı` metni ve misafir numarası kaldırıldı.
- Başlıkta yalnızca misafirin adı soyadı gösteriliyor.
# 2026-09-24 — Masaüstü sürüm göstergesi

- Sağ alt köşedeki saat kaldırıldı ve yerine `Versiyon ggaaY.sıra` biçimindeki yazılım sürümü yerleştirildi.
- Golf yazılımındaki sürüm sistemiyle aynı kurallar kullanıldı: İstanbul tarihine göre gün-ay-yılın son hanesi ve aynı gündeki yayın sıra numarası.
- `public/release.json` sürüm kaynağı, `/api/app-release` doğrulanmış ve önbelleksiz API uç noktası, `npm run release:prepare` yayın hazırlama komutu eklendi.
- Arayüz sürüm bilgisini açılışta ve dakikada bir yeniler; bilgi alınamazsa son gösterilen değeri korur.

# 2026-09-24 — Misafir kartı üyelik başlığı

- Misafir kartındaki `ÜYELİK BİLGİLERİ` ara başlığı kaldırıldı; üyelik alanları mevcut konumunda korunuyor.

# 2026-09-24 — Misafir adı, yaşı ve kan grubu

- Misafir kartındaki tek `Adı Soyadı` alanı ayrı `Ad` ve `Soyad` girişlerine dönüştürüldü; birleşik ad mevcut liste ve rezervasyon uyumluluğu için sunucuda üretilmeye devam ediyor.
- Doğum tarihi değiştiğinde güncel tarihe göre otomatik hesaplanan salt okunur `Yaş` alanı eklendi.
- `Kan Grubu` alanı A, B, AB ve 0 gruplarının pozitif/negatif seçenekleriyle eklendi.
- Mevcut misafir adlarını ad/soyad sütunlarına aktaran veritabanı geçişi eklendi.

# 2026-09-24 — Misafir kartı üyelik işlemleri

- Misafir kartındaki üyelik alanının sağ altına `Sözleşme`, `Sözleşme Yükle` ve `Sağlık Geçmişi` işlem butonları eklendi.

# 2026-09-24 — Ödeme takibinin Muhasebe sekmesine taşınması

- Misafir kartındaki `ÖDEME TAAHHÜTNAMESİ` başlığı ve eski statik ödeme alanları kaldırıldı.
- Toplam hizmet tutarı, ödenen tutar, kalan borç, rezervasyon bazlı ödeme alma formu ve tahsilat geçmişi misafir kartının `Muhasebe` sekmesine taşındı.
- Tahsilat silme işlemi ve bağlı Ön Kasa gelirini kaldırma davranışı korunuyor.
- Misafir listesindeki `Hizmetler` penceresi yalnızca rezervasyonlu hizmetleri göstermeye ayrıldı.

# 2026-09-24 — Hizmetler başlığından üyelik numarası

- Misafirin Hizmetler penceresindeki başlık ve özet alanından üyelik numarası kaldırıldı; yalnızca misafir adı gösteriliyor.

# 2026-09-24 — Kısa sürüm etiketi

- Sağ alt köşedeki sürüm göstergesi `Versiyon` yerine `Vrs:` etiketiyle kısaltıldı.

# 2026-09-24 — Misafir Hizmetler ekranından hizmet ekleme

- Misafirin Hizmetler penceresine `Hizmet Ekle` butonu eklendi.
- Buton mevcut rezervasyon giriş ekranını açar ve ilgili misafiri otomatik seçer; kullanıcı hizmet, tarih, saat ve terapisti belirleyip rezervasyonu kaydedebilir.

# 2026-09-24 — Ölçümler başlığından üyelik numarası

- Misafirin Ölçümler penceresindeki üst başlık ve özet alanından üyelik numarası kaldırıldı; yalnızca misafir adı gösteriliyor.

# 2026-09-24 — Ölçüm satırından düzenleme

- Ölçüm tarihçesindeki bir satırın veri alanlarına tıklanınca ilgili ölçüm sağdaki ayrıntı bölümünde düzenleme modunda açılıyor.
- Düzenleme ve silme işlem düğmelerinin mevcut davranışı korundu; satırlara üzerine gelme vurgusu eklendi.

# 2026-09-24 — Misafir belge düğmeleri görünümü

- Sözleşme, sözleşme yükleme ve sağlık geçmişi düğmeleri normal durumda renkli ikon olarak gösteriliyor.
- Fareyle üzerine gelindiğinde veya klavyeyle odaklandığında düğme genişleyerek işlem adını gösteriyor.

# 2026-09-24 — Misafir kartında üçlü alan düzeni

- Geniş ekranda misafir kartının her satırında üç etiket–veri alanı çifti gösterilecek şekilde form 6 kolonlu ızgaraya dönüştürüldü.
- Adres gibi geniş alanlar tam satırı kullanıyor; orta ve dar ekranlarda sırasıyla iki ve tek veri alanlı uyarlanabilir düzen korunuyor.

# 2026-09-24 — Belge işlem ikonları

- Sözleşme ikonu imzalı belge, sözleşme yükleme ikonu yukarı ok–yükleme simgesiyle değiştirildi.
- Belge ve sağlık geçmişi ikonları daha büyük ve belirgin renklerle gösteriliyor.

# 2026-09-24 — TC Kimlik No doğrulaması

- Misafir kartındaki TC Kimlik No alanı girildiğinde tam 11 rakam olma zorunluluğu getirildi.
- Eksik, fazla veya harf içeren değerler hem tarayıcıda hem API doğrulamasında reddediliyor.

# 2026-09-24 — Üyelik alanlarında dörtlü düzen

- Üyelik Türü, Süresi, Başlangıç ve Bitiş alanları geniş ekranda aynı satırda dört veri alanı olarak yerleştirildi.
- Kişisel bilgilerdeki üçlü düzen korunurken üyelik alanları orta ve dar ekranlarda iki ve tek alanlı düzene geçiyor.

# 2026-09-24 — Taksitli ödeme

- Misafir tahsilatındaki ödeme türlerine `Taksit` seçeneği eklendi.
- Taksit seçildiğinde 1–3 arasında taksit sayısı seçiliyor; API üçten fazla taksiti reddediyor.
- Taksit sayısı tahsilat kaydında saklanıyor, tahsilat geçmişinde ve bağlı Ön Kasa açıklamasında gösteriliyor.

# 2026-09-24 — Çoklu para birimi ve TCMB kurları

- Misafir tahsilatlarında varsayılan TL olmak üzere TL, USD ve EUR seçimi eklendi.
- Yabancı para tutarı, kullanılan TCMB döviz alış kuru, sabit TL karşılığı ve rezervasyon borç kontrolü için EUR karşılığı tahsilat kaydında saklanıyor.
- TCMB USD/EUR döviz alış-satış ve efektif alış-satış kurları tarihsel olarak `exchange_rates` tablosunda tutuluyor.
- `exchange-rates:refresh` komutu TCMB günlük bülteni yayımlandıktan sonra çalışması için her gün Europe/Istanbul saat diliminde 16:00'ya zamanlandı; ödeme sırasında güncel kur yoksa otomatik yenileme deneniyor ve başarısızlıkta son kayıtlı kur kullanılıyor.

# 2026-09-24 — Görünüm Ayarları kaldırıldı

- Başlat menüsündeki `Görünüm Ayarları` seçeneği ile arka plan ve renk seçim paneli kaldırıldı.
- Kullanılmayan tema JavaScript'i ve ilgili stiller temizlendi; masaüstü kalıcı olarak varsayılan Sofitel yeşil görünümünü kullanıyor.

# 2026-09-24 — Kurulumda Kurlar sekmesi

- Kurulum penceresinde Hizmetler sekmesinin yanına `Kurlar` sekmesi eklendi.
- Sekmede veritabanındaki son USD/EUR kur tarihi, döviz alış-satış ve efektif alış-satış değerleri gösterilir.
- `TCMB'den Şimdi Güncelle` düğmesi USD ve EUR kurlarını anında yeniler.

# 2026-09-24 — Giriş/çıkış alanlarının ayrılması

- Misafir giriş/çıkış formundaki birleşik tarih-saat kutuları ayrı giriş tarihi, giriş saati, çıkış tarihi ve çıkış saati alanlarına dönüştürüldü.
- Çıkış tarihi ve saati birlikte girilmediğinde kayıt engellenir; mevcut API ve veritabanı tarih-saat biçimi korunur.
- `Yeni Giriş / Çıkış` düğmesi `Yeni` olarak kısaltıldı.

# 2026-09-24 — Para birimi simgeleri

- Misafir tahsilat formundaki para birimi seçenekleri metin yerine `₺`, `$` ve `€` simgeleriyle gösterilir.
- Sunucuya gönderilen TRY, USD ve EUR kodları değişmeden korunur.

# 2026-09-24 — 24096.03 canlı dağıtımı

- `spa-live-20260924-1535.zip` paketi `/home/krpsoftc/spa-app` dizinine çıkarıldı.
- `ExchangeRateController.php`, `resources/views/spa.blade.php`, `routes/web.php` ve `public/release.json` güncellendi; canlı `.env` ve mevcut veriler korundu.
- Canlıda `Vrs: 24096.03`, Kurulum > Kurlar sekmesi, ayrı giriş/çıkış tarih-saat alanları, kısaltılmış `Yeni` düğmesi ve `₺/$/€` para birimi seçenekleri doğrulandı.

# 2026-09-24 — Sürüm etiketi biçimi

- Sürüm numarası üretme kuralları değiştirilmeden görev çubuğundaki `Vrs: 24096.04` gösterimi `V24096.04` biçimine kısaltıldı.
- Değişiklik yalnızca yerelde yapıldı; canlıya aktarılmadı.

# 2026-09-24 — Tahsilatta baz kur görünürlüğü

- Ödeme alma formunda seçilen ödeme tarihi ve para birimine ait TCMB kuru `Baz Alınan Kur` alanında gösterilir.
- Tahsilat geçmişine `Baz Kur` sütunu eklendi; her tahsilatın kaydedilirken kullanılan TL dönüşüm kuru burada görünür.
- Ödeme formundaki `Not` etiketi ve giriş alanı alt satırın başına taşındı.
- Değişiklikler yalnızca yerelde yapıldı; canlıya aktarılmadı.

# 2026-09-24 — Tahsilat eylemleri

- Tahsilat geçmişinin `Eylem` sütununa standart düzenleme ve kırmızı silme ikonları eklendi.
- Düzenleme işlemi tahsilatı forma yükler; kayıt güncellendiğinde bağlı Ön Kasa hareketi de aynı işlem içinde güncellenir.
- Değişiklik yalnızca yerelde yapıldı; canlıya aktarılmadı.

# 2026-09-24 — Ödeme formu etiketleri

- Ödeme formundaki alan adlarının sonunda görünen yıldız işaretleri kaldırıldı.
- Zorunlu alanların doğrulama kuralları korunarak yalnızca görsel etiketler değiştirildi.
- Değişiklik yalnızca yerelde yapıldı; canlıya aktarılmadı.

# 2026-09-24 — 24096.04 canlı dağıtımı

- `spa-live-20260924-1553.zip` paketi cPanel üzerinden `/home/krpsoftc/spa-app` dizinine çıkarıldı.
- Tahsilat düzenleme/silme eylemleri, baz alınan kur gösterimi, ödeme tarihi bazlı kur sorgusu, yıldızsız ödeme etiketleri ve `V24096.04` sürüm gösterimi canlıya aktarıldı.
- Canlı `.env` ve MySQL verileri korunarak yalnızca uygulama denetleyicileri, görünüm, rotalar ve sürüm dosyası güncellendi; şema değişikliği yapılmadı.
- Canlı uygulamada `V24096.04`, `Baz Alınan Kur`, `Baz Kur` sütunu ve yıldızsız ödeme alan adları doğrulandı.

# 2026-09-24 — Tahsilat para birimi dönüşümü

- Rezervasyon seçimindeki uzun rezervasyon numarası, tarih ve kalan borç metni kaldırılarak yalnızca hizmet adı gösterildi.
- Para birimi değişiklik olayının tarayıcı olayı yanlışlıkla `mevcut değeri koru` seçeneği olarak algılaması düzeltildi.
- Tahsil edilecek tutar artık ₺, $ veya € seçildiğinde seçilen para biriminin kayıtlı kuruna göre anında yeniden hesaplanır.
- Ödeme formundaki `Tahsil Edilecek` etiketi `Ödeme` olarak kısaltıldı.
- Değişiklik yalnızca yerelde yapıldı; canlıya aktarılmadı.

# 2026-09-24 — 24096.05 canlı dağıtımı

- `spa-live-20260924-1622.zip` paketi cPanel üzerinden `/home/krpsoftc/spa-app` dizinine çıkarıldı.
- Rezervasyon seçimi yalnızca hizmet adını gösterecek şekilde sadeleştirildi, `Tahsil Edilecek` etiketi `Ödeme` olarak değiştirildi ve para birimi değiştiğinde ödeme tutarının anında yeniden hesaplanması canlıya aktarıldı.
- Canlı `.env` ve veritabanı korunarak yalnızca `resources/views/spa.blade.php` ile `public/release.json` güncellendi; şema değişikliği yapılmadı.
- Canlı uygulamada `V24096.05`, sade rezervasyon metni ve € seçiminde `270.00` ödeme tutarı doğrulandı.
