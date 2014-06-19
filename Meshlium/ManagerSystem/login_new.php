<?php
/*
 *  Copyright (C) 2012 Libelium Comunicaciones Distribuidas S.L.
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

echo '<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>Login Meshlium Xtreme 3G</title>
        <link rel="stylesheet" type="text/css" href="core/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="core/css/style.css" />
	<link rel="stylesheet" type="text/css" href="core/css/animate-custom.css" />
    </head>
    <body>
        <div class="container">
            <header>
                <h1>Login <span>Meshlium Xtreme 3G</span></h1>
            </header>
            <section>				
                <div id="container_demo" >
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
			  <form method="post" action="#" name="login">
                                <h1>Log in</h1> 
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your email or username </label>
                                    <input id="username" name="username" required="required" type="text" placeholder="myusername "/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input id="password" name="passwd" required="required" type="password" placeholder="eg. password" /> 
                                </p>
                                <p class="login button"> 
                                    <input type="submit" value="Login" id="login_button"/> 
								</p>
                            </form>
                        </div>
						
                    </div>
                </div>  
            </section>
        </div>
    </body>
</html>';


include_once 'core/structure/footer.php';
echo $html;
?>