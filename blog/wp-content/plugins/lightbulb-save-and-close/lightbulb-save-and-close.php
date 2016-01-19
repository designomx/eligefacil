<?php

/**
 * Plugin Name: Lightbulb Save and Close
 * Plugin URI:  http://lightbulbdigital.com.au/wordpress-plugins
 * Description: Adds a button to the Edit Post page which saves the post and redirects back to the post listing page.
 * Version:     1.2.1
 * Author:      Lightbulb Digital
 * Author URI:  http://lightbulbdigital.com.au
 * License:     GPL2
 */

// Set up the plugin if the user has access to the admin area
add_action('admin_init', array('lb_save_close', 'init'));

class lb_save_close {

	/**
	 * Hook into various WordPress events
	 */
	public static function init() {
		add_action('post_submitbox_misc_actions', array('lb_save_close', 'add_button')); // add button
		add_filter('redirect_post_location', array('lb_save_close', 'redirect'), '99'); // change redirect URL
		add_action('admin_notices', array('lb_save_close', 'saved_notice'));
	}

	/**
	 * Adds the custom button into the post edit page
	 */
	public static function add_button() {
		// work out if post is published or not
		$status = get_post_status($_GET['post']);
		// if the post is already published, label the button as "update"
		$button_label = ($status == 'publish' || $status == 'private') ? 'Update and Close' : 'Publish and Close';

		// TODO: fix duplicated IDs
		?>

		<div id="major-publishing-actions" style="overflow:hidden">
			<div id="publishing-action">
				<input type="hidden" name="saveclose_referer" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
				<input type="submit" tabindex="5" value="<?php echo $button_label ?>" class="button-primary" id="custom" name="save-close">
			</div>
		</div>

		<?php
	}

	/**
	 * Generates the URL to redirect to
	 * @param $location The redirect location (we're overwriting this)
	 * @return string The new URL to redirect to, which should be the post listing page of the relevant post type
	 */
	public static function redirect($location) {
		if (!isset($_POST['save-close'])) return $location;

		// determine the post status (private if selected, else published)
		//$post_status = ($_POST['post_status'] == 'private') ? 'private' : 'publish';

		// we want to publish new posts
		$post_status = 'publish';

		// if the post was published, allow the status to be changed to something else (eg. draft)
		if ($_POST['original_post_status'] == 'publish' || $_POST['original_post_status'] == 'private') {
			$post_status = $_POST['post_status'];
		}
		// handle private post visibility
		if ($_POST['post_status'] == 'private') {
			$post_status = 'private';
		}

		wp_update_post(array('ID' => $_POST['post_ID'], 'post_status' => $post_status));

		// if we have an HTTP referer saved, and it's a post listing page, redirect back to that (maintains pagination, filters, etc.)
		if (isset($_POST['saveclose_referer']) && strstr($_POST['saveclose_referer'], 'edit.php') !== false) {
			if (strstr($_POST['saveclose_referer'], 'lbsmessage') === false) {
				if (strstr($_POST['saveclose_referer'], '?') === false) {
					return $_POST['saveclose_referer'] . '?lbsmessage=1';
				}
				return $_POST['saveclose_referer'] . '&lbsmessage=1';
			}
			return $_POST['saveclose_referer'];
		}
		// no referer saved, just redirect back to the main post listing page for the post type
		else {
			return get_admin_url() . 'edit.php?lbsmessage=1&post_type=' . $_POST['post_type'];
		}
	}

	/**
	 * Display a notice on the post listing page to inform the user that a post was saved
	 */
	public static function saved_notice() {
		if (isset($_GET['lbsmessage'])) {
			?>
			<div class="updated">
				<p>Post saved</p>
			</div>
			<?php
		}
	}

}