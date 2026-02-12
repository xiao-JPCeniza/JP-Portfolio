# Deploying This Portfolio (Free Options)

This is a **Laravel 12 + Vue 3 + Vite** app with a contact form that uses a database. Below are **free** hosting options and step-by-step instructions for the recommended one.

---

## Free hosting comparison

| Platform   | Free tier | Best for              | Limits / notes |
|-----------|-----------|------------------------|----------------|
| **Render** | Yes      | **Recommended**        | 750 hrs/month, free PostgreSQL 1 GB. App sleeps after ~15 min idle (~1 min cold start). No credit card. |
| **Koyeb**  | Yes      | Simple PHP deploy      | 1 free web service, 1 free Postgres (50 hrs). Scale-to-zero. |
| **Railway**| Trial    | Quick try-out          | Free trial then paid. Good Laravel docs. |
| **Fly.io** | Trial only | Not free long-term   | 7-day trial, then pay-as-you-go. |

**Recommendation:** Use **Render** for a free, persistent deployment with database.

---

## Deploy to Render (free)

### 1. Push your code to GitHub

Ensure your repo is on GitHub (or GitLab) and includes the Dockerfile, `scripts/00-laravel-deploy.sh`, and `.dockerignore` from this project.

### 2. Create a PostgreSQL database on Render

1. Go to [Render Dashboard](https://dashboard.render.com/).
2. Click **New +** → **PostgreSQL**.
3. Create a free instance and wait for it to be ready.
4. Open the database and copy the **Internal Database URL** (use this so the app and DB are on the same network; do not use the external URL for the app).

### 3. Create the Web Service

1. In Render, click **New +** → **Web Service**.
2. Connect your GitHub account and select this portfolio repo.
3. Configure:
   - **Name:** e.g. `portfolio`
   - **Region:** Choose one close to you.
   - **Runtime:** **Docker** (required).
   - **Instance type:** **Free**.

### 4. Set environment variables

In the Web Service, go to **Environment** and add:

| Key             | Value |
|-----------------|--------|
| `DATABASE_URL`  | The **Internal Database URL** from your Render PostgreSQL (from step 2). |
| `DB_CONNECTION` | `pgsql` |
| `APP_KEY`       | Run locally: `php artisan key:generate --show` and paste the result. |
| `APP_URL`       | Your Render URL, e.g. `https://portfolio-xxxx.onrender.com` (you can set this after first deploy). |

Optional but recommended:

- `APP_ENV` = `production`
- `CONTACT_NOTIFY_EMAIL` = your email (for contact form notifications, if you configure mail later).

### 5. Deploy

Click **Create Web Service**. Render will:

- Build the Docker image (installs PHP deps, builds Vite/Vue assets, runs migrations on startup).
- Assign a URL like `https://portfolio-xxxx.onrender.com`.

First load after idle can take ~1 minute (cold start). After that the site will be fast until it sleeps again.

### 6. Optional: custom domain

In the Web Service → **Settings** → **Custom Domain**, add your domain. Render provides free TLS.

---

## What’s already set up in this repo

- **Dockerfile** – Multi-stage: builds Vite/Vue assets, then PHP app with Nginx + PHP-FPM.
- **scripts/00-laravel-deploy.sh** – Runs on container start: `composer install`, `config:cache`, `route:cache`, `migrate --force`.
- **.dockerignore** – Keeps image smaller and excludes secrets.
- **HTTPS** – In production, Laravel forces HTTPS (see `AppServiceProvider`).
- **Database** – `config/database.php` uses `DATABASE_URL` for PostgreSQL (so Render’s Postgres works without extra config).

---

## Mail (contact form)

The app is set up to use Laravel’s mail config. On Render, the filesystem is ephemeral, so use a mail driver that doesn’t rely on local files:

- **Free:** [Mailtrap](https://mailtrap.io/) (testing) or [Brevo](https://www.brevo.com/) (free tier for real email).
- In Render, set env vars for your chosen driver (e.g. `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_ENCRYPTION`).

If you don’t set these, the contact form may still save messages to the database; only the “send email” part will depend on your mail config.

---

## Troubleshooting

- **502 / timeout on first load** – Free instances sleep; wait up to ~1 minute for the first response.
- **Database connection error** – Ensure you use the **Internal** Database URL and `DB_CONNECTION=pgsql`.
- **Assets 404 or mixed content** – Set `APP_URL` to your exact Render URL (e.g. `https://portfolio-xxxx.onrender.com`) and redeploy; HTTPS is already forced in production.
- **Build fails** – Check Render build logs; ensure `package.json`, `vite.config.js`, and `resources/` are in the repo and that the Docker build completes the `npm run build` step.

For more detail, see [Render: Deploy a PHP Web App with Laravel and Docker](https://render.com/docs/deploy-php-laravel-docker).
