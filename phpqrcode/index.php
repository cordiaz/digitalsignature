<?php
/*
 * PHP QR Code encoder
 * Exemplatory usage
 */

// Include koneksi database dengan path yang benar
include dirname(__DIR__) . "/connect.php";

// Buat koneksi database menggunakan variabel dari connect.php
$conn = mysqli_connect($hostmysql, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Base URL diambil dari request saat ini (bukan hardcoded) supaya link
// "Kembali ke Beranda" selalu menunjuk ke domain/path yang benar.
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$home_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/') . '/index.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP QR Code</title>
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
            padding: 28px 32px 20px;
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

        .home-link {
            margin-left: auto;
            font-size: 13px;
            color: #6b7280;
            text-decoration: none;
            white-space: nowrap;
        }

        .home-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .card-body {
            padding: 8px 32px 32px;
        }

        .qr-preview {
            text-align: center;
            padding: 20px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            margin-bottom: 18px;
        }

        .qr-preview img {
            max-width: 100%;
            height: auto;
        }

        .url-box {
            padding: 12px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 12.5px;
            color: #065f46;
            word-break: break-all;
            margin-bottom: 20px;
        }

        details {
            border-top: 1px solid #f1f5f9;
            padding-top: 16px;
        }

        summary {
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 14px;
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field input:focus,
        .field select:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }

        .field-row {
            display: flex;
            gap: 10px;
        }

        .field-row .field {
            flex: 1;
        }

        .btn {
            width: 100%;
            padding: 11px 14px;
            border-radius: 10px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            transition: opacity 0.15s;
        }

        .btn:hover {
            opacity: 0.92;
        }

        .benchmark {
            margin-top: 14px;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="icon">🔗</div>
            <div>
                <h1>PHP QR Code</h1>
                <p>Generate QR code untuk link digital signature</p>
            </div>
            <a class="home-link" href="<?php echo htmlspecialchars($home_url) ?>">Beranda</a>
        </div>
        <div class="card-body">
<?php

//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'temp/';

include "qrlib.php";

//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);

$filename = $PNG_TEMP_DIR.'test.png';

//processing form input
//remember to sanitize user input in real-life solution !!!
$errorCorrectionLevel = 'Q';
if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
    $errorCorrectionLevel = $_REQUEST['level'];

$matrixPointSize = 4;
if (isset($_REQUEST['size']))
    $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);

// CEK APAKAH ADA DATA DARI GENERATE_LINK.PHP (via GET)
$autoData = isset($_GET['qr_data']) ? $_GET['qr_data'] : '';

if (isset($_REQUEST['data'])) {
    //it's very important!
    if (trim($_REQUEST['data']) == '')
        die('data cannot be empty! <a href="?">back</a>');

    // user data
    $filename = $PNG_TEMP_DIR.'cordiaz'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
    QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);
} elseif (!empty($autoData)) {
    // Jika ada data dari generate_link.php, langsung generate QR code
    $filename = $PNG_TEMP_DIR.'cordiaz'.md5($autoData.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
    QRcode::png($autoData, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
} else {
    //default data
    QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
}

//display generated file
echo '<div class="qr-preview"><img src="'.$PNG_WEB_DIR.basename($filename).'" alt="QR Code"></div>';

// Jika ada data dari generate_link.php, tampilkan pesan dan data yang akan di-QR
if (!empty($autoData)) {
    echo '<div class="url-box">' . htmlspecialchars($autoData) . '</div>';
}

//config form
echo '<details>
    <summary>Generate QR Code manual</summary>
    <form action="index.php" method="post">
        <div class="field">
            <label for="data">Data</label>
            <input type="text" id="data" name="data" value="'.(isset($_REQUEST['data'])?htmlspecialchars($_REQUEST['data']):(isset($autoData)?htmlspecialchars($autoData):'PHP QR Code Here')).'">
        </div>
        <div class="field-row">
            <div class="field">
                <label for="level">ECC</label>
                <select id="level" name="level">
                    <option value="L"'.(($errorCorrectionLevel=='L')?' selected':'').'>L - smallest</option>
                    <option value="M"'.(($errorCorrectionLevel=='M')?' selected':'').'>M</option>
                    <option value="Q"'.(($errorCorrectionLevel=='Q')?' selected':'').'>Q</option>
                    <option value="H"'.(($errorCorrectionLevel=='H')?' selected':'').'>H - best</option>
                </select>
            </div>
            <div class="field">
                <label for="size">Size</label>
                <select id="size" name="size">';

for($i=1;$i<=10;$i++)
    echo '<option value="'.$i.'"'.(($matrixPointSize==$i)?' selected':'').'>'.$i.'</option>';

echo '</select>
            </div>
        </div>
        <button type="submit" class="btn">GENERATE</button>
    </form>
</details>';

?>
            <div class="benchmark">
<?php
    // benchmark
    QRtools::timeBenchmark();
?>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// Tutup koneksi database
mysqli_close($conn);
