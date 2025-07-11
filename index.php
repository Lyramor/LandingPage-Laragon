<?php
// Handle AJAX requests for live search
if (isset($_GET['action']) && $_GET['action'] === 'search') {
    header('Content-Type: application/json');
    
    $query = $_GET['q'] ?? '';
    $results = [];
    
    if (strlen($query) > 0) {
        $wwwPath = $_SERVER['DOCUMENT_ROOT'];
        
        // Get all directories in www folder
        if (is_dir($wwwPath)) {
            $directories = scandir($wwwPath);
            
            foreach ($directories as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($wwwPath . '/' . $dir)) {
                    // Check if directory name contains the search query
                    if (stripos($dir, $query) !== false) {
                        $fullPath = $wwwPath . '/' . $dir;
                        $lastModified = filemtime($fullPath);
                        
                        // Try to determine project type based on files
                        $projectType = 'Directory';
                        $files = scandir($fullPath);
                        
                        if (in_array('index.php', $files)) {
                            $projectType = 'PHP Project';
                        } elseif (in_array('index.html', $files)) {
                            $projectType = 'HTML Project';
                        } elseif (in_array('package.json', $files)) {
                            $projectType = 'Node.js Project';
                        } elseif (in_array('composer.json', $files)) {
                            $projectType = 'PHP/Composer Project';
                        } elseif (in_array('app.js', $files)) {
                            $projectType = 'JavaScript App';
                        }
                        
                        $results[] = [
                            'name' => $dir,
                            'path' => $fullPath,
                            'type' => $projectType,
                            'lastModified' => date('Y-m-d H:i:s', $lastModified)
                        ];
                    }
                }
            }
        }
    }
    
    echo json_encode($results);
    exit;
}

// Handle other queries
if (!empty($_GET['q'])) {
    switch ($_GET['q']) {
        case 'info':
            phpinfo(); 
            exit;
        break;
    }
}

// Get recent directories (sorted by modification time)
function getRecentDirectories($limit = 6) {
    $wwwPath = $_SERVER['DOCUMENT_ROOT'];
    $directories = [];
    
    if (is_dir($wwwPath)) {
        $items = scandir($wwwPath);
        
        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..' && is_dir($wwwPath . '/' . $item)) {
                $fullPath = $wwwPath . '/' . $item;
                $lastModified = filemtime($fullPath);
                
                // Try to determine project type
                $projectType = 'Directory';
                $files = scandir($fullPath);
                
                if (in_array('index.php', $files)) {
                    $projectType = 'PHP Project';
                } elseif (in_array('index.html', $files)) {
                    $projectType = 'HTML Project';
                } elseif (in_array('package.json', $files)) {
                    $projectType = 'Node.js Project';
                } elseif (in_array('composer.json', $files)) {
                    $projectType = 'PHP/Composer Project';
                } elseif (in_array('app.js', $files)) {
                    $projectType = 'JavaScript App';
                }
                
                $directories[] = [
                    'name' => $item,
                    'path' => $fullPath,
                    'type' => $projectType,
                    'lastModified' => $lastModified,
                    'formattedTime' => date('M j, Y H:i', $lastModified)
                ];
            }
        }
        
        // Sort by last modified time (newest first)
        usort($directories, function($a, $b) {
            return $b['lastModified'] - $a['lastModified'];
        });
        
        return array_slice($directories, 0, $limit);
    }
    
    return [];
}

$recentDirectories = getRecentDirectories();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laragon - Dev Environment</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #4facfe 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo {
            font-size: 4rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome {
            font-size: 2.5rem;
            color: white;
            font-weight: 600;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .motivation {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            font-weight: 400;
            margin-bottom: 2rem;
            font-style: italic;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .search-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            position: relative;
            z-index: 100;
        }

        .search-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .live-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
        }

        .live-search-results.show {
            display: block;
            z-index: 1000;
        }

        .live-search-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .live-search-item:hover {
            background: rgba(79,172,254,0.1);
        }

        .live-search-item:last-child {
            border-bottom: none;
        }

        .live-search-icon {
            color: #4facfe;
            font-size: 1rem;
        }

        .live-search-info {
            flex: 1;
        }

        .live-search-name {
            font-weight: 500;
            color: #333;
            font-size: 1rem;
        }

        .live-search-path {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.2rem;
        }

        .live-search-type {
            color: #4facfe;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .search-input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            background: rgba(255,255,255,0.9);
            color: #333;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-input:focus {
            background: white;
            box-shadow: 0 6px 25px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .search-btn {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79,172,254,0.3);
            margin-top: 1rem;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(79,172,254,0.4);
        }

        .recent-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
        }

        .section-title {
            font-size: 1.5rem;
            color: white;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .recent-list {
            display: grid;
            gap: 1rem;
        }

        .recent-item {
            background: rgba(255,255,255,0.1);
            padding: 1rem 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .recent-item:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .recent-info {
            flex: 1;
        }

        .recent-name {
            color: white;
            font-weight: 500;
            font-size: 1.1rem;
        }

        .recent-path {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin-top: 0.2rem;
        }

        .recent-type {
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
            margin-top: 0.2rem;
        }

        .recent-time {
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
            text-align: right;
        }

        .server-info {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: center;
        }

        .server-info p {
            color: rgba(255,255,255,0.8);
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }

        .server-info a {
            color: #4facfe;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .server-info a:hover {
            color: #00f2fe;
            text-shadow: 0 0 10px rgba(79,172,254,0.5);
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(79,172,254,0.1);
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .loading {
            text-align: center;
            color: #666;
            padding: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .logo {
                font-size: 3rem;
            }
            
            .welcome {
                font-size: 2rem;
            }
            
            .search-section, .recent-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo">Laragon</div>
            <div class="welcome">Welcome to Laragon! 👋</div>
            <div class="motivation">"Code is poetry, debugging is art, and every bug is a stepping stone to greatness!"</div>
        </div>

        <div class="main-content">
            <div class="search-section">
                <div class="section-title">
                    Search Your Projects
                </div>
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Enter project name..." id="searchInput">
                    <div class="live-search-results" id="liveSearchResults"></div>
                </div>
                <button class="search-btn" onclick="searchDirectory()">
                    Search Now
                </button>
            </div>

            <div class="recent-section">
                <div class="section-title">
                    Recent Projects
                </div>
                <div class="recent-list" id="recentList">
                    <?php if (!empty($recentDirectories)): ?>
                        <?php foreach ($recentDirectories as $dir): ?>
                            <div class="recent-item" onclick="openDirectory('<?php echo htmlspecialchars($dir['name']); ?>')">
                                <div class="recent-info">
                                    <div class="recent-name"><?php echo htmlspecialchars($dir['name']); ?></div>
                                    <div class="recent-path"><?php echo htmlspecialchars($dir['path']); ?></div>
                                    <div class="recent-type"><?php echo htmlspecialchars($dir['type']); ?></div>
                                </div>
                                <div class="recent-time"><?php echo $dir['formattedTime']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="recent-item">
                            <div class="recent-info">
                                <div class="recent-name">No projects found</div>
                                <div class="recent-path">Create your first project in the www directory</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="server-info">
            <p><i class="fas fa-server"></i> <?php print($_SERVER['SERVER_SOFTWARE']); ?></p>
            <p><i class="fas fa-code"></i> PHP version: <?php print phpversion(); ?> <a title="phpinfo()" href="/?q=info">info</a></p>
            <p><i class="fas fa-folder"></i> Document Root: <?php print ($_SERVER['DOCUMENT_ROOT']); ?></p>
            <p><a href="https://laragon.org/docs" target="_blank"><i class="fas fa-book"></i> Getting Started</a></p>
        </div>
    </div>

    <script>
        let searchTimeout;

        function searchDirectory() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            if (searchTerm) {
                window.location.href = `/${searchTerm}`;
            } else {
                alert('Please enter a project name to search!');
            }
        }

        function performLiveSearch(query) {
            if (query.trim() === '') {
                document.getElementById('liveSearchResults').classList.remove('show');
                return;
            }

            const resultsContainer = document.getElementById('liveSearchResults');
            resultsContainer.innerHTML = '<div class="loading">Searching...</div>';
            resultsContainer.classList.add('show');

            // Make AJAX request to PHP backend
            fetch(`?action=search&q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(results => {
                    resultsContainer.innerHTML = '';
                    
                    if (results.length === 0) {
                        resultsContainer.innerHTML = '<div class="loading">No projects found</div>';
                        return;
                    }

                    results.forEach(dir => {
                        const item = document.createElement('div');
                        item.className = 'live-search-item';
                        item.innerHTML = `
                            <div class="live-search-info">
                                <div class="live-search-name">${dir.name}</div>
                                <div class="live-search-path">${dir.path}</div>
                            </div>
                            <div class="live-search-type">${dir.type}</div>
                        `;
                        
                        item.addEventListener('click', () => {
                            document.getElementById('searchInput').value = dir.name;
                            resultsContainer.classList.remove('show');
                            window.location.href = `/${dir.name}`;
                        });
                        
                        resultsContainer.appendChild(item);
                    });
                })
                .catch(error => {
                    console.error('Search error:', error);
                    resultsContainer.innerHTML = '<div class="loading">Search error occurred</div>';
                });
        }

        function openDirectory(dirname) {
            window.location.href = `/${dirname}`;
        }

        // Live search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performLiveSearch(e.target.value);
            }, 300);
        });

        // Allow search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchDirectory();
            }
        });

        // Hide live search when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-container')) {
                document.getElementById('liveSearchResults').classList.remove('show');
            }
        });

        // Add fade-in animation for recent items
        document.addEventListener('DOMContentLoaded', function() {
            const recentItems = document.querySelectorAll('.recent-item');
            recentItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.style.animation = 'fadeInUp 0.6s ease forwards';
            });
        });

        // CSS animation for fade in
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>