<div class="modal fade" id="modal_<?= $event->id ?>" tabindex="-1" role="dialog"
     aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"><?php _e('Register on Event', 'apex-wordpress-plugin') ?></h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="<?= get_site_url(); ?>/<?= $apex_slug ?>/<?= $post->post_name ?>"
                      method="post">
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="first_name"><?php _e('Given Name *', 'apex-wordpress-plugin') ?></label>
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                   required><br>
                        </div>
                        <div class="form-group col-6">
                            <label for="last_name"><?php _e('Surname *', 'apex-wordpress-plugin') ?></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" required><br>
                        </div>
                        <div class="form-group col-6">
                            <label for="email"><?php _e('Email *', 'apex-wordpress-plugin') ?></label>
                            <input type="email" name="email" id="email" class="form-control" required><br>
                        </div>

                        <div class="form-group col-6">
                            <label for="phone"><?php _e('Phone *', 'apex-wordpress-plugin') ?></label>
                            <input type="text" name="phone" id="phone" class="form-control" required><br>
                        </div>

                        <?php
                        $showTitle = get_option('apex_display_title', false);
                        if ($showTitle === 'yes'):
                            $titles = get_option('apex_plugin_titles', array());
                            if ($titles && is_array($titles)):
                                ?>
                                <div class="apex-col-12">
                                    <label for="title"><?php _e('Title', 'apex-wordpress-plugin') ?></label>
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
                            <label for="company"><?php _e('Company *', 'apex-wordpress-plugin') ?></label>
                            <input type="text" name="company" id="company" class="form-control" required><br>
                        </div>

                        <?php
                        $showSector = get_option('apex_display_sector', false);
                        if ($showSector === 'yes'):
                            $sectors = get_option('apex_plugin_sectors', array());
                            if ($sectors && is_array($sectors)):
                                ?>
                                <div class="form-group col-12">

                                    <label for="sector"><?php _e('Sector', 'apex-wordpress-plugin') ?></label>
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
                            <label for="address_1"><?php _e('Address *', 'apex-wordpress-plugin') ?></label>
                            <input type="text" name="address_1" id="address_1" class="form-control" required><br>
                            <input type="text" name="address_2" id="address_2" class="form-control"><br>
                        </div>
                        <div class="form-group col-4">
                            <label for="zip_code"><?php _e('Zip Code *', 'apex-wordpress-plugin') ?></label>
                            <input type="number" name="zip_code" id="zip_code" class="form-control" required><br>
                        </div>

                        <div class="form-group col-8">
                            <label for="city"><?php _e('City *', 'apex-wordpress-plugin') ?></label>
                            <input type="text" name="city" id="city" class="form-control" required><br>
                        </div>

                        <div class="form-group col-12">
                            <label for="country"><?php _e('Country *', 'apex-wordpress-plugin') ?></label>
                            <select name="country" class="form-control" id="country">
                                <option value="SE" selected><?php _e('Sweden', 'apex-wordpress-plugin') ?></option>
                                <option value="DK"><?php _e('Denmark', 'apex-wordpress-plugin') ?></option>
                                <option value="FI"><?php _e('Finland', 'apex-wordpress-plugin') ?></option>
                                <option value="NO"><?php _e('Norway', 'apex-wordpress-plugin') ?></option>
                            </select>
                        </div>

                        <div class="form-group col-12">
                            <input type="hidden" name='event_id' value="<?= $event->id ?>">
                            <input type="submit" id="formSubmit" class="btn btn-primary"
                                   value="<?php _e('Apply', 'apex-wordpress-plugin') ?>">
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>