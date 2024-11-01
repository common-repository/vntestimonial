<?php
   /*
   Plugin Name: VNTestimonial
   Plugin URI: http://technaharia.in
   Description: a plugin to create company Testimonial
   Version: 1.0
   Author: Varun Naharia
   Author URI: https://stackoverflow.com/users/3851580/varun-naharia
   License: GPL2
   */
   


// Post thumbnails
add_theme_support( 'post-thumbnails' );
add_image_size( 'vn-client-image-size', 100, 100, true );


function VNtestimonial_register_post_type() {

	$labels = array(
		'name'               => esc_html__( 'Testimonials', 'VNtestimonial' ),
		'singular_name'      => esc_html__( 'Testimonial', 'VNtestimonial' ),
		'add_new'            => esc_html__( 'Add New Testimonial', 'VNtestimonial' ),
		'add_new_item'       => esc_html__( 'Add New Testimonial', 'VNtestimonial' ),
		'edit_item'          => esc_html__( 'Edit Testimonial', 'VNtestimonial' ),
		'new_item'           => esc_html__( 'New Testimonial', 'VNtestimonial' ),
		'view_item'          => esc_html__( 'View Testimonial', 'VNtestimonial' ),
		'search_items'       => esc_html__( 'Search Testimonials', 'VNtestimonial' ),
		'not_found'          => esc_html__( 'No Testimonials found', 'VNtestimonial' ),
		'not_found_in_trash' => esc_html__( 'No Testimonials found in Trash', 'VNtestimonial' ),
		'parent_item_colon'  => esc_html__( 'Parent Testimonial:', 'VNtestimonial' ),
		'menu_name'          => esc_html__( 'Testimonials', 'VNtestimonial' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-format-quote',
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => false,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			'editor',
			'thumbnail',
			'page-attributes'
		)
	);

	register_post_type( 'VNtestimonial', $args );
}

add_action( 'init', 'VNtestimonial_register_post_type' );


// Change title placeholder
function VNtestimonial_change_default_title($title) {
	$screen = get_current_screen();
	if('VNtestimonial' == $screen->post_type) {
		$title = esc_html__('Type client name here', 'VNtestimonial');
	}
	return $title;
}
add_filter('enter_title_here','VNtestimonial_change_default_title');


// show shortcode
add_filter( 'views_edit-VNtestimonial', function ( $view_shortcode ) {
	echo '<p>Shortcode <input style="background: #ffffff;width: 245px;padding: 6px;" type="text" onClick="this.select();"
value="<?php  echo do_shortcode(\'[VNtestimonial"]\'); ?>" /></p>';

	return $view_shortcode;
} );


function VNtestimonial_meta_box() {
	add_meta_box( 'VNtestimonial_meta_box_section', esc_html__('Testimonial Options', 'VNtestimonial'),
		'display_VNtestimonial_meta_box',
		'VNtestimonial', 'normal', 'high'
	);
}
add_action( 'admin_init', 'VNtestimonial_meta_box' );


function display_VNtestimonial_meta_box( $client_designation) {
	//
	$vn_designation = esc_html( get_post_meta( $client_designation->ID, 'vn_designation', true ) );

	?>
	<div class="sp-meta-box-framework">

		<div class="sp-mb-element sp-mb-field-text">
			<div class="sp-mb-title">
				<label for="vn_client_designation"><?php esc_html_e('Designation:', 'VNtestimonial') ?></label>
				<p class="sp-mb-desc"><?php esc_html_e('Type client designation here.', 'VNtestimonial') ?></p>
			</div>
			<div class="sp-mb-field-set">
				<input type="text" id="vn_client_designation" name="vn_client_designation" value="<?php echo esc_html($vn_designation); ?>"/>
			</div>
			<div class="clear"></div>
		</div>

	</div>

	<?php
}


function add_VNtestimonial_fields( $client_designation_id, $client_designation) {

	if ( $client_designation->post_type == 'VNtestimonial' ) {
		// Store data in post meta table if present in post data
		$vn_client_designation = sanitize_text_field($_POST['vn_client_designation']);

		if ( isset($tf_client_designation ) ) {
			update_post_meta( $client_designation_id, 'vn_designation', $vn_client_designation );
		}
	}
}
add_action( 'save_post', 'add_VNtestimonial_fields', 10, 2 );



// VNTestimonial shortcode
function VNtestimonial_shortcode() {

	$args = array(
		'post_type'      => 'VNtestimonial',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'posts_per_page' => -1,
	);

	$que = new WP_Query( $args );


	$custom_id = uniqid();

	$outline = '';

	$outline .= '<div id="VNtestimonial-free' . $custom_id . '" class="VNtestimonial-section">';
	if ( $que->have_posts() ) {
        $item = 0;
        $box = 0;
        
        $outline .= '<div class="carousel slide multi-item-carousel" id="theCarousel">
            <div class="carousel-inner">';
        while ( $que->have_posts() ) : $que->the_post();
        $tf_designation = esc_html( get_post_meta( get_the_ID(), 'vn_designation', true ) );
        
            
        if($item > 2)
        {
            $item = 0;
        }
        
        if($item == 0)
        {
            if($box == 0)
            {
                $outline .= '<div class="item active">';
            }
            else 
            {
                $outline .= '<div class="item">';
            }
        }
        $outline .= '
                <div class="col-xs-4">
                  <div class="white-roud-box">
                    <div class="profile-pic"> <img src="'.get_the_post_thumbnail_url().'" alt="profile-pic" title="" />
                      <div class="profile-pic profile-mask"></div>
                    </div>
                    <p>'.get_the_content().'</p>
                    <div class="dimond-devider"><span>&nbsp;</span></div>
                    <span class="profile-name">'.get_the_title().'</span> <span class="profile-location">'.$vn_designation.'</span> </div>
                </div>
              ';
            
            if($item == 2)
            {
                $outline .= '</div>';
            }
        
              $item++;
              $box++;
              
              endwhile;
              
              
              
              
            $outline .= '</div>
            <a class="left carousel-control" href="#theCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a> <a class="right carousel-control" href="#theCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a> </div>';
        
	} else {
		$outline .= '<h2 class="not-found-any-testimonial">' . esc_html__( 'No testimonials found', 'VNtestimonial' ) . '</h2>';
	}
	$outline .= '</div>';


	wp_reset_query();


	return $outline;

}

add_shortcode( 'VNtestimonial', 'VNtestimonial_shortcode' );