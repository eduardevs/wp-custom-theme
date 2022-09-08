<div class="card" style="width: 16rem;">
    <?php the_post_thumbnail('card-header', ['class' => 'card-img-top', 'alt' => '', 'style' => 'height: auto;']); ?>
    <div class="card-body">
        <h5 class="card-title">
            <h4><?php the_title();  ?></h4>
        </h5>
        <h5 class="card-subtitle">
            <h4><?php the_category();  ?></h4>
        </h5>
        <?php
        the_terms(get_the_ID(), 'sport')
        ?>
        <p class="card-text"> <?php the_excerpt(); ?></p>
        <a href="<?php the_permalink(); ?>"> Lien vers l'article</a>
        <p><?php the_author(); ?></p>
    </div>
</div>