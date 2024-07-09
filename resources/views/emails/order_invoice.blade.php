<!-- Start of Selection -->
<!DOCTYPE html>
<html>
<head>
    <title>Invoice Order Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            color: #000;
        }
        p {
            margin: 10px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #000;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: 0.3s;
        }
        .button:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>John Production</h1>
        <p>Halo, {{ $order['name'] }}!</p>
        <p>Kami ingin mengucapkan terima kasih atas kepercayaan Anda dalam menggunakan layanan/kami sebagai pelanggan kami. Terlampir, kami sertakan tagihan untuk pesanan Anda sebagai berikut:</p>
        <p><strong>Order ID:</strong> {{ $order['order_id'] }}</p>
        <p><strong>Produk:</strong> {{ $order['product'] }}</p>
        <p><strong>Tanggal:</strong> {{ $order['date'] }}</p>
        <p><strong>Jatuh Tempo:</strong> {{ $order['due_date'] }}</p>
        <p><strong>Total:</strong> Rp {{ number_format($order['total'], 0, ',', '.') }}</p>
        <a href="{{ route('payment', $order['order_id']) }}" class="button">Bayar Tagihan</a>
        <p>Mohon selesaikan pembayaran Anda sebelum jatuh tempo. Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi kami melalui email ini.</p>
        <p>Terima kasih atas perhatian Anda, dan kami berharap dapat melayani Anda lagi di masa mendatang.</p>
        <p>Hormat kami,</p>
        <p>John Production</p>
    </div>
</body>
</html>
<!-- End of Selection -->
