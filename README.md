# ☕ Rim Café Website

A premium, glassmorphism-themed café management system. This application features a high-end UI, a full administrative suite, and a customer balance/ordering workflow.

---

## ✨ Features

### 🛠️ Administrative Suite (Inside `/admin`)
* **Product Management:** Add, edit, and delete menu items.
* **Order Tracking:** View and export customer orders to Excel/CSV.
* **Inbox System:** View and manage customer messages from the contact form.
* **Authentication:** Secure Login, Signup, and Forgot Password modules.

### ☕ Customer Experience
* **Premium UI:** Modern translucent design with gold-themed accents.
* **Wallet System:** Dedicated `recharge.php` to manage and top-up user balances.
* **Seamless Ordering:** Full checkout process from menu to order confirmation.
* **Responsive Design:** Optimized for mobile, tablet, and desktop screens.

---

## 🚀 Tech Stack
* **Backend:** PHP 8.x, MySQL
* **Frontend:** HTML5, CSS3, FontAwesome 6, Google Fonts
* **Logic:** Procedural PHP for form processing and database CRUD operations

---

## 📂 Project Structure

* **`/admin`** – Core portal including `admin_dashboard.php`, `login.php`, and product management.
* **`/assets`** – Global CSS, images,
* **`/includes`** – Connection logic (`db_connect.php`) and shared UI (`header.php`, `footer.php`).
* **Root Files** – Customer-facing pages like `index.php`, `menu.php`, and `recharge.php`.
* **Process Files** – Backend handlers for orders, contacts, and recharges.

---

## ⚙️ Quick Setup

1. **Clone:**
   ```bash
   git clone [https://github.com/yourusername/rim_cafee_website.git](https://github.com/yourusername/rim_cafee_website.git)