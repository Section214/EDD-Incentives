<?php
/**
 * Metabox functions
 *
 * @package     EDD\Incentives\Admin\MetaBoxes
 * @since       1.0.0
 * @author      Daniel J Griffiths <dgriffiths@section214.com>
 * @copyright   Copyright (c) 2014, Daniel J Griffiths
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


/**
 * Register new meta boxes
 *
 * @since       1.0.0
 * @return      void
 */
function edd_incentives_add_meta_boxes() {
    add_meta_box( 'edd-incentive-preview', __( 'Preview', 'edd-incentives' ), 'edd_incentives_render_preview', 'incentive', 'side', 'default' );
    add_meta_box( 'edd-incentive-options', __( 'Options', 'edd-incentives' ), 'edd_incentives_render_options', 'incentive', 'side', 'default' );
    add_meta_box( 'edd-incentive-exit-options', __( 'Exit Button', 'edd-incentives' ), 'edd_incentives_render_exit_options', 'incentive', 'side', 'default' );
    add_meta_box( 'edd-incentive-conditions', __( 'Conditions For Display', 'edd-incentives' ), 'edd_incentives_render_conditions', 'incentive', 'normal', 'default' );

    // Render the pseudo-metabox for the template tags
    add_action( 'edit_form_after_title', 'edd_incentives_render_template_tags' );
}
add_action( 'add_meta_boxes', 'edd_incentives_add_meta_boxes' );


/**
 * Add the tutorial pseudo meta box
 *
 * @since       1.0.0
 * @global      object $post The WordPress post object
 * @return      void
 */
function edd_incentives_render_template_tags() {
    global $post;

    if( $post->post_type == 'incentive' ) {
        ?>
            <div class="edd-incentive-template-tags postbox">
                <h3><span><?php _e( 'Template Tags', 'edd-incentives' ); ?></span></h3>
                <div class="inside">
                    <?php
                        echo '<h4>' . __( 'Use the following template tags for entering the given data in the modal.', 'edd-incentives' ) . '</h4>';

                        $template_tags = edd_incentives_get_template_tags();
                        foreach( $template_tags as $tag => $description ) {
                            echo '<div class="edd-incentive-template-tag-block">';
                            echo '<span class="edd-incentive-template-tag">{' . $tag . '}</span>';
                            echo '<span class="edd-incentive-template-tag-description">' . $description . '</span>';
                            echo '</div>';
                        }
                    ?>
                    <div class="edd-incentive-clear"></div>
                    <br />
                    <p class="edd-incentive-tip description"><?php printf( __( 'Need more help? <a href="%s">Click here</a> for a more in-depth tutorial on creating Incentives!', 'edd-incentives' ), 'edit.php?post_type=download&page=incentives-tutorial&post=' . $post->ID ); ?></p>
                </div>
            </div>
        <?php
    }
}


/**
 * Add our custom preview meta box
 *
 * @since       1.0.0
 * @global      object $post The WordPress object for this post
 * @return      void
 */
function edd_incentives_render_preview() {
    global $post;
    
    echo '<p>';
    echo '<a class="button button-primary edd-incentives-colorbox">' . __( 'Preview Incentive', 'edd-incentives' ) . '</a>';
    echo '<span class="description">' . __( 'Remember to save your changes before previewing this incentive!', 'edd-incentives' ) . '</span>';
    echo '</p>';

    echo '<div style="display: none;">';
    echo '<div id="edd-incentives-preview">';

    // Display post content
    echo edd_incentives_parse_template_tags( $post->ID );

    echo '</div>';
    echo '</div>';
}


/**
 * Add the options meta box
 *
 * @since       1.0.0
 * @global      object $post The WordPress object for this post
 * @return      void
 */
function edd_incentives_render_options() {
    global $post;

    $post_id        = $post->ID;
    $all_discounts  = edd_get_discounts();
    $meta           = get_post_meta( $post_id, '_edd_incentive_meta', true );
    $subscribe      = ( isset( $meta['subscribe'] ) ? true : false );

    // Selected discount code
    echo '<p>';
    echo '<strong><label for="_edd_incentive_discount">' . __( 'Discount Offer', 'edd-incentives' ) . '</label></strong><br />';
    echo '<select class="edd-incentives-select2" name="_edd_incentive_meta[discount]" id="_edd_incentive_discount">';
    echo '<option></option>';

    foreach( $all_discounts as $id => $discount ) {
        echo '<option value="' . $discount->ID . '"' . ( isset( $meta['discount'] ) && $meta['discount'] == $discount->ID ? ' selected' : '' ) . '>' . $discount->post_title . '</option>';
    }

    echo '</select>';
    echo '</p>';

    do_action( 'edd_incentives_options_fields', $post_id );

    wp_nonce_field( basename( __FILE__ ), 'edd_incentives_nonce' );
}


/**
 * Add the exit options meta box
 *
 * @since       1.0.0
 * @global      object $post The WordPress object for this post
 * @return      void
 */
function edd_incentives_render_exit_options() {
    global $post;

    $post_id        = $post->ID;
    $meta           = get_post_meta( $post_id, '_edd_incentive_meta', true );
    $text_css       = ( ! isset( $meta['button_type'] ) || $meta['button_type'] == 'text' ? '' : ' style="display: none;"' );
    $button_css     = ( isset( $meta['button_type'] ) && $meta['button_type'] == 'button' ? '' : ' style="display: none;"' );
    $image_css      = ( isset( $meta['button_type'] ) && $meta['button_type'] == 'image' ? '' : ' style="display: none;"' );

    // Button type
    echo '<p>';
    echo '<strong><label for="_edd_incentive_button_type">' . __( 'Button Type', 'edd-incentives' ) . '</label></strong><br />';
    echo '<select class="edd-incentives-select2" name="_edd_incentive_meta[button_type]" id="_edd_incentive_button_type">';
    echo '<option value="text"' . ( ! isset( $meta['button_type'] ) || $meta['button_type'] == 'text' ? ' selected' : '' ) . '>' . __( 'Text Link', 'edd-incentives' ) . '</option>';
    echo '<option value="image"' . ( $meta['button_type'] == 'image' ? ' selected' : '' ) . '>' . __( 'Image Link', 'edd-incentives' ) . '</option>';
    echo '<option value="button"' . ( $meta['button_type'] == 'button' ? ' selected' : '' ) . '>' . __( 'Button', 'edd-incentives' ) . '</option>';
    echo '</select>';
    echo '</p>';

    // Button text - text
    echo '<p class="edd-incentives-button-type-text"' . $text_css . '>';
    echo '<strong><label for="_edd_incentive_link_text">' . __( 'Link Text', 'edd-incentives' ) . '</label></strong><br />';
    echo '<input type="text" class="widefat" name="_edd_incentive_meta[link_text]" id="_edd_incentive_link_text" value="' . ( isset( $meta['link_text'] ) && $meta['link_text'] != '' ? $meta['link_text'] : '' ) . '" />';
    echo '</p>';
    
    // Button text - button
    echo '<p class="edd-incentives-button-type-button"' . $button_css . '>';
    echo '<strong><label for="_edd_incentive_button_text">' . __( 'Button Text', 'edd-incentives' ) . '</label></strong><br />';
    echo '<input type="text" class="widefat" name="_edd_incentive_meta[button_text]" id="_edd_incentive_button_text" value="' . ( isset( $meta['button_text'] ) && $meta['button_text'] != '' ? $meta['button_text'] : '' ) . '" />';
    echo '</p>';

    // Button image
    echo '<p class="edd-incentives-button-type-image"' . $image_css . '>';
    echo '<strong><label for="_edd_incentive_button_image">' . __( 'Button Image', 'edd-incentives' ) . '</label></strong><br />';
    if( ! isset( $meta['button_image'] ) || $meta['button_image'] == '' ) {
        echo '<input type="file" name="_edd_incentive_button_image" id="_edd_incentive_button_image" value="" size="25" />';
    } else {
        echo '<img src="' . $meta['button_image'] . '" style="width: 100%;" />';
        echo '<a href="' . add_query_arg( array( 'edd-action' => 'remove_button_image' ) ) . '" class="button button-secondary">' . __( 'Remove', 'edd-incentives' ) . '</a>';
    }
    echo '</p>';
}


/**
 * Add the conditions meta box
 *
 * @since       1.0.0
 * @global      object $post The WordPress object for this post
 * @return      void
 */
function edd_incentives_render_conditions() {
    global $post;

    $post_id        = $post->ID;
    $meta           = get_post_meta( $post_id, '_edd_incentive_meta', true );
    $downloads      = get_posts( array( 'post_type' => 'download', 'numberposts' => 999999, 'post_status' => 'publish' ) );
    $product_css    = ( ! isset( $meta['condition_type'] ) || $meta['condition_type'] == 'products' ? '' : ' style="display: none;"' );
    $value_css      = ( isset( $meta['condition_type'] ) && $meta['condition_type'] == 'value' ? '' : ' style="display: none;"' );
    $count_css      = ( isset( $meta['condition_type'] ) && $meta['condition_type'] == 'count' ? '' : ' style="display: none;"' );

    // Condition type
    echo '<p>';
    echo '<strong><label for="_edd_incentive_condition_type">' . __( 'Type', 'edd-incentives' ) . '</label></strong><br />';
    echo '<select class="edd-incentives-select2" name="_edd_incentive_meta[condition_type]" id="_edd_incentive_condition_type">';
    echo '<option value="products"' . ( ! isset( $meta['condition_type'] ) || $meta['condition_type'] == 'products' ? ' selected' : '' ) . '>' . __( 'Products in cart', 'edd-incentives' ) . '</option>';
    echo '<option value="value"' . ( $meta['condition_type'] == 'value' ? ' selected' : '' ) . '>' . __( 'Combined value', 'edd-incentives' ) . '</option>';
    echo '<option value="count"' . ( $meta['condition_type'] == 'count' ? ' selected' : '' ) . '>' . __( 'Number of products', 'edd-incentives' ) . '</option>';
    echo '</select>';
    echo '</p>';

    echo '<div class="edd-incentive-condition-product"' . $product_css . '>';

    // Condition... condition?
    echo '<p>';
    echo '<strong><label for="_edd_incentive_product_condition">' . __( 'Condition', 'edd-incentives' ) . '</label></strong><br />';
    echo '<select class="edd-incentives-select2" name="_edd_incentive_meta[product_condition]" id="_edd_incentive_product_condition">';
    echo '<option value="any"' . ( ! isset( $meta['product_condition'] ) || $meta['product_condition'] == 'any' ? ' selected' : '' ) . '>' . __( 'Any of the products', 'edd-incentives' ) . '</option>';
    echo '<option value="all"' . ( $meta['product_condition'] == 'all' ? ' selected' : '' ) . '>' . __( 'All of the products', 'edd-incentives' ) . '</option>';
    echo '</select>';
    echo '</p>';

    // Downloads
    echo '<p>';
    echo '<strong><label for="_edd_incentive_downloads">' . __( 'Downloads', 'edd-incentives' ) . '</label></strong><br />';
    echo '<select class="edd-incentives-select2" name="_edd_incentive_meta[downloads][]" id="_edd_incentive_downloads" multiple>';
    foreach( $downloads as $key => $download ) {
        echo '<option value="' . $download->ID . '"' . ( array_key_exists( $download->ID, $meta['downloads'] ) ? ' selected' : '' ) . '>' . $download->post_title . '</option>';
    }
    echo '</select>';
    echo '</p>';

    echo '</div>';

    echo '<div class="edd-incentive-condition-value"' . $value_css . '>';

    // Minimum value
    echo '<p>';
    echo '<strong><label for="_edd_incentive_minimum_value">' . __( 'Minimum Value', 'edd-incentives' ) . '</label></strong><br />';
    echo '<input type="text" name="_edd_incentive_meta[minimum_value]" id="_edd_incentive_minimum_value" value="' . ( isset( $meta['minimum_value'] ) ? $meta['minimum_value'] : '' ) . '" placeholder="' . edd_incentives_get_currency() . '" />';
    echo '</p>';

    // Maximum value
    echo '<p>';
    echo '<strong><label for="_edd_incentive_maximum_value">' . __( 'Maximum Value', 'edd-incentives' ) . '</label></strong><br />';
    echo '<input type="text" name="_edd_incentive_meta[maximum_value]" id="_edd_incentive_maximum_value" value="' . ( isset( $meta['maximum_value'] ) ? $meta['maximum_value'] : '' ) . '" placeholder="' . edd_incentives_get_currency() . '" />';
    echo '</p>';

    echo '</div>';

    echo '<div class="edd-incentive-condition-count"' . $count_css . '>';

    // Minimum count
    echo '<p>';
    echo '<strong><label for="_edd_incentive_minimum_count">' . __( 'Minimum Number', 'edd-incentives' ) . '</label></strong><br />';
    echo '<input type="number" min="0" name="_edd_incentive_meta[minimum_count]" id="_edd_incentive_minimum_count" value="' . ( isset( $meta['minimum_count'] ) && $meta['minimum_count'] != '' ? $meta['minimum_count'] : '0' ) . '" />';
    echo '</p>';

    // Maximum count
    echo '<p>';
    echo '<strong><label for="_edd_incentive_maximum_count">' . __( 'Maximum Number', 'edd-incentives' ) . '</label></strong><br />';
    echo '<input type="number" min="0" name="_edd_incentive_meta[maximum_count]" id="_edd_incentive_maximum_count" value="' . ( isset( $meta['maximum_count'] ) && $meta['maximum_count'] != '' ? $meta['maximum_count'] : '0' ) . '" />';
    echo '</p>';

    echo '</div>';
}


/**
 * Save post meta when the save_post action is called
 *
 * @since       1.0.0
 * @param       int $post_id The ID of the post we are saving
 * @global      object $post The WordPress object for this post
 * @return      void
 */
function edd_incentives_save_meta( $post_id ) {
    global $post;
    
    // Don't process if nonce can't be validated
    if( ! isset( $_POST['edd_incentives_nonce'] ) || ! wp_verify_nonce( $_POST['edd_incentives_nonce'], basename( __FILE__ ) ) ) return $post_id;

    // Don't process if this is an autosave
    if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) return $post_id;

    // Don't process if this is a revision
    if( $post->post_type == 'revision' ) return $post_id;

    // Don't process if the current user shouldn't be editing this
    if( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;

    // Whitelisted fields
    $fields = apply_filters( 'edd_incentives_fields_save', array(
        '_edd_incentive_meta'
    ) );

    if( ! empty( $_FILES ) && isset( $_FILES['_edd_incentive_button_image'] ) ) {
        $image = wp_upload_bits( $_FILES['_edd_incentive_button_image']['name'], null, file_get_contents( $_FILES['_edd_incentive_button_image']['tmp_name'] ) );

        if( $image['error'] == false ) {
            $_POST['_edd_incentive_meta']['button_image'] = $image['url'];
        }
    }

    foreach( $fields as $field ) {
        if( isset( $_POST[$field] ) ) {
            if( is_array( $_POST[$field] ) ) {
                foreach( $_POST[$field] as $field_key => $field_value ) {
                    if( is_string( $field_value ) ) {
                        $_POST[$field][$field_key] = esc_attr( $field_value );
                    }
                }

                $new = $_POST[$field];
            } else {
                if( is_string( $_POST[$field] ) ) {
                    $new = esc_attr( $_POST[$field] );
                } elseif( is_int( $_POST[$field] ) ) {
                    $new = absint( $_POST[$field] );
                } else {
                    $new = $_POST[$field];
                }
            }
            
            $new = apply_filters( 'edd_incentives_save_' . $field, $new );
            update_post_meta( $post_id, $field, $new );
        } else {
            delete_post_meta( $post_id, $field );
        }
    }
}
add_action( 'save_post_incentive', 'edd_incentives_save_meta' );
