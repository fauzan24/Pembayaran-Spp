<?php

$koneksi = mysqli_connect('localhost','root','','dbspp');

if(!$koneksi){
    echo"koneksi anda gagal";
}