<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .content p {
            margin-bottom: 10px;
        }
        .content strong {
            display: inline-block;
            width: 150px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="header">John Production</p>
        <div class="content">
            <p>Halo, {{ $payment['name'] }}!</p>
            <p>Kami ingin mengucapkan terima kasih atas pembayaran Anda yang telah diterima untuk pesanan Anda dengan detail sebagai berikut:</p>
            <p>
                <strong>Order ID:</strong> {{ $payment['order_id'] }}<br>
                <strong>Tgl Pembayaran:</strong> {{ $payment['payment_date'] }}<br>
                <strong>Total:</strong> {{ $payment['total'] }}
            </p>
        </div>
        <div class="footer">
            <p>Terima kasih atas perhatian Anda, dan kami berharap dapat melayani Anda lagi di masa mendatang.</p>
            <p>Hormat kami,<br>Lapak Digital</p>
        </div>
    </div>
</body>
</html>
