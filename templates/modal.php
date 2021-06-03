<div class="modal fade" id="modal_<?= $event->id ?>" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"><?php _e('Register on Event', 'promote-apex') ?></h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="<?= get_site_url(); ?>/<?= $apex_slug ?>/<?= $post->post_name ?>"
                      method="post">
                    <?php if (!empty($extraBookingInfo)) : ?>
                    <div class="row">
                        <div class="col-12 extra-booking-info"><?= $extraBookingInfo ?></div>
                    </div>
                    <?php endif ?>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="first_name"><?php _e('Given Name *', 'promote-apex') ?></label>
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                   required>
                        </div>
                        <div class="form-group col-6">
                            <label for="last_name"><?php _e('Surname *', 'promote-apex') ?></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="email"><?php _e('Email *', 'promote-apex') ?></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group col-6">
                            <label for="phone"><?php _e('Phone *', 'promote-apex') ?></label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>

                        <?php
                        if ($showTitle === 'yes'):
                            if ($titles && is_array($titles)):
                                ?>
                                <div class="apex-col-12">
                                    <label for="title"><?php _e('Title', 'promote-apex') ?></label>
                                    <select name="title" id="title" class="form-control">
                                        <?php
                                        foreach($titles as $key => $title) {
                                            echo "<option value='{$key}'>{$title}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php
                            endif;
                        endif;
                        ?>

                        <div class="form-group col-12">
                            <label for="invoice-reference"><?php _e('Invoice reference', 'promote-apex') ?></label>
                            <input type="text" name="invoice_reference" id="invoice-reference" class="form-control" required>
                        </div>

                        <div class="form-group col-12">
                            <label for="company"><?php _e('Company *', 'promote-apex') ?></label>
                            <input type="text" name="company" id="company" class="form-control" required>
                        </div>

                        <?php
                        if ($showSector === 'yes'):
                            if ($sectors && is_array($sectors)):
                                ?>
                                <div class="form-group col-12">

                                    <label for="sector"><?php _e('Sector', 'promote-apex') ?></label>
                                    <select name="sector" id="sector" class="form-control">
                                        <?php
                                        foreach($sectors as $key => $sector) {
                                            echo "<option value='{$key}'>{$sector}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php
                            endif;
                        endif;
                        ?>
                        <div class="form-group col-12">
                            <label for="address_1"><?php _e('Invoice address *', 'promote-apex') ?></label>
                            <input type="text" name="address_1" id="address_1" class="form-control" required>
                            <input type="text" name="address_2" id="address_2" class="form-control">
                        </div>
                        <div class="form-group col-4">
                            <label for="zip_code"><?php _e('Zip Code *', 'promote-apex') ?></label>
                            <input type="number" name="zip_code" id="zip_code" class="form-control" required>
                        </div>

                        <div class="form-group col-8">
                            <label for="city"><?php _e('City *', 'promote-apex') ?></label>
                            <input type="text" name="city" id="city" class="form-control" required>
                        </div>

                        <div class="form-group col-12">
                            <label for="country"><?php _e('Country *', 'promote-apex') ?></label>
                            <select name="country" class="form-control" id="country">
                                <option value="SE" selected><?php _e('Sweden', 'promote-apex') ?></option>
                                <option value="DK"><?php _e('Denmark', 'promote-apex') ?></option>
                                <option value="FI"><?php _e('Finland', 'promote-apex') ?></option>
                                <option value="NO"><?php _e('Norway', 'promote-apex') ?></option>
                            </select>
                        </div>

                        <?php if (!empty($bookingTerms)) : ?>
                        <div class="form-group col-12">
                            <input type="checkbox" name="terms" id="booking_terms_<?= $event->id ?>" class="booking-terms"> <label for="booking_terms_<?= $event->id ?>" class="checkbox-label"><?= $bookingTerms ?></label>
                        </div>
                        <?php endif; ?>

                        <div class="form-group col-12">
                            <input type="hidden" name='event_id' value="<?= $event->id ?>">
                            <input type="submit" id="formSubmit" class="btn btn-primary"<?php if (!empty($bookingTerms)) { ?> disabled<?php } ?>
                                   value="<?php _e('Apply', 'promote-apex') ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.getElementById("modal_<?= $event->id ?>").getElementsByClassName("booking-terms")[0].addEventListener('change', function() {
        var button = document.getElementById("modal_<?= $event->id ?>").getElementsByClassName("btn-primary")[0];

        if (this.checked) {
            button.disabled = false;
            button.removeAttribute("disabled");
        } else {
            button.disabled = true;
        }
    });
</script>