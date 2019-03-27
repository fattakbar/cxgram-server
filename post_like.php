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
        $jml_like = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM post_like WHERE id_post='$post[idpost]' AND id_member='$post[member]'"));

        if($jml_like == 0){
            $query = mysqli_query($mysqli, "INSERT INTO post_like SET
                    id_member   = '$post[member]',
                    id_post     = '$post[idpost]'
            ");

            if($query) mysqli_query($mysqli, "INSERT INTO notification SET
                    id_member       = '$post[member]',
                    message         = 'menyukai kiriman anda',
                    member_target   = '$post[target]',
                    new             = 1,
                    id_post         = '$post[idpost]'
            ");

            $like = 1;
        }else{
            $query = mysqli_query($mysqli, "DELETE FROM post_like WHERE id_member='$post[member]' AND id_post='$post[idpost]'");
            $like = 0;
        }
        if($query) $result = json_encode(array('success'=>true, 'result'=>$like));
        else $result = json_encode(array('success'=>false));
        echo $result;
    }

?>