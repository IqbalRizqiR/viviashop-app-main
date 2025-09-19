<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode Preview Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }

        .stats {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            margin-top: 15px;
            border-radius: 10px;
            font-size: 16px;
        }

        .content {
            padding: 40px;
        }

        .mode-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .mode-card {
            border: 2px solid #e1e5e9;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            background: white;
        }

        .mode-card:hover {
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.1);
        }

        .mode-card.landscape {
            border-color: #28a745;
        }

        .mode-card.landscape:hover {
            border-color: #218838;
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.15);
        }

        .mode-card.portrait {
            border-color: #007bff;
        }

        .mode-card.portrait:hover {
            border-color: #0056b3;
            box-shadow: 0 15px 35px rgba(0, 123, 255, 0.15);
        }

        .mode-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .mode-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .mode-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .mode-specs {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .mode-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-preview {
            background: #6c757d;
            color: white;
        }

        .btn-preview:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-print {
            background: #28a745;
            color: white;
        }

        .btn-print:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-print.portrait {
            background: #007bff;
        }

        .btn-print.portrait:hover {
            background: #0056b3;
        }

        .back-button {
            text-align: center;
            margin-top: 30px;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .efficiency-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff6b6b;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .mode-selector {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Barcode Print Selection</h1>
            <div class="stats">
                <strong>{{ $data->count() }}</strong> products ready for printing
            </div>
        </div>

        <div class="content">
            <div class="mode-selector">
                <div class="mode-card landscape">
                    <div class="efficiency-badge">73% Saving</div>
                    <div class="mode-icon">🖨️</div>
                    <div class="mode-title">Landscape Mode</div>
                    <div class="mode-description">
                        Perfect for wider labels with more readable text. Ideal for standard barcode printing.
                    </div>
                    <div class="mode-specs">
                        <strong>Layout:</strong> 5×5 Grid<br>
                        <strong>Items per page:</strong> 25<br>
                        <strong>Size:</strong> A4 Landscape<br>
                        <strong>Efficiency:</strong> High
                    </div>
                    <div class="mode-buttons">
                        <a href="{{ route('admin.barcode.preview.landscape') }}" class="btn btn-preview">
                            👁️ Preview
                        </a>
                        <a href="{{ route('admin.barcode.print.landscape') }}" class="btn btn-print">
                            🖨️ Print
                        </a>
                    </div>
                </div>

                <div class="mode-card portrait">
                    <div class="efficiency-badge">78% Saving</div>
                    <div class="mode-icon">📱</div>
                    <div class="mode-title">Portrait Mode</div>
                    <div class="mode-description">
                        Compact layout for maximum items per page. Great for bulk barcode printing.
                    </div>
                    <div class="mode-specs">
                        <strong>Layout:</strong> 4×8 Grid<br>
                        <strong>Items per page:</strong> 32<br>
                        <strong>Size:</strong> A4 Portrait<br>
                        <strong>Efficiency:</strong> Maximum
                    </div>
                    <div class="mode-buttons">
                        <a href="{{ route('admin.barcode.preview.portrait') }}" class="btn btn-preview">
                            👁️ Preview
                        </a>
                        <a href="{{ route('admin.barcode.print.portrait') }}" class="btn btn-print portrait">
                            🖨️ Print
                        </a>
                    </div>
                </div>
            </div>

            <div class="back-button">
                <a href="{{ route('admin.products.index') }}" class="btn-back">
                    ← Back to Products
                </a>
            </div>
        </div>
    </div>
</body>
</html>
