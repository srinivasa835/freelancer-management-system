# TWINEX backend setup — Hostinger MySQL + PHP

Everything in this zip is your entire site: the frontend (`index.html`,
`bundle.js`, `styles.css`, `icons/`, `manifest.json`, `service-worker.js`)
AND the backend (`api/*.php`, `config.php`, `db.php`, `helpers.php`,
`schema.sql`). Upload it all together — same folder, no separate deploy step.

## 1. Create the database

1. hPanel → **Databases → MySQL Databases**.
2. Create a new database (Hostinger will name it something like
   `u123456789_twinex`) and a database user with a password — **save these
   three values**, you'll need them in step 3.
3. Click **phpMyAdmin** next to your new database.
4. Go to the **Import** tab, choose the `schema.sql` file from this bundle,
   and click **Go**. This creates all the tables (clients, projects, team
   members, payments, invoices, tasks) and adds a little starter data so
   the app isn't empty on first login.

## 2. Choose your password

Open `config.php` in a text editor (Notepad, VS Code, anything) and change:

```php
define('APP_PASSWORD', 'change-this-password');
```

to whatever password you want to type to log into your own workspace.
This is separate from your database password — it's just the door into
your TWINEX app.

Also replace `SESSION_SECRET` with any random string (mash the keyboard).

## 3. Fill in your database credentials

Still in `config.php`, replace these three lines with the values from step 1:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'u123456789_twinex');   // your actual DB name
define('DB_USER', 'u123456789_twinex');   // your actual DB user
define('DB_PASS', 'your-database-password-here');
```

`DB_HOST` is almost always `localhost` on Hostinger shared hosting — leave
it unless hPanel told you otherwise.

## 4. Upload everything

1. hPanel → **Files → File Manager** → open `public_html`.
2. Delete any leftover default files in there.
3. Upload every file and folder from this bundle into `public_html`
   (including the `api/` folder, `config.php`, and hidden `.htaccess` —
   File Manager's "Extract" after uploading a zip will bring `.htaccess`
   along automatically).
4. Make sure SSL is on (hPanel → your domain → SSL — Hostinger issues this
   free).

## 5. Log in

Visit `https://yourdomain.com`. You should see a login screen — enter the
password you set in step 2. From then on your browser stays logged in
(session cookie) until you hit **Log out** in Settings.

Every client, project, payment, invoice, team member, and task you add from
here on is written straight to your MySQL database — refreshing, closing
the tab, or opening it on another device will show the same real data.

## If something's not working

- **Still showing the orange "Demo mode" banner instead of a login
  screen** → the browser couldn't reach `/api/auth.php` at all. Double
  check the `api/` folder actually made it into `public_html/api/` (not
  nested one level too deep), and that PHP is enabled for your domain
  (it is by default on Hostinger).
- **Login screen appears but the password doesn't work** → re-check
  `APP_PASSWORD` in `config.php` was saved, and that you re-uploaded the
  edited file (File Manager doesn't auto-sync — re-upload after editing
  locally, or edit directly in File Manager's built-in code editor).
- **"Database connection failed" message** → double-check `DB_HOST`,
  `DB_NAME`, `DB_USER`, `DB_PASS` in `config.php` exactly match what hPanel
  shows under Databases → MySQL Databases.
- **A page loads but stays blank / stats show ₹0 everywhere** → open
  `schema.sql` and confirm the import in step 1 actually completed (check
  phpMyAdmin's left sidebar — you should see 6 tables under your database).
