@extends('layouts.admin')

@section('title', 'Adicionar Slider')

@section('content')

    <section class="form-section container mx-auto py-8 max-w-3xl">
        <h3>Adicionar Slider</h3>

        <x-alert />

        {{-- Elementos de seleção de tipo e busca (lógica do código de baixo, classes do código de cima) --}}
        <div class="form-group">
            <label for="type_select">Tipo de Conteúdo</label>
            <select id="type_select" class="form-control-like">
                <option value="movie">Filme</option>
                <option value="serie">Série</option>
            </select>
        </div>

        <div class="form-group">
            <label for="search">Buscar</label>
            <input type="text" id="search" placeholder="Digite o nome..." class="form-control-like" autocomplete="off">
            {{-- A lista de resultados mantém suas classes originais, pois são específicas para sua funcionalidade e
            aparência --}}
            <ul id="results"
                class="bg-gray-900 border border-gray-700 text-white rounded mt-2 hidden max-h-60 overflow-y-auto"></ul>
        </div>

        <form action="{{ route('sliders.store') }}" method="POST" class="bg-gray-800 p-6 rounded shadow text-white">
            @csrf

            {{-- Campos ocultos (lógica do código de baixo) --}}
            <input type="hidden" name="type" id="input_type">
            <input type="hidden" name="content_id" id="input_content_id">
            <input type="hidden" name="slug" id="input_slug">
            <input type="hidden" name="rating" id="input_rating">
            <input type="hidden" name="season_count" id="input_seasoncount">

            {{-- Campos readonly (lógica do código de baixo, classes e estrutura do código de cima) --}}
            <div class="form-group">
                <label for="input_title">Título</label>
                <input type="text" name="title" id="input_title" class="form-control-like" readonly>
            </div>

            <div class="form-group">
                <label for="input_year">Ano</label>
                <input type="text" name="year" id="input_year" class="form-control-like" readonly>
            </div>

            <div class="form-group">
                <label for="input_runtime">Duração (minutos)</label>
                <input type="text" name="runtime" id="input_runtime" class="form-control-like" readonly>
            </div>

            <div class="form-group">
                <label for="input_backdrop">Imagem (Backdrop)</label>
                <input type="text" name="backdrop_url" id="input_backdrop" class="form-control-like" readonly>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar no Slider
            </button>

            {{-- O botão de limpar foi removido pois os campos do formulário são preenchidos automaticamente e são readonly
            --}}
        </form>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // CORRIGIDO: ID correto é #type_select
        $('#type_select').change(function () {
            $('#search').val('');
            $('#results').empty().hide();

            // Limpar campos do form ao trocar o tipo
            $('#input_type').val('');
            $('#input_content_id').val('');
            $('#input_title').val('');
            $('#input_year').val('');
            $('#input_runtime').val('');
            $('#input_backdrop').val('');
        });

        $('#search').on('input', function () {
            let type = $('#type_select').val(); // CORRIGIDO
            let query = $(this).val();

            if (query.length < 2) {
                $('#results').hide();
                return;
            }

            $.ajax({
                url: "{{ route('sliders.search') }}",
                method: 'GET',
                data: { type, query },
                success: function (data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            let title = item.title ?? item.name ?? 'Sem título';
                            let runtime = item.runtime !== null ? item.runtime : '';

                            html += `<li class="p-2 hover:bg-gray-700 cursor-pointer" 
                                data-id="${item.id}"
                                data-slug="${item.slug ?? ''}"
                                data-title="${title}"
                                data-year="${item.year ?? ''}"
                                data-runtime="${runtime}"
                                data-backdrop="${item.backdrop_url ?? ''}"
                                data-rating="${item.rating ?? ''}"
                                data-seasoncount="${item.seasoncount ?? ''}">
                                ${title}
                            </li>`;
                        });
                    } else {
                        html = '<li class="p-2 text-red-400">Nenhum resultado encontrado</li>';
                    }
                    $('#results').html(html).show();
                }
            });
        });

        $(document).on('click', '#results li', function () {
            $('#input_slug').val($(this).data('slug'));
            $('#input_rating').val($(this).data('rating'));
            $('#input_seasoncount').val($(this).data('seasoncount'));
            $('#input_type').val($('#type_select').val()); // CORRIGIDO
            $('#input_content_id').val($(this).data('id'));
            $('#input_title').val($(this).data('title'));
            $('#input_year').val($(this).data('year'));
            $('#input_runtime').val($(this).data('runtime'));
            $('#input_backdrop').val($(this).data('backdrop'));
            $('#results').hide();
            $('#search').val($(this).data('title'));
        });
    </script>
@endsection