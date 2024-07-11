<?php
// $conn = mysqli_connect("localhost", "root", "", "ars_database") or die("เกิดข้อผิดพลาดเกิดขึ้น ไม่สามารถเชื่อมต่อฐานข้อมูลได้");
$conn = mysqli_connect("localhost", "cp009141_dbt", "Sakdar39814", "cp009141_ars") or die("เกิดข้อผิดพลาดเกิดขึ้น ไม่สามารถเชื่อมต่อฐานข้อมูลได้ T-T");
$conn->set_charset("utf8");

// วัน/เดือน/ปี ภาษาไทย
function datetime()
{
    // เดือนภาษาไทย
    $ThaiMonth = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

    // กำหนดคุณสมบัติ
    $months = date("m") - 1; // ค่าเดือน (1-12)
    $day = date("d"); // ค่าวันที่(1-31)
    $years = date("Y") + 543; // ค่า ค.ศ.บวก 543 ทำให้เป็น พ.ศ.

    return "$day $ThaiMonth[$months] $years";
}

// Generating Random
date_default_timezone_set('Asia/Bangkok');
$date1 = date("Ymd_His");
$numrand = (mt_rand());

$n = 10;
function getName($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

$cr_years = date("Y");
?>