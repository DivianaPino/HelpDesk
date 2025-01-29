@extends('adminlte::page')

@section('title', 'Análisis')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="titulo_prin  titulo_analisis">Análisis de tickets</div>
<div class="wrapper">
  <!-- Main content -->
  <section class="content">
        <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box nuevo">
                    <div class="inner">
                        <h3>{{$cant_tkt_nuevos}}</h3>

                        <p class="estados_analisis">Nuevos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-plus-square"></i>
                    </div>
                    <a href="/noasignados" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box abierto">
                <div class="inner">
                <h3>{{$cant_tkt_abiertos}}</h3>

                <p class="estados_analisis">Abiertos</p>
                </div>
                <div class="icon">
                <i class="fas fa-fw fa-unlock-alt"></i>
                </div>
                <a href="/abiertos" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
            <!-- small box -->
                <div class="small-box" style="background-color:#F6a700;">
                    <div class="inner" style="color:#fff;">
                        <h3>{{$cant_tkt_enEspera}}</h3>
                        <p  class="estados_analisis">En espera</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-clock"></i>
                    </div>
                    <a href="enEspera" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
             <!-- small box -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info resuelto">
                    <div class="inner">
                        <h3>{{$cant_tkt_resueltos}}</h3>
                        <p class="estados_analisis">Resueltos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-check-circle"></i>
                    </div>
                    <a href="/resueltos" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <!-- small box -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info reAbierto">
                    <div class="inner">
                        <h3>{{$cant_tkt_reAbiertos}}</h3>
                        <p class="estados_analisis">Reabiertos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-redo"></i>
                    </div>
                    <a href="/reabiertos" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

             <!-- small box -->
             <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info vencido">
                    <div class="inner">
                        <h3>{{$cant_tkt_vencidos}}</h3>
                        <p class="estados_analisis">Vencidos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-hourglass-end"></i>
                    </div>
                    <a href="/vencidos" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <!-- small box -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info cerrado">
                    <div class="inner">
                        <h3>{{$cant_tkt_cerrados}}</h3>
                        <p class="estados_analisis">Cerrados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-fw fa-lock"></i>
                    </div>
                    <a href="/cerrados" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

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