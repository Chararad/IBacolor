<?php
http_response_code(403);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>403 Forbidden | iBacolor Administration</title>
        <link rel="stylesheet" href="../../css/pages/admin-403.css?v=3" />
    </head>
    <body class="admin-view">
        <header class="site-header">
            <div class="container header-inner">
                <a class="brand" href="dashboard.php">
                    <img class="brand-logo" src="../../assets/bacolor-seal.webp" alt="" />
                    <span><strong>iBacolor</strong><small>Admin portal</small></span>
                </a>
            </div>
        </header>
        <main class="access-denied-section">
            <div class="container">
                <section class="card access-denied-card" aria-labelledby="access-denied-title">
                    <p class="access-denied-code" aria-hidden="true">403</p>
                    <p class="eyebrow">Administration / Access denied</p>
                    <h1 id="access-denied-title">You do not have permission to view this page.</h1>
                    <p class="lead">If you need access, contact your Master Admin to request authorization.</p>
                    <div class="access-denied-actions">
                        <a class="button button-primary" href="dashboard.php">Return to dashboard</a>
                        <a class="button button-outline" href="../logout.php">Sign out</a>
                    </div>
                </section>
            </div>
        </main>
    </body>
</html>
