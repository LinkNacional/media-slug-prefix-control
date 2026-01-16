=== Media Slug Prefix Control ===
Contributors: Link Nacional
Tags: media, attachment, slug, permalink, seo
Requires at least: 5.6
Tested up to: 6.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Fix permalink conflicts between Pages/Posts and Media Attachments by adding a custom prefix to attachment URLs.

== Description ==

Have you ever tried to create a page named "Contact" (/contact) but WordPress renamed it to "contact-2" because you uploaded an image named "contact.jpg"?

This happens because WordPress reserves the slug for the attachment page. 

**Media Slug Prefix Control** solves this problem by allowing you to define a custom base prefix for all your media attachment URLs (e.g., `yoursite.com/media/image-name`).

**Features:**

* **Custom Prefix:** Add a custom base (like `/assets/`, `/media/`, or `/images/`) to your attachment URLs.
* **Conflict Resolver:** Allows you to create Pages and Posts with the exact same name as your images files.
* **Auto Redirect:** Automatically redirects old attachment URLs to the new structure (SEO friendly 301 redirect).
* **Native Integration:** Adds the setting directly to "Settings > Permalinks".

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/media-slug-prefix-control` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to **Settings > Permalinks**.
4. Scroll down to the "Optional" section.
5. Enter your desired prefix in the "Media Base" field (e.g., `media`).
6. Click **Save Changes**.

== Frequently Asked Questions ==

= Does this work with existing images? =
Yes! It applies to all media library items, old and new.

= Will my old links break? =
No. The plugin includes an automatic 301 redirect. If someone accesses the old URL, they are sent to the new one.

== Changelog ==

= 1.0.0 =
* Initial release.
