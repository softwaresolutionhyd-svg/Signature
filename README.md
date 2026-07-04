# Signature Management System

Laravel app for **signature.softwaresolutions.pk**

Changes are made in Cursor → pushed to GitHub → auto-deployed to hosting via FTP.

---

## Abhi kya karna hai (Next Steps)

### Step 1: Server se Laravel code GitHub par lao

Repo abhi empty hai. Pehle server par jo Laravel install hai usko yahan push karo.

**Option A — cPanel File Manager / FTP se download:**
1. Hosting se subdomain folder download karo (usually `public_html/signature` ya jo path hai)
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

### Step 2: GitHub Secrets verify karo

GitHub repo → **Settings → Secrets and variables → Actions** mein ye 4 secrets hon:

| Secret Name | Example Value |
|-------------|---------------|
| `FTP_SERVER` | `ftp.softwaresolutions.pk` ya hosting ka FTP host |
| `FTP_USERNAME` | Subdomain ka alag FTP username |
| `FTP_PASSWORD` | FTP password |
| `FTP_SERVER_DIR` | `/public_html/signature/` ya subdomain ka exact path (trailing `/` ke sath) |

Push to `main` par `.github/workflows/deploy.yml` automatically FTP deploy chala degi.

---

### Step 3: Server par one-time Laravel setup

Deploy ke baad server par (cPanel Terminal ya SSH):

```bash
cd /path/to/laravel
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
| FTP deploy fail | Secrets names exact match karo, `FTP_SERVER_DIR` path verify karo |

---

## Main website vs subdomain

| | Main site repo | Signature repo (ye) |
|--|----------------|---------------------|
| URL | softwaresolutions.pk | signature.softwaresolutions.pk |
| Repo | Alag | `softwaresolutionhyd-svg/Signature` |
| FTP | Alag account | Alag FTP account |
| Deploy | Alag workflow | Is repo ka workflow |

Dono repos independent hain — ek mein change doosri site par effect nahi karega.
