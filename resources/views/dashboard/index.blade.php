@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <section class="charts-section">
        <div class="chart-container">
            <h3>Visão Geral de Vendas</h3>
            <canvas id="salesBarChart"></canvas>
        </div>
        <div class="chart-container">
            <h3>Crescimento de Usuários</h3>
            <canvas id="userGrowthLineChart"></canvas>
        </div>
    </section>

    <section class="cards-container">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-wallet"></i>
                <div class="card-content">
                    <h3>Vendas Totais</h3>
                    <p>R$ 15.780,50</p>
                </div>
            </div>
            <div class="card-footer">
                <i class="fas fa-arrow-up" style="color: #28a745;"></i> 12% desde o último mês
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users"></i>
                <div class="card-content">
                    <h3>Novos Usuários</h3>
                    <p>320</p>
                </div>
            </div>
            <div class="card-footer">
                <i class="fas fa-arrow-up" style="color: #28a745;"></i> 8% esta semana
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-shopping-bag"></i>
                <div class="card-content">
                    <h3>Pedidos Pendentes</h3>
                    <p>45</p>
                </div>
            </div>
            <div class="card-footer">
                <i class="fas fa-exclamation-circle" style="color: #ffc107;"></i> Atualize o status
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line"></i>
                <div class="card-content">
                    <h3>Visitas ao Site</h3>
                    <p>25.6k</p>
                </div>
            </div>
            <div class="card-footer">
                <i class="fas fa-arrow-down" style="color: #dc3545;"></i> 5% desde ontem
            </div>
        </div>
    </section>

    <section class="table-container">
        <div class="table-section-header">
            <h3>Usuários Recentes</h3>
            <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Criar Novo</button>
        </div>
        <div style="max-height: 300px; overflow-y: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Registro</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>001</td>
                        <td>Ana Silva</td>
                        <td>ana.silva@example.com</td>
                        <td>2024-05-28</td>
                        <td><span class="status active">Ativo</span></td>
                    </tr>
                    <tr>
                        <td>002</td>
                        <td>Bruno Costa</td>
                        <td>bruno.costa@example.com</td>
                        <td>2024-05-27</td>
                        <td><span class="status pending">Pendente</span></td>
                    </tr>
                    <tr>
                        <td>003</td>
                        <td>Carla Dias</td>
                        <td>carla.dias@example.com</td>
                        <td>2024-05-26</td>
                        <td><span class="status active">Ativo</span></td>
                    </tr>
                    <tr>
                        <td>004</td>
                        <td>Daniel Faria</td>
                        <td>daniel.faria@example.com</td>
                        <td>2024-05-25</td>
                        <td><span class="status cancelled">Cancelado</span></td>
                    </tr>
                    <tr>
                        <td>005</td>
                        <td>Eduarda Lima</td>
                        <td>eduarda.lima@example.com</td>
                        <td>2024-05-24</td>
                        <td><span class="status active">Ativo</span></td>
                    </tr>
                    <tr>
                        <td>006</td>
                        <td>Fábio Martins</td>
                        <td>fabio.martins@example.com</td>
                        <td>2024-05-23</td>
                        <td><span class="status pending">Pendente</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="form-section">
        <h3>Adicionar Novo Produto</h3>
        <form>
            <div class="form-group">
                <label for="productName">Nome do Produto</label>
                <input type="text" id="productName" class="form-control-like" placeholder="Ex: Smartphone X Pro">
            </div>
            <div class="form-group">
                <label for="productCategory">Categoria</label>
                <select id="productCategory" class="form-control-like">
                    <option value="">Selecione uma categoria</option>
                    <option value="electronics">Eletrônicos</option>
                    <option value="books">Livros</option>
                    <option value="clothing">Vestuário</option>
                    <option value="home">Casa e Cozinha</option>
                </select>
            </div>
            <div class="form-group">
                <label for="productDescription">Descrição</label>
                <textarea id="productDescription" class="form-control-like" placeholder="Descreva o produto..."></textarea>
            </div>
            <div class="form-group">
                <label for="productPrice">Preço (R$)</label>
                <input type="text" id="productPrice" class="form-control-like" placeholder="Ex: 999.90">
            </div>
            <div class="form-group">
                <label for="productDate">Data de Lançamento</label>
                <input type="date" id="productDate" class="form-control-like">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Produto</button>
            <button type="reset" class="btn btn-secondary" style="margin-left: 10px;"><i class="fas fa-eraser"></i>
                Limpar</button>
        </form>
    </section>

    <section class="table-container">
        <div class="table-section-header">
            <h3>Produtos em Destaque</h3>
            <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Criar Novo</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>SKU</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="https://placehold.co/100x100/333/fff?text=Prod1" alt="Produto 1"></td>
                    <td>Smartphone X Pro</td>
                    <td>SPX-001</td>
                    <td>R$ 2.999,00</td>
                    <td>150</td>
                    <td>
                        <button class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-secondary btn-sm" title="Excluir"
                            style="background-color: #dc354530; color: #dc3545; border-color: #dc354580; margin-left:5px;"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://placehold.co/100x100/444/fff?text=Prod2" alt="Produto 2"></td>
                    <td>Fone Bluetooth MaxSound</td>
                    <td>FBM-002</td>
                    <td>R$ 349,90</td>
                    <td>320</td>
                    <td>
                        <button class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-secondary btn-sm" title="Excluir"
                            style="background-color: #dc354530; color: #dc3545; border-color: #dc354580; margin-left:5px;"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://placehold.co/100x100/555/fff?text=Prod3" alt="Produto 3"></td>
                    <td>Smartwatch Fit Plus</td>
                    <td>SWFP-003</td>
                    <td>R$ 780,00</td>
                    <td>85</td>
                    <td>
                        <button class="btn btn-primary btn-sm" title="Editar"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-secondary btn-sm" title="Excluir"
                            style="background-color: #dc354530; color: #dc3545; border-color: #dc354580; margin-left:5px;"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="tmdb-search-section">
        <h3>Pesquisar Filmes/Séries (TMDB)</h3>
        <div class="tmdb-search-input-group">
            <select id="tmdbSearchType" class="form-control-like tmdb-search-select">
                <option value="all">Todos</option>
                <option value="movie">Filme</option>
                <option value="tv">Série</option>
            </select>
            <input type="text" id="tmdbQuery" class="form-control-like" placeholder="Digite o nome...">
            <button class="btn btn-primary" id="tmdbSearchBtn"><i class="fas fa-search"></i> Pesquisar</button>
        </div>
        <div class="tmdb-results-grid" id="tmdbResultsContainer">
            <div class="tmdb-result-item">
                <img src="https://placehold.co/150x225/7852A9/FFF?text=Filme+Exemplo" alt="Poster do Filme">
                <h4>Título do Filme Exemplo</h4>
                <p>2024</p>
                <button class="btn btn-sm btn-import"><i class="fas fa-download"></i> Importar</button>
            </div>
            <div class="tmdb-result-item">
                <img src="https://placehold.co/150x225/7852A9/FFF?text=Outro+Filme" alt="Poster do Filme">
                <h4>Outro Filme Interessante</h4>
                <p>2023</p>
                <button class="btn btn-sm btn-import"><i class="fas fa-download"></i> Importar</button>
            </div>
            <div class="tmdb-result-item">
                <img src="https://placehold.co/150x225/7852A9/FFF?text=Série+TV" alt="Poster da Série">
                <h4>Nome da Série de TV</h4>
                <p>2022-2024</p>
                <button class="btn btn-sm btn-import"><i class="fas fa-download"></i> Importar</button>
            </div>
            <div class="tmdb-result-item">
                <img src="https://placehold.co/150x225/7852A9/FFF?text=Mais+Um" alt="Poster">
                <h4>Mais Um Título</h4>
                <p>2021</p>
                <button class="btn btn-sm btn-import"><i class="fas fa-download"></i> Importar</button>
            </div>
        </div>
    </section>
@endsection