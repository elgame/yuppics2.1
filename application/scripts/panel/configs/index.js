$(function(){
  $("#sliderPorcentage").slider({
      range: "min",
      value: $('#pporcentaje').val(),
      min: 0,
      max: 100,
      slide: function( event, ui ) {
        $( "#sliderPorcentageLabel" ).html(ui.value );
        $('#pporcentaje').val(ui.value);
      }
    });
});