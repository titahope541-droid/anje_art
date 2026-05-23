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

// Fetch artworks from database
try {
    $stmt = $pdo->query("SELECT * FROM artworks ORDER BY upload_date DESC");
    $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $artworks = [];
    $error = "Error fetching artworks: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Art Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="gallery.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Art Gallery</h1>
            <p>Explore the collection of captivating artworks</p>
        </header>
        
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

// Fetch artworks from database
try {
    $stmt = $pdo->query("SELECT * FROM artworks ORDER BY upload_date DESC");
    $artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $artworks = [];
    $error = "Error fetching artworks: " . $e->getMessage();
}
?>

<!-- HTML/PHP Gallery Content -->
<div class="gallery" id="artwork-gallery">
    <?php if (count($artworks) > 0): ?>
        <?php foreach ($artworks as $artwork): ?>
            <div class="gallery-item" data-category="<?php echo htmlspecialchars($artwork['category'] ?? 'uncategorized'); ?>">
                <img src="<?php echo htmlspecialchars($artwork['image_path']); ?>" 
                     alt="<?php echo htmlspecialchars($artwork['name']); ?>" 
                     class="gallery-image" onclick="openModal(this.src, this.alt)">
                <div class="gallery-content">
                    <h3 class="gallery-title"><?php echo htmlspecialchars($artwork['name']); ?></h3>
                    <p class="gallery-description"><?php echo htmlspecialchars($artwork['description']); ?></p>
                    <div class="gallery-meta">
                        <span><?php echo date('M j, Y', strtotime($artwork['publish_date'])); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-gallery">
            <i class="fas fa-image"></i>
            <h3>No Artworks Yet</h3>
        </div>
    <?php endif; ?>
</div>
        
        <footer>
            <p>© 2025 Anje's Art | All rights reserved</p>
        </footer>
    </div>
    
    <!-- Modal -->
<div class="modal" id="imageModal">
    <span class="close-btn" id="closeModal">&times;</span>
    <img class="modal-content" id="modalImage">
    <div class="modal-caption" id="modalCaption"></div>
</div>
    
<script>
        // Function to render gallery items
        function renderGallery(items) {
            const gallery = document.getElementById('artwork-gallery');
            
            if (items.length === 0) {
                gallery.innerHTML = `
                    <div class="empty-gallery">
                        <i class="fas fa-image"></i>
                        <h3>No Artworks Found</h3>
                        <p>Try a different search or filter</p>
                    </div>
                `;
                return;
            }
           
            
            gallery.innerHTML = items.map(item => `
                <div class="gallery-item" data-category="${item.category}">
                    <img src="${item.image}" alt="${item.title}" class="gallery-image">
                    <div class="gallery-content">
                        <h3 class="gallery-title">${item.title}</h3>
                        <p class="gallery-description">${item.description}</p>
                        <div class="gallery-meta">
                            <span>${item.date}</span>
                            <span>${item.views} views</span>
                        </div>
                    </div>
                </div>
            `).join('');
        };

            // Get modal elements
const modal = document.getElementById('imageModal');
const modalImg = document.getElementById('modalImage');
const captionText = document.getElementById('modalCaption');
const closeBtn = document.getElementById('closeModal');

// Function to open modal
function openModal(imgSrc, imgAlt) {
    modal.style.display = 'flex';
    modalImg.src = imgSrc;
    captionText.textContent = imgAlt;
    document.body.style.overflow='hidden';
}

// Close modal when clicking X
closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
});

// Close modal when clicking outside image
modal.addEventListener('click', function(event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && modal.style.display === 'flex') {
        modal.style.display = 'none';
    }
});
        
        // Initial render
        renderGallery(artworkData);
        
        // Filter functionality
        document.querySelectorAll('.filter-buttons button').forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.filter-buttons button').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                
                if (filter === 'all') {
                    renderGallery(artworkData);
                } else {
                    const filteredData = artworkData.filter(item => item.category === filter);
                    renderGallery(filteredData);
                }
            });
        });
        
        // Search functionality
        document.querySelector('.search-box button').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-box input').value.toLowerCase();
            
            if (searchTerm) {
                const filteredData = artworkData.filter(item => 
                    item.title.toLowerCase().includes(searchTerm) || 
                    item.description.toLowerCase().includes(searchTerm)
                );
                
                renderGallery(filteredData);
            } else {
                renderGallery(artworkData);
            }
        });
        
        // Allow pressing Enter to search
        document.querySelector('.search-box input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.search-box button').click();
            }
        });
        
        // Modal functionality
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('imageModal').style.display = 'none';
        });
        
        // Close modal when clicking outside the image
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('imageModal')) {
                document.getElementById('imageModal').style.display = 'none';
            }
        });
    </script>
</body>
</html>

<!-- In the HTML section, replace the JavaScript artworkData with PHP-generated data -->
<script>
    const artworkData = <?php echo json_encode($artworks); ?>;
</script>