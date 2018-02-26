<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-paytm" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paytm" class="form-horizontal">
          <div class="form-group required">
            <label class="control-label col-sm-3" for="input-merchant-id"><span data-toggle="tooltip" title="<?php echo $entry_merchant_help; ?>"><?php echo $entry_merchant; ?></span></label>
            <div class="col-sm-9"><input type="text" name="paytm_merchant" value="<?php echo $paytm_merchant; ?>" class="form-control"/>
              <?php if ($error_merchant) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?></div>
          </div>
		  
		  <div class="form-group required">
            <label class="control-label col-sm-3" for="paytm_key"><span data-toggle="tooltip" title="<?php echo $entry_merchantkey_help; ?>"><?php echo $entry_merchantkey; ?></span></label>
            <div class="col-sm-9"><input type="text" name="paytm_key" value="<?php echo $paytm_key; ?>" class="form-control"/>
              <?php if ($error_key) { ?>
              <span class="error"><?php echo $error_key; ?></span>
              <?php } ?></div>
          </div>
      <!-- <div class="form-group required">
          <label class="control-label col-sm-3" for="paytm_environment"><span data-toggle="tooltip" title="<?php echo $entry_environment_help; ?>"><?php echo $entry_environment; ?></span></label>
          <div class="col-sm-9"><select name="paytm_environment" class="form-control">
              <?php if ($paytm_environment == "P") { ?>
                  <option value="P" selected="selected"><?php echo $text_env_production; ?></option>
                  <option value="T"><?php echo $text_env_test; ?></option>
              <?php } else { ?>
                  <option value="P"><?php echo $text_env_production; ?></option>
                  <option value="T" selected="selected"><?php echo $text_env_test; ?></option>
              <?php } ?>
          </select></div>
      </div> -->

      <div class="form-group required">
          <label class="control-label col-sm-3" for="paytm_key"><span data-toggle="tooltip" title="<?php echo $entry_transaction_url_help; ?>"><?php echo $entry_transaction_url; ?></span></label>
          <div class="col-sm-9"><input type="text" name="payment_paytm_transaction_url" value="<?php echo $payment_paytm_transaction_url; ?>" class="form-control"/>
              <?php if ($error_transaction_url) { ?>
                  <span class="error"><?php echo $error_transaction_url; ?></span>
              <?php } ?>
          </div>
      </div>

      <div class="form-group required">
          <label class="control-label col-sm-3" for="paytm_key"><span data-toggle="tooltip" title="<?php echo $entry_transaction_url_status_help; ?>"><?php echo $entry_transaction_url_status; ?></span></label>
          <div class="col-sm-9"><input type="text" name="payment_paytm_transaction_status_url" value="<?php echo $payment_paytm_transaction_status_url; ?>" class="form-control"/>
              <?php if ($error_transaction_status_url) { ?>
                  <span class="error"><?php echo $error_transaction_status_url; ?></span>
              <?php } ?>
          </div>
      </div>
		  
		  <div class="form-group required">
            <label class="control-label col-sm-3" for="paytm_website"><span data-toggle="tooltip" title="<?php echo $entry_website_help; ?>"><?php echo $entry_website; ?></span></label>
            <div class="col-sm-9"><input type="text" name="paytm_website" value="<?php echo $paytm_website; ?>" class="form-control"/>
              <?php if ($error_website) { ?>
              <span class="error"><?php echo $error_website; ?></span>
              <?php } ?></div>
          </div>
		  
		  
		   <div class="form-group required">
            <label class="control-label col-sm-3" for="paytm_industry"><span data-toggle="tooltip" title="<?php echo $entry_industry_help; ?>"><?php echo $entry_industry; ?></span></label>
            <div class="col-sm-9"><input type="text" name="paytm_industry" value="<?php echo $paytm_industry; ?>" class="form-control"/>
              <?php if ($error_industry) { ?>
              <span class="error"><?php echo $error_industry; ?></span>
              <?php } ?></div>
          </div>
		  
          
		  <div class="form-group">
            <label class="control-label col-sm-3" for="paytm_order_status_id"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-9"><select name="paytm_order_status_id" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $paytm_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></div>
          </div>
		  
		  
          <div class="form-group">
            <label class="control-label col-sm-3" for="paytm_status"><?php echo $entry_status; ?></label>
            <div class="col-sm-9"><select name="paytm_status" class="form-control">
                <?php if ($paytm_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></div>
          </div>
		  
		  
		  <div class="form-group">
            <label class="control-label col-sm-3" for="paytm_callbackurl"><?php echo $callbackurl_status; ?></label>
            <div class="col-sm-9"><select name="paytm_callbackurl" class="form-control">
                <?php if ($paytm_callbackurl) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></div>
          </div>
		  
		  <div class="form-group">
			<label class="control-label col-sm-3" for="paytm_checkstatus"><span data-toggle="tooltip" title="<?php echo $entry_checkstatus_help; ?>"><?php echo $entry_checkstatus; ?></span></label>
			<div class="col-sm-9"><select name="paytm_checkstatus" class="form-control">
			<?php if ($paytm_checkstatus) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></div>
		  </div>
            
            <div class="form-group">
                <label class="col-sm-3 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                <div class="col-sm-9">
                  <input type="text" name="paytm_total" value="<?php echo $paytm_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                <div class="col-sm-9">
                    <select name="paytm_geo_zone_id" id="input-geo-zone" class="form-control">
                      <option value="0"><?php echo $text_all_zones; ?></option>
                      <?php foreach ($geo_zones as $geo_zone) { ?>
                      <?php if ($geo_zone['geo_zone_id'] == $paytm_geo_zone_id) { ?>
                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="paytm_sort_order" value="<?php echo $paytm_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
            </div>
            
		  </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#tabs a:first').tab('show');
//--></script> </div>
<?php echo $footer; ?>