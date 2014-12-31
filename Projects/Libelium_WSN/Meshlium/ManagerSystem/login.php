<?php
/*
 *  Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *  http://www.libelium.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Version 0.1
 *  Author: Joaquin Ruiz  
 */

function login($user,$pass)
{
    if (file_exists('core/globals/users.php'))
    {
        include 'core/globals/users.php';
    }
    else
    {
        echo 'Manager system integrity damaged.';
        exit();
    }
    if (isset($authorized_users[$user]))
    {
        if ((crypt($pass, $authorized_users[$user]) == $authorized_users[$user]))
        {
            session_register('logged_user');
            session_start();
            $_SESSION['logged_user']=$user;
            session_write_close();
            header('Location:index.php');
            flush();
            exit();
        }
    }
}
login($_POST['username'],$_POST['passwd']);
$main_title='Meshlium Manager System';
$html_title='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
        <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <style>
                @import "core/css/reset.css";
                @import "core/css/login.css";
            </style>
            <title>'.$main_title.'</title>
        </head>
        <body>';
echo $html_title;
echo '
<div id="main_div">
    <div class="login_menu">
        <form method="post" action="#" name="login">
            <div id="login_form">
                <div id="login_username">
                    <input name="username">
                </div>
                <div  id="login_password">
                    <input name="passwd" type="password">
                </div>
                <button id="login_button" type="submit"\>
            </div>
        </form>
    </div>
</div>';


include_once 'core/structure/footer.php';
echo $html;
?>