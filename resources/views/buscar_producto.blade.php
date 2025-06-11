@extends('layouts.app')
@section('title', 'Dashboard')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<style>
    #photo-preview {
        max-width: 100%;
        margin-top: 20px;
        display: none;
    }
    #take-photo-btn {
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center">
                            <h3 class="card-title"><i class="fas fa-camera"></i> Capturar Código de Barras</h3>
                        </div>
                        <div class="card-body">
                            <form id="barcode-form" action="{{ route('buscar.producto') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" id="barcode" name="barcode" class="form-control form-control-lg mb-3" placeholder="Código de barras..." required>
                                    <button id="take-photo-btn" class="btn btn-success btn-block" type="button">
                                        <i class="fas fa-camera"></i> Tomar Foto
                                    </button>
                                </div>
                                <button class="btn btn-primary btn-block" type="submit">
                                    <i class="fas fa-search"></i> Buscar manualmente
                                </button>
                            </form>

                            @if(session('error'))
                                <div class="alert alert-danger mt-3 text-center">{{ session('error') }}</div>
                            @endif

                            <img id="photo-preview" src="" alt="Vista previa de la foto">
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/2920/2920320.png" class="img-fluid" style="max-width: 80%;" alt="Nutrición">
                        <p class="text-muted mt-2">Tome una foto del código de barras para escanearlo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>

<script>
$(document).ready(function () {
    let captureButton = $('#take-photo-btn');
    let photoPreview = $('#photo-preview');
    let videoElement;
    let canvasElement;
    let stream;

    captureButton.on('click', function () {
        // Crear elementos si no existen
        if (!videoElement) {
            videoElement = $('<video autoplay playsinline width="100%" style="margin-top:10px; border-radius:8px;"></video>');
            canvasElement = $('<canvas style="display:none;"></canvas>');
            captureButton.after(videoElement);
            captureButton.after(canvasElement);
        }

        // Pedir permisos y abrir cámara
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(function (mediaStream) {
                stream = mediaStream;
                videoElement[0].srcObject = mediaStream;

                // Escanear cada 1 segundo
                const scanInterval = setInterval(() => {
                    const video = videoElement[0];
                    const canvas = canvasElement[0];
                    const context = canvas.getContext('2d');

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = canvas.toDataURL('image/png');

                    // Procesar con Quagga
                    Quagga.decodeSingle({
                        src: imageData,
                        numOfWorkers: 0,
                        inputStream: {
                            size: 800
                        },
                        decoder: {
                            readers: [
                                "code_128_reader",
                                "ean_reader",
                                "ean_8_reader",
                                "code_39_reader",
                                "code_39_vin_reader",
                                "codabar_reader",
                                "upc_reader",
                                "upc_e_reader"
                            ]
                        }
                    }, function(result) {
                        if (result && result.codeResult) {
                            const code = result.codeResult.code;
                            console.log("Código detectado: ", code);

                            // Detener cámara y enviar formulario
                            clearInterval(scanInterval);
                            stream.getTracks().forEach(track => track.stop());
                            videoElement.remove();
                            canvasElement.remove();

                            $('#barcode').val(code);
                            $('#photo-preview').attr('src', imageData).show();
                            $('#barcode-form').submit();
                        } else {
                            console.log("Sin lectura, intentando de nuevo...");
                        }
                    });
                }, 1000);
            })
            .catch(function (error) {
                console.error("Permiso denegado o error al acceder a la cámara:", error);
                alert('No se pudo acceder a la cámara. Verifica los permisos del navegador.');
            });
    });
});
</script>


@endsection


