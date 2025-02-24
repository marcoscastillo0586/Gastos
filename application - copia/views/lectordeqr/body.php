<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <title>Escanear QR</title>
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.css">
    <!-- SELECT 2-->
<script src="<?=base_url()?>assets/vendor/jquery/jquery.js"></script>
<script src="<?=base_url()?>assets/vendor/select2/dist/js/select2.js" ></script>
<script src="<?=base_url()?>/assets/vendor/jquery-ui/jquery-ui.js"></script>
</head>
<body>
<input type="text" id="qrResult" readonly placeholder="Resultado del QR">
    <!-- Contenedor para la cámara -->
    <div id="reader" style="width: 50%; height: 200px;"></div>
    
    <script>
        $(document).ready(function(){
        // Configura el lector de QR usando html5-qrcode
        const html5QrCode = new Html5Qrcode("reader");
   // Iniciar el escaneo de QR con la cámara
   html5QrCode.start(
            { facingMode: "environment" },  // Usar la cámara trasera
            {
                fps: 230, // Fotogramas por segundo
                qrbox: { width: 320, height: 350 },
            },
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error("Error al iniciar el escaneo: ", err);
        });

        function onScanSuccess(decodedText, decodedResult) {
            console.log("Código QR escaneado con éxito: ", decodedText);
            document.getElementById('qrResult').value = decodedText;  // Muestra el resultado en el input
            sendToServer(decodedText);
        }
        function onScanError(errorMessage) { /*console.error("Error durante el escaneo: ", errorMessage);*/  }

        function sendToServer(decodedText) {
            
        //    console.log("sendToServer Código QR escaneado con éxito: ", decodedText);
        
            let url = '<?php echo(base_url());?>/Lectorqr/procesar_qr';
            let datos = { qrData:decodedText};
            $.post(url, datos, function(resp) {
        // Aquí resp ya es un objeto JavaScript, no necesitas usar JSON.parse()
        if (resp.status === 'success') {
            alert('Datos extraídos:\nTítulo: ' + resp.data.title);
            console.log(resp.data.products);
        } else {
            alert('Error: ' + resp.message);
        }
    })
    .fail(function(error) {
        alert('NO DONE');
        console.error("Error al enviar datos al servidor: ", error);
    });
}

});
</script>

</body>
</html>
