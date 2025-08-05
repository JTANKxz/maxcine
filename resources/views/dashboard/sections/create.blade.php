@extends('layouts.admin')

@section('title', 'Nova Seção Personalizada')

@section('content')
<section class="container mx-auto py-8 max-w-3xl">
    <h3 class="text-2xl font-bold mb-4">Nova Seção Personalizada</h3>

    <x-alert />

    <form action="{{ route('sections.store') }}" method="POST" id="sectionForm" class="bg-gray-800 p-6 rounded text-white">
        @csrf

        <div class="form-group mb-4">
            <label for="name">Nome da Seção</label>
            <input type="text" name="name" class="form-control-like" required>
        </div>

        <div class="form-group mb-4">
            <label for="order">Ordem da Seção</label>
            <input type="number" name="order" class="form-control-like" value="0">
        </div>

        <hr class="my-4 border-gray-600">

        <div class="form-group">
            <label for="type_select">Tipo de Conteúdo</label>
            <select id="type_select" class="form-control-like">
                <option value="movie">Filme</option>
                <option value="serie">Série</option>
            </select>
        </div>

        <div class="form-group">
            <label for="search">Buscar Conteúdo</label>
            <input type="text" id="search" placeholder="Digite o nome..." class="form-control-like" autocomplete="off">
            <ul id="results" class="bg-gray-900 border border-gray-700 text-white rounded mt-2 hidden max-h-60 overflow-y-auto"></ul>
        </div>

        <div class="my-4">
            <h4 class="text-lg font-semibold mb-2">Itens Selecionados</h4>
            <ul id="selectedItems" class="space-y-2"></ul>
        </div>

        <button type="submit" class="btn btn-primary mt-4">
            <i class="fas fa-save"></i> Criar Seção
        </button>
    </form>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let itemIndex = 0;

    $('#type_select').change(function () {
        $('#search').val('');
        $('#results').empty().hide();
    });

    $('#search').on('input', function () {
        let type = $('#type_select').val();
        let query = $(this).val();

        if (query.length < 2) {
            $('#results').hide();
            return;
        }

        $.ajax({
            url: "{{ route('sliders.search') }}", // sua rota de busca
            method: 'GET',
            data: { type, query },
            success: function (data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        let title = item.title ?? item.name ?? 'Sem título';

                        html += `<li class="p-2 hover:bg-gray-700 cursor-pointer" 
                            data-id="${item.id}"
                            data-title="${title}"
                            data-type="${type}">
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
        const id = $(this).data('id');
        const title = $(this).data('title');
        const type = $(this).data('type');

        const newItem = `
            <li class="bg-gray-700 p-2 rounded flex items-center justify-between gap-4">
                <div>
                    <strong>${title}</strong> <small class="text-sm">(${type})</small>
                    <input type="hidden" name="items[${itemIndex}][content_id]" value="${id}">
                    <input type="hidden" name="items[${itemIndex}][content_type]" value="${type}">
                </div>
                <div class="flex gap-2 items-center">
                    <label>Ordem:</label>
                    <input type="number" name="items[${itemIndex}][order]" value="0" class="form-control-like w-20">
                    <button type="button" class="removeItem text-red-400 hover:text-red-600 text-sm">Remover</button>
                </div>
            </li>
        `;

        $('#selectedItems').append(newItem);
        $('#search').val('');
        $('#results').hide();
        itemIndex++;
    });

    $(document).on('click', '.removeItem', function () {
        $(this).closest('li').remove();
    });
</script>
@endsection
