<?php
/**
 * Template for displaying the information for a course in wordpress.
 */

use Apex\Api\RestApi;

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

// Titles
$showTitle = get_option('apex_display_title', false);
$titles = get_option('apex_plugin_titles', array());

// Sectors
$showSector = get_option('apex_display_sector', false);
$sectors = get_option('apex_plugin_sectors', array());

// Booking terms
$bookingTerms = (!empty(get_option('apex_plugin_booking_terms')) ? get_option('apex_plugin_booking_terms') : '');

// Booking extra info
$extraBookingInfo = (!empty(get_option('apex_plugin_extra_booking_info')) ? get_option('apex_plugin_extra_booking_info') : '');

// After booking javascript
$afterBooking = (!empty(get_option('apex_plugin_after_booking')) ? get_option('apex_plugin_after_booking') : '');

//Initializing new class instance of RestApi
$api = new RestApi();
$api->register();
$pluginCurrency = $api->getCurrency();

//Get Current Time
$currentDate = time();

foreach ($prices as $price) {
    array_push($onlyCurrencyName, $price->currency_name);
}
if (in_array($pluginCurrency, $onlyCurrencyName)) {
    foreach ($prices as $price) {
        if ($pluginCurrency === $price->currency_name) {
            $course_price = $price;
        }
    }
} else {
    $course_price = $prices[0];
}

if ($eventAddHeaders == 'yes') {
    get_header();
}
?>
<?php if (!empty($coursesStyles)) { ?>
    <style><?= $coursesStyles ?></style>
<?php } ?>
    <script type="text/javascript">
        var course = {
            name: "<?php echo $post->post_title ?>",
            currency: "<?php echo $course_price->currency_name ?>",
            price: <?php echo $course_price->price ?>,
            slug: "<?php echo $post->post_name ?>"
        }
    </script>

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
                        <?php _e('Prices and upcoming dates:', 'promote-apex') ?>
                    </div>
                    <div class="apex-courses__price-price">
                        <?php echo format_currency($course_price->currency_name, $course_price->price); ?>
                    </div>
                    <div class="apex-courses__price-days">
                        <?php if ($days > 1 && $days != '1/2') {
                            printf(__('%s days', 'promote-apex'), $days);
                        } else {
                            printf(__('%s day', 'promote-apex'), $days);
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
                        <div class="apex-courses__events-dates"><?php _e("Dates", 'promote-apex') ?></div>
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
                                <?php _e('No upcoming events', 'promote-apex') ?>
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

                                if (!empty($event_array)) {
                                    foreach($venueReplacementArrays as $replacement) {
                                        if ($replacement[0] === $key) {
                                            $key = $replacement[1];
                                            break;
                                        }
                                    }

                                    require 'venue_events.php';
                                }
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
                            <?php _e('No upcoming events', 'promote-apex') ?>
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