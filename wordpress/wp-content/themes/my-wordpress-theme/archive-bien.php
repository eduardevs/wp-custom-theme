<?php get_header(); ?>

<h1>Voir tous nos bien</h1>

<?php
if (have_posts()) : ?>
    <div class="row">
        <?php
        while (have_posts()) :
            the_post() ?>
            <div class="col-sm-4">
                <?php get_template_part('parts/card') ?>
            </div>
        <?php
        endwhile;
        ?>
    </div>
    <?=
    montheme_pagination();
    ?>
    <?= paginate_links() ?>
<?php else : ?>
    <h1>Pas de biens</h1>
<?php endif ?>


<?php get_footer(); ?>