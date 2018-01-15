# Categories Images #
Wordpress Plugin

**Contributors:** Muhammad Said El Zahlan (elzahlan)

**Requires at least:** Wordpress 2.8

**Tested up to:** Wordpress 4.9.1

**Stable tag:** 2.5.4

## Description ##

The Categories Images is a Wordpress plugin allow you to add image to category, tag or custom taxonomy.

Use `<?php if (function_exists('z_taxonomy_image_url')) echo z_taxonomy_image_url(); ?>` to get the url and put it in any img tag.
Or simply use `<?php if (function_exists('z_taxonomy_image')) z_taxonomy_image(); ?>` in (category or taxonomy) template.

Also from settings menu you can exclude any taxonomies from the plugin to avoid conflicting with another plugins like WooCommerce!

### Documentation ###

Go to [http://zahlan.net/blog/2012/06/categories-images/](http://zahlan.net/blog/2012/06/categories-images/)

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
[http://zahlan.net/blog/2012/06/categories-images/](http://zahlan.net/blog/2012/06/categories-images/)

## Changelog ##

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
* Adding language support for Serbian. Thanks to Andrijana Nikolic [http://webhostinggeeks.com/].
* Adding language support for Catalan. Thanks to Marc Queralt i Bassa [http://www.demomentsomtres.com/].
* Change the plugin text domain from zci to categories-images to match the plugin slug as requested by Wordpress.

### 2.5 ###
* Adding language support for Ukrainian. Thanks to Michael Yunat [http://getvoip.com].
* Adding new function z_taxonomy_image() to display category or taxonomy image directly with support for size, alt and other attributes, for and how to use it please check the documentations.
* Some code enhancements.

### 2.4.2 ###
* Update code to reduce db queries. Thanks to fburatti [http://profiles.wordpress.org/fburatti/].

### 2.4.1 ###
* Fix placeholder bug in backend.

### 2.4 ###
* Adding language support for Spanish (Thansk so much to Maria Ramos [http://webhostinghub.com]).
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

Thanks to Patrick http://www.patrickbos.nl and Hassan http://profiles.wordpress.org/hassanhamm/ for the new ideas.

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

Thank so much to Joe Tse http://tkjune.com :)

### 1.2 ###
Adding some screenshots

### 1.1 ###
Fix javascript bug with wordpress 3.4

### 1.0 ###
The First Release
