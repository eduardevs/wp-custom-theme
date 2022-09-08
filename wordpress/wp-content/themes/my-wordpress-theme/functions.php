<?php

require_once('walker/CommentWalker.php');

function my_wordpress_theme()
{
    add_theme_support('title-tag');
    // post-thumbnails est le support des images pour pouvoir les utiliser dans les posts 
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5');
    register_nav_menu('header', 'en tête du menu');
    register_nav_menu('footer', 'pied de page');

    add_image_size('card-header', 352, 216, true);
}


function my_wordpress_styles()
{

    wp_enqueue_style('bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css');

    wp_enqueue_script('bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js', [], false, true);
}



function my_wordpress_loginheader($url)
{
    return 'https://codex.wordpress.org/Plugin_API/Action_Reference';
}

function montheme_menu_class($classes)
{
    $classes[] = 'nav-item';
    return $classes;
}

function montheme_menu_link_class($attrs)
{
    $attrs['class'] = 'nav-link';
    return $attrs;
}

function montheme_pagination()
{
    $pages = paginate_links(['type' => 'array']);
    if ($pages === null) {
        return;
    }

    echo '<nav aria-label="Pagination" class="mt-4 my-4">';
    echo '<ul class="pagination">';
    $pages = paginate_links(['type' => 'array']);
    foreach ($pages as $page) {
        $active = strpos($page, 'current') !== false;
        $class = 'page-item';
        if ($active) {
            $class .= ' active';
        }
        echo '<li class="' . $class . '">';
        echo str_replace('page-numbers', 'page-link', $page);
        echo '</li>';
    }
    // var_dump($pages);
    echo '</ul>';
    echo '</nav>';
}

function montheme_init()
{
    register_taxonomy('sport', 'post', [
        'labels' => [
            'name' => 'Sport',
            'singular_name' => 'Sport',
            'plural_name' => 'Sport',
            'searcg_items' => 'Rechercher des sports',
            'all_items' => 'Tous les sports',
            'edit_item' => 'Editer le sport',
            'update_item' => 'Mettre à jour le sport',
            'add_new_item' => 'Ajouter un nouveau sport',
            'new_item_name' => 'Ajouter un nouveau sport',
            'menu_name' => 'Sport'
        ],
        'show_in_rest' => true,
        'hierarchical' => true,
        'show_admin_column' => true
    ]);
    register_post_type('bien', [
        'label' => 'Bien',
        'public' => true,
        'menu_position' => 3,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
        'has_archive' => true,

    ]);
}


add_action('init', 'montheme_init');
add_action('after_setup_theme', 'my_wordpress_theme');
add_action('wp_enqueue_scripts', 'my_wordpress_styles');

add_filter('login_headerurl', 'my_wordpress_loginheader');
add_filter('admin_footer_text', function ($txt) {
    return 'Je suis un filtre <3 ' . $txt;
});
add_filter('nav_menu_css_class', 'montheme_menu_class');

add_filter('nav_menu_link_attributes', 'montheme_menu_link_class');

require_once('metaboxes/sponso.php');
require_once('options/agence.php');

SponsoMetaBox::register();
AgenceMenuPage::register();


add_filter('manage_bien_posts_columns', function ($columns) {
    return [
        'cb' => $columns['cb'],
        'thumbnail' => 'Miniature',
        'title' => $columns['title'],
        'date' => $columns['date']
    ];
});

add_filter('manage_bien_posts_custom_column', function ($column, $postId) {
    // var_dump(func_get_args());
    if ($column === 'thumbnail') {
        the_post_thumbnail('thumbnail', $postId);
    }
}, 10, 2);

// add_filter('manage_posts_columns', function ($columns) {
//     return [
//         'cb' => $columns['cb'],
//         'thumbnail' => 'Miniature',
//         'title' => $columns['title'],
//         'date' => $columns['date']
//     ];
// });

// add_filter('manage_posts_columns', function ($column, $postId) {
//     // var_dump(func_get_args());
//     // var_dump($column);
//     // die();
//     // ? If column = 
//     // if ($column === 'thumbnail') {
//     //     // the_post_thumbnail('thumbnail', $postId);
//     // }
// }, 9, 2);

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('admin_montheme', get_template_directory_uri() . '/assets/admin.css');
});

// TODO 22
/**
 * @param WP_Query $query
 */
function montheme_pre_get_posts(WP_Query $query)
{
    if (is_admin() && !is_home() || !is_search() || !$query->is_main_query()) {
        return;
    }
    if (get_query_var('sponso') === '1') {

        $meta_query = $query->get('meta_query', []);
        $meta_query[] = [
            'key' => SponsoMetaBox::META_KEY,
            'compare' => 'EXISTS',
        ];
        $query->set('meta_query', $meta_query);
    }
    // $query->set('posts_per_page', 1);
}

add_action('pre_get_posts', 'montheme_pre_get_posts');


function montheme_query_vars($params)
{
    $params[] = 'sponso';

    return $params;
}

add_filter('query_vars', 'montheme_query_vars');
// TODO * 22
require_once 'widgets/YoutubeWidget.php';

function montheme_register_widget()
{
    register_widget(YoutubeWidget::class);

    register_sidebar([
        'id' => 'homepage',
        'name' => 'Sidebar Accueil',
        'before_widget' => '<div class="p-4 %2$s" id="%1$s">',
        'after_widget' => '</div>',
        // ! DIDNT WORK !
        'before_title' => '<h4 class="fst-italic">',
        'after_title' => '</h4>'
    ]);
}


add_action('widgets_init', 'montheme_register_widget');

add_filter('comment_form_default_fields', function ($fields) {
    // var_dump($fields);
    $fields['email'] = <<<HTML
        <div class="form-group"><label for="email">Email </label><input class="form-control" name="email" id="email" required> </div> 
    HTML;
    return $fields;
});

add_action('after_switch_theme', function () {
    flush_rewrite_rules();
    // var_dump('Je suis actif');
    // die();
});

add_action('switch_theme', 'flush_rewrite_rules');
