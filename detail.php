<?php
    if(isset($_GET['msg'])){
        $msg    =$_GET['msg'];
    }
    else {
        die ("Error. No msg selected!");
    }
    include "connect.php";
    $stmt = $conn->prepare("SELECT * FROM tamu WHERE msg = ?");
    $stmt->bind_param("s", $msg);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array();

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $detail_url = $base_url . '/detail.php?msg=' . $result['msg'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Number Digital Signature</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #0891b2 100%);
        }

        .card {
            width: 100%;
            max-width: 560px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .card-header {
            padding: 28px 32px;
            background: linear-gradient(135deg, #059669, #0891b2);
            color: #fff;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .card-header .badge {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .card-header h1 {
            margin: 0;
            font-size: 18px;
        }

        .card-header p {
            margin: 4px 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .card-body {
            padding: 24px 32px 32px;
        }

        .ref-note {
            font-size: 13px;
            color: #6b7280;
            margin: 0 0 20px;
            padding: 10px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
        }

        .ref-note b {
            color: #065f46;
            font-family: 'Courier New', monospace;
        }

        .info-row {
            display: flex;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-of-type {
            border-bottom: none;
        }

        .info-row .label {
            flex: 0 0 120px;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
        }

        .info-row .value {
            flex: 1;
            font-size: 14px;
            color: #111827;
            word-break: break-word;
        }

        .url-box {
            margin-top: 20px;
            padding: 14px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #1e3a8a;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="badge">✅</div>
            <div>
                <h1>Detail Digital Signature</h1>
                <p>Data terverifikasi dari sistem</p>
            </div>
        </div>
        <div class="card-body">
            <p class="ref-note">Kode referensi: <b><?php echo htmlspecialchars($msg)?></b></p>

            <div class="info-row">
                <div class="label">Nama Klien</div>
                <div class="value"><?php echo htmlspecialchars($result['name'])?></div>
            </div>
            <div class="info-row">
                <div class="label">Email</div>
                <div class="value"><?php echo htmlspecialchars($result['email'])?></div>
            </div>
            <div class="info-row">
                <div class="label">Keterangan</div>
                <div class="value"><?php echo htmlspecialchars($result['address'])?></div>
            </div>
            <div class="info-row">
                <div class="label">Kota</div>
                <div class="value"><?php echo htmlspecialchars($result['city'])?></div>
            </div>
            <div class="info-row">
                <div class="label">Timestamp</div>
                <div class="value"><?php echo htmlspecialchars($result['timestamp'] ?? '-')?></div>
            </div>

            <div class="url-box"><?php echo htmlspecialchars($detail_url)?></div>
        </div>
    </div>
</body>
</html>
