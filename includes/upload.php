<?php
/**
 * Custom Streetwear - File Upload Handler
 */

require_once __DIR__ . '/functions.php';

/**
 * Process multiple file uploads
 */
function processUploads($files, $directory, $allowedTypes = ALLOWED_IMAGE_TYPES) {
    $results = [];
    
    if (!is_array($files['name'])) {
        // Single file
        $file = [
            'name' => $files['name'],
            'type' => $files['type'],
            'tmp_name' => $files['tmp_name'],
            'error' => $files['error'],
            'size' => $files['size']
        ];
        $result = uploadFile($file, $directory, $allowedTypes);
        $results[] = $result;
    } else {
        // Multiple files
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
            
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
            $result = uploadFile($file, $directory, $allowedTypes);
            $results[] = $result;
        }
    }
    
    return $results;
}

/**
 * Delete old upload file
 */
function deleteOldUpload($path) {
    if (!$path || $path === '/uploads/default.jpg') return;
    $fullPath = CSW_ROOT . $path;
    if (file_exists($fullPath)) {
        @unlink($fullPath);
    }
}

/**
 * Create upload directory if not exists
 */
function ensureUploadDir($directory) {
    $path = UPLOADS_PATH . '/' . $directory;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
    return $path;
}

/**
 * Get file extension safely
 */
function getFileExt($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Validate image dimensions
 */
function validateImageDimensions($tmpPath, $minWidth = null, $minHeight = null, $maxWidth = null, $maxHeight = null) {
    $info = getimagesize($tmpPath);
    if (!$info) return ['valid' => false, 'error' => 'Invalid image file'];
    
    $width = $info[0];
    $height = $info[1];
    
    if ($minWidth && $width < $minWidth) {
        return ['valid' => false, 'error' => "Image width must be at least {$minWidth}px"];
    }
    if ($minHeight && $height < $minHeight) {
        return ['valid' => false, 'error' => "Image height must be at least {$minHeight}px"];
    }
    if ($maxWidth && $width > $maxWidth) {
        return ['valid' => false, 'error' => "Image width must not exceed {$maxWidth}px"];
    }
    if ($maxHeight && $height > $maxHeight) {
        return ['valid' => false, 'error' => "Image height must not exceed {$maxHeight}px"];
    }
    
    return ['valid' => true, 'width' => $width, 'height' => $height];
}
