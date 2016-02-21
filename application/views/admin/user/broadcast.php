<div class="form-broadcast-container">
    <form class="form-horizontal form-bordered form-broadcast" role="form" id="broadcastForm" method="post" enctype="multipart/form-data">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Broadcast Message</h4>
            </div>
            <div class="panel-body">
                <?php echo showMessage() ?>
                <p class='f11'>All <span class="asterisk">*</span> marked fields are mandatory</p>
                                
                <div class="form-group">
                    <label class="control-label col-sm-3">Recipient*</label>

                    <div class="controls col-sm-4" id="recipients_block">
                        <input name="recipients" id="recipients" type="text" class="form-control recipients" placeholder="Choose Recipients" value="" />
                    </div>
                    <div class="controls col-sm-4">
                        <input name="all" id="all" type="checkbox" value="1" /> &nbsp;<label for="all">All Users</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Message*</label>
                    <div class="controls col-sm-4">
                        <textarea name="message" id="broadcast_message" class="form-control" placeholder="Message" rows="2" style="height: 105px;"></textarea>
                    </div>
                    <div class="controls col-sm-2">
                        (<span id="message_limit"></span>)
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Event</label>
                    <div class="controls col-sm-4">
                        <input name="events" id="events" type="text" class="form-control recipients" placeholder="Choose Events" value="" />
                    </div>
                </div>
                
                <div class="form-group">
                <div class="form-group">
                    <label class="control-label col-sm-3"></label>
                    <div class="controls col-sm-4"><button type="submit" class="btn btn-primary">Broadcast</button>
                    <a class="btn btn-default" href="<?php echo base_url().'admin/user'; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" value="" name="email_verified" id="email_verified"/>
    </form>
</div>    

