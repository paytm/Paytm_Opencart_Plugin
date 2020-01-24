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
   <?php if ($warning) { ?>
   <div class="warning"><?php echo $warning; ?></div>
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
                  <td><span class="required">*</span> <?php echo $entry_merchant_id; ?><br /><span class="help"></span></td>
                  <td><input type="text" name="paytm_merchant_id" value="<?php echo $paytm_merchant_id; ?>" />
                     <?php if ($error_merchant_id) { ?>
                     <span class="error"><?php echo $error_merchant_id; ?></span>
                     <?php } ?>
                  </td>
               </tr>
               <tr>
                  <td><span class="required">*</span> <?php echo $entry_merchant_key; ?></td>
                  <td><input type="text" name="paytm_merchant_key" value="<?php echo $paytm_merchant_key; ?>" />
                     <?php if ($error_merchant_key) { ?>
                     <span class="error"><?php echo $error_merchant_key; ?></span>
                     <?php } ?>
                  </td>
               </tr>
               <tr>
                  <td><span class="required">*</span> <?php echo $entry_website; ?></td>
                  <td><input type="text" name="paytm_website" value="<?php echo $paytm_website; ?>" />
                     <?php if ($error_website) { ?>
                     <span class="error"><?php echo $error_website; ?></span>
                     <?php } ?>
                  </td>
               </tr>
               <tr>
                  <td><span class="required">*</span> <?php echo $entry_industry_type; ?></td>
                  <td><input type="text" name="paytm_industry_type" value="<?php echo $paytm_industry_type; ?>" />
                     <?php if ($error_industry_type) { ?>
                     <span class="error"><?php echo $error_industry_type; ?></span>
                     <?php } ?>
                  </td>
               </tr>
               <tr>
                  <td><?php echo $entry_environment; ?></td>
                  <td>
                     <select name="paytm_environment">
                        <?php if ($paytm_environment == 1) { ?>
                           <option value="0"><?php echo $text_staging; ?></option>
                           <option value="1" selected="selected"><?php echo $text_production; ?></option>
								<?php } else { ?>
                           <option value="0" selected="selected"><?php echo $text_staging; ?></option>
                           <option value="1"><?php echo $text_production; ?></option>
								<?php } ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td><?php echo $entry_order_success_status; ?></td>
                  <td>
                     <select name="paytm_order_success_status_id">
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $paytm_order_success_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td><?php echo $entry_order_failed_status; ?></td>
                  <td>
                     <select name="paytm_order_failed_status_id">
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $paytm_order_failed_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                     </select>
                  </td>
               </tr> 
               <tr>
                  <td><?php echo $entry_total; ?></td>
                  <td><input type="text" name="paytm_total" value="<?php echo $paytm_total; ?>" /></td>
               </tr>
               <tr>
                  <td><?php echo $entry_geo_zone; ?></td>
                  <td><select name="paytm_geo_zone_id">
                     <option value="0"><?php echo $text_all_zones; ?></option>
                     <?php foreach ($geo_zones as $geo_zone) { ?>
                     <?php if ($geo_zone['geo_zone_id'] == $paytm_geo_zone_id) { ?>
                     <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                     <?php } else { ?>
                     <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                     <?php } ?>
                     <?php } ?>
                  </select></td>
               </tr>
               <tr>
                  <td><?php echo $entry_status; ?></td>
                  <td>
                     <select name="paytm_status">
                        <?php if ($paytm_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td><?php echo $entry_sort_order; ?></td>
                  <td><input type="text" name="paytm_sort_order" value="<?php echo $paytm_sort_order; ?>" size="1" /></td>
               </tr>
			      <tr>
                  <td>&nbsp;</td>
                  <td>
						<span><b><?php echo $text_php_version;?></b> <?php echo $php_version;?></span>
						<span> | </span>
						<span><b><?php echo $text_curl_version;?></b> <?php echo $curl_version;?></span>
						<span> | </span>
						<span><b><?php echo $text_opencart_version;?></b> <?php echo $opencart_version;?></span>
						<span> | </span>
						<span><b><?php echo $text_last_updated;?></b> <?php echo $last_updated;?></span>
                  <span> | </span>
						<span><b><a target="_blank" href="https://developer.paytm.com/docs/eCommerce-plugin/opencart/#oc1-5x"><?php echo $text_developer_docs;?></a></b></span>
					</td>
               </tr>
            </table>
         </form>
      </div>
    </div>
   </div>
</div>
<?php echo $footer; ?>