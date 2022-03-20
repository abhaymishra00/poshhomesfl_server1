<?php

// connect to database
global $con;
$con =  mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

function query($sql)
{
    global $con;
    return mysqli_query($con, $sql);
}

function fetch_all_data($sql, $error = null)
{
    global $con;
    $res = mysqli_query($con, $sql);

    if ($res) {

        if (mysqli_num_rows($res) > 0) {

            $data = [];
            while ($a = mysqli_fetch_assoc($res)) {
                array_push($data, $a);
            }

            // return an associative array
            return $data;
        } else {
            if ($error != null) {
                $error('no record found.');
            }
        }
    } else {
        if ($error != null) {
            $error(mysqli_error($con));
        }
    }
}

function fetch_data($sql, $error = null)
{
    global $con;
    $res = mysqli_query($con, $sql);

    if ($res) {

        if (mysqli_num_rows($res) > 0) {

            // return an associative array
            return mysqli_fetch_assoc($res);
        } else {
            if ($error != null) {
                $error('no record found.');
            }
        }
    } else {
        if ($error != null) {
            $error(mysqli_error($con));
        }
    }
}

function row_exists($sql)
{
    global $con;

    $res = mysqli_query($con, $sql);
    if ($res) {
        $count = mysqli_num_rows($res);

        if ($count > 0) {
            return true;
        }
    }
    return false;
}
