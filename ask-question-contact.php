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

add_shortcode( 'ss_ask_question', 'ss_woo_ask_question' );

function ss_woo_ask_question( $atts ) {

	global $product, $woocommerce_loop;

	ob_start();
	?>

<div class="container">
	<form method="post" id="ask-form" action="">
		<div class="gform_heading">
				<h3 class="gform_title">SELECT PRODUCT</h3>
				<p>Which Product did you have a question about?</p>
				<span class="gform_description"></span>
		</div>
		<div class="gform_body">
			<ul id="gform_fields_2" class="gform_fields as-q top_label form_sublabel_below description_below">
				<li id="field_2_6" class="gfield field_sublabel_below field_description_below">

					<div class="ginput_container ginput_container_radio">
						<ul class="gfield_radio thumb-radio-list" id="input_2_6">

							<?php
								global $post;
								$args = array(
								'post_type' => 'product',
								'orderby' => 'ASC',
								'posts_per_page'      => 55,
								'meta_query' => array(
										array(
											'key' => 'add_that_product_on_product_question_section',
											'value' => '"yes"',
											'compare' => 'LIKE'
										)
									)
								);

								$the_query = new WP_Query( $args );
							?>

							<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

							<li class="gchoice_2_6_0">
								<input name="product" type="radio" value="<?php the_title(); ?>" class="product" id="choice_<?php echo $post->ID; ?>" tabindex="5">
								<label for="choice_<?php echo $post->ID; ?>" id="label_<?php echo $post->ID; ?>">
									<span class="pro-img"><?php the_post_thumbnail( 'thumbnail' ); ?></span>
									<span><?php the_title(); ?></span>
									<span class="over"></span>
								</label>
							</li>

							<?php endwhile; else : ?>

								<p>There are no products :( </p>

							<?php endif;
							wp_reset_postdata(); ?>


						</ul>
					</div>
				</li>

				<h3 class="gform_title">CONTACT INFO</h3>
				<p>Tell us a little about yourself so we can get back to you.</p>

				<?php
					global $current_user;
					$current_user = wp_get_current_user();
				?>
				<li id="field_2_5" class="gfield add gfield_contains_required field_sublabel_below field_description_below">
					<label class="gfield_label" for="input_2_5">Your Name<span class="gfield_required">*</span></label>
					<div class="ginput_container ginput_container_text">
						<input name="name" id="name" type="text" value="<?php if ( 0 == $current_user->ID ) { echo '' ; } else { echo $current_user->display_name ; } ?>" class="medium" tabindex="2" placeholder="Your Full Name">
					</div>
				</li>
				<li id="field_2_2" class="gfield add gfield_contains_required field_sublabel_below field_description_below">
					<label class="gfield_label" for="input_2_2">Your Email Address<span class="gfield_required">*</span></label>
					<div class="ginput_container ginput_container_email">
						<input name="email" id="email" type="email" value="<?php if ( 0 == $current_user->ID ) { echo '' ; } else { echo $current_user->user_email ; } ?>" class="medium" tabindex="3" placeholder="Your Email">
					</div>
				</li>
				<li id="field_2_3" class="gfield msg-text gfield_contains_required field_sublabel_below field_description_below">
					<label class="gfield_label" for="input_2_3">Go ahead ask away. Tell us about your product question.<span class="gfield_required">*</span>
					</label>
					<div class="ginput_container ginput_container_textarea">
						<textarea name="message" id="message" class="textarea medium" tabindex="4" placeholder="Write Your Message Here." rows="10" cols="50"></textarea>
					</div>
				</li>

										</ul>
		</div>
		<div class="gform_footer top_label">
			<input type="submit" name="contact_submit" id="send-button" class="gform_button button" value="Submit" tabindex="8">
		</div>
						</form>
	<div class="col-md-6 col-md-offset-3 success alert">

	</div>
</div>

<?php

$output = ob_get_contents();
ob_end_clean();
return $output ;

}


?>
