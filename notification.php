<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Request-With");
    header("Content-Type: application/json; charset=utf-8");

    include "library/config.php";
    include "library/function_validation.php";

    $post = json_decode(file_get_contents('php://input'), true);

    if(member_valid($post['username'], $post['password'])){
        $data = array();
        $query = mysqli_query($mysqli, "SELECT * FROM notification LEFT JOIN member ON notification.id_member=member.id_member WHERE member_target='$post[member]' ORDER BY notification DESC");

        while($row = mysqli_fetch_array($query)){
            $data[] = array(
                'id'            => $row['id_notification'],
                'id_member'     => $row['id_member'],
                'nama'          => $row['name'],
                'notifikasi'    => $row['message'],
                'baru'          => $row['new'],
                'target'        => $row['member_target'],
                'idpost'        => $row['id_post'],
                'tanggal'       => tgl_indonesia($row['created_at'])
            );

        }
    }

?>