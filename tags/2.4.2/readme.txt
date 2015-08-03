=== Categories Images ===
Contributors: elzahlan
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G8LC4VSYKYSGA
Tags: Category Image, Category Images, Categories Images, taxonomy image, taxonomy images, taxonomies images, category icon, categories icons, category logo, categories logos, admin, wp-admin, category image plugin, categories images plugin
Requires at least: 2.8
Tested up to: 3.9.1
Stable tag: 2.4.2

The Categories Images Plugin allow you to add image with category or taxonomy.

== Description ==

The Categories Images Plugin allow you to add image with category or taxonomy.

Use `<?php if (function_exists('z_taxonomy_image_url')) echo z_taxonomy_image_url(); ?>` to get the url and put it in any img tag in (category or taxonomy) template.

Also from settings menu you can exclude any taxonomies from the plugin to avoid conflicting with another plugins like WooCommerce!

= More documentation =

Go to [http://zahlan.net/blog/2012/06/categories-images/](http://zahlan.net/blog/2012/06/categories-images/)

== Installation ==

You can install Categories Images directly from the WordPress admin panel:

	1. Visit the Plugins > Add New and search for 'Categories Images'.
	2. Click to install.
	3. Once installed, activate and it is functional.
	
OR

Manual Installation:

	1. Download the plugin, then extract it.
	2. Upload `categories-images` extracted folder to the `/wp-content/plugins/` directory
	3. Activate the plugin through the 'Plugins' menu in WordPress
	
You're done! The Plugin ready to use, for more please check the plugin description.

= More documentation =

Go to [http://zahlan.net/blog/2012/06/categories-images/](http://zahlan.net/blog/2012/06/categories-images/)

== Frequently Asked Questions ==

Please check the documentation page:
[http://zahlan.net/blog/2012/06/categories-images/](http://zahlan.net/blog/2012/06/categories-images/)

== Screenshots ==

1. Image preview and new image field in add/edit category or taxonomy with upload button which allow you to select current or upload a new image.
2. New image field with (upload/remove) buttons to allow you to edit category or taxonomy image in quick edit.
3. When you click the upload button the wordpress upload box will popup, upload or select image then press use this image.
4. New submenu (Categories Images) in Settings menu.
5. Now you can exclude any taxonomy from the plugin and save changes.

== Changelog ==

= 2.4.2 =
* Update code to reduce db queries. Thanks to fburatti [http://profiles.wordpress.org/fburatti/].

= 2.4.1 =
* Fix placeholder bug in backend.

= 2.4 =
* Adding language support for Spanish (Thansk so much to Maria Ramos [http://webhostinghub.com]).
* Adding support for resizing categories images (Thanks so much to Rahil Wazir).
* Some code enhancements.

= 2.3.2 =
* Adding language support for French.

= 2.3.1 =
* Bug fix in js for Wordpress media uploader.

= 2.3 =
* New screenshots.
* Updated language file.
* Added support for both old and new Wordpress media uploader.
* Added new submenu (Categories Images) in Settings menu.
* Added new settings for excluding any taxonomies from the plugin.
* Added new placeholder image.

Thanks to Patrick http://www.patrickbos.nl and Hassan http://profiles.wordpress.org/hassanhamm/ for the new ideas.

= 2.2.4 =
* java script bug fixed, reported about conflicting with WooCommerce plugin. Thanks to Marty McGee.

= 2.2.3 =
* bug fix in displaying category or taxonomy image at the frontend.

= 2.2.2 =
* bug fix in displaying placeholder image in wp-admin.

= 2.2.1 =
* edit z_taxonomy_image_url() to only return data in case the user inserted image for the selected category or taxonomy

= 2.2 =
* fix a bug, prevent a function from running execpt when editing a category or taxonomy to avoid affecting other wordpress edit pages in the wp-admin

= 2.1 =
* fix a bug in languages
* fix a bug in quick edit category or taxonomy

= 2.0 =
* New screenshots.
* Added l10n support.
* Added Arabic and Chinese languages.
* Added new button for upload or select an image using wordpress media uploader.
* Added default image placeholder.
* Added thumbnail in categories or taxonomies list.
* Added image thumbnail, image text box, upload button and remove button in quick edit.

Thank so much to Joe Tse http://tkjune.com :)

= 1.2 =
Adding some screenshots

= 1.1 =
Fix javascript bug with wordpress 3.4

= 1.0 =
The First Release
