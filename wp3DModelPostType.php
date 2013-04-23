<?php
/*
Plugin Name: 3D Model Post Type Plugin
Plugin URI: TBD
Description: A plugin to create a special post type for sharing STL files.
Version: 1.0
Author: Agustin Sevilla
Author URI: http://agustinsevilla.com/

License: GPL2

Copyright 2013  Agustin Sevilla  (email : me@agustinsevilla.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/


add_action('init', 'threeDModel_register');
 
function threeDModel_register() {
 
	$labels = array(
		'name' => _x('My 3D Objects', 'post type general name'),
		'singular_name' => _x('3D Object', 'post type singular name'),
		'add_new' => _x('Add New', '3D Object'),
		'add_new_item' => __('Add New 3D Object'),
		'edit_item' => __('Edit 3D Object'),
		'new_item' => __('New 3D Object'),
		'view_item' => __('View 3D Object'),
		'search_items' => __('Search 3D'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => plugins_url( 'images/3dObjectIcon.png' , __FILE__ ),
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','comments','thumbnail','revisions'),
    'has_archive' => true
	  ); 
 
	register_post_type( '3DModel' , $args );
	
	//If there is permalink wonkiness enable this:
	flush_rewrite_rules();
}

/*
register_taxonomy("Skills", array("3DModel"), array("hierarchical" => true, "label" => "Skills", "singular_label" => "Skill", "rewrite" => true));
*/

add_action("admin_init", "admin_init");
 
function admin_init(){
  add_meta_box("collaborate-meta", "3D Repo URLs", "collaborate_meta", "3DModel", "side", "low");
  add_meta_box("purchase-meta", "Fabrication Sevices", "purchase_meta", "3DModel", "side", "low");
  add_meta_box("replication_meta", "Instructions, Recommended Settings &amp; History", "replication_meta", "3DModel", "normal", "low");
}
 
function collaborate_meta(){
  global $post;
  $custom = get_post_custom($post->ID);
  $collaborate_thingiverse = $custom["collaborate_thingiverse"][0];
  $collaborate_tinkercad = $custom["collaborate_tinkercad"][0];
  $collaborate_sketchup = $custom["collaborate_sketchup"][0];
  $collaborate_tpb = $custom["collaborate_tpb"][0];

  ?>
  <p>
    <label>Thingiverse URL:</label><br>
    <input name="collaborate_thingiverse" value="<?php echo $collaborate_thingiverse; ?>" />
  </p>
  <p>
    <label>Tinkercad URL:</label><br>
    <input name="collaborate_tinkercad" value="<?php echo $collaborate_tinkercad; ?>" />
  </p>
  <p>
    <label>Sketchup URL:</label><br>
    <input name="collaborate_sketchup" value="<?php echo $collaborate_sketchup; ?>" />
  </p>
  <p>
    <label>The Pirate Bay URL:</label><br>
    <input name="collaborate_tpb" value="<?php echo $collaborate_tpb; ?>" />
  </p>
  <?php
}
 
function purchase_meta(){
  global $post;
  $custom = get_post_custom($post->ID);
  $purchase_shapeways = $custom["purchase_shapeways"][0];
  $purchase_ponoko = $custom["purchase_ponoko"][0];
  $purchase_imaterialise = $custom["purchase_imaterialise"][0];
  $purchase_sculpteo = $custom["purchase_sculpteo"][0];

  ?>
  <p>
    <label>Shapeways:</label><br>
    <input name="purchase_shapeways" value="<?php echo $purchase_shapeways; ?>" />
  </p>
  <p>
    <label>Ponoko:</label><br>
    <input name="purchase_ponoko" value="<?php echo $purchase_ponoko; ?>" />
  </p>
  <p>
    <label>i.materialise:</label><br>
    <input name="purchase_imaterialise" value="<?php echo $purchase_imaterialise; ?>" />
  </p>
  <p>
    <label>Sculpteo:</label><br>
    <input name="purchase_sculpteo" value="<?php echo $purchase_sculpteo; ?>" />
  </p>
  <?php
}
 
function replication_meta() {
  global $post;
  $custom = get_post_custom($post->ID);
  $instructions = $custom["instructions"][0];
  $recommended_settings = $custom["recommended_settings"][0];
  $object_lineage = $custom["object_lineage"][0];
  ?>
  <h4><label>Instructions:</label></h4>    
  <?php wp_editor( $instructions, 'wp3dInstructions', array('wpautop' => true, 'textarea_name' => 'instructions') ); ?> 

  <h4><label>Recommended Settings:</label></h4> 
  <?php wp_editor( $recommended_settings, 'wp3dSettings', array('wpautop' => true, 'textarea_name' => 'recommended_settings') ); ?> 

  <h4><label>Lineage:</label></h4> 
  <?php wp_editor( $object_lineage, 'wp3dLineage', array('wpautop' => true, 'textarea_name' => 'object_lineage') ); ?> 

  <?php
}


add_action('save_post', 'save_3d_object_metadata');

function save_3d_object_metadata(){
  global $post;
 
  update_post_meta($post->ID, "collaborate_thingiverse", $_POST["collaborate_thingiverse"]);
  update_post_meta($post->ID, "collaborate_tinkercad", $_POST["collaborate_tinkercad"]);
  update_post_meta($post->ID, "collaborate_sketchup", $_POST["collaborate_sketchup"]);
  update_post_meta($post->ID, "collaborate_tpb", $_POST["collaborate_tpb"]);

  update_post_meta($post->ID, "purchase_shapeways", $_POST["purchase_shapeways"]);
  update_post_meta($post->ID, "purchase_ponoko", $_POST["purchase_ponoko"]);
  update_post_meta($post->ID, "purchase_imaterialise", $_POST["purchase_imaterialise"]);
  update_post_meta($post->ID, "purchase_sculpteo", $_POST["purchase_sculpteo"]);

  update_post_meta($post->ID, "instructions", $_POST["instructions"]);
  update_post_meta($post->ID, "recommended_settings", $_POST["recommended_settings"]);
  update_post_meta($post->ID, "object_lineage", $_POST["object_lineage"]);
}

?>