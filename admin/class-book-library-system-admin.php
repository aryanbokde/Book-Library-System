<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/aryanbokde
 * @since      1.0.0
 *
 * @package    Book_Library_System
 * @subpackage Book_Library_System/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Book_Library_System
 * @subpackage Book_Library_System/admin
 * @author     Rakesh <aryanbokde@gmail.com>
 */
class Book_Library_System_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Book_Library_System_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Book_Library_System_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-library-system-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Book_Library_System_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Book_Library_System_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-library-system-admin.js', array( 'jquery' ), $this->version, false );

	}

	//Create custom post type book
	public function bls_cusom_post_type_book() {
		// Check if the custom post type is already registered
		if (!post_type_exists('book')) {
			//Create custom post type Books
			$labels = array(
				'name'                  => _x( 'Books', 'Post type general name', 'textdomain' ),
				// 'register_meta_box_cb' => 'global_notice_meta_box',
				'singular_name'         => _x( 'Book', 'Post type singular name', 'textdomain' ),
				'menu_name'             => _x( 'Books', 'Admin Menu text', 'textdomain' ),
				'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'textdomain' ),
				'add_new'               => __( 'Add New', 'textdomain' ),
				'add_new_item'          => __( 'Add New Book', 'textdomain' ),
				'new_item'              => __( 'New Book', 'textdomain' ),
				'edit_item'             => __( 'Edit Book', 'textdomain' ),
				'view_item'             => __( 'View Book', 'textdomain' ),
				'all_items'             => __( 'All Books', 'textdomain' ),
				'search_items'          => __( 'Search Books', 'textdomain' ),
				'parent_item_colon'     => __( 'Parent Books:', 'textdomain' ),
				'not_found'             => __( 'No Books found.', 'textdomain' ),
				'not_found_in_trash'    => __( 'No Books found in Trash.', 'textdomain' ),
				'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
				'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
				'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
				'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
				'archives'              => _x( 'Book archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
				'insert_into_item'      => _x( 'Insert into Book', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
				'uploaded_to_this_item' => _x( 'Uploaded to this Book', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
				'filter_items_list'     => _x( 'Filter Books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
				'items_list_navigation' => _x( 'Books list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
				'items_list'            => _x( 'Books list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
			);
		
			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => 'book' ),
				'capability_type'    => 'post',
				'has_archive'        => false,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
				'taxonomies'   		 => array( 'publisher', 'author')
				
			);
	
			register_post_type( 'book', $args );

			// unset( $args ); //Unset Argument
        	// unset( $labels );// Unset Labels	
			//Create custom post type Taxonomy Authors
			$taxonomy_labels1 = array(
				'name'              => _x( 'Authors ', 'taxonomy general name', 'textdomain' ),
				'singular_name'     => _x( 'Author', 'taxonomy singular name', 'textdomain' ),
				'search_items'      => __( 'Search Authors', 'textdomain' ),
				'all_items'         => __( 'All Authors', 'textdomain' ),
				'parent_item'       => __( 'Parent Author', 'textdomain' ),
				'parent_item_colon' => __( 'Parent Author:', 'textdomain' ),
				'edit_item'         => __( 'Edit Author', 'textdomain' ),
				'update_item'       => __( 'Update Author', 'textdomain' ),
				'add_new_item'      => __( 'Add New Author', 'textdomain' ),
				'new_item_name'     => __( 'New Author Name', 'textdomain' ),
				'menu_name'         => __( 'Author', 'textdomain' ),
			);

			$taxonomy_args1 = array(
				'labels'                     => $taxonomy_labels1,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
			);

			register_taxonomy( 'author', array( 'book' ), $taxonomy_args1 );

			//Create custom post type Taxonomy Publishers
			$taxonomy_labels2 = array(
				'name'              => _x( 'Publishers', 'taxonomy general name', 'textdomain' ),
				'singular_name'     => _x( 'Publisher', 'taxonomy singular name', 'textdomain' ),
				'search_items'      => __( 'Search Publishers', 'textdomain' ),
				'all_items'         => __( 'All Publishers', 'textdomain' ),
				'parent_item'       => __( 'Parent Publisher', 'textdomain' ),
				'parent_item_colon' => __( 'Parent Publisher:', 'textdomain' ),
				'edit_item'         => __( 'Edit Publisher', 'textdomain' ),
				'update_item'       => __( 'Update Publisher', 'textdomain' ),
				'add_new_item'      => __( 'Add New Publisher', 'textdomain' ),
				'new_item_name'     => __( 'New Publisher Name', 'textdomain' ),
				'menu_name'         => __( 'Publisher', 'textdomain' ),
			);

			$taxonomy_args2 = array(
				'labels'                     => $taxonomy_labels2,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
			);

			register_taxonomy( 'publisher', array( 'book' ), $taxonomy_args2 );	

		}     
   
        flush_rewrite_rules();

	}

	//Create custom post type book custom field
	public function bls_add_book_custom_fields() {
		add_meta_box(
			'book_custom_fields',    // Unique ID
			'Book Details',          // Box title
			array($this, 'bls_render_book_custom_fields'), // Callback function to render the fields
			'book',                 // Custom post type
			'normal',               // Context (normal, advanced, side)
			'default'               // Priority (default, core, high, low)
		);
	}
	
	//Create callback function to display custom fields
	public function bls_render_book_custom_fields($post) {
		// Retrieve existing values from the database
		$price = get_post_meta($post->ID, '_book_price', true);
		$start_rating = get_post_meta($post->ID, '_book_start_rating', true);
	
		// Output field HTML
		?>
		<div id="postcustomstuff">
			<table id="newmeta">
				<tbody>
					<tr>
						<td id="newmetaleft" class="left">
							<b><label for="book_price">Price</label></b>
						</td>
						<td>
							<input type="text" id="book_price" name="book_price" value="<?php echo esc_attr($price); ?>" />
						</td>
					</tr>
					<tr>
						<td id="newmetaleft" class="left">
							<b><label for="book_start_rating">Start Rating</label></b>
						</td>
						<td>
							<input type="text" id="book_start_rating" name="book_start_rating" value="<?php echo esc_attr($start_rating); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
		</div>		
		<?php
	}

	//Save custom field value to database
	public function bls_save_book_custom_fields($post_id) {
		// Save custom field data
		if (array_key_exists('book_price', $_POST)) {
			update_post_meta($post_id, '_book_price', sanitize_text_field($_POST['book_price']));
		}
	
		if (array_key_exists('book_start_rating', $_POST)) {
			update_post_meta($post_id, '_book_start_rating', sanitize_text_field($_POST['book_start_rating']));
		}
	}

	// Add admin menu item
	public function bls_book_settings_menu() {
		add_menu_page(
			'Book Settings',
			'Book Settings',
			'manage_options',
			'bls-book-settings',
			array($this, 'bls_book_settings_page'),
			'dashicons-admin-generic', // Use Dashicons
			25 // Change as needed
		);
	}
	
	// Callback function to display the settings page
	public function bls_book_settings_page() {
		?>
		<div class="wrap">
			<h2>Book Settings</h2>
			<p>Please use this shortcode for search books.</p>
			<p><code>[bls_search_form]</code></p>
		</div>
		<?php
	}


}




