<?php

/*
Plugin Name: WP All Import - Reales Add-On
Plugin URI: http://www.wpallimport.com/
Description: Supporting imports into the Reales theme.
Version: 1.0.4
Author: Soflyy
*/


include "rapid-addon.php";

$reales_addon = new RapidAddon( 'Reales Add-On', 'reales_addon' );

$reales_addon->disable_default_images();

$reales_addon->import_images( 'photo_gallery', 'Photo Gallery' );

function photo_gallery( $post_id, $attachment_id, $image_filepath, $import_options ) {
    
	$new_url = wp_get_attachment_url( $attachment_id );

	$urls = get_post_custom_values( 'photo_gallery', $post_id );

	$urls = ( !empty( $urls ) ) ? explode( '~~~', $urls ) : array();

	$new_urls = array();

	foreach ( $urls as $url ) {

		$new_urls[] = ( !empty( $url ) ) ? '~~~' . $url : null;

	}

	$new_urls[] = '~~~' . $new_url;

	$new_urls = implode( '', $new_urls );

    update_post_meta( $post_id, 'property_gallery', $new_urls );
    
}

$reales_addon->import_files( 'property_plans', 'Property Plans' );

function property_plans( $post_id, $attachment_id, $image_filepath, $import_options ) {
    
	$new_url = wp_get_attachment_url( $attachment_id );

	$urls = get_post_custom_values( 'property_plans', $post_id );

	$urls = ( !empty( $urls ) ) ? explode( '~~~', $urls ) : array();

	$new_urls = array();

	foreach ( $urls as $url ) {

		$new_urls[] = ( !empty( $url ) ) ? '~~~' . $url : null;

	}

	$new_urls[] = '~~~' . $new_url;

	$new_urls = implode( '', $new_urls );

    update_post_meta( $post_id, 'property_plans', $new_urls );
    
}

$reales_addon->add_field( 'property_price', 'Price', 'text');

$reales_addon->add_field( 'property_price_label', 'Price Label', 'text', null, 'Example: per month');

$reales_addon->add_field( 'property_price', 'Price', 'text');

$reales_addon->add_field( 'property_area', 'Area', 'text');

$reales_addon->add_field( 'property_bedrooms', 'Bedrooms', 'text');

$reales_addon->add_field( 'property_bathrooms', 'Bathrooms', 'text');

$reales_addon->add_field( 'property_amenities', 'Amenities', 'text', null, 'Comma separated list of property amenities.');

$reales_addon->add_field( 'property_featured', 'Featured', 'radio', 
	array(
		'' => 'No',
		'1' => 'Yes'
) );

$reales_addon->add_field( 'property_agent', 'Agent', 'text');

$reales_addon->add_title( 'Custom Property Fields' );

$reales_addon->add_text( 'To edit existing and add new custom property details go to Appearance > Reales WP Settings > Property Custom Fields. Make sure that the imported values conform to the requirements of the selected field type designated on the Reales WP settings page.' );


// get all the property fields

$property_fields = get_option( 'reales_fields_settings' );


// build the key array for the UI and the field array for the actual import

if ( !empty( $property_fields ) ) {

	foreach ( $property_fields as $property_field => $property_field_label ) {

		$key = $property_field;

		$type = str_replace( '_', ' ', $property_field_label['type'] );

		$label = $property_field_label['label'] . ' (' . $type . ')';

		$reales_addon->add_field( '_property_field_' . $key, $label, 'text' );

	}
}

$reales_addon->add_title( 'Video' );

$reales_addon->add_field( 'property_video_source', 'Video Source', 'radio', 
	array(
		'youtube' => 'YouTube',
		'vimeo' => 'Vimeo'
) );

$reales_addon->add_field( 'property_video_id', 'Video ID', 'text', null, 'Video ID from http://www.youtube.com/watch?v=dQw4w9WgXcQ would be: dQw4w9WgXcQ' );


$reales_addon->add_field(
	'location_settings',
	'Property Map Location',
	'radio', 
	array(
		'search_by_address' => array(
			'Search by Address',
			$reales_addon->add_options( 
				$reales_addon->add_field(
					'property_address',
					'Property Address',
					'text'
				),
				'Google Geocode API Settings', 
				array(
					$reales_addon->add_field(
						'address_geocode',
						'Request Method',
						'radio',
						array(
							'address_no_key' => array(
								'No API Key',
								'Limited number of requests.'
							),
							'address_google_developers' => array(
								'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
								$reales_addon->add_field(
									'address_google_developers_api_key', 
									'API Key', 
									'text'
								),
								'Up to 2500 requests per day and 5 requests per second.'
							),
							'address_google_for_work' => array(
								'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
								$reales_addon->add_field(
									'address_google_for_work_client_id', 
									'Google for Work Client ID', 
									'text'
								), 
								$reales_addon->add_field(
									'address_google_for_work_digital_signature', 
									'Google for Work Digital Signature', 
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Request Method options array
					) // end Request Method nested radio field 
				) // end Google Geocode API Settings fields
			) // end Google Gecode API Settings options panel
		), // end Search by Address radio field
		'search_by_coordinates' => array(
			'Search by Coordinates',
			$reales_addon->add_field(
				'property_lat', 
				'Latitude', 
				'text', 
				null, 
				'Example: 34.0194543'
			),
			$reales_addon->add_options( 
				$reales_addon->add_field(
					'property_lng', 
					'Longitude', 
					'text', 
					null, 
					'Example: -118.4911912'
				), 
				'Google Geocode API Settings', 
				array(
					$reales_addon->add_field(
						'coord_geocode',
						'Request Method',
						'radio',
						array(
							'coord_no_key' => array(
								'No API Key',
								'Limited number of requests.'
							),
							'coord_google_developers' => array(
								'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
								$reales_addon->add_field(
									'coord_google_developers_api_key', 
									'API Key', 
									'text'
								),
								'Up to 2500 requests per day and 5 requests per second.'
							),
							'coord_google_for_work' => array(
								'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
								$reales_addon->add_field(
									'coord_google_for_work_client_id', 
									'Google for Work Client ID', 
									'text'
								), 
								$reales_addon->add_field(
									'coord_google_for_work_digital_signature', 
									'Google for Work Digital Signature', 
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Geocode API options array
					) // end Geocode nested radio field 
				) // end Geocode settings
			) // end coordinates Option panel
		) // end Search by Coordinates radio field
	) // end Property Location radio field
);

$reales_addon->set_import_function( 'reales_addon_import' );

$reales_addon->admin_notice(
	'The Reales WP Add-On requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=reales" target="_blank">Pro</a> or <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">Free</a>, and the <a href="http://themeforest.net/item/reales-wp-real-estate-wordpress-theme/10330568">Reales WP</a> theme.',
	array( 
		'themes' => array( 'Reales WP' )
) );

$reales_addon->run( array(
		'themes' => array( 'Reales WP' ),
		'post_types' => array( 'property' ) 
) );

function reales_addon_import( $post_id, $data, $import_options ) {
    
    global $reales_addon;
    
    // all fields except for slider and image fields
    $fields = array(
        'property_price',
        'property_price_label',
        'property_area',
        'property_bedrooms',
        'property_bathrooms',
        'property_video_source',
        'property_video_id',
        'property_featured',
    );

	// get all the property fields
	$property_fields = get_option( 'reales_fields_settings' );

	// an array for property field postmeta keys
	$property_fields_keys = array();

	// build the key array for the UI and the field array for the actual import
	foreach ( $property_fields as $property_field => $property_field_value ) {

		$key = $property_field;
		
		$property_fields_keys[] = '_property_field_' . $key;
	}
    
    $fields = array_merge( $fields, $property_fields_keys );

    // update everything in fields arrays
    foreach ( $fields as $field ) {

        if ( $reales_addon->can_update_meta( $field, $import_options ) ) {

			if ( in_array( $field, $property_fields_keys ) ) {

            	$key = substr( $field, 16 );

            	update_post_meta( $post_id, $key, $data[$field] );

            } else {

                update_post_meta( $post_id, $field, $data[$field] );

            }
        }
    }

    // clear image fields to override import settings
    $fields = array(
    	'property_gallery',
    	'property_plans'
    );

    if ( $reales_addon->can_update_image( $import_options ) ) {

    	foreach ($fields as $field) {

	    	delete_post_meta($post_id, $field);

	    }

    }

	// update agent, create a new one if not found
	$field = 'property_agent';

	$post_type = 'agent';

	if ( $reales_addon->can_update_meta( $field, $import_options ) ) {

		$post = get_page_by_title( $data[$field], 'OBJECT', $post_type );

		if ( !empty( $post ) ) {

			update_post_meta( $post_id, $field, $post->ID );

		} else {

			// insert title and attach to property
			$postarr = array(
			  'post_content'   => '',
			  'post_name'      => $data[$field],
			  'post_title'     => $data[$field],
			  'post_type'      => $post_type,
			  'post_status'    => 'publish',
			  'post_excerpt'   => ''
			);

			wp_insert_post( $postarr );

			$post = get_page_by_title( $data[$field], 'OBJECT', $post_type );

			update_post_meta( $post_id, $field, $post->ID );

		}
	}
    
    // add empty amenities
    $fields_option = get_option( 'reales_amenities_settings' );

    $fields = explode( ',', $fields_option['reales_amenities_field'] );
    
    $amenities = array();

    foreach ( $fields as $field ) {

    	if ( !empty( $field ) ) {

	    	$field = trim($field);

	    	$amenities[] = $field;

	    	$field = sanitize_key(str_replace(' ', '_', $field));

	    	if ( $reales_addon->can_update_meta( $field, $import_options ) ) {

	            update_post_meta( $post_id, $field );

	        }
	    }
    }
    
    // add imported amenities
	$fields = explode(',', $data['property_amenities']);

    $reales_addon->log( 'Updating Amenities' );

    foreach ($fields as $field) {

    	$field = trim($field);

    	$field_ = sanitize_key(str_replace(' ', '_', $field));

    	if ( $reales_addon->can_update_meta( $field_, $import_options ) ) {

            update_post_meta( $post_id, $field_, 1 );

            // add new amenities to amenities list

            if ( !in_array( $field, $amenities ) ) {

			    $reales_addon->log( '- <b>WARNING:</b> Existing feature "' . $field . '" not found, adding to database and assigning to property' );

            	$amenities[] = $field;

            }
        }
    }

    // replace wp_estate_feature_list option with amenities list
    // 
    $amenities = implode(',', $amenities);

    $fields_option['reales_amenities_field'] = $amenities;

    update_option( 'reales_amenities_settings', $fields_option );


    // update property location
    $field   = 'property_address';

    $address = $data[$field];

    $lat  = $data['property_lat'];

    $long = $data['property_lng'];
    
    //  build search query
    if ( $data['location_settings'] == 'search_by_address' ) {

    	$search = ( !empty( $address ) ? 'address=' . rawurlencode( $address ) : null );

    } else {

    	$search = ( !empty( $lat ) && !empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );

    }

    // build api key
    if ( $data['location_settings'] == 'search_by_address' ) {
    
    	if ( $data['address_geocode'] == 'address_google_developers' && !empty( $data['address_google_developers_api_key'] ) ) {
        
	        $api_key = '&key=' . $data['address_google_developers_api_key'];
	    
	    } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty( $data['address_google_for_work_client_id'] ) && !empty( $data['address_google_for_work_signature'] ) ) {
	        
	        $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

	    }

    } else {

    	if ( $data['coord_geocode'] == 'coord_google_developers' && !empty( $data['coord_google_developers_api_key'] ) ) {
        
	        $api_key = '&key=' . $data['coord_google_developers_api_key'];
	    
	    } elseif ( $data['coord_geocode'] == 'coord_google_for_work' && !empty( $data['coord_google_for_work_client_id'] ) && !empty( $data['coord_google_for_work_signature'] ) ) {
	        
	        $api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];

	    }

    }

    // if all fields are updateable and $search has a value
    if ( $reales_addon->can_update_meta( $field, $import_options ) && $reales_addon->can_update_meta( 'property_location', $import_options ) && !empty ( $search ) ) {
        
        // build $request_url for api call
        $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;
        $curl        = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $request_url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

        $reales_addon->log( '- Getting location data from Geocoding API: '.$request_url );

        $json = curl_exec( $curl );
        curl_close( $curl );
        
        // parse api response
        if ( !empty( $json ) ) {

            $details = json_decode( $json, true );

            $lat  = $details[results][0][geometry][location][lat];

            $long = $details[results][0][geometry][location][lng];

        	$address = $details[results][0][address_components][0][long_name] . ' ' . $details[results][0][address_components][1][long_name];

        	$city = $details[results][0][address_components][4][long_name];

        	$state = $details[results][0][address_components][6][long_name];

        	$country = $details[results][0][address_components][7][long_name];

        	$zip = $details[results][0][address_components][8][long_name];

        	$neighborhood = $details[results][0][address_components][2][long_name];

        }
        
    }
    
    // update location fields
    $fields = array(
        'property_address' => $address,
        'property_lat' => $lat,
        'property_lng' => $long,
        'property_city' => $city,
        'property_state' => $state,
        'property_country' => $country,
        'property_zip' => $zip,
        'property_neighborhood' => $neighborhood,

    );

    $reales_addon->log( '- Updating location data' );
    
    foreach ( $fields as $key => $value ) {
        
        if ( $reales_addon->can_update_meta( $key, $import_options ) ) {
            
            update_post_meta( $post_id, $key, $value );
        
        }
    }
}






