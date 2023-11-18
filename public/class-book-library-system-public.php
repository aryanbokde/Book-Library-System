<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/aryanbokde
 * @since      1.0.0
 *
 * @package    Book_Library_System
 * @subpackage Book_Library_System/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Book_Library_System
 * @subpackage Book_Library_System/public
 * @author     Rakesh <aryanbokde@gmail.com>
 */
class Book_Library_System_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		
		wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-library-system-public.css', array(), time(), 'all' );
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', array(), '5.15.3', 'all');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-library-system-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-library-system-public.js', array( 'jquery' ), time(), false );
		wp_localize_script($this->plugin_name, 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
		wp_enqueue_script('jquery-ui-core');
    	wp_enqueue_script('jquery-ui-slider');

	}
	
	//Regiter shortcode for publicaly 
	public function bls_register_shortcodes() {
		add_shortcode( 'bls_search_form', array( $this, 'bls_book_search_form_shortcode') );
	}

	//Create a function to display shortcode
	public function bls_book_search_form_shortcode() {
		ob_start(); ?>
		<style>
			div#price-range-slider {
				width: 90%;
				margin: 15px auto;
			}
		</style>
		<form id="book-search-form" style="width:100%">

			<h3 style="text-align:center">Book Search </h3>
			<table id="book-table" class="book-table">
				<tr>
					<td>Book Name:</td>
					<td><input type="text" id="book-search" name="book_search" placeholder="Book Name"/></td>
					<td>Author:</td>
					<td><input type="text" id="book-author" name="book_author" placeholder="Author Name"/></td>
				</tr>
				<tr>
					<td>Publisher:</td>
					<td><input type="text" id="book-publisher" name="book_publisher" placeholder="Publisher Name"/></td>
					<td>Rating:</td>
					<td><input type="number" id="book-rating" name="book_rating" placeholder="Rating Number"  min="1" max="5"/></td>
				</tr>
				<tr>
					<td colspan="1">Book Price:</td>
					<td colspan="3">
						<div class="slider-box">
							<div id="price-range-slider"></div>
							<input type="hidden" name="min_price" id="min-price" />
							<input type="hidden" name="max_price" id="max-price"/>
						</div>
						<p><span id="price-display">1-3000</span></p>
					</td>
				</tr>
				<tr>
					<td colspan="4" align="center"><input type="submit" value="Search" /></td>
				</tr>
			</table>				
		</form>
		<div id="book-search-results"></div>
		
		<?php
		return ob_get_clean();
	}

	public function bls_book_search_ajax_handler() {

		
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

			$args = array(
				'post_type'      => 'book',
				'posts_per_page' => -1,
				'post_status' => 'publish',
			);

            $query = new WP_Query($args);
			
			if ($query->have_posts()) { ?>
				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Title</th>
							<th scope="col">Price</th>
							<th scope="col">Author</th>
							<th scope="col">Publisher</th>
							<th scope="col">Rating</th>
						</tr>
					</thead>
					<tbody>
				<?php 
				$counter = 1;
				while ($query->have_posts()) {
					$query->the_post();

					// Get custom field value
					$price = get_post_meta(get_the_ID(), '_book_price', true);
					$rating = get_post_meta(get_the_ID(), '_book_start_rating', true);

					// Get taxonomy terms (replace 'your_taxonomy' with your actual taxonomy name)
					$auhtors = wp_get_post_terms(get_the_ID(), 'author', array('fields' => 'names'));
					$publishers = wp_get_post_terms(get_the_ID(), 'publisher', array('fields' => 'names'));

					
					echo '<tr>';
							echo '<td scope="row">' .$counter. '</td>';
							echo '<td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
							echo '<td>$'.$price.'</td>';
							echo '<td>';
							if ($auhtors && !is_wp_error($auhtors)) {							
								$author_names = array();				
								foreach ($auhtors as $auhtor) {
									$author_names[] = $auhtor;
								}				
								$author_string = implode(', ', $author_names);				
								echo $author_string;							
							}
							echo '</td>';
							echo '<td>';
							if ($publishers && !is_wp_error($publishers)) {							
								$term_names = array();				
								foreach ($publishers as $publisher) {
									$term_names[] = $publisher;
								}				
								$taxonomy_string = implode(', ', $term_names);				
								echo $taxonomy_string;							
							}
							echo '</td>';
							echo '<td>';
								if(!empty($rating)){
									for ($i = 1; $i <= $rating; $i++) {
										echo '<span style="margin-right:5px;"><i class="fas fa-star"></i></span>';
									}
								}
							echo '</td>';
					echo '</tr>';
					
					// Increment the counter
					$counter++;
				} ?>
					</tbody>
				</table>
				<?php	

			} else {
				echo '<p>No results found</p>';
			}			
			wp_die();

        }elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $form_data = $_POST['form_data'];
			if (!empty($form_data)) {

				// Parse the query string into an array
				parse_str($form_data, $form_array);
				
				// Access individual form fields
				$book_name = sanitize_text_field($form_array['book_search']);
				$book_author = sanitize_text_field($form_array['book_author']);
				$book_publisher = sanitize_text_field($form_array['book_publisher']);
				$book_rating = sanitize_text_field($form_array['book_rating']);
				$min_price = sanitize_text_field($form_array['min_price']);
				$book_price = sanitize_text_field($form_array['max_price']);
				

				//condition for 1 input box
				if(!empty($book_name) && empty($book_author) && empty($book_publisher) && empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
					);
				}

				if(empty($book_name) && !empty($book_author) && empty($book_publisher) && empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name', // Change field as needed (name, slug, term_id)
								'terms'    => $book_author,
							),
						),
					);
				}

				if(empty($book_name) && empty($book_author) && !empty($book_publisher) && empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name', // Change field as needed (name, slug, term_id)
								'terms'    => $book_publisher,
							),
						),
					);
				}

				if(empty($book_name) && empty($book_author) && empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'meta_query'     => array(
							array(
								'key'   => '_book_start_rating',
								'value' => $book_rating,
							),
						),
					);
				}

				if(empty($book_name) && empty($book_author) && empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'meta_query'     => array(
							array(
								'key' => '_book_price', // Replace 'field_name' with the actual custom field key
								'value' => array( $min_price, $book_price ), // Set the minimum and maximum values
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),					
						),
					);
				}

				//condition for 2 input box
				if(!empty($book_name) && !empty($book_author) && empty($book_publisher) && empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name', // Change field as needed (name, slug, term_id)
								'terms'    => $book_author,
							),
						),
					);
				}

				if(!empty($book_name) && empty($book_author) && !empty($book_publisher) && empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name', // Change field as needed (name, slug, term_id)
								'terms'    => $book_publisher,
							),
						),
					);
				}

				if(!empty($book_name) && empty($book_author) && empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'meta_query'     => array(
							array(
								'key'   => '_book_start_rating',
								'value' => $book_rating,
							),
						),
					);
				}

				if(!empty($book_name) && empty($book_author) && empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'meta_query'     => array(
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
						),
					);
				}
				
				//condition for 3 input box
				if(!empty($book_name) && !empty($book_author) && !empty($book_publisher) && empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
					);
					
				}

				if(!empty($book_name) && !empty($book_author) && empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
						),
						'meta_query'     => array(
							array(
								'key'   => '_book_start_rating',
								'value' => $book_rating,
							),
						),
					);			
				}

				if(!empty($book_name) && !empty($book_author) && empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
						),
						'meta_query'     => array(
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
						),
					);			
				}

				//condition for 4 input box
				if(!empty($book_name) && !empty($book_author) && !empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							array(
								'key'   => '_book_start_rating',
								'value' => $book_rating,
							),
						),
					);
					
				}

				if(!empty($book_name) && !empty($book_author) && !empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
						),
					);
					
				}

				//condition for 5 input box
				if(!empty($book_name) && !empty($book_author) && !empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
						),
					);
					
				}

				//condition for 6 input box
				if(empty($book_name) && empty($book_author) && empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
						),
					);			
				}

				if(empty($book_name) && empty($book_author) && !empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
						),
					);
					
				}

				if(empty($book_name) && !empty($book_author) && empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
						),
						'meta_query'     => array(
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
						),
					);			
				}

				if(!empty($book_name) && empty($book_author) && empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'meta_query'     => array(
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
						),
					);			
				}

				//condition for 7 input box
				if(empty($book_name) && empty($book_author) && !empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
						),
					);
					
				}

				if(empty($book_name) && !empty($book_author) && empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
						),
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
						),
					);
					
				}

				if(!empty($book_name) && empty($book_author) && empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
						),
					);
					
				}

				//condition for 8 input box
				if(empty($book_name) && !empty($book_author) && !empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
						),
					);
					
				}
				
				//condition for 9 input box
				if(empty($book_name) && empty($book_author) && !empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),					
						),
					);
					
				}

				if(empty($book_name) && !empty($book_author) && empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
						),
						'meta_query'     => array(
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),					
						),
					);
					
				}

				//condition for 10 input box
				if(empty($book_name) && !empty($book_author) && !empty($book_publisher) && empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
					);
					
				}

				// //condition for 11 input box
				if(empty($book_name) && !empty($book_author) && !empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
							
						),
					);			
				}

				if(!empty($book_name) && empty($book_author) && !empty($book_publisher) && !empty($book_rating) && empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
							
						),
					);
					
				}
				
				//condition for 12 input box
				if(!empty($book_name) && !empty($book_author) && empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'author',
								'field'    => 'name',
								'terms'    => $book_author,
							),
						),
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							
						),
					);
					
				}

				if(!empty($book_name) && empty($book_author) && !empty($book_publisher) && empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							
						),
					);
					
				}

				if(!empty($book_name) && empty($book_author) && !empty($book_publisher) && !empty($book_rating) && !empty($book_price)){
					$args = array(
						'post_type'      => 'book',
						'posts_per_page' => -1,
						's'              => $book_name,
						'post_status' => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'publisher',
								'field'    => 'name',
								'terms'    => $book_publisher,
							),
						),
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key'     => '_book_start_rating',
								'value'   => $book_rating,
							),
							array(
								'key' => '_book_price', 
								'value' => array( $min_price, $book_price ),
								'type' => 'NUMERIC',
								'compare' => 'BETWEEN',
							),
							
						),
					);
					
				}

				$query = new WP_Query($args);
			
				if ($query->have_posts()) { ?>
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Title</th>
								<th scope="col">Price</th>
								<th scope="col">Author</th>
								<th scope="col">Publisher</th>
								<th scope="col">Rating</th>
							</tr>
						</thead>
						<tbody>
					<?php 
					$counter = 1;
					while ($query->have_posts()) {
						$query->the_post();

						// Get custom field value
						$price = get_post_meta(get_the_ID(), '_book_price', true);
						$rating = get_post_meta(get_the_ID(), '_book_start_rating', true);

						// Get taxonomy terms (replace 'your_taxonomy' with your actual taxonomy name)
						$auhtors = wp_get_post_terms(get_the_ID(), 'author', array('fields' => 'names'));
						$publishers = wp_get_post_terms(get_the_ID(), 'publisher', array('fields' => 'names'));

						
						echo '<tr>';
								echo '<td scope="row">' .$counter. '</td>';
								echo '<td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
								echo '<td>$'.$price.'</td>';
								echo '<td>';
								if ($auhtors && !is_wp_error($auhtors)) {							
									$author_names = array();				
									foreach ($auhtors as $auhtor) {
										$author_names[] = $auhtor;
									}				
									$author_string = implode(', ', $author_names);				
									echo $author_string;							
								}
								echo '</td>';
								echo '<td>';
								if ($publishers && !is_wp_error($publishers)) {							
									$term_names = array();				
									foreach ($publishers as $publisher) {
										$term_names[] = $publisher;
									}				
									$taxonomy_string = implode(', ', $term_names);				
									echo $taxonomy_string;							
								}
								echo '</td>';
								echo '<td>';
									if(!empty($rating)){
										for ($i = 1; $i <= $rating; $i++) {
											echo '<span style="margin-right:5px;"><i class="fas fa-star"></i></span>';
										}
									}
								echo '</td>';
						echo '</tr>';
						
						// Increment the counter
						$counter++;
					} ?>
						</tbody>
					</table>
					<?php	

				} else {
					echo '<p>No results found</p>';
				}			
				wp_die();

			}
        }else {
            echo '<p>No results found</p>';
			wp_die();
        }


		
	}

	public function bls_book_post_type_template($single_template) {
		global $post;
	
		if ($post->post_type == 'book') {
			$single_template = BOOK_LIBRARY_SYSTEM_DIR_PATH . 'single-book.php';
		}
	
		return $single_template;
	}
	
	
}
