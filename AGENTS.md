# SPA Projesi Çalışma Yönergeleri

Bu depo Sofitel Spa için geliştirilen Laravel tabanlı yönetim uygulamasıdır. Bir göreve başlamadan önce `PROJECT_CONTEXT.md` dosyasını tamamen oku ve güncel çalışma durumunu oradan doğrula.

## Temel kurallar

- Kullanıcıyla Türkçe iletişim kur.
- Yerel proje yolu `E:\kirpi\spa-web`, yerel adres `http://127.0.0.1:8090/` şeklindedir.
- Canlı adres `https://krpsoft.com.tr/spa/` şeklindedir.
- Backend PHP/Laravel, canlı veritabanı MySQL'dir.
- SQLite yalnızca yerel geliştirme ve test verileri için kullanılabilir.
- Test personel kayıtlarını kullanıcı açıkça istemedikçe canlı MySQL'e aktarma.
- Canlı `.env`, parola, oturum kimliği veya başka bir sırrı depoya yazma.
- Kullanıcının mevcut değişikliklerini ve canlı verilerini koru.
- Yeni veritabanı değişikliklerini Laravel migration dosyasıyla tanımla.
- Canlıya alma işlemlerinde önce yerel doğrulama yap, ardından dosya ve şema değişikliklerini açıkça bildir.
- Canlı dosya üzerine yazma ve MySQL şema değişikliği öncesinde son onay al.
- Canlı dağıtımdan sonra ilgili ekranı ve API davranışını doğrula.

## Arayüz ilkeleri

- Mevcut Sofitel Spa yeşil-altın görsel kimliğini koru.
- Pencereler Vox/Windows masaüstü uygulaması tarzında olmalıdır.
- Mevcut menü adlarını ve kullanıcı tarafından onaylanan yerleşimleri izinsiz değiştirme.
- Form alanlarında Türkçe etiket, açık doğrulama mesajı ve klavye erişilebilirliği kullan.
- Çalışma programında bir çalışma grubu seçilmeden program gösterme veya kaydetme.
- Grup bazlı program kaydında diğer çalışma gruplarının haftalık kayıtlarını silme.

## Geliştirme ve doğrulama

- Dosya aramalarında önce `rg` kullan.
- Düzenlemelerde mevcut Laravel yapısını ve API biçimini koru: JSON cevapları `data` alanında döner.
- PHP dosyalarında `php -l`, çalışma ağacında `git diff --check` çalıştır.
- Uygun olduğunda migration, rota ve tarayıcı kontrollerini çalıştır.
- Bu bilgisayardaki PHP CLI varsayılan olarak `php.ini` yüklemeyebilir. SQLite komutlarında gerekli eklentileri açıkça yüklemek gerekebilir.
- PHPUnit için DOM/XML eklentileri kurulu olmayabilir; çalışmazsa bunu açıkça bildir ve alternatif kontrolleri tamamla.
- Tamamlanan anlamlı değişikliklerde `PROJECT_CONTEXT.md` içindeki “Son durum” ve gerekiyorsa veri modeli bölümlerini güncelle.

## Git ve canlı dağıtım

- GitHub deposu: `https://github.com/Gokben/Spa.git`
- Kullanılan geliştirme dalı: `codex/add-agustos-index`
- Canlı hosting hesabında shell erişimi kapalı olabilir. Mevcut yöntem cPanel Dosya Yöneticisiyle göreli dizinleri koruyan ZIP paketini `/home/krpsoftc/spa-app` altında açmak ve gerekli MySQL değişikliklerini phpMyAdmin üzerinden uygulamaktır.
- Dağıtım paketlerine `.env`, yerel SQLite dosyası, test personel verileri, `vendor` veya gizli dosyalar ekleme.
- Canlı dağıtım tamamlandıktan sonra `https://krpsoft.com.tr/spa/` adresini yenileyerek doğrula.

