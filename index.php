<?php
    session_start();
    if (!isset($_SESSION['username'])){
        header("Location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Digital Signature</title>
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
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #0891b2 100%);
        }

        .card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .card-header {
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .card-header .icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .card-header h1 {
            margin: 0;
            font-size: 18px;
            color: #1f2937;
        }

        .card-header p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        .logout-link {
            margin-left: auto;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            white-space: nowrap;
        }

        .logout-link:hover {
            color: #dc2626;
            text-decoration: underline;
        }

        form {
            padding: 8px 32px 32px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .field input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        .btn {
            flex: 1;
            padding: 11px 14px;
            border-radius: 10px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s, opacity 0.15s;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: 0.92;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="icon">📝</div>
            <div>
                <h1>Detail Digital Signature</h1>
                <p>Isi data klien untuk membuat link & QR code</p>
            </div>
            <a class="logout-link" href="action-logout.php">Logout</a>
        </div>
        <form name="tamu" method="post" action="generate_link.php">
            <div class="field">
                <label for="name">Nama Klien</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="field">
                <label for="address">Keterangan</label>
                <input type="text" id="address" name="address">
            </div>
            <div class="field">
                <label for="city">Kota</label>
                <input type="text" id="city" name="city">
            </div>
            <div class="actions">
                <button type="reset" name="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" name="submit" value="Send" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</body>
</html>
