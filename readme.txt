=== FAQ Manager | WPSSO Add-on ===
Plugin Name: WPSSO FAQ Manager
Plugin Slug: wpsso-faq
Text Domain: wpsso-faq
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-faq/assets/
Tags: shortcodes, faq, faqpage, question, answer, schema, schema.org, google, rich results, faqs, faq page
Contributors: jsmoriss
Requires PHP: 5.5
Requires At Least: 4.0
Tested Up To: 5.3.2
Stable Tag: 2.1.0

Manages Question / Answer pages and FAQ categories, along with offering shortcodes to include FAQs in your content.

== Description ==

<p style="margin:0;"><img class="readme-icon" src="https://surniaulula.github.io/wpsso-faq/assets/icon-256x256.png"></p>

Adds a new FAQs admin menu to create and manager Question pages and FAQ categories.

Includes `[faq]` and `[question]` shortcodes to include FAQs or Questions in your post/page content.

Do you have a feature suggestion?

[Post your suggestion in the WPSSO FAQ support forum.](https://wordpress.org/support/plugin/wpsso-faq/)

**Optional:** This add-on manages Question pages and FAQ categories - it does not create meta tags or Schema markup. If you need <strong>Schema FAQPage</strong> markup for FAQ shortcodes or FAQ categories, you will need the [WPSSO Schema JSON-LD Markup Premium add-on](https://wpsso.com/extend/plugins/wpsso-schema-json-ld/) to create the Schema FAQPage markup.

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

**Version 2.2.0-rc.1 (2020/02/26)**

* **New Features**
	* None.
* **Improvements**
	* Added Schema markup in the [[faq]] and [[question]] shortcode output for the WPSSO JSON Premium add-on.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.5.
	* WordPress v4.0.
	* WPSSO Core v6.22.0-rc.1.

**Version 2.1.0 (2020/02/07)**

* **New Features**
	* None.
* **Improvements**
	* Added the FAQ category title to the [[faq]] shortcode output.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.5.
	* WordPress v4.0.
	* WPSSO Core v6.19.0.

**Version 2.0.1 (2019/12/22)**

* **New Features**
	* None.
* **Improvements**
	* Renamed the "Category" label to "FAQ Category".
	* Disabled the SSO &gt; Schema Markup &gt; Type by Post Type for Questions option.
	* Disabled the SSO &gt; Schema Markup &gt; Type by Taxonomy for FAQ Categories option.
* **Bugfixes**
	* None.
* **Developer Notes**
	* None.
* **Requires At Least**
	* PHP v5.5.
	* WordPress v4.0.
	* WPSSO Core v6.16.2.

**Version 2.0.0 (2019/12/11)**

* **New Features**
	* Added a `[faq]` shortcode to include a FAQ category in post/page content.
	* Added a `[question]` shortcode to include individual Questions in post/page content.
* **Improvements**
	* Added a "Shortcode" column to the "All Questions" and FAQ "Category" lists.
* **Bugfixes**
	* None.
* **Developer Notes**
	* Added new library files:
		* lib/post.php
		* lib/style.php
		* lib/term.php
		* lib/shortcode/faq.php
		* lib/shortcode/question.php
	* Added new hard-coded / fixed constants:
		* WPSSOFAQ_CATEGORY_TAXONOMY = 'faq_category'
		* WPSSOFAQ_QUESTION_POST_TYPE = 'question'
	* Added new variable constants:
		* WPSSOFAQ_FAQ_SHORTCODE_NAME = 'faq'
		* WPSSOFAQ_QUESTION_SHORTCODE_NAME = 'question'
* **Requires At Least**
	* PHP v5.5.
	* WordPress v4.0.
	* WPSSO Core v6.16.2.

**Version 1.1.0 (2019/12/08)**

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
	* WordPress v4.0.
	* WPSSO Core v6.15.0.

== Upgrade Notice ==

= 2.2.0-rc.1 =

(2020/02/26) Added Schema markup in the [[faq]] and [[question]] shortcode output for the WPSSO JSON Premium add-on.

= 2.1.0 =

(2020/02/07) Added the FAQ category title to the [[faq]] shortcode output.

