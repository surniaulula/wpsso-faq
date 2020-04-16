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
Tested Up To: 5.4
Stable Tag: 2.5.0

Create FAQ and Question / Answer Pages with Optional Shortcodes to Include FAQs and Questions in your Content.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-faq/assets/icon-256x256.png"></p>

Adds a new WordPress FAQs admin menu to create and manage Question / Answer pages and FAQ categories.

Includes `[faq]` and `[question]` shortcodes to include FAQs or Questions in your post/page content.

Do you have a feature suggestion?

[Post your suggestion in the WPSSO FAQ Manager support forum.](https://wordpress.org/support/plugin/wpsso-faq/)

**Optional:** If you need <strong>Schema FAQPage</strong> markup for the FAQ shortcode or FAQ categories you create, you will also need the [WPSSO Schema JSON-LD Markup add-on](https://wordpress.org/plugins/wpsso-schema-json-ld/) to generate the Schema FAQPage markup. The WPSSO FAQ Manager add-on manages Question pages and FAQ categories - it does not create Schema markup or meta tags.

**Optional:** See the [WordPress Theme Handbook &gt; Custom Taxonomy section](https://developer.wordpress.org/themes/template-files-section/taxonomy-templates/#custom-taxonomy) for details on creating an archive template for FAQ categories based on your theme archive template.

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

**Version 3.0.0-rc.1 (2020/04/16)**

* **New Features**
	* Added a new SSO &gt; FAQ Settings page:
		* Shortcode Defaults
			* Click Question to Show Answer
			* Question Answer Format
		* Add-on Settings
			* Disable FAQ and Question Pages
* **Improvements**
	* None.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v6.29.0-rc.1.

**Version 2.5.0 (2020/04/06)**

* **New Features**
	* None.
* **Improvements**
	* Updated "Requires At Least" to WordPress v4.2.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Refactored WPSSO Core active and minimum version dependency checks.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.2.
	* WPSSO Core v6.28.0.

**Version 2.4.0 (2020/03/27)**

* **New Features**
	* None.
* **Improvements**
	* Added disabling of the Open Graph Type option for FAQ and Question pages.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.0.
	* WPSSO Core v6.27.1.

**Version 2.3.0 (2020/03/14)**

* **New Features**
	* None.
* **Improvements**
	* Added a filter hook to expand the 'faq' and 'question' shortcodes when the "Use WordPress Content Filters" option is disabled.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.6.
	* WordPress v4.0.
	* WPSSO Core v6.25.0.

== Upgrade Notice ==

= 3.0.0-rc.1 =

(2020/04/16) Added a new SSO &gt; FAQ Settings page.

= 2.5.0 =

(2020/04/06) Updated "Requires At Least" to WordPress v4.2. Refactored WPSSO Core active and minimum version dependency checks.

= 2.4.0 =

(2020/03/27) Added disabling of the Open Graph Type option for FAQ and Question pages.

= 2.3.0 =

(2020/03/14) Added a filter hook to expand the 'faq' and 'question' shortcodes when the "Use WordPress Content Filters" option is disabled.

