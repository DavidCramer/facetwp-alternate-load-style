<?php
/**
 * @wordpress-plugin
 * Plugin Name: FacetWP - Alternate Load Style
 * Plugin URI:  http://digilab.co.za
 * Description: An alternate loader style for FacetWP
 * Version:     1.0.0
 * Author:      David Cramer
 * Author URI:  http://cramer.co.za
 * Text Domain: facetwp-alt-loader
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
add_filter( 'facetwp_load_assets', function( $has_assets ) {
	if( true === $has_assets ){
		add_action( 'wp_footer', 'facetwp_facet_loading_animator', 100);		
	}
	return $has_assets;
});

function facetwp_facet_loading_animator(){
	?>
	<script>
	if( FWP ){ 
		var facets = {};
		jQuery( '.facetwp-facet' ).css({position: "relative"});
		FWP.loading_handler = function( args ){

			var height = args.element.height(),
				loader = jQuery( '<div style="width: 100%; margin: 0px; position: absolute; top: 0px; bottom: 0px; z-index: 999; text-align: center;"><div class="facetwp-loading" style="margin-left:auto;margin-right:auto;"></div></div>' ),
				offset = args.element.scrollTop();
			loader.css({
				"padding-top": ( height / 2 ) - 14,
				"padding-bottom": ( height / 2 ) - 14,
				"top" : offset
			});
			
			args.element.children().css({
				"opacity": '0.4'
			});
			args.element.append( loader );
			facets[ args.facet_name ] = {
				element : args.element,
				height : height
			};
		}
		jQuery(document).on('click', '.facetwp-toggle', function() {
			jQuery( document ).trigger('facetwp-loaded');
		});
		jQuery( document ).on('facetwp-loaded', function(){
			for( var e in facets ){
				if( ! facets[ e ].height ){ continue; }
				facets[ e ].element.css({
					height: ''
				});
				var new_height = facets[ e ].element.height();
				
				facets[ e ].element.height( facets[ e ].height );
				facets[ e ].element.animate( {
					opacity: 1,
					height: new_height
				}, 300 );
			}
			
		})
	}
	</script>
	<?php
}

