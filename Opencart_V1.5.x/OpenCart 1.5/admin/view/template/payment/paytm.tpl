<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		
		
          <tr>
            <td><span class="required">*</span> <?php echo $entry_merchant; ?></td>
            <td><input type="text" name="paytm_merchant" value="<?php echo $paytm_merchant; ?>" />
              <?php if ($error_merchant) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?></td>
          </tr>
		  
		  <tr>
            <td><span class="required">*</span> <?php echo $entry_merchantkey; ?></td>
            <td><input type="text" name="paytm_key" value="<?php echo $paytm_key; ?>" />
              <?php if ($error_key) { ?>
              <span class="error"><?php echo $error_key; ?></span>
              <?php } ?></td>
          </tr>
      <!-- <tr>
          <td><?php echo $entry_environment; ?></td>
          <td>
              <select name="paytm_environment">
                  <?php if ($paytm_environment == "P") { ?>
                      <option value="P" selected="selected"><?php echo $text_env_production; ?></option>
                      <option value="T"><?php echo $text_env_test; ?></option>
                  <?php } else { ?>
                      <option value="P"><?php echo $text_env_production; ?></option>
                      <option value="T" selected="selected"><?php echo $text_env_test; ?></option>
                  <?php } ?>
              </select>
          </td>
      </tr> -->

      <tr>
          <td><span class="required">*</span> <?php echo $entry_transaction_url; ?></td>
          <td>
              <input type="text" name="payment_paytm_transaction_url" value="<?php echo $payment_paytm_transaction_url; ?>" />
              <?php if ($error_transaction_url) { ?>
                  <span class="error"><?php echo $error_transaction_url; ?></span>
              <?php } ?>
          </td>
      </tr>

      <tr>
          <td><span class="required">*</span> <?php echo $entry_transaction_url_status; ?></td>
          <td>
              <input type="text" name="payment_paytm_transaction_status_url" value="<?php echo $payment_paytm_transaction_status_url; ?>" />
              <?php if ($error_transaction_status_url) { ?>
                  <span class="error"><?php echo $error_transaction_status_url; ?></span>
              <?php } ?>
          </td>
      </tr>
		  
		  <tr>
            <td><span class="required">*</span> <?php echo $entry_website; ?></td>
            <td><input type="text" name="paytm_website" value="<?php echo $paytm_website; ?>" />
              <?php if ($error_website) { ?>
              <span class="error"><?php echo $error_website; ?></span>
              <?php } ?></td>
          </tr>
		  
		  
		   <tr>
            <td><span class="required">*</span> <?php echo $entry_industry; ?></td>
            <td><input type="text" name="paytm_industry" value="<?php echo $paytm_industry; ?>" />
              <?php if ($error_industry) { ?>
              <span class="error"><?php echo $error_industry; ?></span>
              <?php } ?></td>
          </tr>
		  
          
		  <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="paytm_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $paytm_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
		  
		  
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="paytm_status">
                <?php if ($paytm_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
		  
		  <tr>
            <td><?php echo $callbackurl_status; ?></td>
            <td><select name="paytm_callbackurl">
                <?php if ($paytm_callbackurl) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
		  
		  
		  <tr>
			<td><?php echo $entry_checkstatus; ?></td>
			<td><select name="paytm_checkstatus">
			<?php if ($paytm_checkstatus) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
		  </tr>
		  
		  
		  
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>