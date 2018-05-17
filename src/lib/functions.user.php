<?php
/**
 * User Functions
 */

function passwordCreate(string $pass)
{
    return password_hash($pass, PASSWORD_DEFAULT);
}

function passwordVerify(string $pass, string $hash)
{
    return password_verify($pass, $hash);
}