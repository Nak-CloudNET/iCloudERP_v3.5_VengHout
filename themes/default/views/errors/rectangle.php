<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $assets; ?>styles/error_styles.css">
</head>
<body>
<div class="wrap">
    <div class="main">
        <h3><?php echo $site_name; ?></h3>

        <h1>Error: 404 Page not found</h1>

        <p>The page you requested was not found.</p>
        <a class="btn" href="javascript:history.go(-1)">&laquo; Back</a>
        <a class="btn" href="<?php echo $site_url; ?>">Home &raquo;</a>
    </div>
    <div class="footer">
        <p>&copy; <?php echo date('Y') . ' ' . $site_name; ?></p>
    </div>
</div>
</body>
</html>

