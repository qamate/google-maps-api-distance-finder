<?php
/**
 * Plugin Name: Google Maps API distance finder
 * Description: This Plugin will expose an endpoint on WordPress. When called, will calculate and provide the nearest point from origin.
 * Plugin URI: 
 * Version: 1.0.0
 * Author: qamate
 * License: GPLv2 or later
 */
 
 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
 define( 'GMADF__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 
 
 
 add_action( 'rest_api_init', function () {
  register_rest_route( 'google-maps-api-distance-finder/v1', '/nearest-point', array(
    'methods' => 'GET',
    'callback' => 'nearest_point',
  ) );
} );
 
 function nearest_point( $data ) {
  $units = $data->get_param('units');
  $key = $data->get_param('key');
  $origins = $data->get_param('origins');
  $destinations = $data->get_param('destinations');
  $debug = $data->get_param('debug');
  
  $response_json = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $origins . "&destinations=" . $destinations . "&key=" . $key . "&units=" . $units);
  
  $response_array = json_decode($response_json, true);
  
  $counter = 0;
  $min_value = -2;
  $nearest_point = 0;
  
  foreach($response_array['rows'][0]['elements'] as $value){
   if($value['status'] == 'NOT_FOUND'){
    $min_value = -1;
    $counter = -1;
    break;
   }
   
   if($value['status'] != 'OK')
    continue;
   
   $destinations_array = str_getcsv($destinations, '|');
   
   if($min_value == -2){
    $min_value = $value['distance']['value'];
   }
   else{
    $log .= " Comparing " . $value['distance']['value'] . " with " .  $min_value . "\n";
    if($value['distance']['value'] <= $min_value){
     $min_value = $value['distance']['value'];
     $log .= " Min value found: " . $min_value;
     $nearest_point = $destinations_array[$counter];
     $log .= " Nearest Point: " . $nearest_point;
    }
   }
   $counter++;
  }
  
  if($min_value == -1 || $nearest_point ==0)
   $req_response['status'] =  "NOT_FOUND";
  else{
   $req_response['status'] =  "OK";
   $req_response['nearest_point'] =  $nearest_point;
  }
  
  if($debug == "true")
   $req_response = json_decode($response_json);
   
   //$req_response['log'] = $log; //uncomment this if you want to see log
  
   return $req_response;
}
 
?>