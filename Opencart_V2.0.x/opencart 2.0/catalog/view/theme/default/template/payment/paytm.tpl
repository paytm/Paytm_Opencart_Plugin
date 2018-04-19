<?php if (isset($conversion_text) && !empty($conversion_text)) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $conversion_text; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="POST" class="form-horizontal" id="paytm_form_redirect">
	<?php foreach($paytm_fields as $k=>$v) { ?>
	<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
	<?php } ?>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>

<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $('#paytm_form_redirect').submit();
});
//--></script>