# Jumuiya Development Foundation — Laravel API & Admin Panel

Backend API powering the Jumuiya Development Foundation website, with a full Filament v3 admin panel.

---

## Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Admin Panel | Filament v3 |
| Database | SQLite (dev) / MySQL (production) |
| File Storage | Local `public` disk (dev) / S3 (production) |
| Auth | Filament session auth (admin panel) |

---

## Quick Start (Local Development)

### 1. Install dependencies
```bash
composer install
```

### 2. Configure environment
```bash
cp .env.example .env
# Edit .env — set APP_URL, DB_* credentials, MAIL_* etc.
php artisan key:generate
```

### 3. Run migrations + seed sample data
```bash
php artisan migrate --seed
```
This creates:
- An admin user: **admin@jumuiyafoundation.org** / **password** ← change this immediately
- Sample events, news, jobs, tenders, and annual reports

### 4. Create storage symlink
```bash
php artisan storage:link
```

### 5. Start the development server
```bash
php artisan serve
```

API base: `http://localhost:8000/api`  
Admin panel: `http://localhost:8000/admin`

---

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/events` | List all events |
| GET | `/api/events/{id}` | Single event |
| GET | `/api/news` | List all news articles |
| GET | `/api/news/{id\|slug}` | Single article (ID or slug) |
| GET | `/api/jobs` | List all jobs/careers |
| GET | `/api/jobs/{id}` | Single job |
| GET | `/api/tenders` | List all tenders |
| GET | `/api/tenders/{id}` | Single tender with documents |
| GET | `/api/annual-reports` | List downloadable annual reports |
| POST | `/api/contact` | Submit contact form |
| POST | `/api/partnership-enquiry` | Submit partnership enquiry |
| POST | `/api/newsletter/subscribe` | Newsletter subscription |
| POST | `/api/analytics/event` | Frontend analytics event (rate-limited) |
| POST | `/api/analytics/error` | Frontend JS error log (rate-limited) |

All list endpoints return `{ "data": [...] }`.  
All single-resource endpoints return a plain object (no envelope).  
Error responses: `{ "error": "message" }` or `{ "message": "...", "errors": {...} }`.

---

## Admin Panel

Access at `/admin`. Features:

### Content Management
- **Events** — Create/edit events with rich text, image upload, status management
- **News & Articles** — Full rich-text editor, slug auto-generation, author assignment, featured toggle
- **Jobs & Careers** — Comprehensive job posting with all fields including document upload
- **Tenders** — Procurement listings with structured document management (RFP, ToR files)
- **Annual Reports** — PDF upload with drag-to-reorder

### Inbox
- **Contact Messages** — View, mark as read/replied/archived. Unread badge on nav.
- **Partnership Enquiries** — Same workflow as contact messages
- **Newsletter Subscribers** — Manage subscriber list, bulk unsubscribe

### Analytics
- **Analytics Dashboard** — Page view stats, 30-day chart, top pages table
- **JS Errors** — Frontend error log with daily badge count

---

## CORS

Allowed origins are configured in `config/cors.php`:
- `https://jumuiyafoundation.org`
- `https://www.jumuiyafoundation.org`
- `http://localhost:3000` (Next.js dev)

Add staging domains as needed.

---

## Production Deployment

### 1. Switch to MySQL
Update `.env`:
```
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=jumuiya_api
DB_USERNAME=jumuiya
DB_PASSWORD=your-password
```

### 2. Configure file storage
For persistent file uploads in production, use S3 or configure the `public` disk with a CDN.
Update `config/filesystems.php` and set `FILESYSTEM_DISK=s3` in `.env`.

### 3. Run production setup
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Create your admin user
```bash
php artisan tinker
>>> \App\Models\User::create(['name' => 'Your Name', 'email' => 'you@domain.com', 'password' => bcrypt('strong-password')]);
```

### 5. Queue worker (for future notifications)
```bash
php artisan queue:work --daemon
```

---

## File Upload Paths

| Resource | Disk | Directory |
|---|---|---|
| Event images | `public` | `events/` |
| News images | `public` | `news/` |
| Career documents | `public` | `careers/documents/` |
| Tender documents | `public` | `tenders/` |
| Annual report PDFs | `public` | `reports/` |

All stored paths are served as absolute URLs by the model accessors.

---

## Next.js Integration

Set in your Next.js `.env.local`:
```
NEXT_PUBLIC_API_URL=https://api.jumuiyafoundation.org
```

Add your Laravel domain to `next.config.ts` remote patterns:
```typescript
images: {
  remotePatterns: [
    { protocol: 'https', hostname: 'api.jumuiyafoundation.org' },
  ],
},
```
