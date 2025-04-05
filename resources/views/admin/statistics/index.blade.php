@extends('layouts.admin')

@section('title', 'Estadisticas | Novelas _try')

@section('typeAdmin', 'Estadisticas')

<style>
    .chart-container {
        height: 200px;
        /* Ajusta la altura según tus necesidades */
        margin-bottom: 20px;
        /* Espacio entre los gráficos */
    }
</style>

@section('content')

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">

        <button id="exportPdf" class="btn btn-danger mb-4">
            <i class="bi bi-file-earmark-pdf-fill"></i> Exportar PDF
        </button>

        <!-- Formulario oculto para enviar las imágenes -->
        <form id="pdfForm" action="{{ route('generate.pdf') }}" method="POST" target="_blank" style="display: none;">
            @csrf
            <input type="hidden" name="images" id="imagesInput">
        </form>

        <div class="row">
            <!-- Contenedor para los gráficos -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="novelsPerUserChart" class="chart-container"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="commentsPerUserChart" class="chart-container"></div> <!-- Cambiado aquí -->
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="comparisonChart" class="chart-container"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="visitsPerNovelChart" class="chart-container"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="favoritesPerNovelChart" class="chart-container"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="chaptersPerNovelChart" class="chart-container"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="visitsPieChart" class="chart-container"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div id="novelsPerCategoryChart" class="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datos de ejemplo, reemplaza con tus variables de PHP
            const totalUsers = {{ $totalUsers }};
            const totalNovels = {{ $totalNovels }};

            // Datos para los gráficos
            const novelsPerUser = @json($novelsPerUser->pluck('novels_count'));
            const commentsPerUser = @json($commentsPerUser->pluck('comments_count'));
            const userNames = @json($novelsPerUser->pluck('username'));
            const visitsPerNovel = @json($visitsPerNovel->pluck('visits_count'));
            const novelTitlesVisits = @json($visitsPerNovel->pluck('title'));
            const favoritesPerNovel = @json($favoritesPerNovel->pluck('favorited_by_count'));
            const novelTitlesFavorites = @json($favoritesPerNovel->pluck('title'));
            const chaptersPerNovel = @json($chaptersPerNovel->pluck('chapters_count'));
            const novelTitlesChapters = @json($chaptersPerNovel->pluck('title'));
            const novelsPerCategory = @json($novelsPerCategory->pluck('novels_count'));
            const categoryNames = @json($novelsPerCategory->pluck('name'));
            const registeredVisits = {{ $registeredVisits }};
            const unregisteredVisits = {{ $unregisteredVisits }};

            // Almacena las instancias de gráficos
            const charts = {};

            // Gráfico de Top 5 Usuarios con más Novelas
            charts.novelsPerUserChart = new ApexCharts(document.querySelector("#novelsPerUserChart"), {
                chart: {
                    type: 'bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#66a3e9'],
                series: [{
                    name: 'Novelas por Usuario',
                    data: novelsPerUser
                }],
                xaxis: {
                    categories: userNames
                },
                title: {
                    text: 'Usuarios con más Novelas',
                    align: 'center'
                }
            });
            charts.novelsPerUserChart.render();

            // Gráfico de Top 5 Usuarios con más comentarios
            charts.commentsPerUserChart = new ApexCharts(document.querySelector("#commentsPerUserChart"), {
                chart: {
                    type: 'bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#66a3e9'],
                series: [{
                    name: 'Comentarios por Usuario',
                    data: commentsPerUser
                }],
                xaxis: {
                    categories: userNames
                },
                title: {
                    text: 'Usuarios con más Comentarios',
                    align: 'center'
                }
            });
            charts.commentsPerUserChart.render();

            // Gráfico de Comparación de Usuarios y Novelas
            charts.comparisonChart = new ApexCharts(document.querySelector("#comparisonChart"), {
                chart: {
                    type: 'donut',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#d05d6c', '#5d81d0'],
                series: [totalUsers, totalNovels],
                labels: ['Total de Usuarios', 'Total de Novelas'],
                title: {
                    text: 'Comparación de Usuarios y Novelas',
                    align: 'center'
                }
            });
            charts.comparisonChart.render();

            // Gráfico de Top 5 Novelas con más Visitas
            charts.visitsPerNovelChart = new ApexCharts(document.querySelector("#visitsPerNovelChart"), {
                chart: {
                    type: 'bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#008FFB'],
                series: [{
                    name: 'Visitas por Novela',
                    data: visitsPerNovel
                }],
                xaxis: {
                    categories: novelTitlesVisits
                },
                title: {
                    text: 'Novelas con más Visitas',
                    align: 'center'
                }
            });
            charts.visitsPerNovelChart.render();

            // Gráfico de Top 5 Novelas con más Favoritos
            charts.favoritesPerNovelChart = new ApexCharts(document.querySelector("#favoritesPerNovelChart"), {
                chart: {
                    type: 'bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#00E396'],
                series: [{
                    name: 'Favoritos por Novela',
                    data: favoritesPerNovel
                }],
                xaxis: {
                    categories: novelTitlesFavorites
                },
                title: {
                    text: 'Novelas con más Favoritos',
                    align: 'center'
                }
            });
            charts.favoritesPerNovelChart.render();

            // Gráfico de Top 5 Novelas con más Capítulos
            charts.chaptersPerNovelChart = new ApexCharts(document.querySelector("#chaptersPerNovelChart"), {
                chart: {
                    type: 'bar',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#FF4560'],
                series: [{
                    name: 'Capítulos por Novela',
                    data: chaptersPerNovel
                }],
                xaxis: {
                    categories: novelTitlesChapters
                },
                title: {
                    text: 'Novelas con más Capítulos',
                    align: 'center'
                }
            });
            charts.chaptersPerNovelChart.render();

            // Gráfico de pastel: Visitas de usuarios registrados vs. no registrados
            charts.visitsPieChart = new ApexCharts(document.querySelector("#visitsPieChart"), {
                chart: {
                    type: 'donut',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#66a3e9', '#d05d6c'],
                series: [registeredVisits, unregisteredVisits],
                labels: ['Visitas de usuarios registrados', 'Visitas de usuarios no registrados'],
                title: {
                    text: 'Visitas de usuarios registrados vs. no registrados',
                    align: 'center'
                }
            });
            charts.visitsPieChart.render();

            // Gráfico de pastel: Distribución de novelas por categoría
            charts.novelsPerCategoryChart = new ApexCharts(document.querySelector("#novelsPerCategoryChart"), {
                chart: {
                    type: 'donut',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                colors: ['#66a3e9', '#d05d6c', '#00E396', '#FF4560', '#775DD0'],
                series: novelsPerCategory,
                labels: categoryNames,
                title: {
                    text: 'Distribución de Novelas por Categoría',
                    align: 'center'
                }
            });
            charts.novelsPerCategoryChart.render();

            // Captura de imágenes para exportar a PDF
            document.getElementById('exportPdf').addEventListener('click', function() {
                Promise.all([
                    charts.novelsPerUserChart.dataURI(),
                    charts.commentsPerUserChart.dataURI(),
                    charts.comparisonChart.dataURI(),
                    charts.visitsPerNovelChart.dataURI(),
                    charts.favoritesPerNovelChart.dataURI(),
                    charts.chaptersPerNovelChart.dataURI(),
                    charts.visitsPieChart.dataURI(),
                    charts.novelsPerCategoryChart.dataURI()
                ]).then(images => {
                    document.getElementById('imagesInput').value = JSON.stringify(images);
                    document.getElementById('pdfForm').submit();
                }).catch(error => {
                    console.error("Error al capturar las gráficas:", error);
                });
            });
        });
    </script>


@endsection
