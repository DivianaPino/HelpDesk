@extends('adminlte::page')

@section('title', 'Gráfico MTTR')

@section('content_header')
    <!-- <h1>Dashboard</h1> -->
@stop

@section('content')
<style>
    .range-date{
        text-align:center;
        font-size: 1.2rem;
        color:#566573;
    }
    .dateMTTR{
        display:flex;
        justify-content: center;
    }
    #date-start, #date-end{
        width:10em;
    }
    .msj_error{
        text-align:center;
        color:red;
    }
    .line-separator {
        width: 2%;
        border-bottom: 1px solid #566573; 
        margin-bottom: 18px; 
        margin-right:12px;
    }

</style>
<div class="card">
    <div class="content-btnVolverTable"  style="padding-top:30px !important; margin-right:20px;">
        <a style="margin-top:8px;" href="{{ route('indexGrafico') }}" class="btn btn-dark btn-volver mb-2">
        <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
    </div>
   
    <label for="dateMTTR" class="range-date">Ingresa el rango de fechas:</label>
    <form action="{{ route('graficoFCR_Filtrado') }}" method="POST">
        @csrf
        <div class="dateMTTR">
            @if(session()->has('fecha_inicial'))
                <input  type="date" id="date-start" class="mx-3" name="fecha_inicial" value="{{ session('fecha_inicial') }}">
            @else
                <input  type="date" id="date-start" class="mx-3" name="fecha_inicial">
            @endif    
            <div class="line-separator"></div>
            @if(session()->has('fecha_final'))
                <input  type="date" id="date-end" name="fecha_final" value="{{ session('fecha_final') }}">
            @else
                <input  type="date" id="date-end" name="fecha_final" value="{{ session('fecha_final') }}">
            @endif
            <button type="submit" id="filtrar" class="btn btn-success btn-filtrarMTTR mx-3">Filtrar</button>
        </div>
        <div class="msj_error">
            @error('fecha_inicial')
                    {{$message}}
            @enderror
        </div>
        <div class="msj_error">
            @error('fecha_final')
                    {{$message}}
            @enderror
        </div>
    
        <div class="container mt-4">
            <div class="row">
                <div class="col">
                    <div id="container"></div>
                </div>
            </div>
        </div>
    </form>
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
<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>


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
        align: 'center',
        text: 'Gráfico FCR (Tasa de Resolución en el Primer Contacto)',
        style: {
            fontSize: '1.7rem',
            fontWeight: 'bold',
            color: '#566573' 
        }
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
            text: 'Porcentaje de Tasa de Resolución en el Primer Contacto en cada área (segmentado por técnicos de soporte)'
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
        pointFormat: '<span style="color:{point.color}">{point.name} </span>: ' +
         'La Tasa de Resolución en el Primer Contacto entre ' +
        '<b style="color:#7c8287">{point.fecha_inicial}</b>' + ' y ' + 
        '<b style="color:#7c8287">{point.fecha_final}</b> ' + 'fue de ' +
            '<b>{point.y:.1f}%</b><br/>'
    },

    

    series: [
        {
            name: 'Área',
            colorByPoint: true,
             data: 
            <?= $data ?>
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