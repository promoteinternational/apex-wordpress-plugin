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
                <div class="apex-courses__template-title">
                    <?php the_title() ?>
                </div>

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

                        //Looping through each event and display event data
                        foreach ($events as $event) {
                            //Determining the number of available seats - TODO: Rewrite to use portal participant count.
                            $availableSeats = $event->max_participants - $event->booked_participant_count;
                            ?>
                            <div class="apex-courses__events-dates"><?php _e("Dates", 'apex-wordpress-plugin') ?></div>
                            <div class="apex-courses__event">
                                <div class="col-12 col-lg-6">
                                    <div class="apex-courses__event-date">
                                        <?= date_i18n('d M', strtotime($event->start_date)) ?><?php if ($days > 1): ?> - <?= date_i18n('d M', strtotime($event->end_date)) ?><?php endif; ?>
                                    </div>
                                    <?php if (!empty($event->venue_city)): ?>
                                        <div class="apex-courses__event-place">
                                            <?= $event->venue_city ?>
                                        </div>
                                    <?php endif; ?>


                                    <?php if (!empty($availableSeats) && $availableSeats >= 1 && $displaySeats === 'yes'): ?>
                                        <div class="apex-courses__event-available">
                                            <?php _e('Available seats:', 'apex-wordpress-plugin') ?>
                                            <?= $availableSeats ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6">

                                    <?php if (!empty($availableSeats) && $availableSeats >= 1): ?>
                                        <button type="button" class="btn apex-courses__event-button"
                                                data-toggle="modal" data-target="#modal_<?= $event->id ?>">
                                            <?php _e('Apply Now', 'apex-wordpress-plugin') ?>
                                        </button>
                                        <?php if ($availableSeats < $event->max_participants / 2): ?>
                                            <div class="apex-courses__event-few-places">
                                                <?php _e('Few seats remaining', 'apex-wordpress-plugin') ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="alert alert-warning"> <?php _e('This event is fully booked', 'apex-wordpress-plugin') ?> </div>
                                    <?php endif;

                                    //Connect Modal template
                                    require 'modal.php'
                                    ?>
                                </div>

                                <?php

                                //If not empty form data, display message
                                if (!empty($_POST['event_id']) && $_POST['event_id'] == $event->id):?>
                                    <div class="apex-courses__confirmation">
                                        <?php
                                        $api->addParticipant($_POST['first_name'], $_POST['last_name'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['country'], $_POST['city'], $_POST['address_1'], $_POST['address_2'], $_POST['zip_code'], isset($_POST['sector']) ? $_POST['sector'] : null, isset($_POST['title']) ? $_POST['title'] : null, $_POST['event_id']);

                                        // Update booked participant count in wp database.
                                        $success = $api->getSuccess();
                                        $trSuccess = __('Your application was successfully submitted!', 'apex-wordpress-plugin');
                                        $trError = __('Something went wrong with your application', 'apex-wordpress-plugin');
                                        if ($success) {
                                            $event->booked_participant_count = $event->booked_participant_count + 1;
                                            echo '<div class="alert alert-success">' . $trSuccess . '</div>';
                                        } else {
                                            echo '<div class="alert alert-danger">' . $trError . '</div>';
                                        }
                                        ?>
                                    </div>
                                <?php
                                endif;

                                //Updating events data in DB
                                array_push($newEvents, $event);
                                $newEventsSerialize = serialize($newEvents);
                                global $wpdb;
                                $postId = get_the_ID();
                                $wpdb->query("UPDATE `wp_postmeta` meta1 SET meta1.meta_value = '$newEventsSerialize' WHERE `post_id` =  $postId  AND meta1.meta_key = 'apex_course_template_events'");
                                ?>
                            </div>
                            <!-- /.apex-courses__events -->
                            <?php
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

                        //Looping through each event and display event data
                        foreach ($venues as $key => $event_array) {
                            ?>
                            <div class="apex-courses__events-dates"><?php printf(__("Dates for %s", 'apex-wordpress-plugin'), $key) ?></div><?php
                            foreach ($event_array as $event) {
                                $availableSeats = $event->max_participants - $event->booked_participant_count; ?>
                                <div class="apex-courses__event">
                                    <div class="col-12 col-lg-6">
                                        <div class="apex-courses__event-date">
                                            <?= date_i18n('d M', strtotime($event->start_date)) ?><?php if ($days > 1): ?> - <?= date_i18n('d M', strtotime($event->end_date)) ?><?php endif; ?>
                                        </div>
                                        <?php if (!empty($availableSeats) && $availableSeats >= 1 && $displaySeats === 'yes'): ?>
                                            <div class="apex-courses__event-available">
                                                <?php _e('Available seats:', 'apex-wordpress-plugin') ?>
                                                <?= $availableSeats ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12 col-lg-6">

                                        <?php if (!empty($availableSeats) && $availableSeats >= 1): ?>
                                            <button type="button" class="btn apex-courses__event-button"
                                                    data-toggle="modal" data-target="#modal_<?= $event->id ?>">
                                                <?php _e('Apply Now', 'apex-wordpress-plugin') ?>
                                            </button>
                                            <?php if ($availableSeats < $event->max_participants / 2): ?>
                                                <div class="apex-courses__event-few-places">
                                                    <?php _e('Few seats remaining', 'apex-wordpress-plugin') ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="alert alert-warning"> <?php _e('This event is fully booked', 'apex-wordpress-plugin') ?> </div>
                                        <?php endif;

                                        //Connect Modal template
                                        require 'modal.php'
                                        ?>
                                    </div>

                                    <?php

                                    //If not empty form data, display message
                                    if (!empty($_POST['event_id']) && $_POST['event_id'] == $event->id):?>
                                        <div class="apex-courses__confirmation">
                                            <?php
                                            $api->addParticipant($_POST['first_name'], $_POST['last_name'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['country'], $_POST['city'], $_POST['address_1'], $_POST['address_2'], $_POST['zip_code'], isset($_POST['sector']) ? $_POST['sector'] : null, isset($_POST['title']) ? $_POST['title'] : null, $_POST['event_id']);

                                            // Update booked participant count in wp database.
                                            $success = $api->getSuccess();
                                            $trSuccess = __('Your application was successfully submitted!', 'apex-wordpress-plugin');
                                            $trError = __('Something went wrong with your application', 'apex-wordpress-plugin');
                                            if ($success) {
                                                $event->booked_participant_count = $event->booked_participant_count + 1;
                                                echo '<div class="alert alert-success">' . $trSuccess . '</div>';
                                            } else {
                                                echo '<div class="alert alert-danger">' . $trError . '</div>';
                                            }
                                            ?>
                                        </div>
                                    <?php
                                    endif;

                                    //Updating events data in DB
                                    array_push($newEvents, $event);
                                    $newEventsSerialize = serialize($newEvents);
                                    global $wpdb;
                                    $postId = get_the_ID();
                                    $wpdb->query("UPDATE `wp_postmeta` meta1 SET meta1.meta_value = '$newEventsSerialize' WHERE `post_id` =  $postId  AND meta1.meta_key = 'apex_course_template_events'");
                                    ?>
                                </div>
                                <!-- /.apex-courses__events -->
                                <?php
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

                    if (!empty($extraCourseInfo)) {
                        ?>
                        <div class="apex-courses__extra-info">
                            <?= $extraCourseInfo ?>
                        </div>
                        <?php
                    }
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