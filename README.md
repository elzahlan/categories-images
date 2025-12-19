# Categories Images #
Wordpress Plugin

**Contributors:** Muhammad El Zahlan (elzahlan)

**Requires at least:** Wordpress 4.0

**Tested up to:** Wordpress 6.9

**Stable tag:** 3.3.0

## Description ##

The Categories Images is a Wordpress plugin allow you to add image to category, tag or custom taxonomy.

### Basic Usage ###
Use `<?php if (function_exists('z_taxonomy_image_url')) echo z_taxonomy_image_url(); ?>` to get the url and put it in any img tag.
Or simply use `<?php if (function_exists('z_taxonomy_image')) z_taxonomy_image(); ?>` in (category or taxonomy) template.

### Additional features ###

*   **REST API Support**: Access term images via the WP REST API. The field `z_taxonomy_image_url` is automatically added to term objects.
*   **Elementor Integration**: Use term images dynamically in Elementor via the native Dynamic Tags system.
*   **Enhanced Shortcodes**:
    *   `[z_taxonomy_image term_id="123" size="medium" link="yes"]` - Display a specific term image with a link.
    *   `[z_taxonomy_list taxonomy="category" style="grid" columns="4" show_name="yes"]` - Display a beautiful grid of terms with their images.
*   **Modern PHP**: Fully refactored for performance and compatibility with modern PHP versions.

### Settings ###
Categories Images settings menu is now under Settings > Categories Images to avoid cluttering the main WordPress menu, the settings now is more organized with a dedicated documentation page that includes usage examples and shortcodes.

### Exclude Taxonomies ###
From the settings menu, you can exclude any taxonomies from the plugin to avoid conflicts with other plugins like WooCommerce!

### Documentation ###
Documentation is now available inside the plugin settings menu. for more information please visit the [Categories Images](https://zahlan.net/blog/2012/06/categories-images/).

## Installation ##

You can install Categories Images directly from the WordPress admin panel:

	1. Visit the Plugins > Add New and search for 'Categories Images'.
	2. Click to install.
	3. Once installed, activate and it is functional.
	
OR

Manual Installation:

	1. Download the plugin, then extract it.
	2. Upload `categories-images` extracted folder to the `/wp-content/plugins/` directory
	3. Activate the plugin through the 'Plugins' menu in WordPress
	
You're done! The plugin is ready to use, for more please check the plugin description.

## Frequently Asked Questions ##

Please check the documentation page:
[https://zahlan.net/blog/2012/06/categories-images/](https://zahlan.net/blog/2012/06/categories-images/)

## Changelog ##

### 3.3.0 ###
* Added native Elementor Dynamic Tag support for taxonomy images.
* Integrated with WordPress REST API (adds `z_taxonomy_image_url` to term responses).
* Major shortcode overhaul:
    * Enhanced `[z_taxonomy_image]` with custom links, placeholders, and size support.
    * Enhanced `[z_taxonomy_list]` with grid/list layouts, column control, and conditional name/count display.
* Categories Images settings menu is now under Settings > Categories Images to avoid cluttering the main WordPress menu, the settings now is more organized with a dedicated documentation page that includes usage examples and shortcodes.
* Performance: Modern PHP array syntax refactor and optimized asset versioning.
* Fixed frontend CSS loading for shortcode grids.

### 3.2.1 ###
* Fixed positional 'default' attribute bug in `[z_taxonomy_image]` shortcode.

### 3.2.0 ###
* Fix wp_options bloat issues by migrating to Term Meta API (wp_termmeta) for WP 4.4+.
* Backward compatibility for WP < 4.4 maintained via wp_options fallback.
* Improved performance by implementing Singleton pattern to reduce class instantiation overhead.
* Security enhancements (nonce verification, input sanitization).

### 3.1.0 ###
* Added DE translation, thanks to denarie.
* Start using imageId instead of imageUrl to solve any the CDN issues, thanks so alessandrocarrera.
* Tested with the latest version of wordpress

### 3.0.1 ###
* Disable options autoload to enhance wordpress queries performace

### 3.0.0 ###
* Fix settings page issues
* Fix compatibility with the latest Wordpress version
* Rewrote the whole plugin from scratch, now the code is much efficient, readable and cleaner

### 2.5.4 ###
* Fix compatibility with the latest Wordpress version

### 2.5.3 ###
* Fix not displaying single tag image bug in tag.php template
* Adding language support for Swedish. Thanks to Simon Sandgren

### 2.5.2 ###
* Fix displaying full size image bug in backend
* Fix quick edit bug
* Some code enhancements

### 2.5.1 ###
* Adding language support for Russian.
* Adding language support for Serbian. Thanks to Andrijana Nikolic.
* Adding language support for Catalan. Thanks to Marc Queralt.
* Change the plugin text domain from zci to categories-images to match the plugin slug as requested by Wordpress.

### 2.5 ###
* Adding language support for Ukrainian. Thanks to Michael Yunat.
* Adding new function z_taxonomy_image() to display category or taxonomy image directly with support for size, alt and other attributes, for and how to use it please check the documentations.
* Some code enhancements.

### 2.4.2 ###
* Update code to reduce db queries. Thanks to fburatti.

### 2.4.1 ###
* Fix placeholder bug in backend.

### 2.4 ###
* Adding language support for Spanish (Thansk so much to Maria Ramos).
* Adding support for resizing categories images (Thanks so much to Rahil Wazir).
* Some code enhancements.

### 2.3.2 ###
* Adding language support for French.

### 2.3.1 ###
* Bug fix in js for Wordpress media uploader.

### 2.3 ###
* New screenshots.
* Updated language file.
* Added support for both old and new Wordpress media uploader.
* Added new submenu (Categories Images) in Settings menu.
* Added new settings for excluding any taxonomies from the plugin.
* Added new placeholder image.

Thanks to Patrick and Hassan for the new ideas.

### 2.2.4 ###
* java script bug fixed, reported about conflicting with WooCommerce plugin. Thanks to Marty McGee.

### 2.2.3 ###
* bug fix in displaying category or taxonomy image at the frontend.

### 2.2.2 ###
* bug fix in displaying placeholder image in wp-admin.

### 2.2.1 ###
* edit z_taxonomy_image_url() to only return data in case the user inserted image for the selected category or taxonomy

### 2.2 ###
* fix a bug, prevent a function from running execpt when editing a category or taxonomy to avoid affecting other wordpress edit pages in the wp-admin

### 2.1 ###
* fix a bug in languages
* fix a bug in quick edit category or taxonomy

### 2.0 ###
* New screenshots.
* Added l10n support.
* Added Arabic and Chinese languages.
* Added new button for upload or select an image using wordpress media uploader.
* Added default image placeholder.
* Added thumbnail in categories or taxonomies list.
* Added image thumbnail, image text box, upload button and remove button in quick edit.

Thank so much to Joe Tse

### 1.2 ###
Adding some screenshots

### 1.1 ###
Fix javascript bug with wordpress 3.4

### 1.0 ###
The First Release