<?php get_header(); ?>
<form name="" class="row gy-2 gx-3 align-items-center">
    <div class="col-auto">
        <input name="s" type="search" class="form-control" id="autoSizingInput" value="<?= get_search_query(); ?>" placeholder="Votre recherche">
    </div>


    <div class="col-auto">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="sponso" id="autoSizingCheck" <?= checked('1', get_query_var('sponso')) ?>>
            <label class="form-check-label" for="autoSizingCheck">
                Article sponsorisé seulement
            </label>
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </div>
</form>

<h1>Résultat pour votre recherche : "<?= get_search_query(); ?>"</h1>
<?php
if (have_posts()) {
?>
    <div class="row">
        <?php
        while (have_posts()) {
            the_post() ?>
            <div class="col-sm-4">
                <?php get_template_part('parts/card') ?>
            </div>
    <?php
        }
    }
    ?>


    <?=
    montheme_pagination();

    ?>


    </div>



    <?php get_footer(); ?>