<?php
session_start();
include 'db.php';

// Front-end Code
if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];

    // Validate user input
    if (empty($judul)) {
        echo "Judul cannot be empty.";
        exit;
    }

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

    // Validate file types
    if (!in_array($gambar_type, ['jpg', 'jpeg', 'png'])) {
        echo "Only JPG, JPEG, and PNG files are allowed for images.";
        exit;
    }

    if ($pdf_type != "pdf") {
        echo "Only PDF files are allowed for files.";
        exit;
    }

    // Move uploaded files
    if (move_uploaded_file($_FILES['file_gambar']['tmp_name'], $target_gambar) && move_uploaded_file($_FILES['file_pdf']['tmp_name'], $target_pdf)) {
        // Simpan data ke database
        $sql = "INSERT INTO buku (judul, file_gambar, file_pdf) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $judul, $file_gambar, $file_pdf);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Data berhasil disimpan ke database.";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah file.";
    }
}

// Back-end Code
if (isset($_POST['upload'])) {
    // Direktori untuk menyimpan file
    $target_dir_gambar = "uploads/images/";
    $target_dir_pdf = "uploads/pdf/";

    // File gambar dan pdf
    $file_gambar = $_FILES["file_gambar"]["name"];
    $file_pdf = $_FILES["file_pdf"]["name"];

    $target_file_gambar = $target_dir_gambar . basename($file_gambar);
    $target_file_pdf = $target_dir_pdf . basename($file_pdf);
    
    // Logika untuk memproses file jika perlu
}
?>
