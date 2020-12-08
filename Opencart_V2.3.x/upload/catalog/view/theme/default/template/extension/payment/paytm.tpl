<script type="application/javascript" crossorigin="anonymous" src="<?php echo $srcUrl; ?>"></script>
<div class="buttons">
  <div class="pull-right">
    <input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript">
  $('#button-confirm').on('click', function() {
	invokeBlinkCheckoutPopup("<?php echo $txnToken; ?>", "<?php echo $orderId; ?>", "<?php echo $amount; ?>");
  });

  function invokeBlinkCheckoutPopup(txnToken, orderId, amount){
    //   console.log(txnToken, orderId, amount);
        var config = {
         "root": "",
         "flow": "DEFAULT",
         "data": {
          "orderId": orderId /* update order id */,
          "token": txnToken /* update token value */,
          "tokenType": "TXN_TOKEN",
          "amount": amount /* update amount */
         },
         "handler": {
            "notifyMerchant": function(eventName,data){
				if(eventName == 'SESSION_EXPIRED'){
					location.reload(); 
				}
            } 
          }
        };
      
        if(window.Paytm && window.Paytm.CheckoutJS){
                // initialze configuration using init method 
                window.Paytm.CheckoutJS.init(config).then(function onSuccess() {
                   // after successfully update configuration invoke checkoutjs
                   window.Paytm.CheckoutJS.invoke();
                }).catch(function onError(error){
                   // console.log("error => ",error);
                });
        } 
    }
</script>