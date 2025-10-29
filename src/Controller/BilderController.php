<?php

namespace MyApi\Controller;

use MyApi\App;
use MyApi\Service\ImageService;
use MyApi\Service\AuditService;

class BilderController
{
    public function __construct(protected App $app, protected ImageService $imageService, protected AuditService $audit)
    {
    }

    public function list(): void
    {
        $files = $this->imageService->listFiles();
        $data = array_map(function ($f) {
            return [
                'filename' => basename($f),
                'url' => $this->app->config['public_url'] . '/' . basename($f),
                'size_kb' => round(filesize($f) / 1024, 2),
                'modified_at' => date('Y-m-d H:i:s', filemtime($f))
            ];
        }, $files);
        echo json_encode(['count' => count($data), 'data' => $data]);
    }

    public function orphans(): void
    {
        $publicBase = $this->app->config['public_url'];
        $knownUrls = [];
        $pattern = $publicBase . '/%';

        foreach ($this->app->config['allowed_tables'] as $tbl) {
            $cols = $this->app->pdo->query("SHOW COLUMNS FROM `$tbl`")->fetchAll(\PDO::FETCH_COLUMN);
            $imageCols = array_filter($cols, fn ($c) => preg_match('/bild|image|foto|picture/i', $c));
            foreach ($imageCols as $col) {
                $stmt = $this->app->pdo->prepare("SELECT `$col` FROM `$tbl` WHERE `$col` LIKE :pat");
                $stmt->execute([':pat' => $pattern]);
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $knownUrls[] = $row[$col];
                }
            }
        }

        $knownFiles = array_unique(array_map(fn ($u) => basename(parse_url($u, PHP_URL_PATH)), $knownUrls));
        $orphans = [];
        foreach ($this->imageService->listFiles() as $filePath) {
            $name = basename($filePath);
            if (!in_array($name, $knownFiles)) {
                $orphans[] = [
                    'filename' => $name,
                    'url' => $publicBase . '/' . $name,
                    'size_kb' => round(filesize($filePath) / 1024, 2),
                    'modified_at' => date('Y-m-d H:i:s', filemtime($filePath)),
                ];
            }
        }

        echo json_encode([
            'total_files' => count($this->imageService->listFiles()),
            'orphans_count' => count($orphans),
            'orphans' => $orphans
        ]);
    }

    public function deleteOrphans(bool $requireConfirm = true): void
    {
        if ($requireConfirm && (!isset($_GET['confirm']) || $_GET['confirm'] !== 'true')) {
            http_response_code(400);
            echo json_encode(['error' => 'Bestätigung erforderlich', 'hint' => 'Nutze ?confirm=true']);
            return;
        }

        $publicBase = $this->app->config['public_url'];
        $knownUrls = [];
        foreach ($this->app->config['allowed_tables'] as $tbl) {
            $cols = $this->app->pdo->query("SHOW COLUMNS FROM `$tbl`")->fetchAll(\PDO::FETCH_COLUMN);
            $imageCols = array_filter($cols, fn ($c) => preg_match('/bild|image|foto|picture/i', $c));
            foreach ($imageCols as $col) {
                $stmt = $this->app->pdo->prepare("SELECT `$col` FROM `$tbl` WHERE `$col` LIKE :pat");
                $stmt->execute([':pat' => $publicBase . '/%']);
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $knownUrls[] = $row[$col];
                }
            }
        }

        $knownFiles = array_unique(array_map(fn ($u) => basename(parse_url($u, PHP_URL_PATH)), $knownUrls));
        $orphans = [];
        foreach ($this->imageService->listFiles() as $filePath) {
            if (!in_array(basename($filePath), $knownFiles)) {
                $orphans[] = $filePath;
            }
        }

        $deleted = 0;
        $errors = [];
        foreach ($orphans as $filePath) {
            try {
                unlink($filePath);
                $deleted++;
                $this->app->logger->info("Orphan deleted: $filePath");
            } catch (\Throwable $e) {
                $errors[] = ['file' => $filePath, 'error' => $e->getMessage()];
                $this->app->logger->warning('Orphan delete error', ['file' => $filePath, 'error' => $e->getMessage()]);
            }
        }

        $this->app->pdo->beginTransaction();
        try {
            $this->audit->write('bilder', 'DELETE_ORPHANS', null, ['deleted' => $deleted, 'errors' => $errors], null);
            $this->app->pdo->commit();
        } catch (\Throwable $e) {
            $this->app->pdo->rollBack();
            $this->app->logger->error('Audit write failed', ['error' => $e->getMessage()]);
        }

        echo json_encode(['deleted' => $deleted, 'errors' => $errors]);
    }

    public function stats(?int $days = null): void
    {
        $cutoff = $days ? time() - ($days * 86400) : null;
        $files = $this->imageService->listFiles();
        $count = count($files);
        $totalSize = 0;
        $oldest = null;
        $newest = null;
        $byType = ['jpg' => 0,'jpeg' => 0,'png' => 0,'webp' => 0];
        $recentCount = 0;
        $recentSize = 0;

        foreach ($files as $filePath) {
            $size = filesize($filePath);
            $totalSize += $size;
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if (isset($byType[$ext])) {
                $byType[$ext]++;
            }
            $mtime = filemtime($filePath);
            if (!$oldest || $mtime < $oldest) {
                $oldest = $mtime;
            }
            if (!$newest || $mtime > $newest) {
                $newest = $mtime;
            }
            if ($cutoff && $mtime >= $cutoff) {
                $recentCount++;
                $recentSize += $size;
            }
        }

        $average = $count > 0 ? round($totalSize / $count, 2) : 0;
        $resp = [
            'total_files' => $count,
            'total_size_kb' => round($totalSize / 1024, 2),
            'total_size_mb' => round($totalSize / 1048576, 2),
            'average_size_kb' => round($average / 1024, 2),
            'oldest_file' => $oldest ? date('Y-m-d H:i:s', $oldest) : null,
            'newest_file' => $newest ? date('Y-m-d H:i:s', $newest) : null,
            'by_type' => $byType
        ];
        if ($days) {
            $resp['period_days'] = $days;
            $resp['recent_files'] = $recentCount;
            $resp['recent_size_kb'] = round($recentSize / 1024, 2);
            $resp['recent_size_mb'] = round($recentSize / 1048576, 2);
        }
        echo json_encode($resp);
    }
}
