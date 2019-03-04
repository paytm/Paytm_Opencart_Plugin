<div class="" id="paytm_payment_area">
    <div class="message"></div>
</div>
<style>.paytm_highlight{ font-weight: bold;}.redColor{color:#f00;}</style>
<table class="table table-striped table-bordered" id="paytm_payment_table">
   <?php foreach($paytm_response as $key => $value){?>
   <?php 
    $_class = '';
      if($key == 'STATUS'){ 
        $_class = 'paytm_highlight';
        if($value == 'PENDING'){ 
          $_class = 'paytm_highlight redColor';
        }
    }   
   ?>
      <tr>
        <td class="text-left <?php echo $_class;?>"><?php echo $key;?></td>
        <td class="text-left <?php echo $_class;?>"><?php echo $value;?>

        <?php if($key == 'STATUS' && $value == 'PENDING'){?>
          <a href="javascript:void(0);" id="button-fetch" class="btn btn-success btn-sm"><?php echo $button_fetch_status?></a>
          <span class="btn btn-success btn-sm" id="loading-fetch" style="display:none;"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></span>
        <?php } ?>
        </td>
      </tr>
    <?php } ?>
</table>


<script type="text/javascript"><!--
    $("#button-fetch").click(function () {
      $('#paytm_payment_area div.message').html('');
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {'paytm_order_id': '<?php echo $paytm_order_id;?>','order_data_id': '<?php echo $order_data_id;?>'},
          url: 'index.php?route=extension/payment/paytm/savetxnstatus&token=<?php echo $token?>',
          beforeSend: function () {
            $('#button-fetch').hide();
            $('#loading-fetch').show();
          },
          success: function (data) {
            var html = '';
            if (data.success == true) {
              var txn_status_btn = false;
              $.each(data.response, function (index, value) {

                var _class = '';
                if(index == 'STATUS'){
                  _class = 'paytm_highlight ';
                  if(value == 'PENDING'){
                  _class += 'redColor ';
                  }
                }

                html += '<tr>';
                html += '<td class="text-left '+ _class +'">' + index + '</td>';
                html += '<td class="text-left '+ _class +'">' + value + '</td>';
                html += '</tr>';
                if(index == 'STATUS' && value == 'PENDING'){
                  var txn_status_btn = true;
                }
              });

              $('#paytm_payment_table').html(html);
              $('#paytm_payment_area div.message').html('<div class="alert alert-success">' + data.message +'</div>');
              if(txn_status_btn == false){
                $('#button-fetch').remove();
              }
            }else{
              $('#paytm_payment_area div.message').html('<div class="alert alert-danger">' + data.message +'</div>');
            }
            $('#loading-fetch').hide();
          }
        });
    });
//--></script>

