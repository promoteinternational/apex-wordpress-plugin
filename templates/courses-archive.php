<?php
/**
 * The template for displaying courses CPT archive page.
 *
  */
$content_title = get_option('apex_courses_listing_title', 'courses');

// Get header and footers setting
$eventAddHeaders = get_option('apex_plugin_add_headers', 'no');

// Courses archive styles
$coursesArchiveStyles = get_option('apex_courses_archive_extra_css');

if ($eventAddHeaders == 'yes') {
    get_header();
}
?>
<?php if (!empty($coursesArchiveStyles)) { ?>
    <style><?= $coursesArchiveStyles ?></style>
<?php } ?>
    <div class="content-area apex-courses apex-bootstrap">
        <section class="container">
            <div class="row">
                <div class="col-12">
                    <h1><?= $content_title ?></h1>
                </div>

                <?php
                echo do_shortcode('[Apex-courses-list-before]');
                ?>

            </div>
        </section>

        <?php
            echo do_shortcode('[Apex-courses-list]')
        ?>

        <section class="container">
            <div class="row">
                <?php
                echo do_shortcode('[Apex-courses-list-after]');
                ?>
            </div>
        </section>

        </section><!-- #main -->
    </div><!-- #primary -->

<?php
if ($eventAddHeaders == 'yes') {
    get_footer();
}
?>