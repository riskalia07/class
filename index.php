<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perpustakaan Digital</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .button-group a {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
        }

        .btn-pinjam { background-color: #007bff; }
        .btn-logout { background-color: #dc3545; }
        .btn-ulasan { background-color: #28a745; }
        .btn-read-ulasan { background-color: #ffc107; }
        .btn-read-koleksi { background-color: #6c757d; }

        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        .book-list {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .book-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 180px;
            text-align: center;
            padding: 10px;
        }

        .book-card img {
            width: 100%;
            height: auto;
        }

        .book-card h3 {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center">
        <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            ☰
        </button>
        <h1>Perpustakaan Digital</h1>
        <p><?php echo date("l, d F Y"); ?></p>
    </header>

    <!-- Sidebar Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-group">
                <li class="list-group-item"><a href="crud-buku.php" class="text-decoration-none">Upload</a></li>
                <li class="list-group-item"><a href="#" class="text-decoration-none">Kategori Buku</a></li>
                <li class="list-group-item"><a href="#" class="text-decoration-none">Buku Terbaru</a></li>
                <li class="list-group-item"><a href="#" class="text-decoration-none">Buku Populer</a></li>
                <li class="list-group-item"><a href="#" class="text-decoration-none">Tentang Kami</a></li>
            </ul>
        </div>
    </div>

    <!-- Button Group -->
    

    <!-- Main Content -->
    <div class="container">
        <p>Hello, <?php echo $username; ?>! Selamat datang di perpustakaan digital.</p>
        <h2>Daftar Buku</h2>

        <div class="book-list">
            <?php
            include 'db.php';
            $sql = "SELECT * FROM buku";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='book-card'>";
                    echo "<img src='uploads/images/" . $row['file_gambar'] . "' alt='" . $row['judul'] . "'>";
                    echo "<h3>" . $row['judul'] . "</h3>";
                    echo "<a href='uploads/pdf/" . $row['file_pdf'] . "' target='_blank'>Lihat File PDF</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>Belum ada buku tersedia.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
