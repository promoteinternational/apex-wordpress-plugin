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

//Unserialize prices data
$prices = unserialize($postMeta['apex_course_prices'][0]);

//Get Display Seats Option
$displaySeats = get_option('apex_display_seats');

//Create empty array for verifing prices data in API
$onlyCurrencyName = [];

// Post slug
$apex_slug = get_option('apex_plugin_slug', 'courses');

//Initializing new class instance of RestApi
$api = new RestApi();
$api->register();
$pluginCurrency = $api->getCurrency();

//Get Current Time
$currentDate = time();

//Get CSS Variables
$titleStyles = (!empty(get_option('apex_courses_title_styles')) ? get_option('apex_courses_title_styles') : '');
$sectionStyles = (!empty(get_option('apex_courses_section_styles')) ? get_option('apex_courses_section_styles') : '');
$contentStyles = (!empty(get_option('apex_courses_content_styles')) ? get_option('apex_courses_content_styles') : '');
$priceTitleStyles = (!empty(get_option('apex_courses_price_title_styles')) ? get_option('apex_courses_price_title_styles') : '');
$priceStyles = (!empty(get_option('apex_courses_price_styles')) ? get_option('apex_courses_price_styles') : '');
$eventStyles = (!empty(get_option('apex_courses_event_styles')) ? get_option('apex_courses_event_styles') : '');
$eventTitleStyles = (!empty(get_option('apex_courses_event_title_styles')) ? get_option('apex_courses_event_title_styles') : '');
$eventDateStyles = (!empty(get_option('apex_courses_event_date_styles')) ? get_option('apex_courses_event_date_styles') : '');
$eventTextStyles = (!empty(get_option('apex_courses_event_text_styles')) ? get_option('apex_courses_event_text_styles') : '');
$eventButtonStyles = (!empty(get_option('apex_courses_event_button_styles')) ? get_option('apex_courses_event_button_styles') : '');
$modalContentStyles = (!empty(get_option('apex_courses_modal_content')) ? get_option('apex_courses_modal_content') : '');
$modalButtonStyles = (!empty(get_option('apex_courses_modal_button')) ? get_option('apex_courses_modal_button') : '');

?>


<section class="apex-courses apex-bootstrap" style="<?= $sectionStyles ?>">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="apex-courses__template-title" style="<?= $titleStyles ?>">
                    <?php the_title() ?>
                </div>

                <div class="apex-courses__template-content" style="<?= $contentStyles ?>">
                    <?= $post->post_content ?>
                </div>
            </div>
            <!-- /.col-8 -->

            <div class="col-12 col-lg-4">

                <div class="apex-courses__price">
                    <div class="apex-courses__price-title" style="<?= $priceTitleStyles ?>">
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
                                <div class="apex-courses__price-price" style="<?= $priceStyles ?>">
                                    <?php echo round($price->price, 0) . ' ' . $price->currency_name; ?>
                                </div>
                                <?php
                            }
                        }

                    } else {
                        ?>

                        <div class="apex-courses__price-price" style="<?= $priceStyles ?>">
                            <?php echo round($prices[0]->price, 0) . ' ' . $prices[0]->currency_name; ?>
                        </div>

                        <?php
                    }
                    ?>
                </div>
                <?php
                if (!empty($events)) {
                    //Creating new empty array for events, needed for decreasing available seats after successful registration
                    $newEvents = [];

                    //Looping through each event and display event data
                    $i = 0;
                    foreach ($events as $event) {
                        //Determining the number of available seats
                        $availableSeats = $event->max_participants - $event->booked_participant_count;
                        $eventDate = strtotime($event->start_date);
                        if (!empty($event->id) && $currentDate <= $eventDate  ) {
                            ?>
                            <div class="apex-courses__event" style="<?= $eventStyles ?>">
                                <?php if (!empty($event->name)): ?>
                                    <div class="col-12">
                                        <div class="apex-courses__event-title" style="<?= $eventTitleStyles ?>">
                                            <h5><?= $event->name ?></h5>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12 col-lg-6">
                                    <?php if (!empty($event->start_date)): ?>
                                        <div class="apex-courses__event-date" style="<?= $eventDateStyles ?>">
                                            <?= date('d M', strtotime($event->start_date)) ?>
                                            - <?= date('d M', strtotime($event->end_date)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($event->venue_city)): ?>
                                        <div class="apex-courses__event-place" style="<?= $eventTextStyles ?>">
                                            <?= $event->venue_city ?>
                                        </div>
                                    <?php endif; ?>


                                    <?php if (!empty($availableSeats) && $availableSeats >= 1 && $displaySeats === 'yes'): ?>
                                        <div class="apex-courses__event-available" style="<?= $eventTextStyles ?>">
                                            <?php _e('Available seats:', 'apex-wordpress-plugin') ?>
                                            <?= $availableSeats ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6">

                                    <?php if (!empty($availableSeats) && $availableSeats >= 1): ?>
                                        <button type="button" class="btn apex-courses__event-button" style="<?= $eventButtonStyles ?>" data-toggle="modal"
                                                data-target="#modal_<?= $event->id ?>">
                                           <?php _e('Apply Now', 'apex-wordpress-plugin') ?>
                                        </button>
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

                                        //Decreasing value of the participant places in wp Database
                                        $success = $api->getSuccess();
                                        $trSuccess = _('You successfully applied on this event!');
                                        $trError = _('You successfully applied on this event!');
                                        if ($success) {
                                            $event->max_participants = $event->max_participants - 1;
                                            echo '<div class="alert alert-success">'.$trSuccess.'</div>';
                                        } else {
                                            echo '<div class="alert alert-danger">'.$trError.'</div>';
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
                            $i++;
                        }

                    }
                    //Display message if all events has wrong date
                    if($i === 0){
                    ?>
                        <div class="apex-courses__event" style="<?= $eventStyles ?>">

                            <div class="alert alert-info" style="margin: 0 auto">
                                <?php _e('No upcoming events', 'apex-wordpress-plugin') ?>

                            </div>
                        </div>
                        <!-- /.apex-courses__events -->
                    <?php
                    }

                } else {
                    ?>
                    <div class="apex-courses__event" style="<?= $eventStyles ?>">

                        <div class="alert alert-info" style="margin: 0 auto">
                            <?php _e('No upcoming events', 'apex-wordpress-plugin') ?>
                        </div>
                    </div>
                    <!-- /.apex-courses__events -->
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
