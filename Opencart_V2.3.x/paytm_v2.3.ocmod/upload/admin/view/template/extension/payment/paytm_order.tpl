<div class="" id="paytm_payment_area">
  <?php if($savePaytmResponse){?>
    <div class="btn-area pull-left">
      <a href="javascript:void(0);"  id="button-fetch" data-toggle="tooltip" title="Fetch Status" class="btn btn-info"><i class="fa fa-refresh"></i></a>
    </div>
  <?php } ?>
  <div class="message"></div>
</div>
<style>.btn-area{margin: 0 20px 30px 0;} .paytm_highlight{ font-weight: bold;}.redColor{color:#f00;}</style>
<table class="table table-striped table-bordered" id="paytm_payment_table">
  <?php foreach($paytm_response as $key => $value){
    $_class = ($key == 'STATUS' && $value == 'PENDING') ? 'paytm_highlight redColor' : '';
  ?>
  <tr>
    <td class="text-left"><?php echo $key;?></td>
    <td class="text-left <?php echo $_class;?>"><?php echo $value;?></td>
  </tr>
  <?php } ?>
</table>

<?php if($savePaytmResponse){?>
<script type="text/javascript">
    $("#button-fetch").click(function () {
      $('#paytm_payment_area div.message').html('');
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data: {'paytm_order_id': '<?php echo $paytm_order_id;?>','order_data_id': '<?php echo $order_data_id;?>'},
          url: 'index.php?route=extension/payment/paytm/savetxnstatus&token=<?php echo $token?>',
          beforeSend: function () {
            $('#button-fetch i').addClass('fa-spin');
          },
          success: function (data) {
            var html = '';
            if (data.success == true) {
              var txn_status_btn = false;
              $.each(data.response, function (index, value) {
                var _class = (index == 'STATUS' && value == 'PENDING') ? 'paytm_highlight redColor' : '';
                html += '<tr>';
                html += '<td class="text-left">' + index + '</td>';
                html += '<td class="text-left '+ _class +'">' + value + '</td>';
                html += '</tr>';
              });
              
              $('#paytm_payment_table').html(html);
              $('#paytm_payment_area div.message').html('<div class="alert alert-success">' + data.message +'</div>');
            }else{
              $('#paytm_payment_area div.message').html('<div class="alert alert-danger">' + data.message +'</div>');
            }
            $('#button-fetch i').removeClass('fa-spin');
        });
    });
</script>
<?php } ?>

