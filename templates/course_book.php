<?php
//Get the ID of the current post
$postID = get_the_ID();

//Get the postmeta of the current post
$postMeta = get_post_meta($postID);

//Get the current post data
$post = get_post($postID);

//Unserialize events data
$events = unserialize($postMeta['apex_course_template_events'][0]);

// Courses styles
$coursesStyles = get_option('apex_courses_extra_css');

// Get header and footers setting
$eventAddHeaders = get_option('apex_plugin_add_headers', 'no');

if ($eventAddHeaders == 'yes') {
    get_header();
}
?>
<?php if (!empty($coursesStyles)) { ?>
    <style><?= $coursesStyles ?></style>
<?php } ?>
<section class="apex-courses apex-bootstrap">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="apex-courses__template-title"><?php the_title() ?></div>
                <div class="apex-courses__template-content">
                    <?= $post->post_content ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
if ($eventAddHeaders == 'yes') {
    get_footer();
}
?>