<?php
require_once('../connect/server.php');
session_start();

// เช็คเซสชั่น
if (isset($_SESSION['member_id'])) {
    // รับค่ามาจากเซสชั่น
    $member_id = $_SESSION['member_id'];

    // เช็คค่าที่ส่งมาจากเซสชั่น
    $query = "SELECT * FROM tbl_admin_db WHERE ad_id ='$member_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $acount = mysqli_fetch_array($result);
    }
}

// ดึงข้อมูลจากฐานข้อมูลมาแสดง
$mb_url = $_GET['mb_url'];
$query_data_con = "SELECT * FROM tbl_member_db WHERE mb_url = '$mb_url'";
$result_data_con = mysqli_query($conn, $query_data_con);
$result_report = mysqli_fetch_assoc($result_data_con);

$search = isset($_POST['search_query']) ? $_POST['search_query'] : '';
$query_data_con_q = "SELECT * FROM tbl_report_db as r
                    INNER JOIN tbl_member_db as m ON r.mb_url=m.mb_url 
                    WHERE mb_token LIKE '%$mb_url%' AND rp_time_add LIKE '%$search%'
                    ORDER BY r.rp_id DESC";
$result_data_con_q = mysqli_query($conn, $query_data_con_q);
$count_data = mysqli_num_rows($result_data_con_q);
$order = 1;

$query_data_con_p1 = "SELECT * FROM tbl_report_db as r
                    INNER JOIN tbl_member_db as m ON r.mb_url=m.mb_url 
                    WHERE mb_token LIKE '%$mb_url%'
                    ORDER BY r.rp_id ASC";
$result_data_con_re = mysqli_query($conn, $query_data_con_p1);
$rp_cash_out = 0;
while( $f = mysqli_fetch_assoc($result_data_con_re)) {
    $rp_cash_out += $f['rp_cash_out'];
}

$query_data_con_p2 = "SELECT * FROM tbl_report_db as r
                    INNER JOIN tbl_member_db as m ON r.mb_url=m.mb_url 
                    WHERE mb_token LIKE '%$mb_url%'
                    ORDER BY r.rp_id ASC";
$result_data_con_re = mysqli_query($conn, $query_data_con_p2);
$rp_cash_in = 0;
while( $f = mysqli_fetch_assoc($result_data_con_re)) {
    $rp_cash_in += $f['rp_cash_in'];
}

$query_data_con_info = "SELECT * FROM tbl_report_db as r
                    INNER JOIN tbl_member_db as m ON r.mb_url=m.mb_url 
                    WHERE mb_token LIKE '%$mb_url%'
                    ORDER BY r.rp_id DESC LIMIT 1";
$result_data_con_display = mysqli_query($conn, $query_data_con_info);
$result_latest = mysqli_fetch_assoc($result_data_con_display);

// เพิ่มข้อมูลลูกค้า
if (isset($_REQUEST['btn_addlist'])) {

    // รับค่าที่ส่งมาจากฟอร์มลงในตัวแปร
    $rp_url = getName($n);
    $mb_url_ = $mb_url;
    $mb_token = $mb_url;
    $rp_cash_out = $_POST["cash_out"];
    $rp_cash_in = $_POST["cash_in"];
    $rp_note = $_POST["note"];
    $rp_time_add = $_POST["datetime"];

    if (empty($rp_time_add)) {
        $errorMsg = "กรุณากรอกข้อมูล วัน/เดือน/ปี โอนเงิน";
    }

    // บันทึกข้อมูล
    if (!isset($errorMsg)) {
        $sql = "INSERT INTO tbl_report_db(rp_url, mb_url, mb_token, rp_cash_out, rp_cash_in, rp_note, rp_time_add)
                VALUE('$rp_url', '$mb_url_', '$mb_token', '$rp_cash_out', '$rp_cash_in', '$rp_note', '$rp_time_add')";

        // สั่งรันคำสั่ง sql
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $successMsg = "บันทึกข้อมูลสำเร็จ";
            header("location:../report/$mb_url");
        } else {
            echo mysqli_error($conn);
        }
    }

}

// ออกจากระบบ
if (isset($_REQUEST['logout'])) {
    session_destroy();
    unset($_SESSION['member_id']);
    header('location:../index');
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="refresh" content="900">
    <meta property="og:title" content="สวัสดี, คุณ <?php echo $result_report['mb_firstname']; ?> <?php echo $result_report['mb_lastname']; ?> :: ระบบบันทึกรายงานบัญชี :: Accounting Record System">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image" content="https://www.dbtlearning.com/ars/assete/images/banner/banner_link.png">
    <meta property="og:url" content="https://www.dbtlearning.com/ars/report/<?php echo $mb_url; ?>">
    <meta property="og:site_name" content="https://www.dbtlearning.com/ars/">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="th_TH">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:description" content="ระบบบันทึกรายงานบัญชี :: Accounting Record System">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:domain" content="https://www.dbtlearning.com/ars/report/<?php echo $mb_url; ?>">
    <meta name="twitter:title" content="สวัสดี, คุณ <?php echo $result_report['mb_firstname']; ?> <?php echo $result_report['mb_lastname']; ?> :: ระบบบันทึกรายงานบัญชี :: Accounting Record System">
    <meta name="twitter:image" content="https://www.dbtlearning.com/ars/assete/images/banner/banner_link.png">
    <meta name="description" content="ระบบบันทึกรายงานบัญชี :: Accounting Record System">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สวัสดี, คุณ <?php echo $result_report['mb_firstname']; ?> <?php echo $result_report['mb_lastname']; ?> :: ระบบบันทึกรายงานบัญชี :: Accounting Record System</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="https://www.dbtlearning.com/ars/assete/images/banner/banner.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.dbtlearning.com/ars/assete/images/banner/banner.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://www.dbtlearning.com/ars/assete/images/banner/banner.png">

    <!-- Library -->
    <link rel="stylesheet" href="../assete/css/bootstrap.min.css">
    <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.10.0/css/all.css">

    <!-- CSS Style -->
    <link rel="stylesheet" href="../assete/css/style_main.css">

    <!-- Javascript -->
    <script src="../assete/js/jquery-3.6.0.min.js"></script>
    <script src="../assete/js/bootstrap.min.js"></script>
    <script src="../assete/js/script_main.js"></script>
</head>

<body class="bg-light">

    <?php include('../alert.php'); ?>
    
    <!-- Box -->
    <div class="blue_box">

        <!-- Wrapper -->
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="navbar-shadow navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #ffffff;">
                <div class="container">
                    <a class="navbar-brand">
                        <img src="../assete/images/banner/banner_full.png" width="250" height="50" class="">
                    </a>
                    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar1">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="navbar1" class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto text-center">
                            <hr style="color: #000;">
                            <?php if (isset($member_id)) { ?>
                            <li class="nav-item">
                                <a href="../index" class="nav-link " style="color: #000;">หน้าหลัก</a>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <?php } else { ?>
                            <li class="nav-item">
                                <a href="../report/<?php echo $result_report['mb_url']; ?>" class="nav-link " style="color: #000;">หน้าหลัก</a>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <?php } ?>
                            <li class="nav-item">
                                <a class="nav-link " style="color: #000;cursor: pointer;">เกี่ยวกับเรา</a>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <li class="nav-item">
                                <a class="nav-link " style="color: #000;cursor: pointer;">นโยบายคุ้มครองข้อมูลส่วนบุคคล</a>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <li class="nav-item">
                                <div class="d-grid">
                                    <a href="https://www.facebook.com/sakdar.sukkhwan/" class="btn btn-blue" target="_blank"><i class="fas fa-mail-bulk"></i> ติดต่อเรา</a>
                                </div>
                            </li>
                            <?php if (isset($member_id)) { ?>
                            <div class="topbar-divider d-none d-sm-block"></div>
                            <li class="nav-item">
                                <form action="" method="post">
                                    <div class="d-grid">
                                        <button type="submit" name="logout" class="btn btn-blue"><i class="fas fa-sign-in-alt"></i> ออกจากระบบ</button>
                                    </div>
                                </form>
                            </li>
                            <?php } else { ?>

                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="mt-4 mb-4 pt-4"></div>
            <!-- End Navbar -->

            <!-- Member Report -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-3 pt-3">
                        <div class="d-flex justify-content-between">
                            <p class="text-white m-0">ประเภทบัญชีผู้ใช้</p>
                            <p class="text-white m-0"><i class="far fa-user mx-2"></i>บัญชีผู้ใช้ - ลูกค้า</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div></div>
                            <p class="text-white">เข้าดูเมื่อ - <?php echo datetime(); ?></p>
                        </div>
                    </div>
                    <div class="col-sm-12 px-3 pt-4">
                        <h1 class="pl-4 mb-1 one-text" style="color: #aedcf5;font-weight: 400;">สวัสดี<strong class="text-light mx-3"><span class=""><?php echo $result_report['mb_firstname']; ?></span><span class="text-light mx-3 "><?php echo $result_report['mb_lastname']; ?></span></strong></h1>
                    </div>
                    <div class="col-sm-12 pt-1 d-none d-sm-block"></div>
                    <div class="col-sm-12 pt-5 d-none d-sm-block"></div>
                    <div class="col-sm-12 px-5"></div>
                    <div class="col-sm-12 col-md-12 col-lg-8 p-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div>
                                            <h5 style="color: #4772f4;"><strong>ข้อมูลบัญชีผู้ใช้</strong></h5>
                                        </div>
                                    </div>
                                    <div class="mb-1"></div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between">
                                            <div><strong>ชื่อจริง : </strong></div>
                                            <div class=""><?php echo $result_report['mb_firstname']; ?><br></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between">
                                            <div><strong>นามสกุล : </strong></div>
                                            <div class=""><?php echo $result_report['mb_lastname']; ?><br></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between">
                                            <div><strong>รายการเงินยืมล่าสุด : </strong></div>
                                            <div class=""><?php echo number_format($result_latest['rp_cash_out'],2); ?> บาท<br></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between">
                                            <div><strong>รายการเงินคืนล่าสุด : </strong></div>
                                            <div class=""><?php echo number_format($result_latest['rp_cash_in'],2); ?> บาท<br></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between">
                                            <div><strong>ลงทะเบียนเมื่อ : </strong></div>
                                            <div class=""><?php echo $result_report['mb_time_add']; ?><br></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between">
                                            <div><strong>รายการบัญชีล่าสุด : </strong></div>
                                            <div class=""><?php echo $result_latest['rp_time_add']; ?><br></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mt-3">
                                            <input type="text" class="form-control text-center" value="รหัสลูกค้า :: <?php echo $result_report['mb_url']; ?>" disabled readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-4 p-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="mb-3">
                                    <h5 style="color: #4772f4;"><strong>ข้อมูลสรุปรายงานบัญชี</strong></h5>
                                </div>
                                <div class="table-responsive-lg">
                                    <table class="table table-striped bg-light m-0">
                                        <tbody>
                                            <tr>
                                                <td>ยอดรวมเงินยืม :</td>
                                                <td class="text-end text-danger">฿<?php echo number_format($rp_cash_out,2); ?> บาท</td>
                                            </tr>
                                            <tr>
                                                <td>ยอดรวมเงินคืน :</td>
                                                <td class="text-end text-success">฿<?php echo number_format($rp_cash_in,2); ?> บาท</td>
                                            </tr>
                                            <tr>
                                                <td>ยอดสุทธิ :</td>
                                                <td class="text-end text-primary">฿<?php $resoult = $rp_cash_out - $rp_cash_in; echo number_format($resoult,2); ?> บาท</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($errorMsg)) {
                    ?>
                    <div class="col-sm-12 pt-3 px-3">
                        <div class="mb-3">
                            <div class="alert alert-danger text-center" role="alert">
                                <?php echo $errorMsg; ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php
                    if (isset($successMsg)) {
                    ?>
                    <div class="col-sm-12 pt-3 px-3">
                        <div class="mb-3">
                            <div class="alert alert-success text-center" role="alert">
                                <?php echo $successMsg; ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-sm-12 pt-3 px-3">
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Desktop Device -->
                                    <h5 class="d-none d-sm-block m-0" style="color: #4772f4;"><strong>ตารางฐานข้อมูลรายงานบัญชี</strong></h5>
                                    <!-- Mobile Device -->
                                    <h5 class="d-sm-none d-sm-block text-center m-0" style="color: #4772f4;"><strong>ตารางฐานข้อมูลรายงานบัญชี</strong></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 pt-3 px-3">
                        <div class="mb-3">
                            <div class="card">
                                <div class="card-body">

                                    <form action="" method="post">
                                        <div class="row">
                                            <?php if (isset($member_id)) { ?>
                                            <div class="col-sm-8 mb-3">
                                                <input type="search" class="form-control" name="search_query" placeholder="วัน/เดือน/ปี ที่โอนเงิน" value="<?php echo isset($search) ? $search : '' ?>" required autocomplete="off">
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="d-grid">
                                                    <a class="btn btn-blue mb-3" data-bs-toggle="modal" data-bs-target="#addList"><i class="fas fa-plus"></i> เพิ่มรายการ</a>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-blue mb-3"><i class="fas fa-search"></i> ค้นหารายการ</button>
                                                </div>
                                            </div>
                                            <?php } else { ?>
                                            <div class="col-sm-10 mb-3">
                                                <input type="search" class="form-control" name="search_query" placeholder="วัน/เดือน/ปี ที่โอนเงิน" value="<?php echo isset($search) ? $search : '' ?>" required autocomplete="off">
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-blue mb-3"><i class="fas fa-search"></i> ค้นหารายการ</button>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="table-responsive-lg">
                                            <table class="table table-striped table-hover bg-light m-0">
                                                <caption class="pb-0">
                                                    <div class="d-flex justify-content-between">
                                                        <div>เร็คคอร์ดทั้งหมด <?php echo number_format($count_data) ?> เร็คคอร์ด</div>
                                                        <div>ข้อมูล ณ วันที่ <?php echo datetime(); ?></div>
                                                    </div>
                                                </caption>
                                                <thead class="">
                                                    <tr class="background-blue text-nowrap">
                                                        <th class="text-center">ลำดับที่</th>
                                                        <th>รายการเงินยืม</th>
                                                        <th>รายการเงินคืน</th>
                                                        <th>วัน/เดือน/ปี เวลาโอน</th>
                                                        <th>ชื่อจริง</th>
                                                        <th>นามสกุล</th>
                                                        <th class="text-center">หมายเหตุ</th>
                                                    </tr>
                                                </thead>
                                                <tbody><?php while ($row = mysqli_fetch_assoc($result_data_con_q)) { ?>

                                                    <tr valign="middle" class="text-nowrap">
                                                        <td class=" text-center"><?php echo number_format($order++) ?></td>
                                                        <td class=""><div class="text-danger">฿<?php $cash_out = $row['rp_cash_out']; if (!empty($cash_out)) { echo number_format($row['rp_cash_out'],2); } else { echo number_format(0,2);} ?> บาท</div></td>
                                                        <td class=""><div class="text-success">฿<?php $cash_in = $row['rp_cash_in']; if (!empty($cash_in)) { echo number_format($row['rp_cash_in'],2); } else { echo number_format(0,2);} ?> บาท</div></td>
                                                        <td class=""><?php echo $row['rp_time_add']; ?></td>
                                                        <td class=""><?php echo $row['mb_firstname']; ?></td>
                                                        <td class=""><?php echo $row['mb_lastname']; ?></td>
                                                        <td class="text-center"><?php $notic = $row['rp_note']; if (!empty($notic)) { echo $notic; } else { echo "-";} ?></td>
                                                    </tr><?php } ?>

                                                </tbody>
                                                <tfoot class="">
                                                    <tr class="background-blue text-nowrap">
                                                        <th class="text-center">ลำดับที่</th>
                                                        <th>รายการเงินยืม</th>
                                                        <th>รายการเงินคืน</th>
                                                        <th>วัน/เดือน/ปี เวลาโอน</th>
                                                        <th>ชื่อจริง</th>
                                                        <th>นามสกุล</th>
                                                        <th class="text-center">หมายเหตุ</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </form>
                                    <form action="" method="post">
                                        <div class="modal fade" id="addList" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addListModalLabel" style="color: #4772f4;"><i class="fas fa-th-list"></i> ฟอร์มเพิ่มรายการ</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="mb-3">
                                                                <label for="cash_out" class="col-form-label">จำนวนเงินออก (ไม่มีให้ปล่อยว่าง) : </label>
                                                                <input type="text" class="form-control" name="cash_out" id="cash_out" placeholder="Cash Out" autocomplete="off">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="cash_in" class="col-form-label">จำนวนเงินเข้า (ไม่มีให้ปล่อยว่าง) : </label>
                                                                <input type="text" class="form-control" name="cash_in" id="cash_in" placeholder="Cash In" autocomplete="off">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="datetime" class="col-form-label">วัน/เดือน/ปี เวลาโอน (ตัวอย่าง 09 กันยายน 2565) : </label>
                                                                <input type="text" class="form-control" name="datetime" id="datetime" placeholder="Date Time" autocomplete="off">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="note" class="col-form-label">หมายเหตุ (ไม่มีให้ปล่อยว่าง) :</label>
                                                                <input type="text" class="form-control" name="note" id="note" placeholder="Note" autocomplete="off">
                                                            </div>
                                                            <div class="d-flex justify-content-center">
                                                                <img src="../assete/images/banner/banner_full.png" width="250" height="50">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="btn_addlist" class="btn btn-blue"><i class="fas fa-user-plus"></i> เพิ่มรายการ</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 pt-5"></div>
                    <div class="col-sm-12 pt-5"></div>       
                </div>
            </div>
            <!-- End Member Report -->

            <!-- Footer -->
            <div class="footer fixed-bottom">
                <div class="footer_copy">
                    <div class="allTime one-text">
                        <span>เซิร์ฟเวอร์หมดอายุใน : </span><br>
                        [<span class="days"></span>
                        <span class="hrs"></span>
                        <span class="min"></span>
                        <span class="sec"></span>]
                    </div>
                </div>
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Wrapper -->

    </div>
    <!-- End Box -->
    
    <!-- Count down -->
    <script>
    let countDownBox = document.querySelector(".allTime");
    let daysBox	= document.querySelector(".days");
    let hrsBox = document.querySelector(".hrs");
    let minBox = document.querySelector(".min");
    let secBox = document.querySelector(".sec");
    let countDownDate = new Date("Nov 20, 2023 23:59:59").getTime();
    let x = setInterval(function() {
    let now = new Date().getTime();
    let distance = countDownDate - now;
    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);
    daysBox.innerHTML = days + " วัน";
    hrsBox.innerHTML = hours + " ชั่วโมง";
    minBox.innerHTML = minutes + " นาที";
    secBox.innerHTML = seconds + " วินาที";
    if (distance < 0) {
    clearInterval(x);
    countDownBox.innerHTML = "<span>เซิร์ฟเวอร์ได้หมดอายุ ขออภัยในความไม่สะดวก.</span>";
    }
    }, 1000);
    </script>
</body>

</html>