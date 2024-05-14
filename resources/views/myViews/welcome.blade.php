<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>UNEG - HELPDESK</title>

     <!-- Font Awesome -->
     <script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arima:wght@700&family=Mulish:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Normalize (Resetear los valores de css) -->
    <link rel="stylesheet" href="css/normalize.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../css/app.css">
</head>

<body>
    <div class="fondo-header">
        <div class="fondo-encabezado">
            <header class="contenedor" >
            
                @if (Route::has('login'))
                <div class="encabezado">
                    <div class="logo-header">
                            <div class="logo">
                                <img src="../../assets/logoUneg.jpg" alt="">
                            </div>
                    </div>

                    <div class="enlaces">
                        @auth
                        <a href="{{url('/usuarios')}}" class=" btn txt-btn ">Dashboard</a>
                        @else
                        <a href="{{ route('login') }}" class=" btn txt-btn ">Iniciar sesión</a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class=" btn txt-btn ">Registrarse</a>
                        @endif
                        @endauth
                </div>
                </div>
                @endif
            </header>
        </div>

        <div class="contenedor-nosotros  contenedor">
            <div class="texto-nosotros ">
                <!--** SOLO DEBE HABER UN SOLO H1 POR PAGINA (TITULO PRINCIPAL)  -->
                <h1 class="titulo-principal">HELP DESK</h1>
                <p class="content"> Asistencia técnica para las Tecnologías de Información (TI) de la 
                    Universidad Nacional Experimental de Guayana (UNEG).
                <br>
                <a href="{{ route('login') }}" class="btn txt-btn solicitar">Solicitar asistencia</a>
            </p>
                
            </div>
        

            <div class="imagenes-nosotros">
                <div class="imagen1">
                    <img src="../../assets/imgSoporte.png" alt="">
                </div>
            </div>
        </div>

    </div>

    <div class="contenido contenedor">

       <div class="contenido-1">
           <div class="icono">
                <i class="fa-solid fa-headset"></i>
           </div>
           <h3>Mejor atención al usuario</h3>
           <div class="text-contenido">
                <p>La comunicación entre los usuarios y los técnicos de soporte es más eficiente. </p>
           </div>
       </div>
       <div class="contenido-1">
           <div class="icono">
                <i class="fa-solid fa-gauge-high"></i>
           </div>
           <h3>Resolución rápida de problemas</h3>
           <div class="text-contenido">
                 <p>Las incidencias de los usuarios son atendidas de manera rápida.</p>
           </div>
       </div>
       <div class="contenido-1">
           <div class="icono">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
           </div>
           <h3>Seguimiento de solicitudes</h3>
           <div class="text-contenido">
                <p>Mejor organización y rastreo de las incidencias de los usuarios.</p>
           </div>
       </div>

    </div>
    <!-- FOOTER -->
    <footer class="footer">
        <p>&copy; {{ \Carbon\Carbon::now()->format('Y') }} - Universidad Nacional Experimental de Guayana - Help Desk - Todos los derechos reservados   </p>
    </footer>
    <!-- FIN FOOTER -->


</body>

</html>