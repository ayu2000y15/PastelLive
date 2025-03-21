<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '管理者画面')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background-color: #f8f9fa;
            padding-left: 250px;
            /* サイドバーの幅分だけ本文をずらす */
            transition: padding-left 0.3s;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            z-index: 1030;
            background-color: #343a40;
            overflow-x: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            transition: left 0.3s;
        }

        .sidebar-sticky {
            padding: 20px 0;
            height: calc(100% - 140px);
            /* ヘッダーとログアウトボタンの高さを考慮 */
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1.25rem;
            margin-bottom: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #007bff;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar-header {
            padding: 20px 15px;
            background-color: #2c3136;
            color: white;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            background-color: #2c3136;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .content {
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            /* コンテンツの最大幅を制限 */
            margin: 0 auto;
            /* 中央寄せ */
        }

        .page-title {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .required {
            color: #dc3545;
            margin-left: 5px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .btn-action {
            margin-right: 5px;
        }

        .form-label {
            font-weight: 500;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.03);
            font-weight: 500;
        }

        .tab-content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.25rem 0.25rem;
        }

        .nav-tabs .nav-link {
            cursor: pointer;
        }

        .badge-public {
            background-color: #28a745;
        }

        .badge-private {
            background-color: #6c757d;
        }

        .unread-badge {
            position: absolute;
            top: -8px;
            right: 10px;
            background-color: #dc3545;
            color: white;
            font-size: 0.7em;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
        }

        .navbar-toggler {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            /* leftからrightに変更 */
            z-index: 1040;
            background-color: #343a40;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1025;
        }

        /* ファイルアップロード関連のスタイルを強化 */
        .file-upload-container {
            margin-bottom: 15px;
        }

        .file-upload-area {
            border: 2px dashed #ddd;
            padding: 30px;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-area:hover,
        .file-upload-area.drag-over {
            border-color: #007bff;
            background-color: #f0f8ff;
        }

        .file-upload-input {
            display: none;
        }

        .file-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .file-preview-item {
            position: relative;
            width: 150px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            background-color: white;
        }

        .file-preview-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 3px;
            margin-bottom: 5px;
        }

        .file-preview-info {
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }

        .file-preview-size {
            font-size: 11px;
            color: #888;
            margin-top: 3px;
        }

        .file-preview-remove {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            font-size: 12px;
            padding: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .file-preview-remove:hover {
            background-color: #c82333;
        }

        /* フォームの幅を調整 */
        .data-form .form-control,
        .data-form .form-select {
            max-width: 100%;
        }

        @media (max-width: 767.98px) {
            body {
                padding-left: 0;
            }

            .sidebar {
                left: -250px;
            }

            .sidebar.show {
                left: 0;
            }

            .navbar-toggler {
                display: block;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- ハンバーガーメニューボタン -->
    <button class="navbar-toggler" type="button" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- サイドバーオーバーレイ -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- サイドバー -->
    @include('admin.component.admin-nav')

    <!-- メインコンテンツ -->
    <main class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // サイドバートグル機能
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // トグルボタンクリック時の処理
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });

            // オーバーレイクリック時の処理
            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });

            // ウィンドウリサイズ時の処理
            window.addEventListener('resize', function () {
                if (window.innerWidth > 767.98) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>