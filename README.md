# Student Management System

A small PHP + MySQL web app for managing student and teacher records, with a public admission form and a simple two-role login system (admin / student).

This README describes what the project **actually contains** today — not an aspirational feature list.

## What's really in here

- **3 database tables**: `user` (admins + students), `teacher`, `admission`
- **2 user roles**: `admin` and `student`, distinguished by a `usertype` column
- **Plain PHP + mysqli** (no framework, no PDO, no REST API)
- **Bootstrap** for styling (a mix of 3.4.1 on the login page and 5.3.8 elsewhere — not yet unified)
- No automated tests, no CI, no email notifications, no GPA/course-enrollment system yet — despite what an earlier README draft claimed

## Features

- Public homepage listing teachers and courses, with an admission enquiry form
- Login for admin and student accounts
- Admin: add/view/update/delete students, add/view/update/delete teachers, view submitted admission enquiries
- Student: view and update their own profile (email, phone, password)

## Security posture (recent changes)

The original version of this app had several serious issues that have since been fixed:

| Issue | Status |
|---|---|
| SQL injection (raw string-concatenated queries) | ✅ Fixed — all queries now use prepared statements |
| Plaintext password storage | ✅ Fixed — passwords now hashed with `password_hash()` / `PASSWORD_DEFAULT`; legacy plaintext rows auto-upgrade on next login |
| Pages continued executing after `header()` redirects (auth bypass risk) | ✅ Fixed — every redirect is now followed by `exit` |
| DB credentials duplicated in every file | ✅ Fixed — centralized in `config/database.php` |
| Uploaded teacher images kept their original (attacker-controlled) filename | ✅ Fixed — files are renamed to random hex names and validated by MIME type |
| Raw DB error messages echoed to the browser | ✅ Fixed — errors are logged server-side instead |

Still not implemented (candidates for future work):

- CSRF tokens on state-changing forms
- Login attempt throttling / rate limiting
- Input validation beyond basic trimming (e.g. proper email/phone format checks)
- HTTPS enforcement / secure session cookie flags
- Roles beyond admin/student (e.g. a real "instructor" role)

## Project structure

```
student-management-system/
├── index.php                  # Public homepage + admission form
├── login.php / login_check.php / logout.php
├── config/
│   ├── database.php           # Centralized DB connection
│   └── auth.php                # require_login() / require_role() guards
├── database/
│   └── schema.sql             # Real schema: user, teacher, admission
├── admin_sidebar.php / student_sidebar.php
├── admin_css.php / student_css.php
├── adminhome.php / studenthome.php
├── add_student.php / view_student.php / update_student.php / delete.php
├── admin_add_teacher.php / admin_view_teacher.php / admin_update_teacher.php
├── admission.php               # Admin view of admission enquiries
├── student_profile.php
└── style.css / admin.css
```

## Getting started

### Prerequisites
- PHP 8.0+
- MySQL 5.7+ / MariaDB
- Apache/Nginx, or just `php -S localhost:8000`

### Setup

1. Clone the repo and move into it.
2. Create the database and load the schema:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   This creates the `schoolproject` database with the `user`, `teacher`, and `admission` tables, and seeds one admin account.
3. Check `config/database.php` — defaults are `localhost` / `root` / no password / `schoolproject`. Edit if your setup differs.
4. Serve the app:
   ```bash
   php -S localhost:8000
   ```
5. Visit `http://localhost:8000`.

### Default login

```
Username: admin
Password: admin123
```
**Change this password immediately after first login** — it's a known seed value.

## License

MIT (see LICENSE).

## Author

Nhlonipho Ndadane — ICT Application Development student, Durban University of Technology.
