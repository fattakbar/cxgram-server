<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Request-With");
    header("Content-Type: application/json; charset=utf-8");

    include "library/config.php";
    include "library/function_validation.php";
    include "library/function_date.php";

    $post = json_decode(file_get_contents('php://input'), true);

    if(member_valid($post['username'], $post['password'])){
        if($post['aksi'] == "profil"){
            $query = mysqli_query($mysqli, "SELECT * FROM follow WHERE id_member='$post[member]' AND member_target='$post[target]'");
            $cek_follow = mysqli_num_rows($query);

            if($query) $result = json_encode(array('success'=>true, 'result'=>$cek_follow));
            else $result = json_encode(array('success'=>false));
            echo $result;
        }
    }


?>