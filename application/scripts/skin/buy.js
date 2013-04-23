$(function(){

  var msg_modal = $("#messajes_alerts"),
     obj_alert = $('#alert-throw');
  if (obj_alert.attr('data-throwalert') === 'true') {
    msg_modal.find('.modal-body').html(obj_alert.val());
    msg_modal.modal('show');
  }

  $('#modal-alert-btn').on('click', function(e) {
    event.preventDefault();
    window.location.href = base_url;
  });

  $('#btnDiscount').on('click', function(e) {
    loader.create('#txt-discount');
    var objcode = $('#codeDiscount'), tipo, campod, disc;
    $('#type_discount_id').val('');
    $('#type_discount').val('');
    if (objcode.val() !== '') {
      $.post(base_url + 'buy/valid_code', {'code': objcode.val(), 'type': $('input[name=typeDiscount]:checked').val()}, function(data) {

        $('#discount_alert').removeClass('hide alert-success alert-info alert-error alert-block').addClass('alert-'+data.frm_errors.ico).show(300).find('span').html(data.frm_errors.msg);
        if (data.frm_errors.ico === 'success') {
          if(data.frm_errors.data.amount==0){
            tipo = 'percent';
            campod = 'percentage';
          }else{
            tipo = 'amount';
            campod = 'amount';
          }

          disc = calcTotal(data.frm_errors.data[campod], tipo);
          $('#type_discount_id').val(data.frm_errors.data.id);
          $('#type_discount').val(data.frm_errors.data.type);
          $('#txt-discount').find('strong').html(util.darFormatoNum(disc) + data.frm_errors.data.amount_format);
        }
        else {
          calcTotal(0);
           $('#txt-discount').find('strong').html('$0.00MXN');
        }
        loader.close();
      }, "json");
    }
  });

  $('#codeDiscount').on('keyup', function(e) {
    var objbtn = $('#btnDiscount'), objinput = $(this);
    if (objinput.val() !== '')
      objbtn.removeAttr('disabled');
    else
      objbtn.attr('disabled', 'disabled');
  });

  $('input#yqty').on('change', function(e) {
      var obj = $(this);
      calcSubtotal();
      calcTotal($('#tdiscount').val());
  });

});

function calcTotal(discount, disco_type) {
  var disc     = parseFloat(discount),
      subtotal = parseFloat($('#tsubtotal').val()),
      ship     = parseFloat($('#tship').val());

  disc = (disco_type=='percent'? (subtotal*discount/100): disc);

  var ttotal = (subtotal + ship) - disc;

  $('#tdiscount').val(disc);
  $('#ttotal').val(ttotal);

  $('#tdiscount-format').html(util.darFormatoNum(disc) + 'MXN');
  $('#ttotal-format').html(util.darFormatoNum(ttotal) + 'MXN');

  return disc;
}

function calcSubtotal() {
  var subtotal = 0;
  $('input[type="number"]#yqty').each(function(i, e){
    var obj = $(this),
        sub = parseFloat(obj.val()) * parseFloat($('input[name="yprice[]"][data-id="'+obj.attr('data-id')+'"]').val());
    $('td#'+obj.attr('data-id')).html(util.darFormatoNum(sub) + 'MXN');
    subtotal += sub;
  });

  $('#tsubtotal').val(subtotal);
}

function address_success_shop() {
  setTimeout(function(){
    window.location.href = base_url+"buy/order?y="+getUrlVars()["y"];
  }, 1000);
}


function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
    vars[key] = value;
  });
  return vars;
}
