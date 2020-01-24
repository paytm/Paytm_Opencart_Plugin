<form action="<?php echo $action; ?>" method="POST">
	<?php foreach($paytm_fields as $k=>$v) { ?>
	<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
	<?php } ?>
	<div class="buttons">
		<div class="right">
			<input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
		</div>
	</div>
</form>