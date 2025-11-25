<?php

declare(strict_types=1);

require_once __DIR__ . PATH_SEPARATOR . "config.php";

function uploadImage($file)
{
    if ($file["size"] > 5000000) {
        return null;
    }

    $imageFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, ["jpg", "jpeg", "webp", "png"])) {
        return null;
    }
    $filename = CUIDGenerator::gnerateCUID() . "." . $imageFileType;

    if (move_uploaded_file($file["temp_name"], UPLOAD_DIR . $filename)) {
        return $filename;
    } else {
        return null;
    }
}

function deleteImage($filename)
{
    if (file_exists($filename)) {
        unlink($filename);
    }
}
