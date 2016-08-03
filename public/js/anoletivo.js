/**
 * Created by eduardojunior on 30/07/16.
 */
$(document).ready(function (){

    $('.btn-inserir-modulo').click(function () {
        add_modulo();
    });

    function add_modulo(){

        var qtdAnosLetivosModulos = $('.anoLetivoModulos').length;
        var options_qtd = $(".modulo > option").clone().length;

        var original = $('select.modulo:eq(0)');
        var allSelects = $('select.modulo');
        var clone = original.clone();
        var quantidade_options = options_qtd - 1;


        if (qtdAnosLetivosModulos < quantidade_options){

            var estilo = 'bg-warning';
            if ((qtdAnosLetivosModulos %2 ) == 0)
                estilo = 'bg-default';

            var template;
            var inputId = $('<input type="hidden" name="anoLetivoModulos[' + qtdAnosLetivosModulos + '][id]" value>');
            var inputAnoLetivo = $('<input type="hidden" name="anoLetivoModulos[' + qtdAnosLetivosModulos + '][anoLetivo]" value>');
            var div_estilo = $('<div class="row ' + estilo + ' anoLetivoModulos">');
            var div_form_group = $('<div class="form-group">');
            var select_modulos = $('<select name="anoLetivoModulos[' + qtdAnosLetivosModulos + '][modulo]" ' +
                'class="form-control">');
            var data_inicio = $('<input type="text" name="anoLetivoModulos[' + qtdAnosLetivosModulos + '][dataInicio]" ' +
                'class="form-control dataInicio">');
            var data_fim = $('<input type="text" name="anoLetivoModulos[' + qtdAnosLetivosModulos + '][dataFim]" ' +
                'class="form-control dataFim">');


            // verificar os options cloned se tem algum selecionado se tiver, remover ele da nova insercao
            // $( "#myselect" ).val();
            // <select name="" class="form-control modulo"><option value="">Selecione</option>

            //console.log(qtdAnosLetivosModulos-1);

            var option_selected = $('select[name="anoLetivoModulos[' + (qtdAnosLetivosModulos-1) + '][modulo]"]').val();
            if (option_selected != "") {
                $('option', clone).filter(function(i) {
                    return allSelects.find('option:selected[value="' + $(this).val() + '"]').length;
                }).remove();
            }
            //console.log($("select[name="anoLetivoModulos[0][modulo]"] option:selected").val());


            var template = div_estilo.append(
                inputId,
                inputAnoLetivo,
                div_form_group.append(
                    $('<div class="col-md-1">').append(
                        $('<label>').append('Módulo:')
                    ),
                    $('<div class="col-md-3">').append(
                        select_modulos.append($(clone).find('option'))
                    ),
                    $('<div class="col-md-1">').append(
                        $('<label>').append('Data Início:')
                    ),
                    $('<div class="col-md-2">').append(
                        data_inicio
                    ),
                    $('<div class="col-md-1">').append(
                        $('<label>').append('Data Fim:')
                    ),
                    $('<div class="col-md-2">').append(
                        data_fim
                    )
                )
            );

            $('.panel-body').append(template);

            try {
                $(".dataInicio").mask("99-99-9999");
                $(".dataFim").mask("99-99-9999");
            }
            catch(err) {
                alert('Biblioteca Masked Input não carregada');
            }


            var qtdAnosLetivosModulos = $('.anoLetivoModulos').length;
            if (qtdAnosLetivosModulos == quantidade_options){
                $('.btn-inserir-modulo').prop('disabled', true);
            }

        }
    }

});