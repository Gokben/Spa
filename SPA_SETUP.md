# Sofitel SPA Yönetim

## Teknoloji

- PHP 8.3+
- Laravel 12
- MySQL 8 (üretim)
- Blade ve vanilla JavaScript

## Yerel geliştirme

Bu bilgisayarda yerel test veritabanı olarak SQLite kullanılmaktadır. Üretimde `.env.example` temel alınarak MySQL bilgileri girilmelidir.

```powershell
php -c E:\kirpi\spa\.tools\php.ini artisan migrate --seed
php -c E:\kirpi\spa\.tools\php.ini -S 127.0.0.1:8090 -t public public\index.php
```

Testler:

```powershell
php -c E:\kirpi\spa\.tools\php.ini vendor\bin\phpunit
```

## Üyeler modülü

Üye liste ve kart uçları yalnızca Laravel oturumu açmış kullanıcılar tarafından kullanılabilir:

- `GET /api/members`
- `GET /api/members/{member}`
- `POST /api/members`
- `PUT /api/members/{member}`

Liste yanıtında kimlik ve adres gibi hassas kart alanları gönderilmez. T.C. kimlik numarası veritabanında Laravel şifreli cast ile saklanır.

## Canlı ortam

Web sunucusunun document root değeri Laravel `public` klasörünü göstermelidir. `.env`, `vendor`, `storage` ve uygulama kaynakları doğrudan web kökünden servis edilmemelidir. Mevcut cPanel dağıtım betiği eski statik sürüme aittir ve Laravel dağıtımı için ayrıca güncellenmelidir.
