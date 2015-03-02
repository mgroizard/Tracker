$(document).ready(function() {
    /* delete confirm */
    $('form#delete').submit(function(e) {
        var $form = $(this);
        var $hidden = $form.find('input[name="modal"]');
        if ($hidden.val() != 1) {
            e.preventDefault();
            $('#delete_confirm').modal('show');
            $('#delete_confirm').find('button.btn-danger').click(function() {
                $('#delete_confirm').modal('hide');
                $hidden.val(1);
                $form.submit();
            });
        }
    });

    // filter icon
    $('button.filter').click(function() {
        var $icon = $(this).find('i');
        var target = $(this).attr('data-target');
        if ($icon.length) {
            var $div = $(target);
            if ($div.height() > 0) {
                $icon.attr('class', 'fa fa-angle-down')
            } else {
                $icon.attr('class', 'fa fa-angle-right')
            }
        }
    });


  $(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
    if ($(this).hasClass("disabled")) {
      e.preventDefault();
      return false;
    }
  });

  $('input[data-date-format]').datepicker({
       language: 'es',
        weekStart: 0,
        todayBtn: true,
        multidate: false,
        autoclose: true,
        todayHighlight: true
  });

  $('input.cuit').mask("99-99999999-9", {placeholder:"_"});

  $('.toolt').tooltip();

   $('.multiselect').multiselect({      
      includeSelectAllIfMoreThan: 5,
      selectAllText: ' Todos',
      selectAllValue: 'multiselect-all',
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      filterPlaceholder: 'Buscar...',
      // possible options: 'text', 'value', 'both'
      filterBehavior: 'text',
      preventInputChangeEvent: false,
      nonSelectedText: 'Nada seleccionado',
      nSelectedText: 'seleccionado',
      templates: {
          button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',
          ul: '<ul class="multiselect-container dropdown-menu"></ul>',
          filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
          li: '<li><a href="javascript:void(0);"><label></label></a></li>',
          divider: '<li class="multiselect-item divider"></li>',
          liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
      }

   });

$('.popover-dismiss').popover({
  placement : 'top',
  trigger: 'focus'  
});

var tipoEntidadABMEntidad = $("#entidad_tipoEntidad"),
    cuitABMEntidad = $("#entidad_cuit");

tipoEntidadABMEntidad.on('change', function(){
  var self = $(this);
  if(self.val() == 1){      
      cuitABMEntidad.attr('disabled', 'disabled');
  }else{
      cuitABMEntidad.removeAttr('disabled');
  }
});

});