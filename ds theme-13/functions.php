<?php
/**
 * DS Theme complete with Bootstrap via CDN
 */

// Enqueue styles & scripts (Bootstrap 5 CDN + theme CSS)
function ds_enqueue_assets() {
  // Bootstrap CSS
  wp_enqueue_style( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' );

  // Main stylesheet
  wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '1.2', 'all' );

  // Bootstrap JS (bundle includes Popper) in footer
  wp_enqueue_script( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), null, true );


  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets' );

function ds_setup() {
  add_theme_support( 'menus' );
  register_nav_menu( 'primary', 'Primary Navigation' );
  register_nav_menu( 'footer', 'Footer Navigation' );

  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'post-formats', array( 'aside', 'image', 'video' ) );
}
add_action( 'init', 'ds_setup' );

function ds_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Primary Sidebar', 'dstheme' ),
    'id'            => 'primary',
    'description'   => __( 'Main sidebar that appears on the right.', 'dstheme' ),
    'class'         => 'sidebar-primary',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array(
    'name'          => __( 'Secondary Sidebar', 'dstheme' ),
    'id'            => 'secondary',
    'description'   => __( 'Optional secondary sidebar (e.g., footer or left side).', 'dstheme' ),
    'class'         => 'sidebar-secondary',
    'before_widget' => '<ul><li id="%1$s" class="widget %2$s">',
    'after_widget'  => '</li></ul>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ) );
}
add_action( 'widgets_init', 'ds_widgets_init' );


class DS_Simple_Text_Widget extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'ds_simple_text', // Base ID
      __( 'DS Simple Text', 'dstheme' ), // Name
      array( 'description' => __( 'A simple text widget for DS Theme.', 'dstheme' ) )
    );
  }

  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
    $text  = ! empty( $instance['text'] ) ? $instance['text'] : '';
    if ( ! empty( $title ) ) {
      $title = apply_filters( 'widget_title', $title );
      echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
    }
    if ( ! empty( $text ) ) {
      echo '<div class="textwidget">' . wp_kses_post( $text ) . '</div>';
    }
    echo $args['after_widget'];
  }

  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
    $text  = ! empty( $instance['text'] ) ? $instance['text'] : '';
    ?>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dstheme' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
             name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
             value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text:', 'dstheme' ); ?></label>
      <textarea class="widefat" rows="5" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $text ); ?></textarea>
    </p>
    <?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = (! empty( $new_instance['title'] )) ? sanitize_text_field( $new_instance['title'] ) : '';
    $instance['text']  = (! empty( $new_instance['text'] )) ? wp_kses_post( $new_instance['text'] ) : '';
    return $instance;
  }
}

add_action( 'widgets_init', function() {
  register_widget( 'DS_Simple_Text_Widget' );
} );

function mytheme_pagination( $query = null, $args = array() ) {

    if ( $query instanceof WP_Query ) {
        $q = $query;
    } else {
        global $wp_query;
        $q = $wp_query;
    }

    if ( empty( $q->max_num_pages ) || $q->max_num_pages < 2 ) {
        return;
    }

    $big     = 999999999;
    $current = max( 1, get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' ) );

    $defaults = array(
        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format'    => '?paged=%#%',
        'current'   => $current,
        'total'     => (int) $q->max_num_pages,
        'mid_size'  => 2,
        'end_size'  => 1,
        'prev_text' => __('« Previous', 'yourtheme'),
        'next_text' => __('Next »', 'yourtheme'),
        'type'      => 'array', 
    );

    $settings = wp_parse_args( $args, $defaults );
    $links    = paginate_links( $settings );

    if ( empty( $links ) ) {
        return;
    }

    echo '<nav class="pagination" role="navigation" aria-label="' . esc_attr__( 'Posts Pagination', 'yourtheme' ) . '">';
    echo '<span class="screen-reader-text">' . esc_html__( 'Navigimi i faqeve', 'yourtheme' ) . '</span>';
    echo '<ul class="pagination__list">';

    foreach ( $links as $link ) {
        $active = strpos( $link, 'current' ) !== false ? ' is-active' : '';
        echo '<li class="pagination__item' . $active . '">' . $link . '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}

add_action( 'init', 'create_posttype' );
add_action( 'init', 'register_taxonomy_movie_genres' );

function create_posttype() {
  register_post_type( 'movie',
    array(
      'labels' => array(
        'name' => __( 'Movies' ),
        'singular_name' => __( 'Movie' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'movies'),
      'show_in_rest' => true,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    )
  );
}

function register_taxonomy_movie_genres() {
  $labels = [
    'name' => _x( 'Movie Genres', 'taxonomy general name' ),
    'singular_name' => _x( 'Movie Genre', 'taxonomy singular name' ),
    'search_items' => __( 'Search Movie Genres' ),
    'parent_item' => __( 'Parent Movie Genre' ),
    'parent_item_colon' => __( 'Parent Movie Genre:' ),
    'edit_item' => __( 'Edit Movie Genre' ),
    'update_item' => __( 'Update Movie Genre' ),
    'add_new_item' => __( 'Add New Movie Genre' ),
    'new_item_name' => __( 'New Movie Genre Name' ),
    'menu_name' => __( 'Movie Genres' ),
  ];
  $args = [
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => ['slug' => 'movie-genres'],
    'show_in_rest' => true,
  ];
  register_taxonomy( 'movie_genres', [ 'movie' ], $args );
}

// Shortcode for front-end movie submission form
function ds_movie_submission_form() {
  if ( ! is_user_logged_in() ) {
    return '<p>You must be logged in to submit a movie.</p>';
  }
  $output = '<form method="post" enctype="multipart/form-data">
    <label>Title: <input type="text" name="ds_movie_title" required></label><br>
    <label>Description:<br><textarea name="ds_movie_desc" required></textarea></label><br>
    <label>Genre: <input type="text" name="ds_movie_genre"></label><br>
    <label>Picture: <input type="file" name="ds_movie_image" accept="image/*"></label><br>
    <input type="submit" name="ds_movie_submit" value="Add Movie">
  </form>';
  return $output;
}
add_shortcode( 'ds_movie_form', 'ds_movie_submission_form' );

// Handle form submission
add_action( 'init', function() {
  if ( isset( $_POST['ds_movie_submit'] ) && is_user_logged_in() ) {
    $title = sanitize_text_field( $_POST['ds_movie_title'] );
    $desc = sanitize_textarea_field( $_POST['ds_movie_desc'] );
    $genre = sanitize_text_field( $_POST['ds_movie_genre'] );
    $post_id = wp_insert_post([
      'post_title' => $title,
      'post_content' => $desc,
      'post_type' => 'movie',
      'post_status' => 'pending',
    ]);
    if ( $post_id && $genre ) {
      wp_set_object_terms( $post_id, $genre, 'movie_genres' );
    }
    // Handle image upload
    if ( $post_id && !empty($_FILES['ds_movie_image']['name']) ) {
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
      require_once( ABSPATH . 'wp-admin/includes/media.php' );
      $attachment_id = media_handle_upload( 'ds_movie_image', $post_id );
      if ( is_numeric($attachment_id) ) {
        set_post_thumbnail( $post_id, $attachment_id );
      }
    }
  }
});

// Shortcode for searching movies
function ds_movie_search_form() {
  $output = '<form method="get">
    <input type="text" name="ds_movie_search" placeholder="Search movies..." value="'. esc_attr( isset($_GET['ds_movie_search']) ? $_GET['ds_movie_search'] : '' ) .'">
    <input type="submit" value="Search">
  </form>';
  if ( isset($_GET['ds_movie_search']) && $_GET['ds_movie_search'] ) {
    $search = sanitize_text_field($_GET['ds_movie_search']);
    $args = [
      'post_type' => 'movie',
      'post_status' => 'publish',
      's' => $search,
      'posts_per_page' => 10,
    ];
    $query = new WP_Query($args);
    if ( $query->have_posts() ) {
      $output .= '<ul class="ds-movie-search-results">';
      while ( $query->have_posts() ) {
        $query->the_post();
        $output .= '<li>';
        if ( has_post_thumbnail() ) {
          $output .= get_the_post_thumbnail( get_the_ID(), 'thumbnail', [ 'style' => 'max-width:100px;height:auto;' ] );
        }
        $output .= '<strong>' . esc_html( get_the_title() ) . '</strong>';
        $output .= '</li>';
      }
      wp_reset_postdata();
      $output .= '</ul>';
    } else {
      $output .= '<p>No movies found.</p>';
    }
  }
  return $output;
}
add_shortcode( 'ds_movie_search', 'ds_movie_search_form' );
