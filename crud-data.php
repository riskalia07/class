<?php
session_start();
include 'db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data buku berdasarkan ID
    $sql = "SELECT * FROM buku WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);

    // Cek apakah data ditemukan
    if (!$data) {
        echo "ID buku tidak ditemukan!";
        exit();
    }

    // Cek apakah user yang login adalah pemilik file
    if ($data['user_id'] == $_SESSION['user_id']) {
        // Hapus file dari folder jika ada
        if (isset($_POST['delete'])) {
            $file_path = "uploads/" . $data['file_buku'];
            if (file_exists($file_path)) {
                unlink($file_path);  // Hapus file fisik
            }

            // Hapus data dari database
            $sql = "DELETE FROM buku WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                echo "Buku berhasil dihapus!";
                header("Location: user.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Anda tidak memiliki izin untuk menghapus buku ini.";
    }

    // Proses update data buku
    if (isset($_POST['update'])) {
        $judul = $_POST['judul'];

        // Cek apakah ada file yang diunggah
        if ($_FILES['file_buku']['name']) {
            $file_buku = $_FILES['file_buku']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file_buku);

            // Pindahkan file ke folder uploads
            move_uploaded_file($_FILES['file_buku']['tmp_name'], $target_file);

            // Update data dengan file baru
            $sql = "UPDATE buku SET judul='$judul', file_buku='$file_buku' WHERE id=$id";
        } else {
            // Update data tanpa mengubah file
            $sql = "UPDATE buku SET judul='$judul' WHERE id=$id";
        }

        if (mysqli_query($conn, $sql)) {
            echo "Data berhasil diupdate!";
            header("Location: user.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "ID buku tidak ditemukan!";
}
?>

<form method="POST" action="edit.php?id=<?= $id; ?>" enctype="multipart/form-data">
    <label for="judul">Judul Buku:</label>
    <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required><br><br>

    <label for="file_buku">Unggah Gambar Buku Baru (Opsional):</label>
    <input type="file" name="file_buku" accept=".jpg,.jpeg,.png"><br><br>

    <button type="submit" name="update">Simpan Perubahan</button>
</form>

<form method="POST" action="edit.php?id=<?= $id; ?>">
    <button type="submit" name="delete">Hapus Buku</button>
</form>
