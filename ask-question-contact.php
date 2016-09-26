<?php
/*
Plugin Name: Ask Question on Contact
Plugin URI: http://www.shoaibsiddiqi.com
Description: Allows Display of WooCommerce Products on contact page.
Author: Shoaib
Version: 1.0
Author URI: http://www.shoaibsiddiqi.com
*/

error_reporting(0);

add_action ('admin_menu', 'ms_ask_question_on_contact');

function ms_ask_question_on_contact() {
	add_menu_page('Ask Question', 'Product Questions', 9, basename(__FILE__), 'ask_question_on_contact');
	//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	add_submenu_page( 'Ask Question Emails', 'Ask Question Emails on Contact', 'Add Emails', 10, basename(__FILE__), 'sub_ask_question_on_contact' );
}

function sub_ask_question_on_contact(){
?>

	<div class="wrap">
		<h1>Add emails</h1>
	</div>

<?php 	
}

function ask_question_on_contact(){
?>



	<div class="wrap">
		<h1>Questions On Products</h1>
		<table class="wp-list-table widefat fixed striped" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="id" class="manage-column" style="width:50px;cursor:pointer;">Id</th>
					<th scope="col" id="name" class="manage-column" style="cursor:pointer;">Name</th>
					<th scope="col" id="email" class="manage-column" style="">Email</th>
					<th scope="col" id="product" class="manage-column" style="">Product</th>
					<th scope="col" id="date" class="manage-column" style="">Date </th>
					<th scope="col" id="message" class="manage-column" style="">Message </th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th scope="col" id="id" class="manage-column" style="width:50px;cursor:pointer;">Id</th>
					<th scope="col" id="name" class="manage-column" style="cursor:pointer;">Name</th>
					<th scope="col" id="email" class="manage-column" style="">Email</th>
					<th scope="col" id="product" class="manage-column" style="">Product</th>
					<th scope="col" id="date" class="manage-column" style="">Date </th>
					<th scope="col" id="message" class="manage-column" style="">Message </th>
				</tr>
			</tfoot>

			<tbody class="list:user user-list">
			<?php 
				global $wpdb;
				$result = $wpdb->get_results( "SELECT * FROM wp_ask_question_on_contact ");
				foreach($result as $row) {
			?>
			
				<tr valign="top">	
					<td class="column-id"><?php echo $row->id ; ?></td>
					<td width="300" class="column-name">
						<strong><a class="row-title" disabled="" href="#" title="Edit"><?php echo $row->name ; ?></a></strong>
					</td>
					<td class="column-email" style="width:10%;"><strong><?php echo $row->email ; ?></strong></td>
					<td class="column-product" style="width:18%;"><strong><?php echo $row->product ; ?></strong></td>
					<td class="column-created" style="width:10%;"><strong><?php echo $row->created_at ; ?></strong></td>
					<td class="column-message"><?php echo $row->message ; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>

<?php	
}


function ms_create_plugin_table()
{
    global $wpdb;
	global $charset_collate;

    $table_name = $wpdb->prefix . 'ask_question_on_contact';

    $sql = "CREATE TABLE $table_name (
      id int(11) NOT NULL AUTO_INCREMENT,
	  created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      name varchar(255) DEFAULT NULL,
	  email varchar(255) DEFAULT NULL,
	  message text NOT NULL,
	  product varchar(255) DEFAULT NULL,
      UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'ms_create_plugin_table' );