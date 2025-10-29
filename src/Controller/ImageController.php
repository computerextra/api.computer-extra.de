<?php

namespace MyApi\Controller;

use MyApi\App;
use MyApi\Service\ImageService;

class ImageController
{
    public function __construct(protected App $app, protected ImageService $imageService)
    {
    }

    /**
     * Upload single image and return URL.
     * Expect multipart/form-data with file field name.
     */
    public function upload(string $field): void
    {
        if (empty($_FILES[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'No file uploaded in field ' . $field]);
            return;
        }
        try {
            $url = $this->imageService->handleUpload($_FILES[$field]);
            http_response_code(201);
            echo json_encode(['url' => $url]);
            return;
        } catch (\Throwable $e) {
            $this->app->logger->error('Image upload failed', ['error' => $e->getMessage()]);
            http_response_code(400);
            echo json_encode(['error' => 'Upload failed', 'details' => $e->getMessage()]);
            return;
        }
    }
}
