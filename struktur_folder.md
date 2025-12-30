# Struktur Folder

```
/siakad-texmaco/
├── app/
│ ├── Controllers/ # Logic pengatur alur (C)
│ │ ├── AuthController.php
│ │ ├── Controller.php
│ │ ├── HomeController.php
│ │ └── ProductController.php
│ ├── Core/ # Base classes (Database Wrapper, Router, Middleware)
│ │ ├── Database.php
│ │ ├── Middleware.php
│ │ └── Route.php
│ └── Models/ # Interaksi Database (M)
│ ├── Model.php
│ ├── Product.php
│ └── User.php
├── config/
│ ├── config.php
│ └── routes.php
├── public/
│ └── assets/
│ ├── css/
│ └── img/ # Assets gambar (AdminLTE, dll)
├── views/ # Tampilan (V)
│ ├── auth/
│ │ └── login.php
│ ├── components/
│ │ ├── header.php
│ │ └── navbar.php
│ ├── layouts/
│ │ └── main.php
│ ├── product/
│ │ ├── create.php
│ │ ├── edit.php
│ │ └── index.php
│ └── dashboard.php
├── create_admin.php
├── db_siakad_texmaco.sql
├── index.php
└── struktur_folder.md
```
