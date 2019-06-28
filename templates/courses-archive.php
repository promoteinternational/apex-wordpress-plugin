<?php
/**
 * The template for displaying courses CPT archive page.
 *
 */
$content_title = get_option('apex_courses_listing_title', 'courses');
$content_end = get_option('apex_courses_listing_end', 'courses');
?>

    <div class="content-area apex-courses apex-bootstrap">
        <section class="container">
            <div class="row">
                <div class="col-12">
                    <h1><?= $content_title ?></h1>
                </div>

                <?php
                echo do_shortcode('[apex-courses-list-before]');
                ?>

            </div>
        </section>

        <?php
            echo do_shortcode('[apex-courses-list]')
        ?>

        <section class="container">
            <div class="row">
                <?php
                echo do_shortcode('[apex-courses-list-after]');
                ?>
            </div>
        </section>

        </section><!-- #main -->
    </div><!-- #primary -->
