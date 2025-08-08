@extends('layouts.admin')

@section('title', 'Editar Seção Personalizada')

@section('content')
    <section class="container mx-auto py-8 max-w-3xl">
        <h3 class="text-2xl font-bold mb-4">Editar Seção: {{ $section->name }}</h3>

        <x-alert />

        <form action="{{ route('sections.update', $section->id) }}" method="POST" id="sectionForm"
            class="bg-gray-800 p-6 rounded text-white">
            @csrf
            @method('PUT')

            <div class="form-group mb-4">
                <label for="name">Nome da Seção</label>
                <input type="text" name="name" class="form-control-like" value="{{ $section->name }}" required>
            </div>

            <div class="form-group mb-4">
                <label for="order">Ordem da Seção</label>
                <input type="number" name="order" class="form-control-like" value="{{ $section->order }}">
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
                <ul id="results"
                    class="bg-gray-900 border border-gray-700 text-white rounded mt-2 hidden max-h-60 overflow-y-auto"></ul>
            </div>

            <div class="my-4">
                <h4 class="text-lg font-semibold mb-2">Itens Selecionados</h4>
                <ul id="selectedItems" class="space-y-2">
                    @foreach ($section->items as $index => $item)
                        <li class="bg-gray-700 p-2 rounded flex items-center justify-between gap-4">
                            <div>
                                <strong>{{ $item->content->title ?? 'Sem título' }}</strong>
                                <small class="text-sm">({{ $item->content_type }})</small>
                                <input type="hidden" name="items[{{ $index }}][content_id]" value="{{ $item->content_id }}">
                                <input type="hidden" name="items[{{ $index }}][content_type]" value="{{ $item->content_type }}">
                            </div>
                            <div class="flex gap-2 items-center">
                                <label>Ordem:</label>
                                <input type="number" name="items[{{ $index }}][order]" value="{{ $item->order }}"
                                    class="form-control-like w-20">
                                <button type="button" class="btn btn-secondary removeItem">
                                    <i class="fas fa-eraser"></i> Remover
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <button type="submit" class="btn btn-primary mt-4">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
        </form>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let itemIndex = {{ $section->items->count() }};

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
                url: "{{ route('sliders.search') }}",
                method: 'GET',
                data: { type, query },
                success: function (data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            let title = item.title ?? item.name ?? 'Sem título';
                            let year = item.year ? `<span class="text-sm text-gray-400 ml-2">(${item.year})</span>` : '';

                            html += `<li class="p-2 hover:bg-gray-700 cursor-pointer flex items-center justify-between"
                                    data-id="${item.id}"
                                    data-title="${title}"
                                    data-type="${type}">
                                    <span>${title} ${year}</span>
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
                            <button type="button" class="btn btn-secondary removeItem"><i class="fas fa-eraser"></i> Remover</button>
                        </div>
                    </li>`;

            $('#selectedItems').append(newItem);
            $('#search').val('');
            $('#results').hide();
            itemIndex++;
        });

        $(document).on('click', '.removeItem', function () {
            $(this).closest('li').remove();
        });
    </script>

    <style>
        #results li {
            padding: 10px 14px;
            border-bottom: 1px solid #2d2d2d;
            transition: background-color 0.2s ease, transform 0.1s ease;
            border-radius: 6px;
            margin: 4px;
        }

        #results li:hover {
            background-color: #4c4c70;
            transform: scale(1.02);
        }

        #results {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        #selectedItems {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        #selectedItems li {
            background-color: #2d2d3f;
            border-radius: 6px;
            padding: 8px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            gap: 8px;
        }

        #selectedItems li:hover {
            background-color: #3a3a56;
        }

        #selectedItems li strong {
            font-weight: 600;
            font-size: 14px;
        }

        #selectedItems li small {
            font-size: 12px;
            color: #aaa;
        }

        #selectedItems input[type="number"] {
            width: 50px;
            font-size: 13px;
            padding: 4px;
        }
    </style>
@endsection