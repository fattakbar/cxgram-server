<?php

    function member_valid($username, $password){
        global $mysqli;

        $query = mysqli_query($mysqli, "SELECT * FROM member WHERE username='$username' AND password='$password'");
        $jml = mysqli_num_rows($query);

        if($jml > 0) return true;
        else return false;
    }

?>