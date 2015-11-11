<div class="container-fluid-md">
    <form class="form-horizontal form-bordered" role="form" id="loginForm" method="post">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Create Hosting</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage(); //echo $error;?>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-block  alert-danger fade in">
                        <button class="close" type="button" data-dismiss="alert">×</button><?php echo $error; ?></div>
                <?php } ?>

                <div class="form-group" id="hosting_name_div">
                    <label class="control-label col-sm-3">User Email</label>

                    <div class="controls col-sm-4" id ="hosting_name_div_err">
                        <input name="name" id="name" type="text" class="typeahead-devs form-control" placeholder="User Email" value="<?php echo $this->input->post('name'); ?>" required />
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Select Venue</label>
                    <div class="controls col-sm-4">
                        <select name="venue" class="form-control" required>
                            <option value="">Select Venue</option>
                            <?php foreach ($venues as $venue) { ?>
                                <option value="<?php echo $venue["id"]; ?>" <?php echo $this->input->post("venue") == $venue["id"] ? "selected" : ""; ?>><?php echo $venue["name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Theme</label>

                    <div class="controls col-sm-4">
                        <input name="theme" type="text" class="form-control" placeholder="Theme" value="<?php echo $this->input->post('theme'); ?>" />

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Offering</label>
                    <div class="controls col-sm-6">
                        <textarea name="description" class="form-control autogrow" placeholder="Offering" rows="4" style="height: 105px;"><?php echo $this->input->post('description'); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Date</label>
                    <div class="controls col-sm-4">

                        <input name="date" data-rel="datepicker" class="datepicker_event form-control" type="text" class="form-control" placeholder="Date" value="<?php echo $this->input->post('date'); ?>" required/>

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Time</label>
                    <div class="col-sm-3 col-md-6 col-lg-4">
                        <div class="input-group">
                            <input name="time" id="time" class="form-control" type="text"  data-minute-step="10" data-show-meridian="false" data-template="dropdown" data-rel="timepicker" value="<?php echo $this->input->post('time'); ?>" required />

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Recurring</label>
                    <div class="controls col-sm-6">
                         <input type="checkbox" name="is_recurring" id="is_recurring" data-off-color="success" data-off-text="NO"  data-on-text="YES" data-size="small" data-on-color="primary" value ="1" <?php echo $this->input->post("is_recurring") == 1 ? "checked" : "";?> class="boot-switch"/>
                         <span id="p_recurring"></span>
                    </div>
                </div>

                <div class="form-group">                    
                    <label class="control-label col-sm-3">Promoter</label>
                    <div class="controls col-sm-6">
                         <input type="checkbox" name="promoter" id="promoter" data-off-color="success" data-off-text="NO"  data-on-text="YES" data-size="small" data-on-color="primary" value ="1" <?php echo $this->input->post("promoter") == 1 ? "checked" : ""; ?> class="boot-switch"/>
                         <span id="p_promoter"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Rank</label>

                    <div class="controls col-sm-4">
                        <input name="rank" type="text" class="form-control" placeholder="Rank" value="<?php echo $this->input->post('rank'); ?>" />

                    </div>
                </div>

                <div class="form-group">

                    <label class="control-label col-sm-3">Hosting Status</label>
                    <div class="controls col-sm-6">
                        <input type="checkbox" name="hstatus" id="hstatus" data-off-color="success" data-off-text="INACTIVE"  data-on-text="ACTIVE" data-size="small" data-on-color="primary" value ="1" <?php echo $this->input->post("hstatus") == 1 ? "checked" : ""; ?> class="boot-switch" <?php if(!isset($_POST["hstatus"])) echo "checked";?>/>
                         <span id="p_hstatus"></span>
                    </div>
                </div>

                <!--<div class="form-group">
                    <label class="control-label col-sm-3">User Image URL</label>

                    <div class="controls col-sm-4">
                        <input name="user_image_url" type="text" class="form-control" placeholder="User Image URL" value="<?php echo $this->input->post('user_image_url'); ?>" />

                    </div>
                </div>-->

                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4">
                        <button type="submit" class="btn btn-primary">Create Hosting</button>
                        <a class="btn btn-default" href="<?php echo base_url().'/admin/hostings'; ?>">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
</div> 
