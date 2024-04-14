<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Navigation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .navigation {
            margin-bottom: 20px;
        }
        .navigation ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-direction: column; /* 竖向排列 */
        }
        .navigation ul li {
            margin-bottom: 10px;
        }
        .navigation ul li a {
            display: block;
            font-size: 18px;
            text-decoration: none;
            color: #000; /* 链接文字颜色 */
            transition: color 0.3s ease; /* 添加过渡效果 */
        }
        .navigation ul li a:hover {
            color: #ff0000; /* 鼠标悬停时文字颜色 */
        }
    </style>
</head>
<body>
    <div class="navigation">
        <h2 style="font-size: 24px;">iptv 导航菜单</h2>
        <ul>
            <li><a href="houtai123.php" target="_blank">1</a></li>
            <li><a href="diyp/xml2db.php" target="_blank">2</a></li>
            <li><a href="diyp/channel.php" target="_blank">3</a></li>
            <li><a href="diyp/info.php " target="_blank">4</a></li>
			<li><a href="diyp/l2list.php" target="_blank">5</a></li>
        </ul>
    </div>

    <!-- Other sections (placeholders) -->
    <div class="section">
        <h2></h2>
        <p>.</p>
    </div>
	
	<div class="section">
    <h2>其他接口</h2>
    <ul>
        <li><a href="">1</a></li>
        <li><a href="">2</a></li>
        <li><a href="">3</a></li>
    </ul>
	</div>

    <div class="section">
        <h2></h2>
        <p>测试使用，如有问题请反馈.</p>
    </div>
</body>
</html>
