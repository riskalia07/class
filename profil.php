<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

include 'db.php'; // Menyertakan file koneksi database

// Mendapatkan data pengguna saat ini
$username = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$userData = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    
    <!-- Link ke file CSS eksternal -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Link ke Bootstrap dan Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <a href="index.php" class="btn-back">
        <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
    </a>

    <div class="container profile-container text-center">
        <!-- Menggunakan tag <img> untuk foto profil -->
        <div class="profile-picture">
            <img src="uploads/user/<?php echo htmlspecialchars($userData['profile_picture'] ?: 'default.png'); ?>" alt="Foto Profil" class="rounded-circle" style="width: 150px; height: 150px;">
        </div>
        <div class="profile-name">
            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($userData['name']); ?>
        </div>
        <div class="info">
            <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($userData['email']); ?>
        </div>
        <div class="info">
            <i class="bi bi-phone"></i> <?php echo htmlspecialchars($userData['phone'] ?? 'Tidak ada nomor telepon'); ?>
        </div>
        <a href="edit-profil.php" class="btn-edit-profile">
            <i class="bi bi-pencil-square"></i> Ubah Profil
        </a>
    </div>

    <div class="footer">
        <div class="container">
            <a href="home.php"><i class="bi bi-house"></i> Home</a>
            <a href="confirm_trip.php"><i class="bi bi-check-circle"></i> Konfirmasi Perjalanan</a>
            <a href="my_trip.php"><i class="bi bi-briefcase"></i> Perjalanan Saya</a>
            <a href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
            <a href="profile.php"><i class="bi bi-person"></i> Profil</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
