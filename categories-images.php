<?php
/**
 * @package CategoriesImages
 */

/**
 * Plugin Name: Categories Images
 * Plugin URI: https://zahlan.net/blog/categories-images/
 * Description: Categories Images Plugin allow you to add an image to category or any custom term.
 * Author: Muhammad El Zahlan
 * Version: 3.3.3
 * Author URI: https://zahlan.net/
 * Domain Path: /languages
 * Text Domain: categories-images
 * License: GPLv2 or later
 */

if (!defined('ABSPATH'))
    die;

if (!defined('Z_PLUGIN_URL'))
    define('Z_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));

if (!defined('ZCI_VERSION'))
    define('ZCI_VERSION', '3.3.3');

class ZCategoriesImages
{
    public $plugin_name;
    private $zci_placeholder;
    private static $instance = null;

    /**
     * Singleton instance
     */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        $this->plugin_name = plugin_basename(__FILE__);

        // The placeholder image url
        $this->zci_placeholder = plugins_url('/assets/images/placeholder.png', __FILE__);
        add_action('init', [$this, 'zInit']);
        add_action('admin_init', [$this, 'zAdminInit']);

        // save our taxonomy image while edit or create term
        add_action('edit_term', [$this, 'zSaveTaxonomyImage']);
        add_action('create_term', [$this, 'zSaveTaxonomyImage']);

        // Plugin menu in admin panel
        add_action('admin_menu', [$this, 'zSettingsMenu']);

        // Settings page link in plugins list
        add_filter("plugin_action_links_{$this->plugin_name}", [$this, 'zSettingsLink']);

        // Elementor Dynamic Tag Registration
        add_action('elementor/dynamic_tags/register', [$this, 'zRegisterElementorTags']);

        // Register REST API Field
        add_action('rest_api_init', [$this, 'zInitRestApi']);

        // Enqueue frontend styles
        add_action('wp_enqueue_scripts', [$this, 'zPublicEnqueue']);
    }

    /**
     * Abstraction for retrieving term metadata
     */
    public function zci_get_term_meta( $term_id, $key ) {
        if ( function_exists( 'get_term_meta' ) ) {
            return get_term_meta( $term_id, $key, true );
        } else {
            return get_option( $key . $term_id );
        }
    }

    /**
     * Abstraction for updating term metadata
     */
    public function zci_update_term_meta( $term_id, $key, $value ) {
        if ( function_exists( 'update_term_meta' ) ) {
            return update_term_meta( $term_id, $key, $value );
        } else {
            return update_option( $key . $term_id, $value );
        }
    }

    /**
     * Abstraction for deleting term metadata
     */
    public function zci_delete_term_meta( $term_id, $key ) {
        if ( function_exists( 'delete_term_meta' ) ) {
            return delete_term_meta( $term_id, $key );
        } else {
            return delete_option( $key . $term_id );
        }
    }

    function zAdminInit() {
        // Migration Routine: Move data from options to term_meta if supported and not yet done
        if ( function_exists( 'get_term_meta' ) && ! get_option( 'zci_migrated_to_termmeta' ) ) {
            $taxonomies = get_taxonomies();
            foreach ( $taxonomies as $taxonomy ) {
                $terms = get_terms( $taxonomy, ['hide_empty' => false] );
                if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        // Check for old option data
                        $app_option_url = get_option( 'z_taxonomy_image' . $term->term_id );
                        $app_option_id  = get_option( 'z_taxonomy_image_id' . $term->term_id );

                        if ( $app_option_url !== false ) {
                            update_term_meta( $term->term_id, 'z_taxonomy_image', $app_option_url );
                            delete_option( 'z_taxonomy_image' . $term->term_id );
                        }
                        if ( $app_option_id !== false ) {
                            update_term_meta( $term->term_id, 'z_taxonomy_image_id', $app_option_id );
                            delete_option( 'z_taxonomy_image_id' . $term->term_id );
                        }
                    }
                }
            }
            update_option( 'zci_migrated_to_termmeta', 1 );
        }

        $z_taxonomies = get_taxonomies();
        if (is_array($z_taxonomies)) {
            $zci_options = (array) get_option('zci_options', []);
            
            if (empty($zci_options['excluded_taxonomies']) || !is_array($zci_options['excluded_taxonomies']))
                $zci_options['excluded_taxonomies'] = [];
            
            foreach ($z_taxonomies as $z_taxonomy) {
                if (in_array($z_taxonomy, $zci_options['excluded_taxonomies']))
                    continue;
                add_action($z_taxonomy.'_add_form_fields', [$this, 'zAddTexonomyField']);
                add_action($z_taxonomy.'_edit_form_fields', [$this, 'zEditTexonomyField']);
                add_filter('manage_edit-'.$z_taxonomy.'_columns', [$this, 'zTaxonomyColumns']);
                add_filter('manage_'.$z_taxonomy.'_custom_column', [$this, 'zTaxonomyColumn'], 10, 3 );

                // If tax is deleted
                // Note: term_meta deletes automatically when term is deleted in WP 4.4+, but we need to handle fallback.
                add_action("delete_{$z_taxonomy}", [$this, 'zDeleteTaxonomyData']);
            }
        }

        // Register styles and scripts
        if ( strpos( $_SERVER['SCRIPT_NAME'], 'edit-tags.php' ) > 0 || strpos( $_SERVER['SCRIPT_NAME'], 'term.php' ) > 0 ) {
            add_action('admin_enqueue_scripts', [$this, 'zAdminEnqueue']);
            add_action('quick_edit_custom_box', [$this, 'zQuickEditCustomBox'], 10, 3);
        }

        // Register settings
        register_setting('zci_options', 'zci_options');
        add_settings_section('zci_settings', __('Categories Images settings', 'categories-images'), [$this, 'zSectionText'], 'zci-options');
        add_settings_field('z_excluded_taxonomies', __('Excluded Taxonomies', 'categories-images'), [$this, 'zExcludedTaxonomies'], 'zci-options', 'zci_settings');
    }

    function zInit() {
        // Register Shortcodes
        add_shortcode('z_taxonomy_image', [$this, 'z_taxonomy_image_shortcode']);
        add_shortcode('z_taxonomy_list', [$this, 'z_taxonomy_list_shortcode']);
    }

    function zDeleteTaxonomyData($tt_id) {
        // delete_term_meta handles itself, but for backward compatibility with options:
        if ( ! function_exists( 'delete_term_meta' ) ) {
            delete_option('z_taxonomy_image'.(int)$tt_id);
            delete_option('z_taxonomy_image_id'.(int)$tt_id);
        }
    }

    function zAdminEnqueue() {
        wp_enqueue_style('categories-images-admin-styles', plugins_url('/assets/css/zci-admin.css', __FILE__), [], ZCI_VERSION);
        wp_enqueue_script('categories-images-scripts', plugins_url('/assets/js/zci-scripts.js', __FILE__), [], ZCI_VERSION, true);

        $zci_js_config = [
            'wordpress_ver' => get_bloginfo("version"),
            'placeholder' => $this->zci_placeholder
        ];
        wp_localize_script('categories-images-scripts', 'zci_config', $zci_js_config);
    }
    
    function zPublicEnqueue() {
        wp_enqueue_style('categories-images-styles', plugins_url('/assets/css/zci-styles.css', __FILE__), [], ZCI_VERSION);
    }

    // add image field in add form
    function zAddTexonomyField() {
        wp_enqueue_media();
        
        $nonce = wp_create_nonce( 'zci_save_image' );
        
        echo '<div class="form-field">
            <input type="hidden" name="zci_nonce" value="' . esc_attr($nonce) . '" />
            <input type="hidden" name="zci_taxonomy_image_id" id="zci_taxonomy_image_id" value="" />
            <label for="zci_taxonomy_image">' . esc_html__('Image', 'categories-images') . '</label>
            <input type="text" name="zci_taxonomy_image" id="zci_taxonomy_image" value="" />
            <br/>
            <button class="z_upload_image_button button">' . esc_html__('Upload/Add image', 'categories-images') . '</button>
        </div>';
    }

    // add image field in edit form
    function zEditTexonomyField($taxonomy) {
        wp_enqueue_media();
        
        if ($this->zTaxonomyImageUrl( $taxonomy->term_id, NULL, TRUE ) == $this->zci_placeholder) {
            $image_url = "";
            $image_id  = "";
        } else {
            $image_url = $this->zTaxonomyImageUrl( $taxonomy->term_id, NULL, TRUE );
            $image_id  = $this->zTaxonomyImageID( $taxonomy->term_id );
        }
        
        $nonce = wp_create_nonce( 'zci_save_image' );

        echo '<tr class="form-field">
            <th scope="row" valign="top"><label for="zci_taxonomy_image">' . esc_html__('Image', 'categories-images') . '</label></th>
            <td>
            <input type="hidden" name="zci_nonce" value="' . esc_attr($nonce) . '" />
            <input type="hidden" name="zci_taxonomy_image_id" id="zci_taxonomy_image_id" value="'.esc_attr($image_id).'" /><img class="zci-taxonomy-image" src="' . esc_url( $this->zTaxonomyImageUrl( $taxonomy->term_id, 'medium', TRUE ) ) . '"/><br/><input type="text" name="zci_taxonomy_image" id="zci_taxonomy_image" value="'.esc_url($image_url).'" /><br />
            <button class="z_upload_image_button button">' . esc_html__('Upload/Add image', 'categories-images') . '</button>
            <button class="z_remove_image_button button">' . esc_html__('Remove image', 'categories-images') . '</button>
            </td>
        </tr>';
    }

    function zTaxonomyColumns( $columns ) {
        $new_columns = [];

        // Keep checkbox column if it exists
        if ( isset( $columns['cb'] ) ) {
            $new_columns['cb'] = $columns['cb'];
            unset( $columns['cb'] );
        }

        // Add image column
        $new_columns['thumb'] = esc_html__( 'Image', 'categories-images' );

        // Merge back the rest of the columns
        return array_merge( $new_columns, $columns );
    }

    function zTaxonomyColumn( $columns, $column, $id ) {
        if ( $column == 'thumb' ) {
            // Get full URL and ID for Quick Edit
            $full_url = $this->zTaxonomyImageUrl($id, 'full', FALSE);
            $image_id = $this->zTaxonomyImageID($id);
            
            $columns = '<span><img src="' . esc_url($this->zTaxonomyImageUrl($id, 'thumbnail', TRUE)) . '" alt="' . esc_attr(__('Thumbnail', 'categories-images')) . '" class="wp-post-image" />';
            $columns .= '<span class="zci-data" style="display:none;" data-url="' . esc_url($full_url) . '" data-id="' . esc_attr($image_id) . '"></span>';
            $columns .= '</span>';
        }
        
        return $columns;
    }

    function zQuickEditCustomBox($column_name, $screen, $name) {
        if ($column_name == 'thumb') {
            $nonce = wp_create_nonce( 'zci_save_image' );
            echo '<fieldset>
            <div class="thumb inline-edit-col">
                <label>
                    <span class="title"><img src="" alt="Thumbnail"/></span>
                    <span class="input-text-wrap"><input type="text" name="zci_taxonomy_image" value="" class="tax_list" /></span>
                    <span class="input-text-wrap">
                        <input type="hidden" name="zci_nonce" value="' . esc_attr($nonce) . '" />
                        <button class="z_upload_image_button button">' . esc_html__('Upload/Add image', 'categories-images') . '</button>
                        <button class="z_remove_image_button button">' . esc_html__('Remove image', 'categories-images') . '</button>
                    </span>
                    <span class="input-text-wrap">
                        <input type="hidden" name="zci_taxonomy_image_id" value="" />
                    </span>
                </label>
            </div>
        </fieldset>';
        }
    }

    function zSaveTaxonomyImage($term_id) {
        // Security check
        if ( ! isset( $_POST['zci_nonce'] ) || ! wp_verify_nonce( $_POST['zci_nonce'], 'zci_save_image' ) ) {
            return;
        }

        if(isset($_POST['zci_taxonomy_image'])) {
            $this->zci_update_term_meta($term_id, 'z_taxonomy_image', esc_url_raw($_POST['zci_taxonomy_image']));
        }
        if(isset($_POST['zci_taxonomy_image_id'])) {
            $this->zci_update_term_meta($term_id, 'z_taxonomy_image_id', absint($_POST['zci_taxonomy_image_id']));
        }
    }

    // get attachment ID by image url
    function zGetAttachmentIdByUrl($image_src) {
        $id = attachment_url_to_postid($image_src);
        return (!empty($id)) ? $id : NULL;
    }

    // get attachment ID by term id
    function zTaxonomyImageID($term_id = NULL) {
        if (!$term_id) {
            if (is_category())
                $term_id = get_query_var('cat');
            elseif (is_tag())
                $term_id = get_query_var('tag_id');
            elseif (is_tax()) {
                $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                $term_id = $current_term->term_id;
            }
        }
        
        return $this->zci_get_term_meta($term_id, 'z_taxonomy_image_id');
    }

    // get taxonomy image url for the given term_id (Place holder image by default)
    function zTaxonomyImageUrl($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
        if (!$term_id) {
            if (is_category())
                $term_id = get_query_var('cat');
            elseif (is_tag())
                $term_id = get_query_var('tag_id');
            elseif (is_tax()) {
                $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                $term_id = $current_term->term_id;
            }
        }
        
        $attachment_id = $this->zTaxonomyImageID($term_id);
        $taxonomy_image_url = '';

        if (!empty($attachment_id)) {
            $taxonomy_image_src = wp_get_attachment_image_src($attachment_id, $size);
            if ($taxonomy_image_src) {
                $taxonomy_image_url = $taxonomy_image_src[0];
            }
        }

        if (empty($taxonomy_image_url)) {
            $taxonomy_image_url = $this->zci_get_term_meta($term_id, 'z_taxonomy_image');
            if (!empty($taxonomy_image_url) && empty($attachment_id)) {
                $attachment_id = $this->zGetAttachmentIdByUrl($taxonomy_image_url);
                if (!empty($attachment_id)) {
                    $taxonomy_image_src = wp_get_attachment_image_src($attachment_id, $size);
                    if ($taxonomy_image_src) {
                        $taxonomy_image_url = $taxonomy_image_src[0];
                    }
                }
            }
        }

        if ($return_placeholder)
            return ($taxonomy_image_url != '') ? $taxonomy_image_url : $this->zci_placeholder;
        else
            return $taxonomy_image_url;
    }

    // display taxonomy image for the given term_id
    function zTaxonomyImage($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE) {
        if (!$term_id) {
            if (is_category())
                $term_id = get_query_var('cat');
            elseif (is_tag())
                $term_id = get_query_var('tag_id');
            elseif (is_tax()) {
                $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                $term_id = $current_term->term_id;
            }
        }
        
        $attachment_id = $this->zTaxonomyImageID($term_id);
        $taxonomy_image_url = '';
        $taxonomy_image = '';

        if (empty($attachment_id)) {
            $taxonomy_image_url = $this->zci_get_term_meta($term_id, 'z_taxonomy_image');
            if (!empty($taxonomy_image_url)) {
                $attachment_id = $this->zGetAttachmentIdByUrl($taxonomy_image_url);
            }
        } else {
            $taxonomy_image_url = $this->zci_get_term_meta($term_id, 'z_taxonomy_image');
        }

        if (!empty($attachment_id)) {
            $taxonomy_image = wp_get_attachment_image($attachment_id, $size, FALSE, $attr);
        }

        // Fallback to raw URL if wp_get_attachment_image failed or no ID found
        if (empty($taxonomy_image) && !empty($taxonomy_image_url)) {
            $image_attr = '';
            if(is_array($attr)) {
                if(!empty($attr['class']))
                    $image_attr .= ' class="'.esc_attr($attr['class']).'" ';
                if(!empty($attr['alt']))
                    $image_attr .= ' alt="'.esc_attr($attr['alt']).'" ';
                if(!empty($attr['width']))
                    $image_attr .= ' width="'.esc_attr($attr['width']).'" ';
                if(!empty($attr['height']))
                    $image_attr .= ' height="'.esc_attr($attr['height']).'" ';
                if(!empty($attr['title']))
                    $image_attr .= ' title="'.esc_attr($attr['title']).'" ';
            }
            $taxonomy_image = '<img src="'.esc_url($taxonomy_image_url).'" '.$image_attr.'/>';
        }

        if ($echo)
            echo wp_kses_post($taxonomy_image);
        else
            return $taxonomy_image;
    }

    function zSettingsMenu() {
        add_options_page(__('Categories Images settings', 'categories-images'), __('Categories Images', 'categories-images'), 'manage_options', 'zci_settings', [$this, 'zSettingsPage']);
    }

    // Plugin option page
    function zSettingsPage() {
        if (!current_user_can('manage_options'))
            wp_die(esc_html__( 'You do not have sufficient permissions to access this page.', 'categories-images'));
        
        // Enqueue admin styles for settings page if not already there
        wp_enqueue_style('categories-images-admin-styles', plugins_url('/assets/css/zci-admin.css', __FILE__), [], ZCI_VERSION);
        
        require_once plugin_dir_path(__FILE__).'templates/admin.php';
    }

    function zSettingsLink($links) {
        $settings_link = '<a href="options-general.php?page=zci_settings">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }

    // Settings section description
    function zSectionText() {
        echo '<p>'.esc_html__('Please select the taxonomies you want to exclude it from Categories Images plugin', 'categories-images').'</p>';
    }

    // Excluded taxonomies checkboxs
    function zExcludedTaxonomies() {
        $options = (array) get_option('zci_options', []);
        $disabled_taxonomies = ['nav_menu', 'link_category', 'post_format'];
        foreach (get_taxonomies() as $tax) : if (in_array($tax, $disabled_taxonomies)) continue; ?>
            <input type="checkbox" name="zci_options[excluded_taxonomies][<?php echo esc_attr($tax) ?>]" value="<?php echo esc_attr($tax) ?>" <?php checked(isset($options['excluded_taxonomies'][$tax]) && is_array($options['excluded_taxonomies']) && $options['excluded_taxonomies'][$tax] == $tax); ?> /> <?php echo esc_html($tax) ;?><br />
        <?php endforeach;
    }
    
    function activate() {
        // Things will happen if the plugin activated.
        flush_rewrite_rules();
    }

    function deactivate() {
        // Things will happen if the plugin deactivated.
        flush_rewrite_rules();
    }

    function zRegisterElementorTags( $dynamic_tags ) {
        // Include the tag class file
        if ( ! class_exists( 'ZCI_Elementor_Taxonomy_Image_Tag' ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'includes/elementor-integration.php';
        }

        // Register the tag
        $dynamic_tags->register( new ZCI_Elementor_Taxonomy_Image_Tag() );
    }

    // Register field 'z_taxonomy_image_url' to the WP REST API
    function zInitRestApi() {
        $taxonomies = get_taxonomies();
        $zci_options = (array) get_option('zci_options', []);
        if (!isset($zci_options['excluded_taxonomies']) || !is_array($zci_options['excluded_taxonomies']))
            $zci_options['excluded_taxonomies'] = [];

        foreach ($taxonomies as $taxonomy) {
            if (in_array($taxonomy, $zci_options['excluded_taxonomies']))
                continue;
            
            register_rest_field($taxonomy, 'z_taxonomy_image_url', [
                'get_callback' => [$this, 'zGetTermImage'],
                'update_callback' => null,
                'schema' => null,
            ]);
        }
    }

    function zGetTermImage($object, $field_name, $request) {
        $term_id = $object['id'];
        $image_url = $this->zTaxonomyImageUrl($term_id, 'full', true);
        
        return $image_url;
    }

    /**
     * Shortcode [z_taxonomy_image]
     */
    function z_taxonomy_image_shortcode($atts) {
        $raw_atts = $atts;
        $atts = shortcode_atts([
            'term_id'  => '',
            'taxonomy' => 'category',
            'size'     => 'full',
            'class'    => '',
            'default'  => null,
            'link'     => 'no', // yes, no, custom_url
            'format'   => 'img' // img, url
        ], $atts);

        // Handle positional 'default' attribute
        if (is_array($raw_atts) && in_array('default', $raw_atts, true)) {
            $atts['default'] = "";
        }

        $term_id = $atts['term_id'];

        // If no term_id, try to detect
        if (empty($term_id)) {
            $term_id = get_queried_object_id();
        }

        // Output logic
        if ($atts['format'] === 'url') {
            $url = $this->zTaxonomyImageUrl($term_id, $atts['size'], true);
            if ($url == $this->zci_placeholder) {
                if ($atts['default'] === "") {
                    return $this->zci_placeholder;
                } elseif (!empty($atts['default'])) {
                    return $atts['default'];
                } elseif (is_null($atts['default'])) {
                    return ''; // No placeholder by default
                }
            }
            return $url;
        }

        $image = $this->zTaxonomyImage($term_id, $atts['size'], ['class' => $atts['class']], false);
        
        // Handle empty/placeholder
        if (empty($image) || strpos($image, 'placeholder.png') !== false) {
             if ($atts['default'] === "") {
                 $image = '<img src="' . esc_url($this->zci_placeholder) . '" class="' . esc_attr($atts['class']) . '" />';
             } elseif (!empty($atts['default'])) {
                 $image = '<img src="' . esc_url($atts['default']) . '" class="' . esc_attr($atts['class']) . '" />';
             } else {
                 $image = '';
             }
        }

        // Link wrapping
        if ($atts['link'] === 'yes') {
             $term_link = get_term_link((int)$term_id, $atts['taxonomy']);
             if (!is_wp_error($term_link)) {
                 $image = '<a href="' . esc_url($term_link) . '">' . $image . '</a>';
             }
        } elseif ($atts['link'] !== 'no') {
             // custom URL
             $image = '<a href="' . esc_url($atts['link']) . '">' . $image . '</a>';
        }

        return $image;
    }

    /**
     * Shortcode [z_taxonomy_list]
     */
    function z_taxonomy_list_shortcode($atts) {
        $atts = shortcode_atts([
            'taxonomy'   => 'category',
            'post_id'    => '',
            'include'    => '',
            'exclude'    => '',
            'parent'     => '',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => 'yes',
            'size'       => 'full',
            'style'      => 'list', // list, grid, inline
            'columns'    => '3',
            'show_name'  => 'no',
            'show_count' => 'no',
            'format'     => 'img'
        ], $atts);

        // Get Terms
        $args = [
            'taxonomy' => $atts['taxonomy'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'hide_empty' => ($atts['hide_empty'] === 'yes'),
        ];

        if (!empty($atts['include'])) {
            $args['include'] = explode(',', $atts['include']);
        }
        if (!empty($atts['exclude'])) {
            $args['exclude'] = explode(',', $atts['exclude']);
        }
        if ($atts['parent'] !== '') {
            $args['parent'] = (int)$atts['parent'];
        }

        $terms = [];
        if (!empty($atts['post_id'])) {
            $pid = ($atts['post_id'] === 'current') ? get_the_ID() : (int)$atts['post_id'];
            $terms = get_the_terms($pid, $atts['taxonomy']);
        } else {
            $terms = get_terms($args);
        }

        if (empty($terms) || is_wp_error($terms)) {
            return '';
        }

        if ($atts['format'] === 'array') {
            return '<pre>' . print_r($terms, true) . '</pre>';
        }

        // CSS Classes
        $classes = 'zci-taxonomy-list zci-' . $atts['style'];

        // Grid Styles
        $style_attr = '';
        if ($atts['style'] === 'grid') {
             $style_attr = 'style="--zci-columns: ' . intval($atts['columns']) . ';"';
        }

        $output = '<ul class="' . esc_attr($classes) . '" ' . $style_attr . '>';
        
        foreach ($terms as $term) {
            $image = $this->zTaxonomyImage($term->term_id, $atts['size'], ['class' => 'zci-img'], false);

            // Skip if no image and no name to show
            if (empty($image) && $atts['show_name'] !== 'yes') {
                continue;
            }

            $link = get_term_link($term);
            
            $output .= '<li class="zci-item">';
            $output .= '<a href="' . esc_url($link) . '" class="zci-link">';
            $output .= '<span class="zci-image">' . $image . '</span>';
            
            if ($atts['show_name'] === 'yes') {
                 $output .= '<span class="zci-term-name">' . esc_html($term->name);
                 if ($atts['show_count'] === 'yes') {
                     $output .= ' <span class="zci-term-count">(' . $term->count . ')</span>';
                 }
                 $output .= '</span>';
            }
            $output .= '</a>';
            $output .= '</li>';
        }

        $output .= '</ul>';

        return $output;
    }
}

if (class_exists('ZCategoriesImages')) {
    // Instantiate the class via Singleton
    $z_categories_images = ZCategoriesImages::get_instance();

    // After activating the plugin
    register_activation_hook(__FILE__, [$z_categories_images, 'activate']);

    // After deactivating the plugin
    register_deactivation_hook(__FILE__, [$z_categories_images, 'deactivate']);
    
    function z_taxonomy_image_url($term_id = NULL, $size = 'full', $return_placeholder = FALSE) {
        $zci = ZCategoriesImages::get_instance();
        return $zci->zTaxonomyImageUrl($term_id, $size, $return_placeholder);
    }

    function z_taxonomy_image_id($term_id = NULL) {
        $zci = ZCategoriesImages::get_instance();
        return $zci->zTaxonomyImageID($term_id);
    }

    function z_taxonomy_image($term_id = NULL, $size = 'full', $attr = NULL, $echo = TRUE) {
        $zci = ZCategoriesImages::get_instance();
        return $zci->zTaxonomyImage($term_id, $size, $attr, $echo);
    }
}