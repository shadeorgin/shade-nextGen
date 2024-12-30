<?php
session_start();
require_once('../includes/header.php');

// Security and file handling code...
$filename = isset($_GET['file']) ? basename($_GET['file']) : '';

// Basic security check
if (empty($filename) || !preg_match('/^[a-zA-Z0-9_-]+\\.md$/', $filename)) {
    header('Location: index.php');
    exit();
}

// Read and clean file content
$filepath = __DIR__ . '/' . $filename;
if (!file_exists($filepath)) {
    $error = "File not found";
} else {
    $content = file_get_contents($filepath);
    if ($content === false) {
        $error = "Error reading file";
    } else {
        $content = trim($content);
    }
}
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Guides</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars(str_replace('-', ' ', pathinfo($filename, PATHINFO_FILENAME))); ?></li>
            </ol>
        </nav>
        <a href="index.php" class="btn btn-primary">Back to Guides</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php else: ?>
        <div id="markdown-content" class="markdown-body"><?php echo htmlspecialchars($content); ?></div>
    <?php endif; ?>
</div>

<style>
    .markdown-body {
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif;
        font-size: 16px;
        line-height: 1.5;
        word-wrap: break-word;
        padding: 2rem;
    }
    .markdown-body h1 {
        padding-bottom: 0.3em;
        font-size: 2em;
        border-bottom: 1px solid #eaecef;
        margin-bottom: 16px;
    }
    .markdown-body pre {
        background-color: #f6f8fa;
        border-radius: 6px;
        padding: 16px;
        overflow: auto;
    }
    .markdown-body code {
        background-color: rgba(27,31,35,.05);
        border-radius: 3px;
        padding: .2em .4em;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Guides</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars(str_replace('-', ' ', pathinfo($filename, PATHINFO_FILENAME))); ?></li>
            </ol>
        </nav>
        <a href="index.php" class="btn btn-primary">Back to Guides</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <div class="markdown-body">
            <div id="markdown-source" class="d-none"><?php echo htmlspecialchars($content); ?></div>
            <div id="markdown-output" class="markdown-content"></div>
        </div>
    <?php endif; ?>
</div>

<script src="../js/marked.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    marked.setOptions({
        gfm: true,
        breaks: true,
        pedantic: false,
        headerIds: true,
        mangle: false
    });

    const content = document.getElementById('markdown-content');
    if (content) {
      try {
          const markdown = content.textContent.trim();
          console.log('Raw markdown:', markdown);  // Debug log
          content.innerHTML = marked.parse(markdown);
          console.log('Parsed HTML:', content.innerHTML);  // Debug log
      } catch (e) {
          console.error('Markdown parsing error:', e);
          content.innerHTML = '<div class="alert alert-danger">Error parsing markdown content</div>';
      }
    }

    const source = document.getElementById('markdown-source');
    const output = document.getElementById('markdown-output');

    if (source && output) {
        try {
            // Get raw markdown and clean it
            let markdown = source.textContent.trim();

            // Debug raw content
            console.log('Raw markdown content:', markdown);

            // Clean the content
            markdown = markdown.replace(/^\\uFEFF/, '');       // Remove BOM
            markdown = markdown.replace(/\\r\\n/g, '\\n');     // Normalize line endings

            // Parse and render
            const parsed = marked.parse(markdown);
            output.innerHTML = parsed;

            // Debug parsed output
            console.log('Parsed HTML:', output.innerHTML);

        } catch (e) {
            console.error('Markdown parsing error:', e);
            content.innerHTML = '<div class="alert alert-danger">Error parsing markdown content</div>';
        }
    }
});
</script>
<?php include '../includes/footer.php'; ?>
