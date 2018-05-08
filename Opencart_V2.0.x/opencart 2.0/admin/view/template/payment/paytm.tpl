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
						<label class="control-label col-sm-3" for="paytm_merchant_id">
							<span data-toggle="tooltip" title="<?php echo $entry_merchant_id_help; ?>"><?php echo $entry_merchant_id; ?></span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_merchant_id" id="paytm_merchant_id" value="<?php echo $paytm_merchant_id; ?>" class="form-control"/>
							<?php if ($error_merchant_id) { ?>
							<div class="text-danger"><?php echo $error_merchant_id; ?></div>
							<?php } ?>
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
						</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_website">
							<span data-toggle="tooltip" title="<?php echo $entry_website_help; ?>"><?php echo $entry_website; ?></span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_website" id="paytm_website" value="<?php echo $paytm_website; ?>" class="form-control"/>
							<?php if ($error_website) { ?>
							<div class="text-danger"><?php echo $error_website; ?></div>
							<?php } ?></div>
					</div>
			 		<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_industry_type">
							<span data-toggle="tooltip" title="<?php echo $entry_industry_type_help; ?>"><?php echo $entry_industry_type; ?></span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_industry_type" id="paytm_industry_type" value="<?php echo $paytm_industry_type; ?>" class="form-control"/>
							<?php if ($error_industry_type) { ?>
							<div class="text-danger"><?php echo $error_industry_type; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_transaction_url">
							<span data-toggle="tooltip" title="<?php echo $entry_transaction_url_help; ?>"><?php echo $entry_transaction_url; ?></span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_transaction_url" id="paytm_transaction_url" value="<?php echo $paytm_transaction_url; ?>" class="form-control"/>
							<?php if ($error_transaction_url) { ?>
									<div class="text-danger"><?php echo $error_transaction_url; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_transaction_status_url">
							<span data-toggle="tooltip" title="<?php echo $entry_transaction_status_url_help; ?>"><?php echo $entry_transaction_status_url; ?></span>
						</label>
						<div class="col-sm-9"><input type="text" name="paytm_transaction_status_url" id="paytm_transaction_status_url" value="<?php echo $paytm_transaction_status_url; ?>" class="form-control"/>
							<?php if ($error_transaction_status_url) { ?>
									<div class="text-danger"><?php echo $error_transaction_status_url; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="control-label col-sm-3" for="paytm_callback_url_status">
							<?php echo $entry_callback_url_status; ?>
						</label>
						<div class="col-sm-9">
							<select name="paytm_callback_url_status" id="paytm_callback_url_status" class="form-control">
								<?php if ($paytm_callback_url_status) { ?>
								<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<option value="0"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
								<option value="1"><?php echo $text_enabled; ?></option>
								<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					
					<div class="callback_url_group form-group required">
						<label class="control-label col-sm-3" for="paytm_callback_url">
							<?php echo $entry_callback_url; ?>
						</label>
						<div class="col-sm-9">
							<input type="text" name="paytm_callback_url" id="paytm_callback_url" value="<?php echo $paytm_callback_url; ?>" class="form-control" <?php if($paytm_callback_url_status==0) echo "readonly"; ?>/>
							<?php if ($error_callback_url) { ?>
									<div class="text-danger"><?php echo $error_callback_url; ?></div>
							<?php } ?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_order_status_id"><?php echo $entry_order_status; ?></label>
						<div class="col-sm-9">
							<select name="paytm_order_status_id" id="paytm_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $paytm_order_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_status"><?php echo $entry_status; ?></label>
						<div class="col-sm-9">
							<select name="paytm_status" class="form-control">
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
					<div class="form-group">
						<label class="control-label col-sm-3" for="paytm_status"><?php echo $entry_multi_currency_support; ?></label>
						<div class="col-sm-9">
							<label class="control-label radio-inline">
								<input type="radio" name="paytm_multi_currency_support" value="0" <?php if($paytm_multi_currency_support == "0") echo "checked"; ?> /> <?php echo $entry_multi_currency_support_disabled; ?>
								<span data-toggle="tooltip" title="<?php echo $entry_multi_currency_support_disabled_help; ?>"></span>
							</label>
							<label class="control-label radio-inline">
								<input type="radio" name="paytm_multi_currency_support" value="1" <?php if($paytm_multi_currency_support == "1") echo "checked"; ?> /> <?php echo $entry_multi_currency_support_conversion; ?>
								<span data-toggle="tooltip" title="<?php echo $entry_multi_currency_support_conversion_help; ?>"></span>
							</label>
							<label class="control-label radio-inline">
								<input type="radio" name="paytm_multi_currency_support" value="-1" <?php if($paytm_multi_currency_support == "-1") echo "checked"; ?> /> <?php echo $entry_multi_currency_support_no_conversion; ?>
								<span data-toggle="tooltip" title="<?php echo $entry_multi_currency_support_no_conversion_help; ?>"></span>
							</label>
						</div>
					</div>
					<?php if(isset($last_updated) && !empty($last_updated)) echo $last_updated; ?>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
$('#tabs a:first').tab('show');

var default_callback_url = "<?php echo $default_callback_url; ?>";

function toggleCallbackUrl(){
	if($("select[name=\"paytm_callback_url_status\"]").val() == "1"){
		$(".callback_url_group").removeClass("hidden");
		$("input[name=\"paytm_callback_url\"]").prop("readonly", false);
	} else {
		$(".callback_url_group").addClass("hidden");
		$("#paytm_callback_url").val(default_callback_url);
		$("input[name=\"paytm_callback_url\"]").prop("readonly", true);
	}
}

$(document).on("change", "select[name=\"paytm_callback_url_status\"]", function(){
	toggleCallbackUrl();
});
toggleCallbackUrl();
//--></script>
<?php echo $footer; ?>