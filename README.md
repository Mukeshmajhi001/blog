# 📝 The Daily Blog
### A simple PHP + MySQL blog website with admin panel

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## 🚀 Features

- 🏠 Public homepage with blog post grid
- 📄 Single post pages with cover images
- 🔐 Admin panel at a secret URL
- ✍️ Create, Edit & Delete posts
- 🖼️ Cover image upload per post
- 🔒 Password hashing with bcrypt
- 🛡️ SQL Injection protection (prepared statements)
- 🔁 Wrong URL → Custom 404 page

---

## 📁 Folder Structure

```
blog/
├── index.php               → Public homepage (all posts)
├── post.php                → Single post view
├── 404.php                 → Custom error page
├── .htaccess               → URL routing
├── setup.sql               → Database setup script
├── uploads/                → Cover images stored here
│
├── includes/
│   ├── db.php              → DB connection (not in repo)
│   └── auth.php            → Session & helper functions
│
├── assets/
│   └── css/style.css       → All styles
│
├── mks75@2062/             → Secret admin URL folder
│   ├── login.php           → Admin login
│   └── signup.php          → Admin registration
│
└── admin/
    ├── dashboard.php       → Admin home & stats
    ├── posts.php           → Manage all posts
    ├── new-post.php        → Create new post
    ├── edit-post.php       → Edit existing post
    ├── delete-post.php     → Delete post
    ├── sidebar.php         → Sidebar include
    └── logout.php          → Logout handler
```

---

## ⚙️ Setup Instructions

### Step 1 — Database banao
phpMyAdmin ya MySQL terminal mein yeh file run karo:
```sql
source setup.sql
```

### Step 2 — DB Config karo
`includes/db.php` file banao aur yeh fill karo:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_mysql_username');
define('DB_PASS', 'your_mysql_password');
define('DB_NAME', 'blog_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
```
> ⚠️ `db.php` `.gitignore` mein hai — yeh file Git mein **push mat karna**

### Step 3 — Server pe upload karo
`blog/` folder apne `htdocs` ya `public_html` mein rakh do.

### Step 4 — Admin account banao
Browser mein jao:
```
yourdomain.com/mks75@2062/signup.php
```

### Step 5 — Login karo
```
yourdomain.com/mks75@2062/login.php
```
> 🔖 Yeh URL bookmark kar lo — yeh tumhara secret admin URL hai!

### Step 6 — Blogging shuru karo! 🎉
Dashboard se posts create, edit aur delete kar sakte ho.

---

## 🛠️ Requirements

| Tool | Version |
|------|---------|
| PHP | 8.0+ |
| MySQL | 5.7+ |
| Apache | mod_rewrite enabled |

---

## 📌 Notes

- `uploads/` folder mein user images save hoti hain — Git mein sirf `.gitkeep` hai
- Admin panel ka URL secret rakho
- Production mein strong DB password use karo

---

## 📄 License

This project is open source under the [MIT License](LICENSE).