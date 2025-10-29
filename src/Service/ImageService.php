<?php

namespace MyApi\Service;

use Ramsey\Uuid\Uuid;

class ImageService
{
    private string $uploadDir;
    private string $publicBase;

    public function __construct(string $uploadDir, string $publicBase)
    {
        $this->publicBase = rtrim($publicBase, "/");
        $this->uploadDir = rtrim($uploadDir, "/");
    }

    public function handleUpload(array $file): string
    {
        if ($file["error"] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException("Upload error: {$file["error"]}");
        }

        $allowed = ["image/jpeg" => "jpeg", "image/png" => "png", "image/webp" => "webp", "image/jpg" => "jpg"];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file["tmp_name"]);
        finfo_close($finfo);

        if (!isset($allowed[$mime])) {
            throw new \RuntimeException("Ungültiger Bildtyp ($mime)");
        }


        $ext = $allowed[$mime];
        $uuid = Uuid::uuid4()->toString();
        $filename = $uuid . "." . $ext;
        $path = $this->uploadDir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $path)) {
            throw new \RuntimeException('Fehler beim Speichern der Datei');
        }

        chmod($path, 0644);
        return $this->publicBase . "/" . $filename;
    }

    public function deleteByUrl(string $url): bool
    {
        $filename = basename(parse_url($url, PHP_URL_PATH));
        $path = $this->uploadDir . $filename;
        if (is_file($path)) {
            return unlink($path);
        }
        return false;
    }

    public function listFiles(array $patterns = ["jpg", "jpeg", "png", "webp"]): array
    {
        $globs = [];
        foreach ($patterns as $p) {
            $globs[] = $this->uploadDir . "/*." . $p;
        }
        $files = [];
        foreach ($globs as $g) {
            foreach (glob($g, GLOB_NOSORT) ?: [] as $f) {
                $files[] = $f;
            }
        }
        return $files;
    }

}
