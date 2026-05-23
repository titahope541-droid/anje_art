<?php
// Database configuration
$host = 'localhost';
$dbname = 'art_gallery';
$username = 'gallery_user';
$password = 'password';

// Create connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create uploads directory if it doesn't exist
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle file upload
$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['artwork'])) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $publishDate = $_POST['publish_date'] ?? date('Y-m-d');
    $category = $_POST['category'] ?? 'Uncategorized';
    
    $file = $_FILES['artwork'];
    $fileName = time() . '_' . basename($file['name']);
    $targetFile = $uploadDir . $fileName;
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        $message = 'Invalid file type. Only JPG, PNG, and GIF files are allowed.';
    } 
    // Validate file size (max 5MB)
    elseif ($file['size'] > 5000000) {
        $message = 'File is too large. Maximum size is 5MB.';
    }
    // Move uploaded file
    elseif (move_uploaded_file($file['tmp_name'], $targetFile)) {
        try {
            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO artworks (name, description, image_path, publish_date, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $targetFile, $publishDate, $category]);
            
            $message = 'Artwork uploaded successfully!';
            $success = true;
        } catch(PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            // Delete the uploaded file if database insert failed
            unlink($targetFile);
        }
    } else {
        $message = 'Sorry, there was an error uploading your file.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Artwork</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
            min-height: 100vh;
            display: flex;
            flex-direction:column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #fff;
        }
        
        .container {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 600px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            text-align: center;
            padding: 25px 20px;
        }
        
        .header h1 {
            font-weight: 600;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .form-container {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ddd;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 14px;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid #333;
            border-radius: 8px;
            font-size: 16px;
            color: #fff;
            transition: border-color 0.3s;
        }
        
        input:focus, textarea:focus, select:focus {
            border-color: #4a86e8;
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 134, 232, 0.2);
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        button {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            border: none;
            padding: 16px 20px;
            border-radius: 8px;
            cursor: pointer;
            width: 30%;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(38, 117, 252, 0.4);
        }
        
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
        }
        
        .success {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }
        
        .error {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            color: #6a11cb;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-btn:hover {
            color: #cca1faff;
        }

        .upload-history {
    margin-top: 40px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.upload-history h2 {
    margin-bottom: 20px;
    color: #fff;
    border-bottom: 2px solid #6a11cb;
    padding-bottom: 10px;
}

.history-container {
    max-height: 400px;
    overflow-y: auto;
    width: 100%;
}

.history-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 8px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.history-item:hover {
    background: rgba(0, 0, 0, 0.4);
}

.history-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 15px;
}

.history-details {
    flex: 1;
}

.history-name {
    font-weight: 600;
    color: #fff;
    margin-bottom: 5px;
}

.history-date {
    color: #888;
    font-size: 0.9rem;
}

.delete-btn {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.3s;
}

.delete-btn:hover {
    background: #c0392b;
}

.empty-history {
    text-align: center;
    color: #888;
    padding: 30px;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Upload New Artwork</h1>
        </div>
        
        <div class="form-container">
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Artwork Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="publish_date">Publish Date</label>
                    <input type="date" id="publish_date" name="publish_date" required>
                </div>
                
                <div class="form-group">
                    <label for="artwork">Artwork Image</label>
                    <input type="file" id="artwork" name="artwork" accept="image/*" required>
                </div>
                
                <button type="submit">Upload Artwork</button>
            </form>
            
            <a href="gallery.php" class="back-btn"> Back to Gallery</a>
        </div>
    </div>

 <script>
        // Set today's date as default for the date field
        document.getElementById('publish_date').valueAsDate = new Date();
    </script>
</body>
</html>