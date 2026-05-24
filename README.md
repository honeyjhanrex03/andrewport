# Rovic Andrew V. Bungalan - Professional Portfolio

A dynamic, fully-featured, and highly responsive personal portfolio website built with raw PHP, MySQL, and modern frontend technologies. This platform not only showcases professional skills, projects, and certificates but also includes a comprehensive Administrative Dashboard for real-time content management.

## 🚀 Features

### Public Facing Interface
- **Premium Dark Mode Design**: A visually striking, glassmorphic UI utilizing curated color palettes (`#1e212b` & `#ff6b57`) and fluid micro-animations.
- **Dynamic Content**: All sections (About, Skills, Services, Portfolio, Certificates) are populated directly from the database.
- **Automated Contact System**: Integrated with the **Brevo SMTP API**. When a visitor sends a message:
  - An instant auto-responder email is sent to the visitor confirming receipt.
  - A formatted notification email is sent to the admin.
  - The message is securely logged in the database.
- **Fully Responsive**: Optimized for desktops, tablets, and mobile devices with interactive sliding navbars.

### Administrative Dashboard (`/admin`)
- **Secure Authentication**: Password-hashed login system protecting the admin routes.
- **Content Management System (CMS)**:
  - **Profile Details**: Update core identity, contact information, social links, and upload a custom CV.
  - **Skills & Services**: Full CRUD capabilities to manage skill proficiency levels and service offerings.
  - **Project Gallery**: Upload and manage portfolio projects and professional certificates.
  - **Message Inbox**: Read and delete contact inquiries securely.
- **Real-Time Analytics**: Visual charts (via Chart.js) mapping page views and profile interactions over the last 7 days.
- **Global Alerts**: Integrated **SweetAlert2** for beautiful, non-intrusive success/error popups across the entire platform.

## 🛠️ Technology Stack
*   **Backend**: PHP 8+ (PDO for secure database interactions)
*   **Database**: MySQL
*   **Frontend**: HTML5, Vanilla CSS3, Vanilla JavaScript
*   **Frameworks/Libraries**: Bootstrap 5.3 (Grid & Utilities), FontAwesome 6.4 (Icons), SweetAlert2, Chart.js
*   **API Integrations**: Brevo Email API

## 📂 Project Structure

```text
/
├── admin/                  # Secure Administrative Dashboard & CMS logic
│   ├── includes/           # Admin header, footer, and sidebar partials
│   ├── index.php           # Admin analytics dashboard
│   ├── login.php           # Secure authentication portal
│   └── ...                 # Content management pages (skills, profile, etc.)
├── assets/                 # Public static assets
│   ├── css/                # Global stylesheets (style.css)
│   ├── images/             # Uploaded media and static graphics
│   └── js/                 # Public interactivity scripts
├── config/                 # Environment configuration
│   └── database.php        # Environment-aware PDO database connection
├── database/               # SQL architecture files
├── includes/               # Public header and footer partials
├── about.php               # Public About section
├── certificates.php        # Public Certificates gallery
├── contact.php             # Public Contact form (Brevo API logic)
├── index.php               # Public Landing page
├── portfolio.php           # Public Projects gallery
├── services.php            # Public Services section
└── skills.php              # Public Skills section
```

## ⚙️ Setup & Installation

1. **Environment Setup**: Ensure you have a local server environment like XAMPP or Laragon running Apache and MySQL.
2. **Database Import**: 
   - Create a new MySQL database named `andrew_portfolio_db`.
   - Import the provided SQL schema (`database/andrew_portfolio_db.sql`) to generate the tables and initial seed data.
3. **Configuration**:
   - Navigate to `config/database.php`.
   - The connection is configured to be environment-aware. It will default to `localhost` with the `root` user for local development. Modify credentials if necessary.
4. **API Keys**:
   - The `contact.php` file requires a Brevo API key to send emails. Ensure your API key is correctly inserted into the cURL configuration block.

## 🔒 Security Practices Implemented
*   **Prepared Statements**: Complete usage of PHP PDO prepared statements to prevent SQL injection.
*   **XSS Protection**: All user-generated outputs are sanitized using `htmlspecialchars()`.
*   **Password Hashing**: Admin passwords are encrypted using `password_hash()` and verified via `password_verify()`.
*   **PRG Pattern**: The contact form implements the Post/Redirect/Get pattern to prevent duplicate email dispatches upon page refresh.
*   **Cache-Busting**: Asset URLs append a dynamic timestamp parameter (`?v=time()`) to enforce immediate cache renewal on production environments.
