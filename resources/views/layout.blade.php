<!DOCTYPE html>
<html>
<head>
    <title>SPK AHP & WSM</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Sidebar default */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 220px;
            background-color: #343a40;
            padding: 20px;
            transition: width 0.3s;
            overflow: hidden;
        }
        /* Sidebar dalam keadaan collapsed */
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar h4 {
            color: #fff;
            margin-bottom: 30px;
            transition: opacity 0.3s;
        }
        /* Sembunyikan teks pada judul ketika sidebar collapsed */
        .sidebar.collapsed h4 span {
            display: none;
        }
        /* Tombol toggle */
        .toggle-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        /* Navigasi Link */
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 10px 0;
        }
        /* Main content */
        main {
            margin-left: 240px;
            padding: 20px;
            transition: margin-left 0.3s;
        }
        /* Sesuaikan margin main saat sidebar collapsed */
        .sidebar.collapsed ~ main {
            margin-left: 80px;
        }
        /* Active link style */
        .sidebar a.active {
            color: #17a2b8;
            font-weight: bold;
        }
        /* Icon untuk menu */
        .menu-icon {
            margin-right: 10px;
            display: inline-block;
            width: 20px;
            text-align: center;
        }
        /* Adjust icon when sidebar is collapsed */
        .sidebar.collapsed .menu-text {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <button class="toggle-btn" id="toggle-btn">☰</button>
        <h4><a href="{{ route('home') }}"><span>SPK</span></a></h4>
        <!-- Menu links -->
        <a href="{{ route('transaksi.index') }}">
            <span class="menu-icon">📤</span>
            <span class="menu-text">Upload</span>
        </a>
        <a href="{{ route('history.index') }}">
            <span class="menu-icon">📋</span>
            <span class="menu-text">Riwayat</span>
        </a>
        <!-- Tambahan link bisa ditambahkan di sini jika diperlukan -->
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <script>
        // Toggle collapse sidebar
        document.getElementById('toggle-btn').addEventListener('click', function(){
            document.getElementById('sidebar').classList.toggle('collapsed');
        });
        
        // Set active class based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const links = document.querySelectorAll('.sidebar a');
            
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href.replace('{{ url("/") }}', ''))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>