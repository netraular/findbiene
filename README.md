# Biene Hunt! (HackUPC 2025) 🐝📸

[![Hackathon](https://img.shields.io/badge/Event-HackUPC%202025-blueviolet)](https://hackupc.com/)
[![Devpost](https://img.shields.io/badge/Devpost-Submission-003E54?logo=devpost)](https://devpost.com/software/find-biene) <!-- Added Devpost Badge -->

A fun, interactive web application built during the **HackUPC 2025** hackathon. Biene Hunt allowed participants to upload and view photos of the event mascot, "Biene," creating a collaborative scavenger hunt experience across the venue.

The project was a notable success during the event, used by **over 200 unique participants** and receiving positive feedback from attendees and organizers for enhancing the hackathon atmosphere.

**Live Demo:**
*   **Main Site:** [https://findbiene.raular.com/](https://findbiene.raular.com/)
*   **Share Page (QR Code):** [https://findbiene.raular.com/share](https://findbiene.raular.com/share)

---

## 📸 Project Showcase

| Main Page                                     | Gallery View                                      | Meeting the Organizers                     |
| :--------------------------------------------: | :-----------------------------------------------: | :--------------------------------------: |
| ![Biene Hunt Main Page](/images/hero_banner.png) | ![Biene Hunt Gallery](/images/gallery.png) | ![Biene Hunt Organizers](/images/organizers.jpg) |
*<p align="center">Showcasing the user interface and a moment with the HackUPC team.</p>*

---

## ✨ Features

*   **📸 Image Upload:** Simple, anonymous photo uploads of Biene sightings.
*   **🖼️ Gallery View:** Displays the latest sightings in a paginated, visually appealing gallery (Polaroid effect!).
*   **🚀 Fast & Efficient:** Client-side image compression before upload to save bandwidth and storage.
*   **📱 Responsive Design:** Fully functional across desktop and mobile devices.
*   **🔗 Shareable QR Code:** Dedicated page with a QR code for easy sharing (used for posters at the event).
*   **🕶️ Dark Mode Theme:** Custom dark theme with subtle background animations (ghosts!).
*   **🛡️ Moderation:** Hidden login panel for administrators (event organizers/project owner) to delete inappropriate or duplicate images.
*   **💾 Self-Hosted:** Runs on a home lab setup, demonstrating deployment outside typical cloud providers.

---

## 🔧 Tech Stack

*   **Backend:** Laravel (PHP Framework)
*   **Frontend:** Laravel Blade, Bootstrap 5, Vanilla JavaScript, [browser-image-compression](https://github.com/Donaldcwl/browser-image-compression)
*   **Database:** SQL Database (e.g., MySQL/MariaDB)
*   **Web Server:** Nginx / Apache (Configured for Laravel)
*   **Hosting:** Home Lab

---

## ⚙️ Setup and Installation (For Development/Deployment)

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/netraular/findbiene.git
    cd findbiene
    ```
2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```
3.  **Install Node.js Dependencies:**
    ```bash
    npm install
    ```
4.  **Compile Frontend Assets:**
    ```bash
    npm run build
    ```
    *(Or `npm run dev` for development)*
5.  **Environment Configuration:**
    *   Copy the example environment file: `cp .env.example .env`
    *   Generate the application key: `php artisan key:generate`
    *   Configure your database credentials (`DB_*` variables) and any other necessary settings (like `APP_URL`) in the `.env` file.
6.  **Database Migration:**
    ```bash
    php artisan migrate
    ```
    *(You might need to create the database manually first)*
7.  **Storage Link:**
    *   Create the symbolic link for public file access:
        ```bash
        php artisan storage:link
        ```
8.  **Permissions:**
    *   Ensure the `storage` and `bootstrap/cache` directories are writable by the web server.
9.  **Web Server Configuration:**
    *   Configure your web server (Nginx/Apache) to point the document root to the `public` directory of the project. Ensure URL rewriting is enabled (standard Laravel setup).

---

## 🚀 Usage

*   **Public Users:**
    *   Visit the main page: [https://findbiene.raular.com/](https://findbiene.raular.com/)
    *   Browse the gallery of recent Biene sightings.
    *   Click "Upload Biene Photo!", select an image file (it will be compressed if large), and confirm the upload.
    *   Visit the `/share` page ([https://findbiene.raular.com/share](https://findbiene.raular.com/share)) to get the QR code for sharing.
*   **Admin:**
    *   Access the designated admin login route (this route is intentionally not explicitly defined here for security).
    *   Log in with the admin credentials.
    *   View the image gallery with delete buttons visible next to each image.
    *   Click the delete button (and confirm) to remove an image.

---

## 🎉 Hackathon Story

This project was born out of the HackUPC 2025 hackathon. The idea was to create a fun, interactive element related to the event's mascot, Biene. By allowing participants to share their findings, it aimed to foster a sense of community and add a playful layer to the intense coding environment. The positive reception and high usage were fantastic validation of this goal.

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check [issues page](https://github.com/netraular/findbiene/issues).

---

## 📜 License

This project is open-sourced under the [MIT License](LICENSE).

---

Created with ❤️ during HackUPC 2025 by [raular](https://raular.com)