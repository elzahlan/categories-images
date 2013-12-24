<?php
/*
Plugin Name: Categories Images
Plugin URI: http://zahlan.net/blog/2012/06/categories-images/
Description: Categories Images Plugin allow you to add an image to category or any custom term.
Author: Muhammad Said El Zahlan
Version: 2.2.4
Author URI: http://zahlan.net/
*/
?>
<?php
if ( ! defined( 'Z_PLUGIN_URL' ) )
	define( 'Z_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

define( 'Z_IMAGE_PLACEHOLDER', Z_PLUGIN_URL . "/images/placeholder.png");

// l10n
load_plugin_textdomain( 'zci', false, 'categories-images/languages' );

add_action('admin_init', 'z_init');
function z_init() {
	$z_taxonomies = get_taxonomies();
	if (is_array($z_taxonomies)) {
	    foreach ($z_taxonomies as $z_taxonomy ) {
	        add_action($z_taxonomy.'_add_form_fields', 'z_add_texonomy_field');
			add_action($z_taxonomy.'_edit_form_fields', 'z_edit_texonomy_field');
			add_filter( 'manage_edit-' . $z_taxonomy . '_columns', 'z_taxonomy_columns' );
			add_filter( 'manage_' . $z_taxonomy . '_custom_column', 'z_taxonomy_column', 10, 3 );
	    }
	}
}

function z_add_style() {
	echo '<style type="text/css" media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image {border:1px solid #eee;max-width:300px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.column-thumb span {width:48px;height:48px;border:1px solid #eee;display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:48px;height:48px;}
	</style>';
}

// add image field in add form
function z_add_texonomy_field() {
wp_enqueue_style('thickbox');
wp_enqueue_script('thickbox');
	echo '<div class="form-field">
		<label for="taxonomy_image">' . __('Image', 'zci') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
		<br/>
		<button class="z_upload_image_button button">' . __('Upload/Add image', 'zci') . '</button>
	</div>'.z_script();
}

// add image field in edit form
function z_edit_texonomy_field($taxonomy) {
	wp_enqueue_style('thickbox');
	wp_enqueue_script('thickbox');
	if (z_taxonomy_image_url( $taxonomy->term_id, TRUE ) == Z_IMAGE_PLACEHOLDER) 
		$image_text = "";
	else
		$image_text = z_taxonomy_image_url( $taxonomy->term_id, TRUE );
	echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . __('Image', 'zci') . '</label></th>
		<td><img class="taxonomy-image" src="' . z_taxonomy_image_url( $taxonomy->term_id, TRUE ) . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="'.$image_text.'" /><br />
		<button class="z_upload_image_button button">' . __('Upload/Add image', 'zci') . '</button>
		<button class="z_remove_image_button button">' . __('Remove image', 'zci') . '</button>
		</td>
	</tr>'.z_script();
}
// upload using wordpress upload
function z_script() {
	return '<script type="text/javascript">
	    jQuery(document).ready(function() {
			jQuery(".z_upload_image_button").click(function() {
				upload_button = jQuery(this);
				tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
				return false;
			});
			jQuery(".z_remove_image_button").click(function() {
				jQuery("#taxonomy_image").val("");
				jQuery(this).parent().siblings(".title").children("img").attr("src","' . Z_IMAGE_PLACEHOLDER . '");
				jQuery(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			window.send_to_editor = function(html) {
				imgurl = jQuery("img",html).attr("src");
				if (upload_button.parent().prev().children().hasClass("tax_list")) {
					upload_button.parent().prev().children().val(imgurl);
					upload_button.parent().prev().prev().children().attr("src", imgurl);
				}
				else
					jQuery("#taxonomy_image").val(imgurl);
				tb_remove();
			}
			jQuery(".editinline").live("click", function(){  
			    var tax_id = jQuery(this).parents("tr").attr("id").substr(4);
			    var thumb = jQuery("#tag-"+tax_id+" .thumb img").attr("src");
				if (thumb != "' . Z_IMAGE_PLACEHOLDER . '") {
					jQuery(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					jQuery(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				jQuery(".inline-edit-col .title img").attr("src",thumb);
			    return false;  
			});  
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
function z_taxonomy_image_url($term_id = NULL, $return_placeholder = FALSE) {
	if (!$term_id) {
		if (is_category())
			$term_id = get_query_var('cat');
		elseif (is_tax()) {
			$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$term_id = $current_term->term_id;
		}
	}
	$taxonomy_image_url = get_option('z_taxonomy_image'.$term_id);
	if ($return_placeholder)
		return ($taxonomy_image_url != "") ? $taxonomy_image_url : Z_IMAGE_PLACEHOLDER;
	else
		return $taxonomy_image_url;
}

function z_quick_edit_custom_box($column_name, $screen, $name) {
	if ($column_name == 'thumb') 
		echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
				<span class="input-text-wrap">
					<button class="z_upload_image_button button">' . __('Upload/Add image', 'zci') . '</button>
					<button class="z_remove_image_button button">' . __('Remove image', 'zci') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

/**
 * Thumbnail column added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @return void
 */
function z_taxonomy_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['thumb'] = __('Image', 'zci');

	unset( $columns['cb'] );

	return array_merge( $new_columns, $columns );
}

/**
 * Thumbnail column value added to category admin.
 *
 * @access public
 * @param mixed $columns
 * @param mixed $column
 * @param mixed $id
 * @return void
 */
function z_taxonomy_column( $columns, $column, $id ) {
	if ( $column == 'thumb' )
		$columns = '<span><img src="' . z_taxonomy_image_url($id, TRUE) . '" alt="' . __('Thumbnail', 'zci') . '" class="wp-post-image" /></span>';
	
	return $columns;
}

// change 'insert into post' to 'use this image'
function z_change_insert_button_text($safe_text, $text) {
    return str_replace("Insert into Post", "Use this image", $text);
}

// style the image in category list
if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 ) {
	add_action( 'admin_head', 'z_add_style' );
	add_action('quick_edit_custom_box', 'z_quick_edit_custom_box', 10, 3);
	add_filter("attribute_escape", "z_change_insert_button_text", 10, 2);
}

?>