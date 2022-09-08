<?php

/**
 * Template Name: Page avec bannière
 */
?>

<?php get_header() ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <p>ici la banniere</p>
        <h1><?php the_title() ?></h1>
        <img src="<?php the_post_thumbnail_url() ?>" alt="" style="width:100%; height:auto;" />
        <p><?php the_content() ?></p>
    <?php endwhile; ?>
<?php endif; ?>

<?php get_footer() ?>