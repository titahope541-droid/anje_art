<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $filename = $data['filename'];
    
    // Security: Validate filename to prevent directory traversal
    if (preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
        $filepath = 'uploads/' . $filename;
        
        if (file_exists($filepath)) {
            if (unlink($filepath)) {
                // Also remove from database if needed
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Could not delete file']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid filename']);
    }
}
?>