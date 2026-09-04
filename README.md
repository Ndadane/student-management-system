# Student Management System

A PHP/MySQL web app for managing student and teacher records, with role-based
admin/student logins and a public admission enquiry form. Built as a personal
project to practice full-stack development and secure coding practices.

## What it actually does

- **Public site**: homepage listing teachers, and an admission enquiry form
  (name, email, phone, message) that saves to the database for admin review.
- **Admin login**: add, view, update, and delete student records; add, view,
  update, and delete teacher records (with image upload); view submitted
  admission enquiries; view an audit log of system activity.
- **Student login**: view own profile.
- **Audit log**: every create/update/delete on students and teachers, and
  every login attempt (successful or failed), is recorded with a timestamp,
  the acting user, and the affected record — viewable on an admin-only page.
- **Server-side validation**: email, phone, username, name, and password
  inputs are validated on the server (not just via HTML attributes) before
  being written to the database.

## Tech stack

- PHP (procedural, no framework)
- MySQL / MariaDB (via `mysqli`, prepared statements throughout)
- Plain HTML/CSS, no JS framework
- Designed to run under XAMPP or any Apache+PHP+MySQL stack

## Setup

1. Install XAMPP (or Apache + PHP 8+ + MySQL) and place this project in your
   `htdocs` folder.
2. Start Apache and MySQL.
3. Import the schema: open phpMyAdmin, create/select the `schoolproject`
   database, and import `database/schema.sql`.
4. Import the audit log table: also import `database/audit_log.sql`.
5. Check `config/database.php` — defaults are `localhost` / `root` / no
   password / `schoolproject`. Edit if your MySQL setup differs.
6. Visit `http://localhost/student-management-system` in your browser.
7. Log in as admin with the seeded account: username `admin`, password
   `admin123`. **Change this password after first login** — it's a known
   default sitting in a public schema file.

### Optional: seed test data

`database/seed_students.php` inserts a configurable number of synthetic
student records, useful for testing the app at scale:

```
php database/seed_students.php 500
```

## Security work done on this project

This project went through a real security pass — not built securely from
day one, but audited and fixed deliberately as a learning exercise:

- **SQL injection**: all database queries use `mysqli` prepared statements
  with bound parameters. No raw string interpolation into SQL anywhere in
  the codebase.
- **Password storage**: passwords are hashed with `password_hash()`
  (bcrypt) and checked with `password_verify()`. A legacy-upgrade path
  exists in `login_check.php` that transparently rehashes any old
  plaintext password the first time a user logs in successfully.
- **Authentication/authorization**: `config/auth.php` provides
  `require_login()` and `require_role()`, called at the top of every
  page that should be gated, with an `exit` immediately after each
  `header()` redirect (an earlier version of this project had a bypass
  caused by a missing `exit`).
- **File uploads**: teacher images are validated by MIME type
  (`mime_content_type()`, not just the file extension) and saved under
  a randomly generated filename, preventing path traversal and
  disguised-executable uploads.
- **Session security**: `config/session.php` sets `HttpOnly`, `SameSite=Lax`
  cookie flags, regenerates the session ID on login
  (`session_regenerate_id()`), and enforces a 30-minute inactivity
  timeout.
- **Input validation**: `config/validation.php` validates email format,
  phone format/length, username characters, name characters, and minimum
  password length on the server, independent of any client-side/HTML
  validation.
- **Audit logging**: `config/audit.php` records every create/update/delete
  and every login attempt to an `audit_log` table, viewable at
  `audit_log.php`.

## Known limitations

Being upfront about what this project is *not*:

- **No HTTPS enforcement.** The `secure` cookie flag is currently `false`
  in `config/session.php` because local development runs over plain HTTP.
  This must be set to `true` before any real-world deployment, or the
  `HttpOnly`/`SameSite` protections above are undermined.
- **No CSRF tokens** on forms. State-changing POST requests (add/update/
  delete) rely on the login session but don't verify a per-form token,
  so this app is not hardened against cross-site request forgery.
- **No rate limiting** on login attempts, so it's not resistant to
  brute-force password guessing.
- **No automated tests.** All verification so far has been manual.
- **Two roles only** (`admin`, `student`) — no granular permissions
  within a role.
- **No pagination on student/teacher list pages** (the audit log page is
  the only one with pagination), so very large tables may render slowly
  in the browser.
- **This has not been deployed or used by a real institution.** All
  testing, including the 500-record seed data, is synthetic and done
  locally for development/learning purposes.

## Default credentials (development only)

| Role  | Username | Password   |
|-------|----------|------------|
| Admin | admin    | admin123   |
| Student | Student    | 1234   |

Change this immediately in any environment beyond local testing — it is
committed in plaintext in `database/schema.sql`.