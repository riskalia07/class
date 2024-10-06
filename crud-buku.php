<?php
session_start();
include 'db.php'; // Koneksi ke database

// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    
    // Proses Gambar
    $file_gambar = $_FILES['file_gambar']['name'];
    $gambar_dir = "uploads/images/";
    $target_gambar = $gambar_dir . basename($file_gambar);
    $gambar_type = pathinfo($target_gambar, PATHINFO_EXTENSION);

    // Proses PDF
    $file_pdf = $_FILES['file_pdf']['name'];
    $pdf_dir = "uploads/pdf/";
    $target_pdf = $pdf_dir . basename($file_pdf);
    $pdf_type = pathinfo($target_pdf, PATHINFO_EXTENSION);

    // Validasi Gambar (Hanya jpg, png)
    if (!in_array($gambar_type, ['jpg', 'jpeg', 'png'])) {
        echo "Hanya file gambar (JPG, PNG) yang diperbolehkan untuk gambar.";
        exit;
    }

    // Validasi PDF (Hanya pdf)
    if ($pdf_type != "pdf") {
        echo "Hanya file PDF yang diperbolehkan untuk file buku.";
        exit;
    }

    // Pindahkan file gambar ke folder uploads/images
    if (move_uploaded_file($_FILES['file_gambar']['tmp_name'], $target_gambar) && move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target_pdf)) {
        // Simpan data ke database
        $sql = "INSERT INTO buku (judul, file_gambar, file_pdf) VALUES ('$judul', '$file_gambar', '$file_pdf')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Gambar dan file PDF berhasil diunggah!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah file. Gambar Error: " . $_FILES['file_gambar']['error'] . " PDF Error: " . $_FILES['file_pdf']['error'];
    }
}
?>
<form method="POST" action="upload.php" enctype="multipart/form-data">
    <label for="judul">Judul Buku:</label>
    <input type="text" name="judul" placeholder="Masukkan Judul Buku" required><br><br>

    <label for="file_gambar">Unggah Gambar Buku (jpg, png):</label>
    <input type="file" name="file_gambar" accept=".jpg,.jpeg,.png" required><br><br>

    <label for="file_pdf">Unggah File Buku (PDF):</label>
    <input type="file" name="file_pdf" accept=".pdf" required><br><br>

    <button type="submit" name="submit">Unggah</button>
</form>

<h2>Data Buku</h2>
<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Judul</th>
            <th>Gambar</th>
            <th>File Buku</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        $sql = "SELECT * FROM buku";
        $result = mysqli_query($conn, $sql);
        while ($data = mysqli_fetch_array($result)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($data['judul']); ?></td>
                <td><img src="uploads/images/<?= htmlspecialchars($data['file_gambar']); ?>" width="100" height="100"></td>
                <td><a href="uploads/pdf/<?= htmlspecialchars($data['file_pdf']); ?>" target="_blank">Lihat File PDF</a></td>
                <td>
                    <a href="crud-data.php?id=<?= $data['id']; ?>">Edit</a> | 
                    <a href="crud-data.php?id=<?= $data['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">Hapus</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
