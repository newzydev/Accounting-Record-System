<?php
require_once('connect/server.php');
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

if (isset($_REQUEST['btn_login'])) {

    // รับค่าที่ส่งมาจากฟอร์มลงในตัวแปร
    $email = mysqli_real_escape_string($conn, $_POST['email_login']);
    $password = mysqli_real_escape_string($conn, $_POST['password_login']);
    $passwordenc = md5($password);

    $query = "SELECT * FROM tbl_admin_db WHERE ad_email ='$email' AND ad_password ='$passwordenc'";
    $result = mysqli_query($conn, $query);

    // เช็คการป้อนข้อมูล
    if (empty($email) || empty($password)) {
        $errorMsg = "กรุณากรอกข้อมูล ที่มีเครื่องหมาย (*) ให้ครบทุกช่อง";
    }

    // เช็คล็อคอิน
    if (!isset($errorMsg)) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);

            $_SESSION['member_id'] = $row['ad_id'];

            $successMsg = "เข้าสู่ระบบสำเร็จ";
            header("location:index");
            
        } else {
            $errorMsg = "ที่อยู่อีเมลหรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่";
        }
    }
}

// เพิ่มข้อมูลลูกค้า
if (isset($_REQUEST['btn_addcustomer'])) {

    // รับค่าที่ส่งมาจากฟอร์มลงในตัวแปร
    $mb_url = getName($n);
    $mb_firstname = $_POST["firstname"];
    $mb_lastname = $_POST["lastname"];
    $mb_time_add = datetime();

    if (empty($mb_firstname) || empty($mb_lastname)) {
        $errorMsg = "กรุณากรอกข้อมูล ที่มีเครื่องหมาย (*) ให้ครบทุกช่อง";
    }

    // บันทึกข้อมูล
    if (!isset($errorMsg)) {
        $sql = "INSERT INTO tbl_member_db(mb_url, mb_firstname, mb_lastname, mb_time_add)
                VALUE('$mb_url', '$mb_firstname', '$mb_lastname', '$mb_time_add')";

        // สั่งรันคำสั่ง sql
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $successMsg = "บันทึกข้อมูลสำเร็จ";
            header("location:index");
        } else {
            echo mysqli_error($conn);
        }
    }

}

// ดึงข้อมูลจากฐานข้อมูลมาแสดงทั้งหมด
$search = isset($_POST['search_query']) ? $_POST['search_query'] : '';

$query_data = "SELECT * FROM tbl_member_db WHERE mb_firstname LIKE '%$search%' OR mb_lastname LIKE '%$search%' ORDER BY mb_id ASC";
$result_data = mysqli_query($conn, $query_data);
$count_data = mysqli_num_rows($result_data);
$order = 1;

// ออกจากระบบ
if (isset($_REQUEST['logout'])) {
    session_destroy();
    unset($_SESSION['member_id']);
    header('location:index');
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกรายงานบัญชี :: Accounting Record System</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="https://www.dbtlearning.com/ars/assete/images/banner/banner.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.dbtlearning.com/ars/assete/images/banner/banner.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://www.dbtlearning.com/ars/assete/images/banner/banner.png">

    <!-- Library -->
    <link rel="stylesheet" href="assete/css/bootstrap.min.css">
    <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.10.0/css/all.css">

    <!-- CSS Style -->
    <link rel="stylesheet" href="assete/css/style_main.css">

    <!-- Javascript -->
    <script src="assete/js/jquery-3.6.0.min.js"></script>
    <script src="assete/js/bootstrap.min.js"></script>
    <script src="assete/js/script_main.js"></script>
</head>

<body class="bg-light">

    <?php include('alert.php'); ?>

    <!-- Box -->
    <div class="blue_box">

        <!-- Wrapper -->
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="navbar-shadow navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #ffffff;">
                <div class="container">
                    <a class="navbar-brand">
                        <img src="assete/images/banner/banner_full.png" width="250" height="50" class="">
                    </a>
                    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar1">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div id="navbar1" class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto text-center">
                            <hr style="color: #000;">
                            <li class="nav-item">
                                <a href="index" class="nav-link " style="color: #000;">หน้าหลัก</a>
                            </li>
                            <div class="topbar-divider d-none d-sm-block"></div>
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

            <?php if (isset($member_id)) { ?>
            <div class="modal fade" id="loginComModal" tabindex="-1" aria-labelledby="loginComLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="loginComLabel" style="color: #4772f4;">แจ้งเตือนจากระบบ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-center">
                                <img src="assete/images/banner/banner_full.png" width="250" height="50">
                            </div>
                            <hr>
                            <h5 class="text-center">สวัสดี,&nbsp;&nbsp;<?php echo $acount['ad_firstname']; ?>&nbsp;&nbsp;<?php echo $acount['ad_lastname']; ?><br>" ยินดีต้อนรับเข้าสู่ระบบแอดมิน "</h5>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <!-- Login Modal -->
            <form action="" method="post">
                <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="loginModalLabel" style="color: #4772f4;"><i class="fas fa-user-alt"></i> ฟอร์มเข้าสู่ระบบแอดมิน</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label for="email_login" class="col-form-label">ที่อยู่อีเมล์ :</label>
                                        <input type="email" class="form-control" name="email_login" id="email_login" placeholder="E-Mail" autocomplete="off">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_login" class="col-form-label">รหัสผ่าน :</label>
                                        <input type="password" class="form-control" name="password_login" id="password_login" placeholder="Password">
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <img src="assete/images/banner/banner_full.png" width="250" height="50">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="btn_login" class="btn btn-blue"><i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบแอดมิน</button>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php } ?>

            <div class="mt-4 mb-4 pt-4"></div>
            <!-- End Navbar -->

            <!-- Member Report -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 px-3 pt-3">
                        <?php if (isset($member_id)) { ?>
                        <div class="d-flex justify-content-between">
                            <p class="text-white m-0">ประเภทบัญชีผู้ใช้</p>
                            <p class="text-white m-0"><i class="far fa-user mx-2"></i>บัญชีผู้ใช้ - แอดมิน</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div></div>
                            <p class="text-white">เข้าสู่ระบบเมื่อ - <?php echo datetime(); ?></p>
                        </div>
                        <?php } else { ?>
                        <div class="d-flex justify-content-between">
                            <p class="text-white m-0">ประเภทบัญชีผู้ใช้</p>
                            <p class="text-white m-0"><i class="far fa-user mx-2"></i>บัญชีผู้ใช้ - ผู้ใช้ทั่วไป</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div></div>
                            <p class="text-white">เข้าสู่ระบบเมื่อ - <?php echo datetime(); ?></p>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-sm-12 px-3 pt-4">
                        <?php if (isset($member_id)) { ?>
                        <h1 class="pl-4 mb-1" style="color: #aedcf5;font-weight: 400;">สวัสดี<strong class="text-light mx-3"><span class=""><?php echo $acount['ad_firstname']; ?></span><span class="text-light mx-3 "><?php echo $acount['ad_lastname']; ?></span></strong></h1>
                        <?php } else { ?>
                        <h1 class="pl-4 mb-1" style="color: #aedcf5;font-weight: 400;">สวัสดี<strong class="text-light mx-3"><span class="">แอดมิน</span></strong></h1>
                        <?php } ?>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-4 p-3">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h5 style="color: #4772f4;"><strong>ข้อมูลบัญชีลูกค้า</strong></h5>
                                </div>
                                <!-- Desktop -->
                                <p class="d-none d-sm-block mb-5">คุณสามารถเพิ่มข้อมูลบัญชีลูกค้าได้</p>
                                <!-- Mobile -->
                                <p class="d-sm-none d-sm-block">คุณสามารถเพิ่มข้อมูลบัญชีลูกค้าได้</p>
                                <?php if (isset($member_id)) { ?>
                                <div class="d-grid">
                                    <!-- Desktop -->
                                    <!-- <button class="btn btn-blue d-none d-sm-block mt-3" onclick="window.print()">พิมพ์ทั้งหมด</button> -->
                                    <button class="btn btn-blue d-none d-sm-block mt-3" data-bs-toggle="modal" data-bs-target="#addCustomer">เพิ่มบัญชีลูกค้า</button>
                                    <!-- Mobile -->
                                    <button class="btn btn-blue d-sm-none d-sm-block" data-bs-toggle="modal" data-bs-target="#addCustomer">เพิ่มบัญชีลูกค้า</button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal fade" id="addCustomer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addCustomerModalLabel" style="color: #4772f4;"><i class="fas fa-user-alt"></i> ฟอร์มเพิ่มบัญชีลูกค้า</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="mb-3">
                                                            <label for="firstname" class="col-form-label">ชื่อจริง (ภาษาไทย) : </label>
                                                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" autocomplete="off">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="lastname" class="col-form-label">นามสกุล (ภาษาไทย) : </label>
                                                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" autocomplete="off">
                                                        </div>
                                                        <div class="d-flex justify-content-center">
                                                            <img src="assete/images/banner/banner_full.png" width="250" height="50">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" name="btn_addcustomer" class="btn btn-blue"><i class="fas fa-user-plus"></i> เพิ่มบัญชีลูกค้า</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php } else { ?>
                                <div class="d-grid">
                                    <!-- Desktop -->
                                    <button class="btn btn-blue d-none d-sm-block mt-3" disabled>เพิ่มบัญชีลูกค้า</button>
                                    <!-- Mobile -->
                                    <button class="btn btn-blue d-sm-none d-sm-block" disabled>เพิ่มบัญชีลูกค้า</button>
                                </div>
                                <?php } ?>
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
                                    <h5 class="d-none d-sm-block m-0" style="color: #4772f4;"><strong>ตารางฐานข้อมูลลูกค้า</strong></h5>
                                    <!-- Mobile Device -->
                                    <h5 class="d-sm-none d-sm-block text-center m-0" style="color: #4772f4;"><strong>ตารางฐานข้อมูลลูกค้า</strong></h5>
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
                                            <div class="col-sm-10 mb-3">
                                                <input type="search" class="form-control" name="search_query" placeholder="ชื่อจริง หรือ นามสกุล" value="<?php echo isset($search) ? $search : '' ?>" required autocomplete="off">
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-blue mb-3"><i class="fas fa-search"></i> ค้นหาลูกค้า</button>
                                                </div>
                                            </div>
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
                                                        <th>ชื่อจริง</th>
                                                        <th>นามสกุล</th>
                                                        <th>วันที่เพิ่ม</th>
                                                        <th>รายการบัญชีล่าสุด</th>
                                                        <th>จำนวนเงินคงเหลือ</th>
                                                        <th class="text-center">ดูรายการบัญชี</th>
                                                    </tr>
                                                </thead>
                                                <tbody><?php while ($row = mysqli_fetch_assoc($result_data)) { ?>

                                                    <tr valign="middle" class="text-nowrap">
                                                        <td class=" text-center"><?php echo number_format($order++) ?></td>
                                                        <td class=""><?php echo $row['mb_firstname']; ?></td>
                                                        <td class=""><?php echo $row['mb_lastname']; ?></td>
                                                        <td class=""><?php echo $row['mb_time_add']; ?></td>
                                                        <td class=""><?php
                                                            $mb_url = $row['mb_url'];
                                                            $query_data_con_info = "SELECT * FROM tbl_report_db as r
                                                            INNER JOIN tbl_member_db as m ON r.mb_url=m.mb_url 
                                                            WHERE mb_token LIKE '%$mb_url%'
                                                            ORDER BY r.rp_id DESC LIMIT 1";
                                                            $result_data_con_display = mysqli_query($conn, $query_data_con_info);
                                                            $result_latest = mysqli_fetch_assoc($result_data_con_display);
                                                            echo $result_latest['rp_time_add'];
                                                            ?></td>
                                                        <td class=""><?php
                                                            $mb_url = $row['mb_url'];
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
                                                            $resoult = $rp_cash_out - $rp_cash_in; echo "฿". number_format($resoult,2) ." บาท";
                                                            ?></td>
                                                        <td class="text-center">
                                                            <a href="report/<?php echo $row['mb_url']; ?>" class="btn btn-sm btn-success"><i class="fas fa-desktop"></i> ดูรายการบัญชี</a>
                                                        </td>
                                                    </tr><?php } ?>

                                                </tbody>
                                                <tfoot class="">
                                                    <tr class="background-blue">
                                                        <th class="text-center">ลำดับที่</th>
                                                        <th>ชื่อจริง</th>
                                                        <th>นามสกุล</th>
                                                        <th>วันที่เพิ่ม</th>
                                                        <th>รายการบัญชีล่าสุด</th>
                                                        <th>จำนวนเงินคงเหลือ</th>
                                                        <th class="text-center">ดูรายการบัญชี</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
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
    countDownBox.innerHTML = "<span>เซิร์ฟเวอร์ได้หมดอายุ ขออภัยในความไม่สะดวก ...</span>";
    }
    }, 1000);
    </script>
</body>

</html>