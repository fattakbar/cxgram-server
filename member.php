<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Request-With");
    header("Content-Type: application/json; charset=utf-8");

    include "library/config.php";
    include "library/function_validation.php";

    $post = json_decode(file_get_contents('php://input'), true);
    // $post = array('username'=>'akbar', 'password'=>'akbar', 'aksi'=>'edit');
    if($post['aksi'] == "daftar"){
        $password = md5($post['password']);

        $jml = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM member WHERE username='$post[username]'"));
        echo $mysqli_error($mysqli);

        if($jml < 1){
            $query = mysqli_query($mysqli, "INSERT INTO member SET
                    name        = '$post[nama]',
                    email       = '$post[email]',
                    username    = '$post[username]',
                    password    = '$post[password]',
                    $photo      = 'images/member/member.jpg'
            ");

            if($query) $result = json_encode(array('success'=>true));
            else $result = json_encode(array('success'=>false, 'msg'=>'Tidak Dapat Menyimpan Data'));
        }
    }

?>