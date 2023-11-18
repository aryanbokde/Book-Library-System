<?php get_header(); ?>

<div id="primary" class="content-area book-post">
    <main id="main" class="site-main" role="main">

        <?php
        while (have_posts()) :
            the_post();

            $authors = get_the_terms(get_the_ID(), 'author');
            $publishers = get_the_terms(get_the_ID(), 'publisher');
            
            $book_price = get_post_meta(get_the_ID(), '_book_price', true);
            $rating = get_post_meta(get_the_ID(), '_book_start_rating', true);

            echo "<h1>" .get_the_title(). "</h1>";
            
            echo '<div class="taxonomy">';
            if ($authors && !is_wp_error($authors)) {
                echo '<p class="taxonomy-list">';
                    $first_term = reset($authors);
                    // Output a link for the first term
                    echo '<span><b>Book Author : </b><a href="' . esc_url(get_term_link($first_term)) . '">' . esc_html($first_term->name) . '</a></span>';    
                echo '</p>';
            }

            if ($publishers && !is_wp_error($publishers)) {
                echo '<p class="taxonomy-list">';
                    $second_term = reset($publishers);
                    // Output a link for the first term
                    echo '<span><b>Book Publisher : </b><a href="' . esc_url(get_term_link($second_term)) . '">' . esc_html($second_term->name) . '</a></span>';    
                echo '</p>';
            }
            echo '</div>';

            if(!empty($rating)){
                echo '<p style="margin-bottom:10px;"><b>Rating : </b>';
                for ($i = 1; $i <= $rating; $i++) {
                    echo '<span style="margin-right:5px;"><i class="fas fa-star"></i></span>';
                }
                echo '</p>';
            }
           
            if (!empty($book_price)) {
                echo "<h4>Price : $" .$book_price ."</h4>";
            }

            echo '<br>';
            the_content();            

        endwhile; 
        ?>

    </main>
</div>
  
<?php get_footer(); ?>
