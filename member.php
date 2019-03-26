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
        }else{
            $msq = "Username Sudah Digunakan";
            $result = json_encode(array('success'=>false, 'msg'=>$msq));
        }
        echo $result;
    }else if($post['aksi'] == "login"){
        $password = md5($post['password']);
        $query = mysqli_query($mysqli, "SELECT * FROM member WHERE username='$post[username]' AND password='$password'");
        $jml = mysqli_num_rows($query);

        if($jml > 0){
            $data = mysqli_fetch_array($query);
            $photo = file_get_contents($data['photo']);
            $datamember = array(
                'id_member' => $data['id_member'],
                'nama'      => $data['name'],
                'email'     => $data['email'],
                'username'  => $data['username'],
                'password'  => $data['password'],
                'foto'      => base64_encode($photo)
            );

            if($query) $result = json_encode(array('success'=>true, 'result'=>$datamember));
            else $result = json_encode(array('success'=>false, 'msg'=>'Login Gagal'));
        }else{
            $result = json_encode(array('success'=>false, 'msg'=>'Akun Tidak Terdaftar'));
        }
        echo $result;
    }else if($post['aksi'] == "cari"){
        $data = array();

        if(member_valid($post['username'], $post['password'])){
            $query = mysqli_query($mysqli, "SELECT * FROM member WHERE name LIKE '%$post[kata]%' LIMIT $post[start], $post[limit]");

            while($row = mysqli_fetch_array($query)){
                $data[] = array(
                    'id_member' => $row['id_member'],
                    'foto'      => $row['photo'],
                    'nama'      => $row['name']
                );
            }

            if($query) $result = json_encode(array('success'=>true, 'result'=>$data));
            else $result = json_encode(array('success'=>false));
            echo $result;
        }
    }

?>