=== WPSSO FAQ Manager ===
Plugin Name: WPSSO FAQ Manager
Plugin Slug: wpsso-faq
Text Domain: wpsso-faq
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-faq/assets/
Tags: faq, faqpage, question, shortcode, schema, schema.org, answer, google, rich results, faqs, faq page
Contributors: jsmoriss
Requires PHP: 7.2
Requires At Least: 5.2
Tested Up To: 5.8.2
Stable Tag: 3.11.2

Create FAQ and Question / Answer Pages with optional shortcodes to include FAQs and Questions / Answers in your content.

== Description ==

<!-- about -->

The WPSSO FAQ Manager allows you to easily manage Questions / Answers from a standard WordPress editing page, and categorize your Questions / Answers into FAQ categories.

You can use the standard WordPress FAQ category archive and question page templates and URLs, and/or use the <code>&#91;faq&#93;</code> and <code>&#91;question&#93;</code> shortcode(s) in your content.

The WPSSO FAQ Manager add-on uses a 'question' custom post type and 'faq_category' custom taxonomy to manage Questions / Answers and FAQs. If you need to adjust the default FAQ category archive page layout, see the [WordPress Theme Handbook &gt; Custom Taxonomy section](https://developer.wordpress.org/themes/template-files-section/taxonomy-templates/#custom-taxonomy) for details on creating a new FAQ category archive page template based on your current theme templates.

<!-- /about -->

<h3>WPSSO Core Required</h3>

WPSSO FAQ Manager (WPSSO FAQ) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO FAQ Manager add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/install-the-plugin/).
* [Uninstall the WPSSO FAQ Manager add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/uninstall-the-plugin/).

== Frequently Asked Questions ==

<h3 class="top">Frequently Asked Questions</h3>

* [How are questions sorted in the FAQ shortcode?](https://wpsso.com/docs/plugins/wpsso-faq/faqs/how-are-questions-sorted-in-the-faq-shortcode/)
* [How do I create a Schema FAQPage?](https://wpsso.com/docs/plugins/wpsso-faq/faqs/how-do-i-create-a-schema-faqpage/)

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

<h3>Standard Edition Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-faq/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-faq/)

<h3>Development Version Updates</h3>

<p><strong>WPSSO Core Premium customers have access to development, alpha, beta, and release candidate version updates:</strong></p>

<p>Under the SSO &gt; Update Manager settings page, select the "Development and Up" (for example) version filter for the WPSSO Core plugin and/or its add-ons. Save the plugin settings and click the "Check for Plugin Updates" button to fetch the latest version information. When new development versions are available, they will automatically appear under your WordPress Dashboard &gt; Updates page. You can always reselect the "Stable / Production" version filter at any time to reinstall the latest stable version.</p>

<h3>Changelog / Release Notes</h3>

**Version 3.11.2 (2021/11/16)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Refactored the `SucomAddOn->get_missing_requirements()` method.
* **Requires At Least**
	* PHP v7.2.
	* WordPress v5.2.
	* WPSSO Core v9.8.0.

**Version 3.11.1 (2021/10/06)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Standardized `get_table_rows()` calls and filters in 'submenu' and 'sitesubmenu' classes.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.1.0.

**Version 3.11.0 (2021/09/24)**

Maintenance release for WPSSO Core v9.0.0.

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v9.0.0.

**Version 3.10.1 (2021/06/16)**

* **New Features**
	* None.
* **Improvements**
	* Minor update for FAQ Manager settings page translation strings.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v5.0.
	* WPSSO Core v8.34.0.

**Version 3.10.0 (2021/05/15)**

* **New Features**
	* None.
* **Improvements**
	* Added new options in the SSO &gt; FAQ Settings page:
		* FAQ Shortcode Title Heading
		* Question Shortcode Title Heading
	* Added new 'heading' and 'title' shortcode attributes:
		* See the [FAQ shortcode documentation](https://wpsso.com/docs/plugins/wpsso-faq/notes/faq-shortcode/).
		* See the [Question shortcode documentation](https://wpsso.com/docs/plugins/wpsso-faq/notes/question-shortcode/).
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.28.2.

**Version 3.9.1 (2021/02/25)**

* **New Features**
	* None.
* **Improvements**
	* Updated the banners and icons of WPSSO Core and its add-ons.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.25.2.

**Version 3.9.0 (2020/12/11)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated the `get_posts_mods()` module object call for WPSSO Core v8.17.0.
* **Requires At Least**
	* PHP v7.0.
	* WordPress v4.5.
	* WPSSO Core v8.17.0.

**Version 3.8.0 (2020/12/02)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Included the `$addon` argument for library class constructors.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.5.
	* WPSSO Core v8.16.0.

**Version 3.7.1 (2020/10/17)**

* **New Features**
	* None.
* **Improvements**
	* Refactored the add-on class to extend a new WpssoAddOn abstract class.
* **Bugfixes**
	* Fixed backwards compatibility with older 'init_objects' and 'init_plugin' action arguments.
* **Developer Notes**
	* Added a new WpssoAddOn class in lib/abstracts/add-on.php.
	* Added a new SucomAddOn class in lib/abstracts/com/add-on.php.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.4.
	* WPSSO Core v8.13.0.

== Upgrade Notice ==

= 3.11.2 =

(2021/11/16) Refactored the `SucomAddOn->get_missing_requirements()` method.

= 3.11.1 =

(2021/10/06) Standardized `get_table_rows()` calls and filters in 'submenu' and 'sitesubmenu' classes.

= 3.11.0 =

(2021/09/24) Maintenance release for WPSSO Core v9.0.0.

= 3.10.1 =

(2021/06/16) Minor update for FAQ Manager settings page translation strings.

= 3.10.0 =

(2021/05/15) Added new options in the SSO &gt; FAQ Settings page. Added new 'heading' and 'title' shortcode attributes.

= 3.9.1 =

(2021/02/25) Updated the banners and icons of WPSSO Core and its add-ons.

= 3.9.0 =

(2020/12/11) Updated the `get_posts_mods()` module object call for WPSSO Core v8.17.0.

= 3.8.0 =

(2020/12/02) Included the `$addon` argument for library class constructors.

= 3.7.1 =

(2020/10/17) Refactored the add-on class to extend a new WpssoAddOn abstract class.

