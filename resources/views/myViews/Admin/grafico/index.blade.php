@extends('adminlte::page')

@section('title', 'Gráficos KPIs')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<style>
    .small-box {
    height: 200px; /* Cambia este valor según tus necesidades */
    display: flex;
    flex-direction: column; /* Asegura que los elementos se apilen verticalmente */
    justify-content: space-between; /* Espacia los elementos para que el enlace quede abajo */
}

.title-kpi{
    font-size:3rem !important;
}

.a-ver{
    font-size:1.2rem !important;
}

.csat{
    background-color:#2789d6 ;
    color:#f6f6f7;
}
.mttr{
    background-color:#1c69b6  ;
    color:#f6f6f7;
}
.fcr{
    background-color: #1c659e  ;
    color:#f6f6f7;
}
.delete-mb{
    margin-bottom:0;
}
</style>

<div class="titulo_prin  titulo_analisis">Gráficos KPIs (Indicadores clave de rendimiento)</div>
<div class="wrapper">
  <!-- Main content -->
  <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box csat">
                        <div class="inner">
                            <p class="estados_analisis title-kpi delete-mb">CSAT</p>
                            <p>Satisfacción del usuario</p>
                        </div>
                        <div class="icon">
                        <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        <a href="/grafico_kpi/csat" class="small-box-footer a-ver">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-6">
                <!-- small box -->
                    <div class="small-box mttr">
                        <div class="inner">
                            <p class="estados_analisis  title-kpi delete-mb">MTTR</p>
                            <p class="delete-mb">Tiempo medio de</p>
                            <p class="delete-mb">resolución</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        <a href="/grafico_kpi/mttr" class="small-box-footer a-ver">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-6">
                <!-- small box -->
                    <div class="small-box fcr">
                        <div class="inner">
                            <p  class="estados_analisis  title-kpi delete-mb">FCR</p>
                            <p class="delete-mb">Tasa de Resolución en el</p>
                            <p class="delete-mb">Primer Contacto</p>
                        </div>
                        <div class="icon">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        <a href="/grafico_kpi/fcr" class="small-box-footer a-ver">Ver <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            
            </div>

        </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

@stop

@section('css')

@stop

@section('js')
    <script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>
@stop