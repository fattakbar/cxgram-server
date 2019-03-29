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
            $query = mysqli_query($mysqli, "SELECT * FROM comment LEFT JOIN member ON comment.id_member=member.id_member WHERE id_post='$post[idpost]' ORDER BY comment.id_comment");

            while($row = mysqli_fetch_array($query)){
                $data[] = array(
                    'id'        => $row['id_comment'],
                    'id_member' => $row['id_member'],
                    'nama'      => $row['name'],
                    'komentar'  => $row['comment'],
                    'tanggal'   => tgl_indonesia($row['created_at'])
                );
            }
            if($query) $result = json_encode(array('success'=>true, 'result'=>$data));
            else $result = json_encode(array('success'=>false));
            echo $result;
        }
    }

?>