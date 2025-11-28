# Ngrok Setup untuk Laravel

## Langkah-langkah:

### 1. Install Ngrok (Jika belum)
- Download dari: https://ngrok.com/download
- Extract `ngrok.exe` ke folder (contoh: C:\ngrok\)

### 2. Setup Auth Token
```powershell
ngrok config add-authtoken YOUR_TOKEN_HERE
```
Get token dari: https://dashboard.ngrok.com/get-started/your-authtoken

### 3. Jalankan Ngrok
```powershell
ngrok http 8000
```

### 4. Copy URL Ngrok
Setelah ngrok berjalan, Anda akan melihat:
```
Forwarding    https://xxxx-xxx-xxx-xxx.ngrok-free.app -> http://localhost:8000
```

Copy URL tersebut (contoh: `https://xxxx-xxx-xxx-xxx.ngrok-free.app`)

### 5. Update .env
Buka file `.env` dan update:
```
APP_URL=https://xxxx-xxx-xxx-xxx.ngrok-free.app
SESSION_DOMAIN=.ngrok-free.app
```

### 6. Update TrustProxies (Penting!)
Edit file `app/Http/Middleware/TrustProxies.php`:

```php
protected $proxies = '*';
```

### 7. Clear Cache
```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 8. Test
Buka URL ngrok di browser:
```
https://xxxx-xxx-xxx-xxx.ngrok-free.app
```

## Tips:

### Ngrok dengan Domain Custom (Berbayar)
```powershell
ngrok http 8000 --domain=your-domain.ngrok.app
```

### Ngrok dengan Basic Auth
```powershell
ngrok http 8000 --basic-auth="username:password"
```

### Ngrok dengan Region
```powershell
ngrok http 8000 --region=ap
```
Regions: us, eu, ap, au, sa, jp, in

### Lihat Ngrok Dashboard
Buka: http://localhost:4040

## Troubleshooting:

### Error: "Invalid Host Header"
Tambahkan di `.env`:
```
APP_URL=https://your-ngrok-url.ngrok-free.app
```

### Error: CSRF Token Mismatch
Clear cache:
```powershell
php artisan config:clear
php artisan cache:clear
```

### Error: Mixed Content (HTTP/HTTPS)
Tambahkan di `app/Providers/AppServiceProvider.php`:
```php
public function boot()
{
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }
}
```

## Ngrok Free vs Paid

### Free:
- Random URL setiap restart
- 1 concurrent tunnel
- 40 connections/minute

### Paid ($8/month):
- Custom domain
- Multiple tunnels
- No connection limit
- Reserved domains
