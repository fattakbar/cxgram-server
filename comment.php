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
        }else if($post['aksi'] == "tambah"){
            $member = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM member WHERE username='$post[username]' AND password='$post[password]'"));

            $query = mysqli_query($mysqli, "INSERT INTO comment SET
                    id_post     = '$post[idpost]',
                    id_member   = '$member[id_member]',
                    comment     = '$post[komentar]'
            ");
            $tanggal = date('Y-m-d');
            $data = array(
                'id'            => mysqli_insert_id($mysqli),
                'id_member'     => $member['id_member'],
                'nama'          => $member['name'],
                'komentar'      => $post['komentar'],
                'tanggal'       => tgl_indonesia($tanggal)
            );

            if($query) mysqli_query($mysqli, "INSERT INTO notification SET
                    id_member       = '$member[id_member]',
                    message         = 'mengomentari kiriman anda',
                    member_target   = '$post[target]',
                    new             = 1,
                    id_post         = '$post[idpost]'
            ");
            if($query) $result = json_encode(array('success'=>true, 'result'=>$data));
            else $result = json_encode(array('success'=>false));
            echo $result;
        }
    }

?>