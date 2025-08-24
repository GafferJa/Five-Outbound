<?php
// Database configuration
$host = 'localhost';
$dbname = 'five_outbound';
$username = 'root';  // Change this to your database username
$password = '';      // Change this to your database password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get statistics
    $stats = getStatistics($pdo);

    // Get all inquiries
    $inquiries = getAllInquiries($pdo);

} catch (PDOException $e) {
    $error = "Database connection failed: " . $e->getMessage();
}

function getStatistics($pdo)
{
    $stats = [];

    // Total inquiries
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM inquiries");
    $stats['total'] = $stmt->fetch()['total'];

    // New inquiries
    $stmt = $pdo->query("SELECT COUNT(*) as new_count FROM inquiries WHERE status = 'new'");
    $stats['new'] = $stmt->fetch()['new_count'];

    // Read inquiries
    $stmt = $pdo->query("SELECT COUNT(*) as read_count FROM inquiries WHERE status = 'read'");
    $stats['read'] = $stmt->fetch()['read_count'];

    // Today's inquiries
    $stmt = $pdo->query("SELECT COUNT(*) as today_count FROM inquiries WHERE DATE(created_at) = CURDATE()");
    $stats['today'] = $stmt->fetch()['today_count'];

    return $stats;
}

function getAllInquiries($pdo)
{
    $stmt = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Five Outbound</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <header>
        <div class="logo">The Five Outbound - Admin</div>
        <nav>
            <a href="index.php">← Back to Website</a>
            <a href="#stats">Statistics</a>
            <a href="#inquiries">Inquiries</a>
        </nav>
    </header>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <p>Manage customer inquiries and view statistics</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message" style="text-align: center; color: #f44336; margin-bottom: 30px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php else: ?>

            <!-- Statistics Section -->
            <section id="stats">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['total']; ?></div>
                        <div class="stat-label">Total Inquiries</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['new']; ?></div>
                        <div class="stat-label">New Inquiries</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['read']; ?></div>
                        <div class="stat-label">Read Inquiries</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $stats['today']; ?></div>
                        <div class="stat-label">Today's Inquiries</div>
                    </div>
                </div>
            </section>

            <!-- Inquiries Table -->
            <section id="inquiries">
                <div class="inquiries-table">
                    <div class="table-header">
                        <h2>All Inquiries</h2>
                    </div>

                    <?php if (empty($inquiries)): ?>
                        <div style="padding: 40px; text-align: center; color: #cce6ff;">
                            <p>No inquiries found.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inquiries as $inquiry): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($inquiry['id']); ?></td>
                                            <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                                            <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                                            <td><?php echo htmlspecialchars($inquiry['phone'] ?: 'N/A'); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $inquiry['status']; ?>">
                                                    <?php echo ucfirst($inquiry['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y g:i A', strtotime($inquiry['created_at'])); ?></td>
                                            <td>
                                                <button class="action-btn btn-view" onclick="viewInquiry(
                                                    <?php echo $inquiry['id']; ?>,
                                                    '<?php echo htmlspecialchars($inquiry['name'], ENT_QUOTES); ?>',
                                                    '<?php echo htmlspecialchars($inquiry['email'], ENT_QUOTES); ?>',
                                                    '<?php echo htmlspecialchars($inquiry['phone'], ENT_QUOTES); ?>',
                                                    '<?php echo htmlspecialchars($inquiry['description'], ENT_QUOTES); ?>',
                                                    '<?php echo date('M j, Y g:i A', strtotime($inquiry['created_at'])); ?>'
                                                )">
                                                    View
                                                </button>

                                                <?php if ($inquiry['status'] === 'new'): ?>
                                                    <button class="action-btn btn-view"
                                                        onclick="markAsRead(<?php echo $inquiry['id']; ?>)"
                                                        style="background: #FF9800;">
                                                        Mark Read
                                                    </button>
                                                <?php endif; ?>

                                                <button class="action-btn btn-delete"
                                                    onclick="deleteInquiry(<?php echo $inquiry['id']; ?>)">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        <?php endif; ?>
    </div>

    <!-- View Inquiry Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('viewModal').style.display='none'">&times;</span>
            <div id="viewContent"></div>
        </div>
    </div>

    <footer>
        &copy; 2025 The Five Outbound. All rights reserved.
    </footer>

    <script src="../style/script.js"></script>
</body>

</html>