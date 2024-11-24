<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación - Ticket Reasignado</title>
</head>
<body>
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
            <div style="position: absolute; top: 0; left: 50%; height:330px; transform: translate(-50%, -50%);  background: linear-gradient(to bottom, #0d77c9 50%, #e2e7ed 50%); padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.2); margin-top:0;" class="card">
                <div class="card-body" style="background-color:white; margin-top:35px; height:210px;">
                    <h5  style="text-align:center;padding-top:20px;">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</h5>
                    <h1 style="color:#000; text-align:center;">Ticket Reasignado</h1>
                    <h4 style="font-weight:400; color:#3d3d3d;text-align:center; margin-bottom:25px;">Para ver detalles del ticket, haga click en el botón que aparece a continuación.</h4>
                    <center>
                        <a style="background-color: #0d77c9; color: white; padding: 10px 20px; text-decoration: none; border-radius:2px;" href="{{ url('/detalles/' . $ticket->id) }}" class="btn">Ver ticket</a>
                    </center>
                </div>
                <h5 style="text-align:center; margin-top:27px;">&copy; {{ \Carbon\Carbon::now()->format('Y') }} - Universidad Nacional Experimental de Guayana - LEMA</h5>
            </div>
        </div>
</body>
</html>