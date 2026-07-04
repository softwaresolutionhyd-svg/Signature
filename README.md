<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Signature Management System

Laravel app for **signature.softwaresolutions.pk**

Changes are made in Cursor → pushed to GitHub → auto-deployed to hosting via FTP.

---

## Abhi kya karna hai (Next Steps)

### Step 1: Server se Laravel code GitHub par lao

Repo abhi empty hai. Pehle server par jo Laravel install hai usko yahan push karo.

**Option A — StackCP / cPanel File Manager / FTP se download:**
1. StackCP → Files → FTP Accounts se subdomain folder download karo: `public_html/Signature`
2. Local machine par folder kholo
3. Ye repo clone karo aur files copy karo (`.env` **mat** copy karo repo mein)
4. Push karo:

```bash
git clone https://github.com/softwaresolutionhyd-svg/Signature.git
cd Signature
# Laravel files yahan copy karo (vendor/ optional — neeche dekho)
git add .
git commit -m "Add Laravel application from server"
git push origin main
```

**Option B — Server par SSH ho to:**
```bash
cd /path/to/laravel
git init
git remote add origin https://github.com/softwaresolutionhyd-svg/Signature.git
git add .
git commit -m "Initial Laravel commit"
git push -u origin main
```

> **Important:** Server par `.env` file rehni chahiye — sirf `.env.example` repo mein jaye.

---

### Step 2: GitHub Secrets verify karo (StackCP / cPanel)

GitHub repo → **Settings → Secrets and variables → Actions** mein ye 4 secrets hon:

| Secret Name | Tumhara Exact Value |
|-------------|---------------------|
| `FTP_SERVER` | StackCP FTP page par jo **Server/Host** likha ho (e.g. `ftp.softwaresolutions.pk`) |
| `FTP_USERNAME` | `signature@softwaresolutions.pk` |
| `FTP_PASSWORD` | Is account ka password |
| `FTP_SERVER_DIR` | `/` |

**`FTP_SERVER_DIR` = `/`** — kyunki `signature@softwaresolutions.pk` account already `public_html/signature` folder par scoped hai. Login ke baad woh folder hi root hota hai.

> **Case sensitive:** Server par folder `public_html/signature` (lowercase) hai — `Signature` nahi. StackCP mein bhi yehi path dikh raha hai.

Push to `main` par `.github/workflows/deploy.yml` automatically FTP deploy chala degi.

---

### Step 2b: Subdomain document root (Laravel ke liye zaroori)

Laravel ki asli entry point `public/` folder hai. cPanel mein verify karo:

1. **cPanel → Domains → Subdomains** (ya StackCP → Domains)
2. `signature.softwaresolutions.pk` ka **Document Root** ye ho:
   ```
   public_html/signature/public
   ```
   **NA ke** sirf `public_html/Signature`

Agar abhi root `public_html/Signature` par hai aur site chal rahi hai, to shayad pehle se `.htaccess` redirect laga ho — phir change ki zaroorat nahi. Warna 404/500 aayega deploy ke baad.

---

### Step 3: Server par one-time Laravel setup

Deploy ke baad server par (StackCP Terminal / cPanel Terminal):

```bash
cd ~/public_html/signature
composer install --no-dev --optimize-autoloader
php artisan key:generate   # sirf pehli dafa agar .env mein APP_KEY empty ho
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

Agar SSH nahi hai to `vendor/` folder bhi FTP se upload karo ya hosting panel se Composer chalao.

---

### Step 4: Cursor se kaam karna

1. **GitHub connect:** [cursor.com/dashboard/integrations](https://cursor.com/dashboard/integrations) → GitHub → `Signature` repo allow karo
2. **Cloud Agent:** [cursor.com/dashboard/cloud-agents](https://cursor.com/dashboard/cloud-agents) → is repo ke liye environment banao
3. **Cursor mein repo kholo** → Cloud Agent se changes karo → commit + push → auto deploy

Daily workflow:
```
Cursor mein code change → git push main → GitHub Actions deploy → signature.softwaresolutions.pk update
```

---

## Folder structure (Laravel standard)

```
/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← subdomain document root yahan point kare
├── resources/
├── routes/
├── storage/
├── .env.example     ← repo mein (template)
├── .env             ← sirf server par (repo mein NAHI)
└── .github/workflows/deploy.yml
```

---

## Troubleshooting

| Problem | Fix |
|---------|-----|
| 500 error after deploy | `storage/` aur `bootstrap/cache/` permissions 775 |
| APP_KEY missing | Server par `php artisan key:generate` |
| CSS/JS nahi load | `npm run build` locally, `public/build` commit karo |
| DB error | Server `.env` mein DB credentials check karo |
| FTP deploy fail | Secrets names exact match karo; alag FTP account ho to `FTP_SERVER_DIR` = `/` try karo |
| 404 / blank page | Document root `public_html/signature/public` set karo |
| Files galat jagah upload | StackCP FTP Accounts page se account ki directory confirm karo |

---

## Main website vs subdomain

| | Main site repo | Signature repo (ye) |
|--|----------------|---------------------|
| URL | softwaresolutions.pk | signature.softwaresolutions.pk |
| Repo | Alag | `softwaresolutionhyd-svg/Signature` |
| FTP | Alag account | Alag FTP account |
| Deploy | Alag workflow | Is repo ka workflow |

Dono repos independent hain — ek mein change doosri site par effect nahi karega.
>>>>>>> origin/cursor/laravel-ftp-deploy-setup-f039
