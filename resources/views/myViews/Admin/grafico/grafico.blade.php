@extends('adminlte::page')

@section('title', 'Gráfico KPI')

@section('content_header')
    <!-- <h1>Dashboard</h1> -->
@stop

@section('content')

    <h1 class=titulo_grafico>Gráfico KPI (indicador clave de rendimiento)</h1>

    <div class="container mt-4">
        <div class="row">
            <div class="col">
                <div id="container"></div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/grafico.css">  
@stop

@section('js')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


<script>
   // Data retrieved from https://gs.statcounter.com/browser-market-share#monthly-202201-202201-bar

// Create the chart
Highcharts.chart('container', {
    lang: {   
        viewFullscreen:"Ver en pantalla completa",
        printChart:"Imprimir gráfico",  
        downloadPNG:"Descargar imagen PNG", 
        downloadJPEG:"Descargar imagen JPEG",
        downloadPDF:"Descargar documento PDF",
        downloadSVG:"Descargar imagen vectorial SVG",
        downloadCSV:"Descargar CSV",
        downloadXLS:"Descargar XLS",
        viewData:"Ver tabla de datos",
        exitFullscreen:"Salir de pantalla completa",
        hideData:"Ocultar tabla de datos",
        noData:"No hay información para mostrar",
        
  
    

    },
    chart: {
        type: 'column'
    },
    title: {
        align: 'left',
        text: 'Gráfico KPI',
    },
    subtitle: {
        align: 'left',
        text: ''
    },
    accessibility: {
        announceNewData: {
            enabled: true
        }
    },
    xAxis: {
        type: 'category',
        title: {
            text: 'Categorizado por',
            enabled: false 
        }

    },
    yAxis: {
        title: {
            text: 'Porcentaje de satisfacción de tickets en cada área (segmentado por técnicos de soporte)'
        }

    },

    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
            '<b>{point.y:.2f}%</b> de satisfacción <br/>'
    },

    

    series: [
        {
            name: 'Área',
            colorByPoint: true,
            data: <?= $data ?>
        }
    ],
    drilldown: {
        breadcrumbs: {
            position: {
                align: 'right'
            }
        },

  
        series: <?= $seriesDrilldown?>
            
    }      
        
});

</script>

@stop