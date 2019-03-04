<form action="<?php echo $action; ?>" method="POST" class="form-horizontal" id="paytm_form_redirect">
	<?php foreach($paytm_fields as $k=>$v) { ?>
	<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
	<?php } ?>
</form>

<?php if($show_promo_code){ ?>
<div id="promo-code-section" class="row">
	<div class="col-md-offset-9">
		<div class="input-group">
			<input type="text" id="promo_code" name="promo_code" class="form-control" placeholder="Promo Code">
			<span class="input-group-btn">
				<button id="btn_promo_code" class="btn btn-primary" type="button">Apply</button>
			</span>
		</div>
  </div>
</div>
<?php } ?>

<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>

<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $('#paytm_form_redirect').submit();
});


<?php if($show_promo_code){ ?>
/*
* Promo Code functionality starts here
*/
var original_checksum = "<?php echo $paytm_fields['CHECKSUMHASH']; ?>";

$("#btn_promo_code").click(function(){

	$("#promo-code-section .has-error").removeClass("has-error");
	$("#promo-code-section .text-danger, #promo-code-section .text-success").remove();

	// if some promo code already applied and now user requests to remove it
	if($(this).hasClass("removePromoCode")){

		// remove promo code from form params
		$("form#paytm_form_redirect input[name=PROMO_CAMP_ID]").remove();
		$("form#paytm_form_redirect input[name=CHECKSUMHASH]").val(original_checksum);


		// enable input to allow user to enter promo code
		$("#promo_code").prop("disabled", false).val("");
		$("#btn_promo_code").addClass("btn-primary").removeClass("btn-danger").removeClass("removePromoCode").text("Apply");

	} else {

		if($("#promo_code").val().trim() == "") {
			$("#promo_code").parent().addClass("has-error");
			return;
		};

		$.ajax({
			url: 'index.php?route=payment/paytm/apply_promo_code',
			type: 'post',
			dataType: 'json',
			data: $("form#paytm_form_redirect").serialize() + "&promo_code="+$("#promo_code").val(),
			success: function(res){
				if(res.success == true){
					// remove old input if there is already exists, to avoid duplicate inputs
					$("form#paytm_form_redirect input[name=PROMO_CAMP_ID]").remove();

					// add promo code to form post
					$("form#paytm_form_redirect").append('<input type="hidden" name="PROMO_CAMP_ID" value="'+$("#promo_code").val()+'"/>');

					// bind new generated checksum
					$("form#paytm_form_redirect input[name=CHECKSUMHASH]").val(res.CHECKSUMHASH);

					$("#promo_code").parent().parent().append("<span class=\"text-success\">"+ res.message +"</span>");

					$("#promo_code").prop("disabled", true);
					$("#btn_promo_code").removeClass("btn-primary").addClass("btn-danger").addClass("removePromoCode").text("Remove");
				} else {
					$("#promo_code").parent().addClass("has-error").parent().append("<span class=\"text-danger\">"+ res.message +"</span>");
				}			
			}
		});
	}
});
/*
* Promo Code functionality starts here
*/
<?php } ?>

//--></script>