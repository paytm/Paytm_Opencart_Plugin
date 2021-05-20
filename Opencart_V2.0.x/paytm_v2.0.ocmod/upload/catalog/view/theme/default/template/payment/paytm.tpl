<script type="application/javascript" crossorigin="anonymous" src="<?php echo $srcUrl; ?>"></script>
<div class="buttons">
  <div class="pull-right text-right" id="btn-confirm-paytm">
    <input type="submit" value="<?php echo $button_confirm; ?>"  onClick="openJsCheckout();" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<style>
#paytmError{ color:red; }
</style>
<script type="text/javascript">
  function openJsCheckout(){
    if(document.getElementById("paytmError")!==null){ 
      document.getElementById("paytmError").remove(); 
    }
    var txntoken = "<?php echo $txnToken; ?>";
    if(txntoken){
      invokeBlinkCheckoutPopup("<?php echo $txnToken; ?>", "<?php echo $orderId; ?>", "<?php echo $amount; ?>");
    }else{
      document.getElementById("btn-confirm-paytm").innerHTML += '<div id="paytmError"><?php echo $message; ?></div>';
    }
  }


  function invokeBlinkCheckoutPopup(txnToken, orderId, amount){
        var config = {
         "root": "",
         "flow": "DEFAULT",
         "data": {
          "orderId": orderId /* update order id */,
          "token": txnToken /* update token value */,
          "tokenType": "TXN_TOKEN",
          "amount": amount /* update amount */
         },
         "integration": {
                          "platform": "Opencart",
                          "version": "<?php echo $version; ?>"
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
                  //  console.log("error => ",error);
                });
        } 
    }
</script>