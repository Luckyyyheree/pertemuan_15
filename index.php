<?php
include 'koneksi.php';

$per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$where = $keyword != '' ? "WHERE nama LIKE '%$keyword%'" : '';

$data = mysqli_query($conn,
    "SELECT * FROM data_barang $where LIMIT $start, $per_page"
);

$total = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM data_barang $where")
)[0];

$total_page = ceil($total / $per_page);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Enterprise Inventory System</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

:root{
    --primary:#6366f1;
    --accent:#22d3ee;
    --bg:#0f172a;
    --glass:rgba(255,255,255,.08);
    --border:rgba(255,255,255,.15);
    --text:#e5e7eb;
    --muted:#94a3b8;
}

*{
    box-sizing:border-box;
    font-family:'Inter',system-ui;
}

body{
    margin:0;
    min-height:100vh;
    background:
        radial-gradient(circle at top left,#1e293b,#020617 60%);
    color:var(--text);
    overflow-x:hidden;
}

.background-blur{
    position:fixed;
    inset:0;
    background:
        radial-gradient(circle at 80% 20%,rgba(99,102,241,.25),transparent 40%),
        radial-gradient(circle at 20% 80%,rgba(34,211,238,.25),transparent 40%);
    filter:blur(120px);
    z-index:-1;
}

.wrapper{
    max-width:1100px;
    margin:80px auto;
    padding:0 20px;
}

.glass-card{
    background:var(--glass);
    backdrop-filter:blur(18px);
    border:1px solid var(--border);
    border-radius:22px;
    padding:34px;
    box-shadow:
        0 30px 80px rgba(0,0,0,.55),
        inset 0 0 0 1px rgba(255,255,255,.05);
    animation:fadeUp .8s remember;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(20px)}
    to{opacity:1; transform:none}
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
}

.header h1{
    font-size:26px;
    font-weight:700;
    letter-spacing:.4px;
}

.header span{
    font-size:13px;
    color:var(--muted);
}

.search{
    display:flex;
    gap:12px;
}

.search input{
    width:260px;
    padding:12px 16px;
    border-radius:12px;
    border:1px solid var(--border);
    background:rgba(0,0,0,.3);
    color:var(--text);
    outline:none;
}

.search button{
    padding:12px 22px;
    border-radius:12px;
    border:none;
    cursor:pointer;
    background:linear-gradient(135deg,var(--primary),var(--accent));
    color:#020617;
    font-weight:600;
}

table{
    width:100%;
    border-collapse:separate;
    border-spacing:0 12px;
}

thead th{
    font-size:12px;
    text-transform:uppercase;
    color:var(--muted);
    text-align:left;
    padding:0 16px;
}

tbody tr{
    background:rgba(255,255,255,.04);
    border-radius:14px;
    transition:.3s;
}

tbody tr:hover{
    background:rgba(255,255,255,.08);
    transform:scale(1.005);
}

tbody td{
    padding:16px;
}

tbody tr td:first-child{
    border-radius:14px 0 0 14px;
}

tbody tr td:last-child{
    border-radius:0 14px 14px 0;
}

.price{
    font-weight:700;
    color:#67e8f9;
}

.pagination{
    display:flex;
    justify-content:center;
    gap:10px;
    margin-top:40px;
}

.pagination a{
    padding:10px 16px;
    border-radius:12px;
    border:1px solid var(--border);
    color:var(--text);
    text-decoration:none;
    font-size:14px;
    background:rgba(0,0,0,.25);
    transition:.3s;
}

.pagination a:hover{
    background:linear-gradient(135deg,var(--primary),var(--accent));
    color:#020617;
    border-color:transparent;
}
</style>
</head>

<body>
<div class="background-blur"></div>

<div class="wrapper">
    <div class="glass-card">

        <div class="header">
            <div>
                <h1>Enterprise Inventory</h1>
                <span>High-End Management Interface</span>
            </div>
            <form class="search" method="get">
                <input type="text" name="keyword" placeholder="Search product..." value="<?= $keyword ?>">
                <button>Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="80">No</th>
                    <th>Product Name</th>
                    <th width="220">Price</th>
                </tr>
            </thead>
            <tbody>
            <?php $no=$start+1; while($row=mysqli_fetch_assoc($data)){ ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td class="price">Rp <?= number_format($row['harga'],0,',','.') ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if($page>1){ ?>
                <a href="?page=<?= $page-1 ?>&keyword=<?= $keyword ?>">‹ Prev</a>
            <?php } ?>
            <?php for($i=1;$i<=$total_page;$i++){ ?>
                <a href="?page=<?= $i ?>&keyword=<?= $keyword ?>"><?= $i ?></a>
            <?php } ?>
            <?php if($page<$total_page){ ?>
                <a href="?page=<?= $page+1 ?>&keyword=<?= $keyword ?>">Next ›</a>
            <?php } ?>
        </div>

    </div>
</div>

</body>
</html>
