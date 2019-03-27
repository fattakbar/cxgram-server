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
            $query = mysqli_query($mysqli, "SELECT * FROM post LEFT JOIN member ON post.id_member=member.id_member WHERE post.id_member='$post[member]' OR post.id_member IN (SELECT member_target FROM follow WHERE id_member='$post[member]') ORDER BY post.id_post DESC LIMIT $post[start],$post[limit]");

            while($row = mysqli_fetch_array($query)){
                $jml_like = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM post_like WHERE id_post='$row[id_post]' AND id_member='$post[member]'"));
                $jml_comment = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM comment WHERE id_post='$row[id_post]'"));

                $data[] = array(
                    'id'           => $row['id_post'],
                    'id_member'    => $row['id_member'],
                    'foto'         => $row['photo'],
                    'nama'         => $row['name'],
                    'post'         => $row['post'],
                    'gambar'       => $row['image'],
                    'suka'         => $jml_like,
                    'jml_komentar' => $jml_comment,
                    'tanggal'      => tgl_indonesia($row['created_at'])
                );
            }

            if($query) $result = json_encode(array('success'=>true, 'result'=>$data));
            else $result = json_encode(array('success'=>false));
            echo $result;
        }else if($post['aksi'] == "single"){
            $data = array();
            $query = mysqli_query($mysqli, "SELECT * FROM post LEFT JOIN member ON post.id_member=member.id_member WHERE post.id_post='$post[idpost]'");
            $row = mysqli_fetch_array($query);
            $jml_like = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM post_like WHERE id_post='$row[id_post]' AND id_member='$post[member]'"));
            $jml_comment = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM comment WHERE id_post='$row[id_post]'"));

            $data[] = array(
                'id'           => $row['id_post'],
                'id_member'    => $row['id_member'],
                'foto'         => $row['photo'],
                'nama'         => $row['name'],
                'post'         => $row['post'],
                'gambar'       => $row['image'],
                'suka'         => $jml_like,
                'jml_komentar' => $jml_comment,
                'tanggal'      => tgl_indonesia($row['created_at'])
            );
            if($query) $result = json_encode(array('success'=>true, 'result'=>$data));
            else $result = json_encode(array('success'=>false));
            echo $result;
        }else if($post['aksi'] == "profil"){
            $data = array();
            $query = mysqli_query($mysqli, "SELECT * FROM post WHERE id_member='$post[target]' ORDER BY id_post DESC");

            while($row = mysqli_fetch_array($query)){
                $data[] = array(
                    'id'     => $row['id_post'],
                    'gambar' => $row['image']
                );
            }
            $profil = array();
            $member = mysqli_fetch_array(mysqli_query($mysqli, "SELECT * FROM member WHERE id_member='$post[target]'"));
            $jmlpost = mysqli_num_rows($query);
            $jmlfollow = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM follow WHERE id_member='$post[target]'"));
            $jmlfollower = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM follow WHERE member_target='$post[target]'"));

            $profil[] = array(
                'foto'          => $member['photo'],
                'nama'          => $member['name'],
                'jmlpost'       => $jmlpost,
                'jmlfollow'     => $jmlfollow,
                'jmlfollower'   => $jmlfollower
            );

            if($query) $result = json_encode(array('success'=>true, 'profile'=>$profil, 'result'=>$data));
            else $result = json_encode(array('success'=>false));
            echo $result;
        }else if($post['aksi'] == "tambah"){
            //add Create Action
        }
    }

?>