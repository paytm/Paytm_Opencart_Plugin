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
		<?php if ($warning) { ?>
		<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paytm" class="form-horizontal">
					<input type="hidden" name="paytm_is_webhook_triggered" id="paytm_is_webhook_triggered" value="0"/>
				<input type="hidden" name="paytm_db_webhook_value" id="paytm_db_webhook_value" value="<?php echo $paytm_webhook?>"/>

				<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_environment">
							<span data-toggle="tooltip" title="<?php echo $entry_environment_help; ?>"><?php echo $entry_environment; ?></span>
						</label>
						<div class="col-sm-9">
							<select name="paytm_environment" class="form-control">
								<?php if ($paytm_environment == 1) { ?>
								<option value="0"><?php echo $text_staging; ?></option>
								<option value="1" selected="selected"><?php echo $text_production; ?></option>
								<?php } else { ?>
								<option value="0" selected="selected"><?php echo $text_staging; ?></option>
								<option value="1"><?php echo $text_production; ?></option>
								<?php } ?>
							</select>
							<span>Select "Test/Staging" to setup test transactions & "Production" once you are ready to go live</span>
						</div>
					</div>
					
					<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_merchant_id">
							<span data-toggle="tooltip" title="<?php echo $entry_merchant_id_help; ?>"><?php echo $entry_merchant_id; ?></span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_merchant_id" id="paytm_merchant_id" value="<?php echo $paytm_merchant_id; ?>" class="form-control"/>
							<?php if ($error_merchant_id) { ?>
							<div class="text-danger"><?php echo $error_merchant_id; ?></div>
							<?php } ?>
							<span>Based on the selected Environment Mode, copy the relevant Merchant ID for test or production environment available on <a href="https://dashboard.paytm.com/next/apikeys" target="_blank">Paytm dashboard</a>.</span>
						</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_merchant_key">
							<span data-toggle="tooltip" title="<?php echo $entry_merchant_key_help; ?>"><?php echo $entry_merchant_key; ?></span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_merchant_key" id="paytm_merchant_key" value="<?php echo $paytm_merchant_key; ?>" class="form-control"/>
							<?php if ($error_merchant_key) { ?>
							<div class="text-danger"><?php echo $error_merchant_key; ?></div>
							<?php } ?>
							<span>Based on the selected Environment Mode, copy the Merchant Key for test or production environment available on <a href="https://dashboard.paytm.com/next/apikeys" target="_blank">Paytm dashboard</a>.</span>
						</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_website">
							<span data-toggle="tooltip" title="<?php echo $entry_website_help; ?>"><?php echo $entry_website; ?></span>
						</label>
						<!-- <div class="col-sm-9">
							<input type="text" name="paytm_website" id="paytm_website" value="<?php echo $paytm_website; ?>" class="form-control"/>
							<?php if ($error_website) { ?>
							<div class="text-danger"><?php echo $error_website; ?></div>
							<?php } ?>
						</div> -->
						<div class="col-sm-9">
						<select name="paytm_website" id="paytm_website" class="form-control">
								<?php if ($paytm_website == $text_staging_website){?>

								<option value="<?php echo $text_staging_website ?>" selected="selected" ><?php echo $text_staging_website ?></option>
								<option value="<?php echo $text_production_website ?>" ><?php echo $text_production_website?></option>
								<?php } else { ?>
									<option value="<?php echo $text_staging_website ?>"> <?php echo $text_staging_website ?></option>
									<option value="<?php echo $text_production_website ?>" selected="selected"><?php echo $text_production_website ?></option>
								<?php }?>
							</select> 
							<span> Select "WEBSTAGING" for test/integration environment & "DEFAULT" for production environment.</span>
						</div>

					</div>

					<!-- <div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_industry_type">
							<span data-toggle="tooltip" title="<?php echo $entry_industry_type_help; ?>"><?php echo $entry_industry_type; ?></span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_industry_type" id="paytm_industry_type" value="<?php echo $paytm_industry_type; ?>" class="form-control"/>
							<?php if ($error_industry_type) { ?>
							<div class="text-danger"><?php echo $error_industry_type; ?></div>
							<?php } ?>
						</div>
					</div>  -->
					
					<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_order_success_status_id">
							<span data-toggle="tooltip" title="<?php echo $entry_order_success_status_help; ?>"><?php echo $entry_order_success_status; ?></span>
						</label>
						<div class="col-sm-9">
							<select name="paytm_order_success_status_id" id="paytm_order_success_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paytm_order_success_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_order_pending_status_id">
							<span data-toggle="tooltip" title="<?php echo $entry_order_pending_status_help; ?>"><?php echo $entry_order_pending_status; ?></span>
						</label>
						<div class="col-sm-9">
							<select name="paytm_order_pending_status_id" id="paytm_order_pending_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paytm_order_pending_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_order_failed_status_id">
							<span data-toggle="tooltip" title="<?php echo $entry_order_failed_status_help; ?>"><?php echo $entry_order_failed_status; ?></span>
						</label>
						<div class="col-sm-9">
							<select name="paytm_order_failed_status_id" id="paytm_order_failed_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paytm_order_failed_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-total">
							<span data-toggle="tooltip" title="<?php echo $entry_total_help; ?>"><?php echo $entry_total; ?></span>
						</label>
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

					<!-- Enable Paytm Webhook -->
					<div class="form-group">
						<label class="control-label col-sm-3" for="payment_paytm_webhook">
							<span data-toggle="tooltip" title="<?php echo $entry_webhook ?>"><?php echo $entry_webhook ?></span>
						</label>
						<div class="col-sm-9">
							<select name="paytm_webhook" class="form-control">
								<?php if ($paytm_webhook) {?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
							<span>Enable Paytm Webhook <a href="https://dashboard.paytm.com/next/webhook-url">here</a> with the URL listed below.<br> <?php echo $base_url_for_paytm_webhook?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_status" >
							<span data-toggle="tooltip" title="<?php echo $entry_status_help; ?>"><?php echo $entry_status; ?></span>
						</label>
						<div class="col-sm-9">
							<select name="paytm_status" class="form-control" id="paytm_status">
								<?php if ($paytm_status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>	

				</form>
			</div>

			<div class="panel-footer">
				<div class="text-center">
					<span><b><?php echo $text_php_version;?></b> <?php echo $php_version;?></span>
					<span> | </span>
					<span><b><?php echo $text_curl_version;?></b> <?php echo $curl_version;?></span>
					<span> | </span>
					<span><b><?php echo $text_opencart_version;?></b> <?php echo $opencart_version;?></span>
					<span> | </span>
					<span><b><?php echo $text_last_updated;?></b> <?php echo $last_updated;?></span>
					<span> | </span>
					<span><b><a target="_blank" href="https://developer.paytm.com/docs/eCommerce-plugin/opencart/#oc2-3x"><?php echo $text_developer_docs;?></a></b></span>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>
<script>
$(function() {
    $('select[name=paytm_webhook]').change(function() {
	    var get_current_webhook_value = $('option:selected', this).val();
	    var get_db_webhook_value = $("#paytm_db_webhook_value").val();
	    if(get_current_webhook_value != get_db_webhook_value){
	        $('input[name=paytm_is_webhook_triggered]').val(1);
	    }else{
	    	$('input[name=paytm_is_webhook_triggered]').val(0);
	    }
    });

       $('select[name=paytm_status]').change(function() {
	    var get_current_status_value = $('option:selected', this).val();
	    if(get_current_status_value == 0){
	         if(!confirm("Are you sure you want to disable Paytm Payment Gateway, you will no longer be able to accept payments through us?")){
            		$('#paytm_status').val(1);
        		}
	    }
    });
});
</script>