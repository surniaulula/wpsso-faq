=== FAQ Manager | WPSSO Add-on ===
Plugin Name: WPSSO FAQ Manager
Plugin Slug: wpsso-faq
Text Domain: wpsso-faq
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-faq/assets/
Tags: faq, faqpage, question, shortcode, schema, schema.org, answer, google, rich results, faqs, faq page
Contributors: jsmoriss
Requires PHP: 5.6
Requires At Least: 4.2
Tested Up To: 5.5
Stable Tag: 3.6.0

Create FAQ and Question / Answer Pages with Optional Shortcodes to Include FAQs and Questions / Answers in your Content.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-faq/assets/icon-256x256.png"></p>

**A better way to manage Questions / Answers than using a FAQ editor block:**

FAQ editor blocks from other plugins force you to re-update your Post / Page content to make any change (ie. adding / removing questions, updating answers, etc.).

The WPSSO FAQ Manager allows you to easily manage Questions / Answers from a standard WordPress Question editing page. You can also categorize your Questions / Answers into FAQ categories, just like standard WordPress Posts.

Use the standard WordPress FAQ category archive and question pages, and/or use `[faq]` and `[question]` shortcode(s) in your Post / Page content -- both will automatically reflect any change your make to your Questions / Answers or FAQ categories (ie. adding / removing questions, updating answers, etc.).

**Optional:**

If you need **Schema FAQPage** markup for the **FAQ shortcode** or **FAQ categories** you create, you will also need the [WPSSO Schema JSON-LD Markup add-on](https://wordpress.org/plugins/wpsso-schema-json-ld/) to generate the Schema FAQPage markup. The WPSSO FAQ Manager add-on manages Question / Answer pages and FAQ categories - it does not create Schema markup or meta tags.

**Optional:**

If you need to adjust the default FAQ category archive page layout, see the [WordPress Theme Handbook &gt; Custom Taxonomy section](https://developer.wordpress.org/themes/template-files-section/taxonomy-templates/#custom-taxonomy) for details on creating a new FAQ category archive page template based on your current theme archive page template.

<h3>WPSSO Core Plugin Required</h3>

WPSSO FAQ Manager (aka WPSSO FAQ) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO FAQ Manager add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/install-the-plugin/).
* [Uninstall the WPSSO FAQ Manager add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

<h3 class="top">Frequently Asked Questions</h3>

* [How are questions sorted in the FAQ shortcode?](https://wpsso.com/docs/plugins/wpsso-faq/faqs/how-are-questions-sorted-in-the-faq-shortcode/)

<h3>Notes and Documentation</h3>

* The [FAQ Shortcode](https://wpsso.com/docs/plugins/wpsso-faq/notes/faq-shortcode/)
* The [Question Shortcode](https://wpsso.com/docs/plugins/wpsso-faq/notes/question-shortcode/)

== Screenshots ==

01. The FAQs menu allows you to manage individual FAQs and their categories.
02. The FAQ editing page allows you to create and edit individual FAQs.

== Changelog ==

<h3 class="top">Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Standard Version Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-faq/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-faq/)

<h3>Changelog / Release Notes</h3>

**Version 3.6.1-dev.1 (TBD)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated `WpssoPost->add_attached()` to `WpssoPost::add_attached()` for WPSSO Core v8.4.0.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v8.3.0.

**Version 3.6.0 (2020/09/05)**

* **New Features**
	* None.
* **Improvements**
	* Added support for the new 'bc_category_tax_slug' filter in WPSSO BC v3.0.0.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added a new lib/filters-messages.php library file.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v8.3.0.

== Upgrade Notice ==

= 3.6.0 =

(2020/09/05) Added support for the new 'bc_category_tax_slug' filter in WPSSO BC v3.0.0.

