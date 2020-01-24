<form action="<?php echo $action; ?>" method="POST" class="form-horizontal" id="paytm_form_redirect">
	<?php foreach($paytm_fields as $k => $v) { ?>
	<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
	<?php } ?>
<div class="buttons">
  <div class="pull-right">
    <input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
</form>
