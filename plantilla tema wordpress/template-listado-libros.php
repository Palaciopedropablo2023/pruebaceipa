<?php
/*
Template Name: Listado de Libros
*/

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section class="libros-lista">
            <?php
            $args = array(
                'post_type' => 'libro',
                'posts_per_page' => -1,
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
                while ( $query->have_posts() ) :
                    $query->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('libro-item'); ?>>
                        <div class="libro-thumbnail">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </div>
                        <div class="libro-content">
                            <header class="entry-header">
                                <h2 class="entry-title"><?php the_title(); ?></h2>
                            </header><!-- .entry-header -->

                            <div class="entry-content">
                                <div class="libro-genero"><strong>Género:</strong> <?php echo get_post_meta( get_the_ID(), 'genero', true ); ?></div>
                                <div class="libro-autor"><strong>Autor:</strong> <?php echo get_post_meta( get_the_ID(), 'autor', true ); ?></div>
                                <div class="libro-anio-publicacion"><strong>Año de Publicación:</strong> <?php echo get_post_meta( get_the_ID(), 'anio_publicacion', true ); ?></div>
                                <div class="libro-detalle"><?php the_content(); ?></div>
                            </div><!-- .entry-content -->
                        </div><!-- .libro-content -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No se encontraron libros.</p>';
            endif;
            ?>
        </section><!-- .libros-lista -->
    </main><!-- #main -->
</div><!-- #primary -->

<style>
    .libro-item {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        padding: 20px;
    }
    .libro-thumbnail {
        float: left;
        margin-right: 20px;
    }
    .libro-thumbnail img {
        max-width: 150px;
        height: auto;
    }
    .libro-content {
        overflow: hidden;
    }
    .libro-content .entry-title {
        margin-top: 0;
        margin-bottom: 10px;
    }
    .libro-content .entry-content {
        line-height: 1.6;
    }
</style>

<?php
get_footer();