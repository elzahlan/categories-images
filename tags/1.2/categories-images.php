<?php
/*
Plugin Name: Categories Images
Plugin URI: http://zahlan.net/blog/2012/06/categories-images/
Description: Categories Images Plugin allow you to add an image to category or any custom term.
Author: Muhammad Said El Zahlan
Version: 1.2
Author URI: http://zahlan.net/
*/
?>
<?php
// inti the plugin
add_action('admin_head', 'z_inti');
function z_inti() {
	$z_taxonomies = get_taxonomies();
	if (is_array($z_taxonomies)) {
	    foreach ($z_taxonomies as $z_taxonomy ) {
	        add_action($z_taxonomy.'_add_form_fields', 'z_add_texonomy_field');
			add_action($z_taxonomy.'_edit_form_fields', 'z_edit_texonomy_field');
	    }
	}
}

// add image field in add form
function z_add_texonomy_field() {
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');
	echo '<div class="form-field">
		<label for="taxonomy_image">Image</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
	</div>'.z_script();
}

// add image field in edit form
function z_edit_texonomy_field($taxonomy) {
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">Image</label></th>
		<td><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.get_option('z_taxonomy_image'.$taxonomy->term_id).'" /><br /></td>
	</tr>'.z_script();
}
// upload using wordpress upload
function z_script() {
	return '<script type="text/javascript">
	    jQuery(document).ready(function() {
			jQuery("#taxonomy_image").click(function() {
				tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
				return false;
			});
			window.send_to_editor = function(html) {
				imgurl = jQuery("img",html).attr("src");
				jQuery("#taxonomy_image").val(imgurl);
				tb_remove();
			}
	    });
	</script>';
}

// save our taxonomy image while edit or save term
add_action('edit_term','z_save_taxonomy_image');
add_action('create_term','z_save_taxonomy_image');
function z_save_taxonomy_image($term_id) {
    if(isset($_POST['taxonomy_image']))
        update_option('z_taxonomy_image'.$term_id, $_POST['taxonomy_image']);
}

// output taxonomy image url for the given term_id (NULL by default)
function z_taxonomy_image_url($term_id = NULL) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
    return get_option('z_taxonomy_image'.$term_id);
}
?>