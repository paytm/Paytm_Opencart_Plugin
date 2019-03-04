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
		<?php if (empty($curl_version)) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_curl_disabled; ?>
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
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
						<li><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
						<li><a href="#tab-promo-code" data-toggle="tab"><?php echo $tab_promo_code; ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
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
									<span data-toggle="tooltip" title="<?php echo $entry_callback_url_status_help; ?>"><?php echo $entry_callback_url_status; ?></span>
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
								<label class="col-sm-3 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $entry_total_help; ?>"><?php echo $entry_total; ?></span></label>
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
								<label class="control-label col-sm-3" for="paytm_status">
									<span data-toggle="tooltip" title="<?php echo $entry_status_help; ?>"><?php echo $entry_status; ?></span>
								</label>
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
								<label class="col-sm-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-9">
								  <input type="text" name="paytm_sort_order" value="<?php echo $paytm_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
								</div>
							 </div>

							<div class="row-fluid">
								<div class="pull-right btn btn-primary" onclick="switchToTab('tab-order-status');"><?php echo $text_next; ?></div>
							</div>

						</div>

						<div class="tab-pane" id="tab-order-status">
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

							<div class="row-fluid">
								<div class="pull-right btn btn-primary" onclick="switchToTab('tab-promo-code');"><?php echo $text_next; ?></div>
							</div>

						</div>

						<div class="tab-pane" id="tab-promo-code">

							<div class="form-group">
								<label class="control-label col-sm-3" for="paytm_promo_code_status">
									<?php echo $entry_promo_code_status; ?>
								</label>
								<div class="col-sm-9">
									<select name="paytm_promo_code_status" class="form-control">
										<?php if ($paytm_promo_code_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
									<span><b><?php echo $entry_promo_code_status_help1; ?></b></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3" for="paytm_promo_code_validation">
									<span data-toggle="tooltip" title="<?php echo $entry_promo_code_validation_help1; ?>"><?php echo $entry_promo_code_validation; ?></span>
								</label>
								<div class="col-sm-9">
									<select name="paytm_promo_code_validation" class="form-control">
										<?php if ($paytm_promo_code_validation) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
									<span><b><?php echo $entry_promo_code_validation_help2; ?></b></span>
								</div>
							</div>
							<hr/>

							<table id="promo-codes" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<td class="text-left">
											<label class="control-label">
												<span data-toggle="tooltip" title="<?php echo $entry_promo_code_help1; ?>"><?php echo $entry_promo_code; ?></span>
											</label>
										</td>
										<td class="text-left"><?php echo $entry_promo_code_status; ?></td>
										<td class="text-left"><?php echo $entry_promo_code_start_date; ?></td>
										<td class="text-left"><?php echo $entry_promo_code_end_date; ?></td>
										<td></td>
									</tr>
								</thead>
								<tbody>
									<?php $promo_code_row = 0; ?>
									<?php foreach ($paytm_promo_codes as $code) { ?>
									<tr id="promo-code-row<?php echo $promo_code_row; ?>">

										<td class="text-left">
											<input type="text" name="paytm_promo_codes[<?php echo $promo_code_row; ?>][code]" value="<?php echo $code['code']; ?>" placeholder="<?php echo $entry_promo_code; ?>" class="form-control" />
											<?php if (isset($error_promo_codes[$promo_code_row]['promo_code'])) { ?>
											<div class="text-danger">
												<?php echo $error_promo_codes[$promo_code_row]['promo_code']; ?>
											</div>
											<?php } ?>
										</td>

										<td class="text-left">
											<select name="paytm_promo_codes[<?php echo $promo_code_row; ?>][status]" class="form-control">
												<?php if ($code['status']) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</td>

										<td class="text-left">
											<div class="col-sm-12">
												<div class="input-group date">
													<input type="text" name="paytm_promo_codes[<?php echo $promo_code_row; ?>][start_date]" value="<?php echo $code['start_date']; ?>" placeholder="<?php echo $entry_promo_code_start_date; ?>" data-format="YYYY-MM-DD" id="input-value<?php echo $promo_code_row; ?>" class="form-control" maxlength="10"/>
													<span class="input-group-btn">
														<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
												<?php if (isset($error_promo_codes[$promo_code_row]['start_date'])) { ?>
												<div class="text-danger">
													<?php echo $error_promo_codes[$promo_code_row]['start_date']; ?>
												</div>
												<?php } ?>
											</div>
										</td>
										<td class="text-left">
											<div class="col-sm-12">
												<div class="input-group date">
													<input type="text" name="paytm_promo_codes[<?php echo $promo_code_row; ?>][end_date]" value="<?php echo $code['end_date']; ?>" placeholder="<?php echo $entry_promo_code_end_date; ?>" data-format="YYYY-MM-DD" id="input-value<?php echo $promo_code_row; ?>" class="form-control"  maxlength="10"/>
													<span class="input-group-btn">
														<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
												<?php if (isset($error_promo_codes[$promo_code_row]['end_date'])) { ?>
												<div class="text-danger">
													<?php echo $error_promo_codes[$promo_code_row]['end_date']; ?>
												</div>
												<?php } ?>
											</div>
										</td>

										<td class="text-left"><button type="button" onclick="$('#promo-code-row<?php echo $promo_code_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_promo_code_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
									</tr>
									<?php $promo_code_row++; ?>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="4"></td>
										<td class="text-left"><button type="button" onclick="addPromoCode();" data-toggle="tooltip" title="<?php echo $button_promo_code_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
									</tr>
								</tfoot>
							</table>
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
				</div>
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

var promo_code_row = <?php echo $promo_code_row; ?>;

function addPromoCode() {
	html  = '<tr id="promo-code-row' + promo_code_row + '">';
	html += '  <td class="text-left"><input type="text" name="paytm_promo_codes['+promo_code_row+'][code]" value="" placeholder="<?php echo $entry_promo_code; ?>" class="form-control" /></td>';
	html += '<td class="text-left"><select name="paytm_promo_codes['+promo_code_row+'][status]" class="form-control"><option value="1" selected="selected"><?php echo $text_enabled; ?></option><option value="0"><?php echo $text_disabled; ?></option></select></td>';
	html += '<td>'
			+		'<div class="col-sm-12">'
			+			'<div class="input-group date">'
			+				'<input type="text" name="paytm_promo_codes['+promo_code_row+'][start_date]" value="" placeholder="<?php echo $entry_promo_code_start_date; ?>" data-format="YYYY-MM-DD" id="input-value'+promo_code_row+'" class="form-control" maxlength="10" />'
			+				'<span class="input-group-btn">'
			+					'<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>'
			+				'</span>'
			+			'</div>'
			+		'</div>'
			+	'</td>'
			+	'<td>'
			+		'<div class="col-sm-12">'
			+			'<div class="input-group date">'
			+				'<input type="text" name="paytm_promo_codes['+promo_code_row+'][end_date]" value="" placeholder="<?php echo $entry_promo_code_end_date; ?>" data-format="YYYY-MM-DD" id="input-value'+promo_code_row+'" class="form-control" maxlength="10" />'
			+				'<span class="input-group-btn">'
			+					'<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>'
			+				'</span>'
			+			'</div>'
			+		'</div>'
			+	'</td>';

	html += '  <td class="text-left"><button type="button" onclick="$(\'#promo-code-row' + promo_code_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_promo_code_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#promo-codes tbody').append(html);
	$('.date').datetimepicker({pickTime: false});
	
	promo_code_row++;
}

$('.date').datetimepicker({pickTime: false});


$(document).ready(function(){
	var active_tab = $(".tab-pane .text-danger").eq(0).parents(".tab-pane").attr("id");
	$("a[href='#"+active_tab+"'").trigger("click");
});

function switchToTab(tab_name){
	$('.nav-tabs a[href="#'+tab_name+'"]').tab('show');
}

//--></script>
<?php echo $footer; ?>