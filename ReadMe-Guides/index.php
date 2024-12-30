<?php
require_once('../includes/header.php');

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Get all markdown files
$markdown_files = glob("*.md");

// Function to make title readable
function make_title_readable($filename) {
    $title = pathinfo($filename, PATHINFO_FILENAME);
    $title = str_replace('-', ' ', $title);
    $title = ucwords($title);
    return $title;
}

// Function to get first line of file as description
function get_description($filename) {
    $content = file_get_contents($filename);
    $lines = explode("\n", $content);
    return !empty($lines[0]) ? trim($lines[0], '#- ') : 'No description available';
}
?>

<div class="container mt-4">
    <h2>ReadMe Guides</h2>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($markdown_files as $file): ?>
                    <tr>
                        <td>
                            <a href="view.php?file=<?php echo urlencode($file); ?>">
                                <?php echo htmlspecialchars(make_title_readable($file)); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars(get_description($file)); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($markdown_files)): ?>
                    <tr>
                        <td colspan="2" class="text-center">No guide files found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>

