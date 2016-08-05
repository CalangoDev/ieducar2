/**
 * Created by eduardojunior on 30/07/16.
 */
$(document).ready(function (){


    function validaDatas(dataFim, dataInicio){

        var dataInicio = new Date(dataInicio.split('-')[2], dataInicio.split('-')[1], dataInicio.split('-')[0]);
        var dataFim = new Date(dataFim.split('-')[2], dataFim.split('-')[1], dataFim.split('-')[0]);

        if (dataInicio > dataFim){
            return false;
        }

        return true;
    }

    /*

     <div class="form-group has-error">
     <label class="control-label" for="inputError1">Input with error</label>
     <input type="text" class="form-control" id="inputError1">
     </div>

     */

    $('.dataFim').on('focusout', function () {
        var dataFim = $(this).val();
        var dataInicio = $(this).parent().parent().prev().prev().find('.dataInicio').val();
        if (!validaDatas(dataFim, dataInicio)){
            alert('Atenção! Data Fim não pode ser menor que a Data Inicial');
            // mostrar na tela que tem um erro no input
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
        }
    });

    $('.dataInicio').on('focusout', function () {
        // verificar se tem dataFIm já preenchido se tiver chama a funcao validaDatas
        var dataInicio = $(this).val();
        var dataFim = $(this).parent().parent().next().next().find('.dataFim').val();
        if (dataFim != '')
            if (!validaDatas(dataFim, dataInicio)){
                alert('Atenção! Data Fim não pode ser menor que a Data Inicial');
                $(this).parent().addClass('has-error');
            } else {
                $(this).parent().removeClass('has-error');
            }
    });

    function add_modulo(){

        var qtdAnosLetivosModulos = $(".anoLetivoModulos").length;
        var options_qtd = $(".modulo > option").clone().length;

        var original = $('select.modulo:eq(0)');
        var allSelects = $('select.modulo');
        var clone = original.clone();
        var quantidade_options = options_qtd - 1;


        if (qtdAnosLetivosModulos < quantidade_options){

            var estilo = "bg-warning";
            if ((qtdAnosLetivosModulos %2 ) == 0)
                estilo = "bg-default";

            var template;
            var inputId = $("<input type='hidden' name='anoLetivoModulos[" + qtdAnosLetivosModulos + "][id]' value>");
            var inputAnoLetivo = $("<input type='hidden' name='anoLetivoModulos[" + qtdAnosLetivosModulos + "][anoLetivo]' value>");
            var div_estilo = $("<div class='row " + estilo + " anoLetivoModulos'>");
            var div_form_group = $("<div class='form-group'>");
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
                        $("<div class='input-group date'>").append(
                            data_inicio,
                            $("<span class='input-group-addon'>").append(
                                $("<span class='glyphicon glyphicon-calendar'>")
                            )
                        )
                    ),
                    $('<div class="col-md-1">').append(
                        $('<label>').append('Data Fim:')
                    ),
                    $('<div class="col-md-2">').append(
                        $("<div class='input-group date'>").append(
                            data_fim,
                            $("<span class='input-group-addon'>").append(
                                $("<span class='glyphicon glyphicon-calendar'>")
                            )
                        )
                    )
                )
            );

            $('.panel-body').append(template);

            try {
                $('.dataInicio, .dataFim').datetimepicker({
                    locale: 'pt-br',
                    format: 'DD-MM-YYYY'
                });
            }
            catch(err) {
                alert('Biblioteca DateTimePicker não carregada');
            }

            $('.dataFim').on('focusout', function () {
                var dataFim = $(this).val();
                var dataInicio = $(this).parent().parent().prev().prev().find('.dataInicio').val();
                if (!validaDatas(dataFim, dataInicio)){
                    alert('Atenção! Data Fim não pode ser menor que a Data Inicial');
                    // mostrar na tela que tem um erro no input
                    $(this).parent().addClass('has-error');
                } else {
                    $(this).parent().removeClass('has-error');
                }
            });

            $('.dataInicio').on('focusout', function () {
                var dataInicio = $(this).val();
                var dataFim = $(this).parent().parent().next().next().find('.dataFim').val();
                if (dataFim != '')
                    if (!validaDatas(dataFim, dataInicio)){
                        alert('Atenção! Data Fim não pode ser menor que a Data Inicial');
                        $(this).parent().addClass('has-error');
                    } else {
                        $(this).parent().removeClass('has-error');
                    }
            });


            var qtdAnosLetivosModulos = $('.anoLetivoModulos').length;
            if (qtdAnosLetivosModulos == quantidade_options){
                $('.btn-inserir-modulo').prop('disabled', true);
            }

        }
    }

    $(".btn-inserir-modulo").click(function () {
        add_modulo();
    });

});