=== WPSSO Schema FAQ Page Manager ===
Plugin Name: WPSSO Schema FAQ Page Manager
Plugin Slug: wpsso-faq
Text Domain: wpsso-faq
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-faq/assets/
Tags: schema, faqpage, faq, google, rich results
Contributors: jsmoriss
Requires Plugins: wpsso
Requires PHP: 7.4.33
Requires At Least: 5.9
Tested Up To: 6.8.2
Stable Tag: 7.1.0

Create FAQ and Question / Answer Pages with optional shortcodes to include FAQs and Questions / Answers in your content.

== Description ==

<!-- about -->

The WPSSO Schema FAQ Page Manager allows you to easily manage questions / answers from a standard WordPress editing page, and combine your questions / answers into FAQ pages.

You can use the standard WordPress FAQ archive and question page templates and/or use the <code>&#91;faq&#93;</code> and <code>&#91;question&#93;</code> shortcode(s) in your content.

<!-- /about -->

<h3>WPSSO Core Required</h3>

WPSSO Schema FAQ Page Manager is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/), which creates extensive and complete structured data to present your content at its best for social sites and search results – no matter how URLs are shared, reshared, messaged, posted, embedded, or crawled.

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO Schema FAQ Page Manager add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/install-the-plugin/).
* [Uninstall the WPSSO Schema FAQ Page Manager add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

<h3 class="top">Frequently Asked Questions</h3>

* [How are questions sorted in the FAQ shortcode?](https://wpsso.com/docs/plugins/wpsso-faq/faqs/how-are-questions-sorted-in-the-faq-shortcode/)
* [How do I create a Schema FAQPage?](https://wpsso.com/docs/plugins/wpsso-faq/faqs/how-do-i-create-a-schema-faqpage/)

<h3>Notes and Documentation</h3>

* The [FAQ Shortcode](https://wpsso.com/docs/plugins/wpsso-faq/notes/faq-shortcode/)
* The [Question Shortcode](https://wpsso.com/docs/plugins/wpsso-faq/notes/question-shortcode/)

== Screenshots ==

01. The FAQ editing page.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes and/or incompatible API changes (ie. breaking changes).
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-faq/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-faq/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium edition customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<p><strong>WPSSO Core Standard edition users (ie. the plugin hosted on WordPress.org) have access to <a href="https://wordpress.org/plugins/wpsso-faq/advanced/">the latest development version under the Advanced Options section</a>.</strong></p>

<h3>Changelog / Release Notes</h3>

**Version 7.1.0 (2025/07/02)**

* **New Features**
	* None.
* **Improvements**
	* Renamed "FAQ Categories" to "FAQ Pages".
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.4.33.
	* WordPress v5.9.
	* WPSSO Core v21.4.1.

**Version 7.0.0 (2025/06/14)**

* **New Features**
	* Renamed the add-on to "WPSSO Schema PAQ Page Manager".
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.4.33.
	* WordPress v5.9.
	* WPSSO Core v20.0.0.

**Version 6.0.0 (2025/05/28)**

* **New Features**
	* None.
* **Improvements**
	* Renamed "FAQ Groups" to "FAQ Categories".
	* Added new options under the SSO &gt; FAQ Settings &gt; Add-on Settings tab:
		* Disable FAQ Categories
		* Disable FAQ Tags
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.4.33.
	* WordPress v5.9.
	* WPSSO Core v18.21.0.

**Version 5.4.1 (2025/03/08)**

* **New Features**
	* None.
* **Improvements**
	* Updated admin menu priority.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.4.33.
	* WordPress v5.9.
	* WPSSO Core v18.20.0.

**Version 5.4.0 (2024/09/07)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Renamed and moved the `WpssoFaqPost` class to `WpssoFaqIntegAdminPost`.
* **Requires At Least**
	* PHP v7.4.33.
	* WordPress v5.9.
	* WPSSO Core v18.10.0.

**Version 5.3.0 (2024/08/25)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Changed the main instantiation action hook from 'init_objects' to 'init_objects_preloader'.
* **Requires At Least**
	* PHP v7.2.34.
	* WordPress v5.8.
	* WPSSO Core v18.5.0.

== Upgrade Notice ==

= 7.1.0 =

(2025/07/02) Renamed "FAQ Categories" to "FAQ Pages".

= 7.0.0 =

(2025/06/14) Renamed the add-on to "WPSSO Schema PAQ Page Manager".

= 6.0.0 =

(2025/05/28) Renamed "FAQ Groups" to "FAQ Categories". Added new "Disable FAQ Categories" and "Disable FAQ Tags" options.

= 5.4.1 =

(2025/03/08) Updated admin menu priority.

= 5.4.0 =

(2024/09/07) Renamed and moved the `WpssoFaqPost` class to `WpssoFaqIntegAdminPost`.

= 5.3.0 =

(2024/08/25) Changed the main instantiation action hook from 'init_objects' to 'init_objects_preloader'.

