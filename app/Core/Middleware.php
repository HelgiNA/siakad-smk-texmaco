<?php

function authMiddleware()
{
    if (! isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
}

function dd($data)
{
    echo "<pre>";
    die(var_dump($data));
}