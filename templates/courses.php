<?php
use Inc\Api\RestApi;

//Get the ID of the current post
$postID = get_the_ID();

//Get the postmeta of the current post
$postMeta = get_post_meta($postID);

//Get the current post data
$post = get_post($postID);

//Unserialize events data
$events = unserialize($postMeta['apex_course_template_events'][0]);
$venues = unserialize($postMeta['apex_course_template_places'][0]);

//Unserialize prices data
$prices = unserialize($postMeta['apex_course_prices'][0]);

// Get the number of days
$days = $postMeta['apex_course_number_of_days'][0];

// Get Display Seats Option
$displaySeats = get_option('apex_display_seats');

// Create empty array for verifing prices data in API
$onlyCurrencyName = [];

// Get extra course information
$extraCourseInfo = (!empty(get_option('apex_courses_extra_info')) ? get_option('apex_courses_extra_info') : '');

// Get display type
$eventDisplayType = get_option('apex_plugin_display_venue', 'dates');

// Get header and footers setting
$eventAddHeaders = get_option('apex_plugin_add_headers', 'no');

// Post slug
$apex_slug = get_option('apex_plugin_slug', 'courses');

// Courses styles
$coursesStyles = get_option('apex_courses_extra_css');

// Venues sort order
$venuesSortOrder = explode(",", get_option('apex_plugin_venue_order', ''));

// Venues replacements
$venuesReplacements = explode(",", get_option('apex_plugin_venue_replacement', ''));

//Initializing new class instance of RestApi
$api = new RestApi();
$api->register();
$pluginCurrency = $api->getCurrency();

//Get Current Time
$currentDate = time();

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
            <!-- /.col-8 -->

            <div class="col-12 col-lg-4">
                <div class="apex-courses__price">
                    <div class="apex-courses__price-title">
                        <?php _e('Prices and upcoming dates:', 'apex-wordpress-plugin') ?>
                    </div>

                    <?php
                    foreach ($prices as $price) {
                        array_push($onlyCurrencyName, $price->currency_name);
                    }
                    if (in_array($pluginCurrency, $onlyCurrencyName)) {
                        foreach ($prices as $price) {
                            if ($pluginCurrency === $price->currency_name) {

                                ?>
                                <div class="apex-courses__price-price">
                                    <?php echo format_currency($price->currency_name, $price->price); ?>
                                </div>
                                <?php
                            }
                        }

                    } else {
                        ?>

                        <div class="apex-courses__price-price">
                            <?php echo format_currency($prices[0]->currency_name, $prices[0]->price); ?>
                        </div>

                        <?php
                    }
                    ?>
                    <div class="apex-courses__price-days">
                        <?php if ($days > 1) {
                            printf(__('%s days', 'apex-wordpress-plugin'), $days);
                        } else {
                            printf(__('%s day', 'apex-wordpress-plugin'), $days);
                        }
                        ?>
                    </div>
                </div>
                <?php
                if ($eventDisplayType === 'dates') {
                    if (!empty($events)) {
                        //Creating new empty array for events, needed for decreasing available seats after successful registration
                        $newEvents = [];

                        ?>
                        <div class="apex-courses__events-dates"><?php _e("Dates", 'apex-wordpress-plugin') ?></div>
                        <?php
                        //Looping through each event and display event data
                        foreach ($events as $event) {
                            $venue_city = $event->venue_city;

                            require 'event.php';
                        }
                    } else {
                        ?>
                        <div class="apex-courses__event">

                            <div class="alert alert-info">
                                <?php _e('No upcoming events', 'apex-wordpress-plugin') ?>
                            </div>
                        </div>
                        <!-- /.apex-courses__events -->
                        <?php
                    }
                } else {
                    if (!empty($venues)) {
                        //Creating new empty array for events, needed for decreasing available seats after successful registration
                        $newEvents = [];
                        $venueKeys = array_keys($venues);
                        $venueReplacementArrays = [];

                        foreach ($venuesReplacements as $replacement) {
                            array_push($venueReplacementArrays, explode('-', $replacement));
                        }


                        if (!empty($venuesSortOrder)) {
                            foreach ($venuesSortOrder as $key) {
                                $event_array = $venues[$key];

                                foreach($venueReplacementArrays as $replacement) {
                                    if ($replacement[0] === $key) {
                                        $key = $replacement[1];
                                        break;
                                    }
                                }

                                require 'venue_events.php';
                            }
                        }

                        //Looping through each event and display event data
                        foreach ($venues as $key => $event_array) {
                            if (!in_array($key, $venuesSortOrder)) {
                                foreach($venueReplacementArrays as $replacement) {
                                    if ($replacement[0] === $key) {
                                        $key = $replacement[1];
                                        break;
                                    }
                                }

                                require 'venue_events.php';
                            }
                        }
                    } else {
                    ?>
                    <div class="apex-courses__event">
                        <div class="alert alert-info">
                            <?php _e('No upcoming events', 'apex-wordpress-plugin') ?>
                        </div>
                    </div>
                    <!-- /.apex-courses__events -->
                    <?php
                    }
                }
                if (!empty($extraCourseInfo)) {
                    ?>
                    <div class="apex-courses__extra-info">
                        <?= $extraCourseInfo ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- /.col-4 -->
        </div>
        <!-- /.apex-courses__row -->
    </div>
    <!-- /.apex-course__container -->
</section>
<!-- /.apex-courses -->

<?php
if ($eventAddHeaders == 'yes') {
    get_footer();
}
?>