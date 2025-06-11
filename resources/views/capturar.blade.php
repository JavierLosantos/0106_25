@extends('layouts.app')
@section('title', 'Escanear Código de Barras')

@section('css')
<style>
    video {
        width: 100%;
        max-height: 300px;
        border-radius: 10px;
        margin-bottom: 10px;
    }
    #photo-preview {
        display: none;
        width: 100%;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Escanear Código de Barras</h4>
        </div>
        <div class="card-body text-center">
            <video id="video" autoplay></video>
            <img id="photo-preview" src="" alt="Captura">
            <form id="barcode-form" action="{{ route('buscar.producto1') }}" method="POST">
                @csrf
                <input type="text" name="barcode" id="barcode" class="form-control mb-3" placeholder="Código escaneado..." readonly required>
                <button type="submit" class="btn btn-success btn-block">Buscar producto</button>
            </form>
            <p class="text-muted mt-3">Apunta la cámara al código de barras. Se detectará automáticamente.</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const videoElement = document.getElementById("video");
    const barcodeInput = document.getElementById("barcode");
    const form = document.getElementById("barcode-form");

    // Configurar Quagga para escaneo en tiempo real desde video
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: videoElement,
            constraints: {
                facingMode: "environment" // Usa cámara trasera si está disponible
            }
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader", "code_39_reader"]
        }
    }, function (err) {
        if (err) {
            console.error("Error inicializando Quagga:", err);
            alert("No se pudo acceder a la cámara.");
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(function (data) {
        const code = data.codeResult.code;
        if (code) {
            barcodeInput.value = code;
            Quagga.stop(); // Detiene escaneo
            form.submit(); // Enviar formulario automáticamente
        }
    });
});
</script>
@endsection
