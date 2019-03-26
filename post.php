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
        if($post['aksi'] == "tampil"){
            $data = array();
            $query = mysqli_query($mysqli, "SELECT * FROM post LEFT JOIN member ON post.id_member=member.id_member WHERE post.id_member='$post[member]' OR post.id_member IN (SELECT member target FROM follow WHERE id_member='$post[member]') ORDER BY post.id_post DESC LIMIT $post[start],$post[limit]");

            while($row = mysqli_fetch_array($query)){
                $jml_like = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM post_like WHERE id_post='$row[id_post]' AND id_member='$post[member]'"));
                $jml_comment = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM comment WHERE id_post='$row[id_post]'"));
            }
        }
    }

?>