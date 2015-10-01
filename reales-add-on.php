<?php

/*
Plugin Name: WP All Import - Reales Add-On
Plugin URI: http://www.wpallimport.com/
Description: Supporting imports into the Reales theme.
Version: 1.0.7
Author: Soflyy
*/


include "rapid-addon.php";

$reales_addon = new RapidAddon( 'Reales Add-On', 'reales_addon' );

$reales_addon->disable_default_images();

$reales_addon->import_images( 'photo_gallery', 'Photo Gallery' );

function photo_gallery( $post_id, $attachment_id, $image_filepath, $import_options ) {
    
	$new_url = wp_get_attachment_url( $attachment_id );

	$old_urls = get_post_meta( $post_id, 'property_gallery', true );

	// turn leading ~~~ into commas
 	$old_urls = ( !empty ($old_urls ) ) ? str_replace( '~~~', ',', $old_urls ) : null;

 	// strip comma from front of string
 	$old_urls = ( $old_urls[0] == ',' ) ? substr( $old_urls, 1 ) : $old_urls;

 	// make an array of urls
 	$urls = explode( ',', $old_urls );

 	// convert to an array explode( ',' , $old_urls );
	$new_urls = array();

	foreach ( $urls as $url ) {

		if ( !empty( $url ) ) {

			$new_urls[] = '~~~' . $url;

		}

	}

	// add our new url
	$new_urls[] = '~~~' . $new_url;

	// turn it all back in to a string
	$new_urls = implode( '', $new_urls );

    update_post_meta( $post_id, 'property_gallery', $new_urls );
    
}

$reales_addon->import_files( 'property_plans', 'Property Plans' );

function property_plans( $post_id, $attachment_id, $image_filepath, $import_options ) {
    
	$new_url = wp_get_attachment_url( $attachment_id );

	$old_urls = get_post_meta( $post_id, 'property_plans', true );

	// turn leading ~~~ into commas
 	$old_urls = ( !empty ($old_urls ) ) ? str_replace( '~~~', ',', $old_urls ) : null;

 	// strip comma from front of string
 	$old_urls = ( $old_urls[0] == ',' ) ? substr( $old_urls, 1 ) : $old_urls;

 	// make an array of urls
 	$urls = explode( ',', $old_urls );

 	// convert to an array explode( ',' , $old_urls );
	$new_urls = array();

	foreach ( $urls as $url ) {

		if ( !empty( $url ) ) {

			$new_urls[] = '~~~' . $url;

		}

	}

	// add our new url
	$new_urls[] = '~~~' . $new_url;

	// turn it all back in to a string
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

$reales_addon->add_title( 'Property Map Location' );

$reales_addon->add_field(
	'location_settings',
	'Search Method',
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
					), // end Request Method nested radio field 

					$reales_addon->add_field( 'address_county_or_state', 'Use County or State', 'radio', 
						array(
							'state' => 'State',
							'county' => 'County',
							'county_state' => 'County, State'
						), "Google outputs both County and State/Province, but Reales WP has one field called 'County/State'" )

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
					), // end Geocode nested radio field 
					
					$reales_addon->add_field( 'coord_county_or_state', 'Use County or State', 'radio', 
						array(
							'state' => 'State',
							'county' => 'County',
							'county_state' => 'County, State'
						), "Google outputs both County and State, but Reales WP has one field called 'County/State'" )
					
				) // end Geocode settings
			) // end coordinates Option panel
		) // end Search by Coordinates radio field
	) // end Property Location radio field
);

$reales_addon->add_field( 'property_neighborhood', 'Neighborhood', 'text');

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
        'property_neighborhood'
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

    	if ( $reales_addon->can_update_meta( $field_, $import_options ) && !empty( $field ) ) {

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

            $address_data = array();

			foreach ( $details[results][0][address_components] as $type ) {

				// parse Google Maps output into an array we can use
				$address_data[ $type[types][0] ] = $type[long_name];

			}

            $lat  = $details[results][0][geometry][location][lat];

            $long = $details[results][0][geometry][location][lng];

        	$address = $address_data[street_number] . ' ' . $address_data[route];

        	$city = $address_data[locality];

        	$country = $address_data[country];

        	$zip = $address_data[postal_code];

        	$state = $address_data[administrative_area_level_1];

        	$county = $address_data[administrative_area_level_2];

        	if ( empty( $zip ) ) {

			    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a Postal Code for this property location.' );

        	}

        	if ( empty( $country ) ) {

			    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a Country for this property location.' );

        	}

        	if ( empty( $city ) ) {

			    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a City for this property location.' );

        	}

        	if ( empty( $address_data[street_number] ) ) {

			    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Number for this property location.' );

        	}

        	if ( empty( $address_data[route] ) ) {

			    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Name for this property location.' );

        	}

        }
        
    }
    
    // update location fields
    $fields = array(
        'property_address' => $address,
        'property_lat' => $lat,
        'property_lng' => $long,
        'property_city' => $city,
        'property_country' => $country,
        'property_zip' => $zip
    );

	if ( $data['location_settings'] == 'search_by_address' ) {

		$setting = $data['address_county_or_state'];

	} else { 

		$setting = $data['coord_county_or_state'];

	}

	if ( empty( $setting ) ) {

		$setting = 'state';

	}

    if ( $setting == 'state') {

        $fields[ 'property_state' ] = $state;

    	if ( empty( $state ) ) {

		    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a State for this property location.' );

    	}

	} elseif ( $setting == 'county') {

        $fields[ 'property_state' ] = $county;

    	if ( empty( $county ) ) {

		    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a County for this property location.' );

    	}

	} elseif ( $setting == 'county_state') {

    	if ( empty( $state ) ) {

		    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a State for this property location.' );

    	}

    	elseif ( empty( $county ) ) {

		    $reales_addon->log( '<b>WARNING:</b> Google Maps has not returned a County for this property location.' );

    	} else {

    		$fields[ 'property_state' ] = $county . ', ' . $state;

    	}

	}

    $reales_addon->log( '- Updating location data' );
    
    foreach ( $fields as $key => $value ) {
        
        if ( $reales_addon->can_update_meta( $key, $import_options ) ) {
            
            update_post_meta( $post_id, $key, $value );
        
        }
    }
}






