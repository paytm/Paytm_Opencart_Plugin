<form action="<?php echo $action_url; ?>" method="post">
  <input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum; ?>" />

<input type='hidden' name='MID' value="<?php echo $merchant; ?>"/>
<input type='hidden' name='ORDER_ID' value="<?php echo $trans_id; ?>" />
<input type='hidden' name='CUST_ID' value="<?php echo $customer_id; ?>"/>
<input type='hidden' name='TXN_AMOUNT' value="<?php echo $amount; ?>" />
<input type='hidden' name='CHANNEL_ID' value="<?php echo $channel_id; ?>" />
<input type='hidden' name='INDUSTRY_TYPE_ID' value="<?php echo $industry_type_id; ?>" />
<input type='hidden' name='WEBSITE' value="<?php echo $website; ?>" />
<input type='hidden' name='EMAIL' value="<?php echo $email; ?>" />
<input type='hidden' name='MOBILE_NO' value="<?php echo $mobile_no; ?>" />


  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
