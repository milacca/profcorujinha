@extends('layouts.admin')
@section('content')

<div class="content">
     <h1>Olá, <b>{{ $user->name ?? '' }}</b>! Que bom te ver por aqui!</h1><br>

    <div class="row">
            <div class="col-md-4">
                <div class="card-pink">
                    <div class="card-body">
                        <div class="box-icon-dash"><i class="fas fa-book"></i></div>
                        <span style="font-size:42px;">{{ $totalEbooks }}</span><br>
                        Total de e-books
                    </div>
                </div>
            </div>

        <div class="col-md-4">
            <div class="card-yellow">
                <div class="card-body">
                    <div class="box-icon-dash"><i class="fas fa-user"></i></div>
                    <span style="font-size:42px;">{{ Cache::get('visits', 0) }}</span><br>
                    Total de visitas
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-blue">
                <div class="card-body">
                    <div class="box-icon-dash"><i class="fas fa-bell"></i></div>
                    <span style="font-size:42px;">{{ $usuarios }}</span><br>
                    Total de usuários
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body-chart">
                    <h1 class="text-center">Quantidade de e-books por categorias</h1>

                    <canvas id="categoriesChart"></canvas>

                    <script>
                        var categoryNames = @json($categories->pluck('name'));
                        var categoryCounts = @json($categories->pluck('products_count'));
                        var barColors = [
                           "#ff7a9f",
                           "#fcd402",
                           "#47bee6",
                           "#17b88d",
                        ];

                        new Chart("categoriesChart", {
                           type: "pie",
                           data: {
                               labels: categoryNames,
                               datasets: [{
                                   backgroundColor: barColors,
                                   data: categoryCounts
                               }]
                           },
                        });
                        </script>

                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-body-chart">
                    <h1 class="text-center">Downloads por mês</h1>
                    <canvas id="downloadChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent

@endsection
