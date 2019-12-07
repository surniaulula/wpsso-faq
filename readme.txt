=== FAQ Manager | WPSSO Add-on ===
Plugin Name: WPSSO FAQ Manager
Plugin Slug: wpsso-faq
Text Domain: wpsso-faq
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-faq/assets/
Tags: faq, faqpage, question, answer, schema, schema.org, google, rich results, faq page
Contributors: jsmoriss
Requires PHP: 5.5
Requires At Least: 3.9
Tested Up To: 5.3
Stable Tag: 1.0.3

Manage FAQ categories with Question and Answer pages.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-faq/assets/icon-256x256.png"></p>

Adds a FAQs admin menu item to manage FAQ categories with Question and Answer pages.

Note that this add-on manages FAQ categories, along with Question and Answer pages -- it does not create meta tags or Schema markup in webpages. If you need <strong>Schema FAQPage</strong> markup in JSON-LD format for your webpages, you will need the [WPSSO Schema JSON-LD Markup (Premium) add-on](https://wpsso.com/extend/plugins/wpsso-schema-json-ld/) to create that markup.

Optionally, see the [Theme Handbook &gt; Custom Taxonomy section](https://developer.wordpress.org/themes/template-files-section/taxonomy-templates/#custom-taxonomy) for details on creating a different template for FAQ category archives using your existing theme archive template.

<h3>WPSSO Core Plugin Required</h3>

WPSSO FAQ Manager (aka WPSSO FAQ) is an add-on for the [WPSSO Core plugin](https://wordpress.org/plugins/wpsso/).

== Installation ==

<h3 class="top">Install and Uninstall</h3>

* [Install the WPSSO FAQ Add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/install-the-plugin/)
* [Uninstall the WPSSO FAQ Add-on](https://wpsso.com/docs/plugins/wpsso-faq/installation/uninstall-the-plugin/)

== Frequently Asked Questions ==

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

**Version 1.1.0-rc.1 (2019/12/07)**

* **New Features**
	* None.
* **Improvements**
	* Added a `flush_rewrite_rules()` function call on plugin activation / deactivation to refresh the 'faq_category' taxonomy and 'question' post type URLs.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.5.
	* WordPress v3.9.
	* WPSSO Core v6.15.0-rc.1.

**Version 1.0.3 (2019/11/23)**

* **New Features**
	* None.
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Updated `WpssoFaqRegister->activate_plugin()` for the new WpssoUtilReg class in WPSSO Core v6.13.1.

== Upgrade Notice ==

= 1.1.0-rc.1 =

(2019/12/07) Added a `flush_rewrite_rules()` function call on plugin activation / deactivation to refresh the 'faq_category' taxonomy and 'question' post type URLs.

= 1.0.3 =

(2019/11/23) Update for the new WpssoUtilReg class in WPSSO Core v6.13.1.

