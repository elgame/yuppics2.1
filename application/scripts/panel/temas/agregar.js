$(function(){
  $('#pbgcolor, #ptxtcolor').ColorPicker({
    onSubmit: function(hsb, hex, rgb, el) {
      $(el).val('#'+hex).css('background-color', hex);
      $(el).ColorPickerHide();
    },
    onBeforeShow: function () {
      $(this).ColorPickerSetColor(this.value);
    }
  }).on('keyup', function(){
    // $(this).ColorPickerSetColor(this.value);

  });

});