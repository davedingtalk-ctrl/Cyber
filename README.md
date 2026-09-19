# Cyber Legend — PHP + MySQL edition

A real backend version: public site + admin panel, backed by a MySQL database
through a small PHP API, with hashed-password session login. Any change made
in the admin panel is stored in the database and visible to **every**
visitor — unlike a browser-only version.

## Folder structure

```
/index.php          public site (reads live data from the database)
/admin/index.html   admin panel UI
/api/*.php          the backend API (auth, data, uploads, analytics)
/uploads/           uploaded images land here
/config.php         your database credentials go here
/schema.sql         run this once to create the database tables
```

## Deploying to InfinityFree

1. **Create the site & database**
   - Log in to InfinityFree → create/open your hosting account.
   - In the control panel, go to **MySQL Databases** and create a database.
     Note the DB host, database name, username and password it gives you.

2. **Import the schema**
   - Open **phpMyAdmin** from the control panel, select your new database,
     go to the **Import** tab, and upload `schema.sql` from this folder.
   - This creates all the tables and default content (no admin login yet
     on purpose — see step 4).

3. **Upload the files**
   - Open **File Manager** (or connect via FTP with the details InfinityFree
     gave you).
   - Go into `htdocs`, delete any default files there, and upload everything
     from this folder (`index.php`, `admin/`, `api/`, `uploads/`,
     `config.php`, `schema.sql`) preserving the folder structure.

4. **Fill in your database credentials**
   - Edit `config.php` and replace the four placeholder values with the ones
     from step 1 (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).

5. **Create your admin login**
   - Visit `yourdomain.com/api/setup.php` once in your browser. It creates a
     single admin account: username `admin`, password `admin123`.
   - It refuses to run a second time once an admin exists, so it's safe to
     leave in place — but for extra safety you can delete or rename
     `api/setup.php` after this step.

6. **Log in and change your password**
   - Go to `yourdomain.com/admin/`, log in with `admin` / `admin123`, then
     open the **Security** tab and set your own username/password
     immediately.

7. **Check the uploads folder is writable**
   - If profile picture / section image uploads fail, use File Manager to
     set permissions on `/uploads` to `755` (or `775` if your host requires
     it).

## What's actually secure here

- Passwords are hashed with bcrypt (`password_hash`/`password_verify`),
  never stored in plain text.
- All database queries use prepared statements (no SQL injection).
- Uploaded files are checked by real image content (not just file
  extension), size-limited, renamed randomly, and the `/uploads` folder
  blocks PHP execution via `.htaccess`.
- Admin routes check a real server-side session; nothing admin-only is
  reachable without being logged in.

## Honest limitations

- This is a straightforward single-admin setup, not an enterprise auth
  system — no 2FA, no rate-limiting on login attempts, no CSRF tokens.
  Fine for a personal profile site; if this is a serious high-value
  target, ask a developer to harden it further before going live.
- Always serve this over HTTPS (InfinityFree gives free subdomains HTTPS
  automatically) — session cookies aren't protected without it.
- `api/setup.php` is a one-time bootstrap. Delete it once your admin
  account exists, just to be tidy.
