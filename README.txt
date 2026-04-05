==============================================
  THE DAILY BLOG - PHP + MySQL Blog Website
==============================================

FOLDER STRUCTURE:
-----------------
blog/
├── index.php              → Public homepage (all posts)
├── post.php               → Single post view
├── 404.php                → Error page with "Go Home" button
├── .htaccess              → URL routing (wrong URLs → 404)
├── setup.sql              → Run this first to create DB tables
├── uploads/               → Uploaded cover images stored here
│
├── includes/
│   ├── db.php             → Database connection config
│   └── auth.php           → Session & helper functions
│
├── assets/
│   └── css/style.css      → All website styles
│
├── mks75@2062/            ← SECRET ADMIN URL FOLDER
│   ├── login.php          → Admin login  (/mks75@2062/login.php)
│   └── signup.php         → Admin sign up
│
└── admin/
    ├── dashboard.php      → Admin home / stats
    ├── posts.php          → Manage all posts
    ├── new-post.php       → Create new post
    ├── edit-post.php      → Edit existing post
    ├── delete-post.php    → Delete post handler
    ├── sidebar.php        → Sidebar navigation include
    └── logout.php         → Logout & clear session

==============================================
  SETUP INSTRUCTIONS
==============================================

STEP 1 — Create the database:
  Open phpMyAdmin or MySQL terminal and run:
  → setup.sql

STEP 2 — Configure database connection:
  Edit includes/db.php and set:
    DB_HOST  = your host (usually localhost)
    DB_USER  = your MySQL username
    DB_PASS  = your MySQL password
    DB_NAME  = blog_db

STEP 3 — Upload to your server:
  Place the entire "blog" folder in your htdocs / public_html

STEP 4 — Create your admin account:
  Go to: yourdomain.com/mks75@2062/signup.php
  Register your admin username, email & password

STEP 5 — Login:
  Go to: yourdomain.com/mks75@2062/login.php
  (Bookmark this — it's your secret admin URL!)

STEP 6 — Start blogging!
  From the dashboard you can create, edit & delete posts.

==============================================
  FEATURES
==============================================
✓ Public blog homepage with post grid
✓ Single post pages
✓ Admin login at secret URL /mks75@2062/login.php
✓ Admin sign up form
✓ Create / Edit / Delete posts
✓ Cover image upload per post
✓ Wrong URL → 404 page with "Go Back Home" button
✓ Clean responsive UI
✓ Password hashing (bcrypt)
✓ SQL injection protection (prepared statements)

