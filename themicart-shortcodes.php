<?php
/*
  Plugin Name: Themicart Shortcodes
  Plugin URI: http://www.themicart.com
  Description: Shortcodes for Themicart themes
  Version: 1.0.2
  Author: Themicart
  Author URI: http://www.themicart.com
  License: GPL2 or later
  Text domain: themicartshortcodes
*/

class ThemicartShortcodes {

    public function __construct() {
        add_action( 'init', array( $this, 'add_shortcodes' ) ); // initiate shortcodes
        add_filter ( 'widget_text', 'do_shortcode' ); // enable shortcodes in widgets
    }

    /*--------------------------------------------------------------------------------------
      * add_shortcodes function
      *-------------------------------------------------------------------------------------*/
    public function add_shortcodes() {
      $shortcodes = array(
        'divider',
        'dash',
        'centerdash',
        'subtitle',
        'caps',
        'lnr',
        'fa',
        'dividerbot',
        'grayborder',
        'button',
        'table',
        'collapse',
        'collapsibles',
        'tabs',
        'tab',
        'googlemap'
      );

      foreach ( $shortcodes as $shortcode ) {
        $function = 'tc_' . str_replace( '-', '_', $shortcode );
        add_shortcode( $shortcode, array( $this, $function ) );
      }
    }

  /*--------------------------------------------------------------------------------------
    * Divider light
    *-------------------------------------------------------------------------------------*/
  function tc_divider( $atts, $content = null ){
  	return '<div class="divider-light"></div>';
  }

  /*--------------------------------------------------------------------------------------
    * Divider dash
    *-------------------------------------------------------------------------------------*/
  function tc_dash( $atts, $content = null ){
    extract( shortcode_atts( array(
      'style' => 'dark', 'light',
      ), $atts ) );
    return '<div class="dash style-' . esc_attr($style) . '"></div>';
  } 

  /*--------------------------------------------------------------------------------------
    * Divider dash
    *-------------------------------------------------------------------------------------*/
  function tc_centerdash( $atts, $content = null ){
    extract( shortcode_atts( array(
      'style' => 'dark', 'light',
      ), $atts ) );
    return '<div class="dash centered style-' . esc_attr($style) . '"></div>';
  } 

  /*--------------------------------------------------------------------------------------
    * Subtitle
    *-------------------------------------------------------------------------------------*/
  function tc_subtitle( $atts, $content = null ){
    extract( shortcode_atts( array(
      'dash' => 'true', 'false',
      'style' => 'dark', 'light',
      ), $atts ) );
    return '<span class="subtitle dash-' . esc_attr($dash) . ' ' . 'style-' . esc_attr($style) . '">' . $content . '</span>';
  } 

  /*--------------------------------------------------------------------------------------
    * Caps
    *-------------------------------------------------------------------------------------*/
  function tc_caps( $atts, $content = null ){
    return '<span class="caps">' . $content . '</span>';
  }

   /*--------------------------------------------------------------------------------------
    * Lnr icon
    *-------------------------------------------------------------------------------------*/
  function tc_lnr( $atts, $content = null ){
    extract( shortcode_atts( array(
      'size' => '36',
      'color' => 'inherit',
      ), $atts ) );
    return '<span style="font-size:' . esc_attr($size) . 'px; color: ' . esc_attr($color) . ';" class="lnr '  . $content . '"></span>';
  } 

  /*--------------------------------------------------------------------------------------
    * Font awesome icon
    *-------------------------------------------------------------------------------------*/
  function tc_fa( $atts, $content = null ){
    extract( shortcode_atts( array(
      'size' => '36',
      'color' => 'inherit',
      ), $atts ) );
    return '<i style="font-size:' . esc_attr($size) . 'px; color: ' . esc_attr($color) . ';" class="fa '  . $content . '" aria-hidden="true"></i>';
  } 

  /*--------------------------------------------------------------------------------------
    * Bottom divider with Link button
    *-------------------------------------------------------------------------------------*/
  function tc_dividerbot( $atts, $content = null ){
    extract( shortcode_atts( array(
      'style' => 'light', 'dark',
      'link'  => '',
      ), $atts ) );
    return '<div class="divider-bot-' . esc_attr($style) . '"></div><a class="read-more read-more-' . esc_attr($style) . '" href="' . esc_url($link) . '">' . $content . '</a>';
  }


  /*--------------------------------------------------------------------------------------
    * Gray Border
    *-------------------------------------------------------------------------------------*/
  function tc_grayborder( $atts, $content = null ){
    extract( shortcode_atts( array(
      'style' => 'gray', 'dark',
      ), $atts ) );
    return '<div class="border-' . esc_attr($style) . '"></div>';
  }
  


  /*--------------------------------------------------------------------------------------
    * Button
    *-------------------------------------------------------------------------------------*/
  function tc_button( $atts , $content = null ) {
      extract( shortcode_atts( array(
  		'style' => 'primary', 'regular',
  		'link' => '',
      'arrow' => 'right', 'none',
  		'target' => '_self',
  		), $atts ) );

  		return '<a href="' . esc_url($link) . '" class="btn btn-' . esc_attr($style) . ' arrow-' . esc_attr($arrow) . '" target="' . esc_attr($target) . '"><span>' . $content . '</span></a>';
  }
  


  /*--------------------------------------------------------------------------------------
    * Table
    *-------------------------------------------------------------------------------------*/
  function tc_table( $atts ) {
  	extract( shortcode_atts( array(
  		'cols' => 'none',
  		'data' => 'none',
  		'type' => 'type'
  	), $atts ) );
  	$cols = explode(',',$cols);
  	$data = explode(',',$data);
  	$total = count($cols);
  	$output = '';
  	$output .= '<table class="table-'. $type .'"><tbody><tr>';
  	foreach($cols as $col):
  		$output .= '<th>'.$col.'</th>';
  	endforeach;
  	$output .= '</tr><tr>';
  	$counter = 1;
  	foreach($data as $datum):
  		$output .= '<td>'.$datum.'</td>';
  		if($counter%$total==0):
  			$output .= '</tr>';
  		endif;

  		$counter++;
  	endforeach;
  	$output .= '</tbody></table>';

  	return $output;
    }
  


  /*--------------------------------------------------------------------------------------
    * Accordion
    *-------------------------------------------------------------------------------------*/
    function tc_collapsibles( $atts, $content = null ) {
      if( isset($GLOBALS['collapsibles_count']) )
        $GLOBALS['collapsibles_count']++;
      else
        $GLOBALS['collapsibles_count'] = 0;
      $defaults = array();
      extract( shortcode_atts( $defaults, $atts ) );
      // Extract the tab titles for use in the tab widget.
      preg_match_all( '/collapse title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
      $tab_titles = array();
      if( isset($matches[1]) ){ $tab_titles = $matches[1]; }
      $output = '';
      if( count($tab_titles) ){
        $output .= '<div class="panel-group" id="accordion-' . $GLOBALS['collapsibles_count'] . '">';
        $output .= do_shortcode( $content );
        $output .= '</div>';
      } else {
        $output .= do_shortcode( $content );
      }
      return $output;
    }
  

    function tc_collapse( $atts, $content = null ) {
      if( !isset($GLOBALS['current_collapse']) )
        $GLOBALS['current_collapse'] = 0;
      else
        $GLOBALS['current_collapse']++;
      extract(shortcode_atts(array(
        "title" => '',
        "state" => false
      ), $atts));
      if ($state == "active")
        $state = 'in';
      return '<div class="panel"><div class="panel-heading"><h3 class="panel-title"><a class="accordion-toggle ' . $state . '" data-toggle="collapse" data-parent="#accordion-' . $GLOBALS['collapsibles_count'] . '" href="#collapse_' . $GLOBALS['current_collapse'] . '_'. sanitize_title( $title ) .'">' . $title . '</a></h3></div><div id="collapse_' . $GLOBALS['current_collapse'] . '_'. sanitize_title( $title ) .'" class="panel-collapse collapse ' . $state . '"><div class="panel-body">' . do_shortcode($content) . ' </div></div></div>';
    }
  

   function tc_tabs( $atts, $content = null ) {
      if( isset($GLOBALS['tabs_count']) )
        $GLOBALS['tabs_count']++;
      else
        $GLOBALS['tabs_count'] = 0;
      $defaults = array('class' => 'nav-tabs', 'style' => 'accent', 'light');
      extract( shortcode_atts( $defaults, $atts ) );
      // Extract the tab titles for use in the tab widget.
      preg_match_all( '/tab title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
      $tab_titles = array();
      if( isset($matches[1]) ){ $tab_titles = $matches[1]; }
      $output = '';
      if( count($tab_titles) ){
        $output .= '<ul class="nav ' . $class . ' style-' . $style . '" id="custom-tabs-'. rand(1, 100) .'">';
        $i = 0;
        foreach( $tab_titles as $tab ){
          if($i == 0)
            $output .= '<li class="active">';
          else
            $output .= '<li>';
          $output .= '<a href="#custom-tab-' . $GLOBALS['tabs_count'] . '-' . sanitize_title( $tab[0] ) . '"  data-toggle="tab">' . $tab[0] . '</a></li>';
          $i++;
        }
          $output .= '</ul>';
          $output .= '<div class="tab-content' . ' style-' . $style . '">';
          $output .= do_shortcode( $content );
          $output .= '</div>';
      } else {
        $output .= do_shortcode( $content );
      }
      return $output;
    }
  


  /*--------------------------------------------------------------------------------------
    * Tabs
    *-------------------------------------------------------------------------------------*/
    /*--------------------------------------------------------------------------------------
      * 
      * @author Filip Stefansson
      * @since 1.0
      *
      *-------------------------------------------------------------------------------------*/
    function tc_tab( $atts, $content = null ) {
      if( !isset($GLOBALS['current_tabs']) ) {
        $GLOBALS['current_tabs'] = $GLOBALS['tabs_count'];
        $state = 'active';
      } else {
        if( $GLOBALS['current_tabs'] == $GLOBALS['tabs_count'] ) {
          $state = '';
        } else {
          $GLOBALS['current_tabs'] = $GLOBALS['tabs_count'];
          $state = 'active';
        }
      }
      $defaults = array( 'title' => 'Tab');
      extract( shortcode_atts( $defaults, $atts ) );
      return '<div id="custom-tab-' . $GLOBALS['tabs_count'] . '-'. sanitize_title( $title ) .'" class="tab-pane animated fadeIn ' . $state . '">'. do_shortcode( $content ) .'</div>';
    }
  


  /*--------------------------------------------------------------------------------------
    * Google Map
    *-------------------------------------------------------------------------------------*/
  function tc_googlemap( $atts, $content = null ){
      extract( shortcode_atts( array(
      'height'    => '',  
        'lat'       => '',
        'long'      => '',
        'zoom'    => '15',
      'marker'    => '',
     ), $atts ) );
        
      ob_start(); ?>
         
      <div id="map" style="<?php if ( $height ) echo 'height: ' . $height . 'px;'; ?>"></div>
    
      <script src="https://maps.googleapis.com/maps/api/js?v=3"></script>

      <script type="text/javascript">
        (function($) {
          "use strict"
          $(document).ready(function(){
              
                // Basic options for a simple Google Map
                // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                var myLatlng = new google.maps.LatLng( <?php echo esc_js( $lat ); ?>, <?php echo esc_js( $long );?> );

                var mapOptions = {
                    zoom: <?php echo esc_js( $zoom ); ?>,
                    disableDefaultUI: false,
                    scrollwheel: false, 
                    center: myLatlng,

                    // This is where you can paste any style found on Snazzy Maps.
                    styles: [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]
                };

                // Get the HTML DOM element that will contain your map 
                // We are using a div with id="map"
                var mapElement = document.getElementById( 'map' );

                // Create the Google Map using out element and options defined above
                var map = new google.maps.Map( mapElement, mapOptions );
                  
          var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            icon: '<?php echo esc_js( $marker );?>',
            title: 'Our Location'
          });

        });
       })(jQuery);     
      </script>
  <?php

      return ob_get_clean();

  }
  
  
}

new ThemicartShortcodes();

/*
 * Custom Post Type: Themicart Testimonials
 * Create the custom post type: Testimonials.
 */

function register_themicart_testimonials() {

  // Labels
  $labels = array ( 
    'name' => __( 'Testimonials','themicart_testimonial' ),
    'singular_name' => __( 'Testimonial','themicart_testimonial' ),
    'add_new' => __( 'Add Testimonial','themicart_testimonial' ),
    'add_new_item' => __( 'Add new Testimonial','themicart_testimonial' ),
    'edit_item' => __( 'Edit Testimonial','themicart_testimonial' ),
    'new_item' => __( 'Add new Testimonial','themicart_testimonial' ),
    'all_items' => __( 'All Testimonials','themicart_testimonial' ),
    'view_item' => __( 'View Testimonial','themicart_testimonial' ),
    'search_item' => __( 'Search Testimonial','themicart_testimonial' ),
    'not_found' => __( 'No Testimonials found.','themicart_testimonial' ),
    'not_found_in_trash' => __( 'No Testimonial found in Trash.','themicart_testimonial' ),
    'menu_name' => 'Testimonials'
  );

  // Settings
  $args = array(
    'labels' => $labels,
    'hierarchical' => true,
    'description' => 'Manages Testimonials',
    'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => null,
    'menu_icon' => 'dashicons-admin-generic',   
    'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite'  => array( 'slug' => 'Testimonials' ),
        'capability_type' => 'post',
    'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
  );

  register_post_type( 'testimonial', $args );
}
add_action( 'init', 'register_themicart_testimonials' );


?>
