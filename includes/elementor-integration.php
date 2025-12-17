<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class ZCI_Elementor_Taxonomy_Image_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

    public function get_name() {
        return 'zci-taxonomy-image';
    }

    public function get_title() {
        return __( 'Taxonomy Image', 'categories-images' );
    }

    public function get_group() {
        return 'archive';
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
    }

    public function get_value( array $options = [] ) {
        $term_id = null;

        if ( is_category() || is_tag() || is_tax() ) {
            $term_id = get_queried_object_id();
        }

        if ( $term_id ) {
            $image_id = z_taxonomy_image_id( $term_id );
            
            if ( $image_id ) {
                $image_data = [
                    'id' => $image_id,
                    'url' => wp_get_attachment_image_url( $image_id, 'full' ),
                ];
                return $image_data;
            }
        }

        return [];
    }
}
