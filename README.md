# Media Slug Prefix Control

**Prevent slug conflicts between WordPress Pages and Media Attachments by adding a custom prefix to attachment URLs.**

![License](https://img.shields.io/badge/license-GPLv2-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.6%2B-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)

## 🛑 The Problem

By default, when you upload an image named `contact.jpg` to WordPress, the system creates an attachment page with the slug `/contact/`.

If you later try to create a Page named **"Contact"**, WordPress will force the URL to become `/contact-2/` because `/contact/` is already taken by the image. This is frustrating for site architecture and SEO.

## ✅ The Solution

**Media Slug Prefix Control** solves this by virtually moving all media attachments to a sub-path (e.g., `/media/` or `/assets/`).

* **Before:** `https://example.com/contact/` (Image takes the slot)
* **After:** `https://example.com/media/contact/` (Image moves here)

Now, the slug `https://example.com/contact/` is free to be used by your actual Pages or Posts.

## ✨ Features

* **Custom Prefix:** Define any base slug you want via the admin panel (e.g., `media`, `assets`, `images`).
* **Conflict Resolver:** Automatically allows Pages/Posts to "steal" the slug back from attachments if the attachment is using the custom prefix.
* **SEO Friendly:** Automatically adds **301 Redirects** from old attachment URLs to the new prefixed URLs.
* **Native Integration:** Adds a setting field directly to **Settings > Permalinks**.
* **Retroactive:** Works for both new uploads and existing images.

## 🚀 Installation

### Manual Installation
1. Download the repository as a ZIP file.
2. Go to your WordPress Dashboard: **Plugins > Add New > Upload Plugin**.
3. Upload the ZIP and activate the plugin.

### via FTP/Git
1. Clone this repository into your `wp-content/plugins/` directory:
   ```bash
   git clone [https://github.com/seu-usuario/media-slug-prefix-control.git](https://github.com/seu-usuario/media-slug-prefix-control.git)
