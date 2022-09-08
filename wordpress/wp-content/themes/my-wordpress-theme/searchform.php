   <form class="d-flex" role="search" action="<?= esc_url(home_url('/')) ?>">
       <input class="form-control me-2" type="search" name="s" placeholder="Recherche" value="<?php get_search_query() ?>" aria-label="Search">
       <button class="btn btn-outline-success" type="submit">Search</button>
   </form>