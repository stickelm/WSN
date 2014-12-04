<?php

exec("sudo remountrw");
exec("sudo rm /var/www/ManagerSystem/upload/*");

if ($_POST['uploadKind'] == "local")
{
   if ((!strstr($_FILES["file"]["name"], 'libupd')) && (!strstr($_FILES["file"]["name"], 'libplg')))
   {
      //$uploadhtml.= "File: " . $_FILES["file"]["name"];
      $uploadhtml.= "Invalid file";
   }
   else
   {
      if ($_FILES["file"]["error"] > 0)
      {
      $uploadhtml.= "Error: " . $_FILES["file"]["error"] . "<br /><pre>".print_r($_FILES, true)."</pre>";
      }
      else
      {
      $uploadhtml.= "File: " . $_FILES["file"]["name"] . "<br />";
      // $uploadhtml.= "Type: " . $_FILES["file"]["type"] . "<br />";
      //$uploadhtml.= "Size: " . round(($_FILES["file"]["size"] / 1024),2) . " Kb<br />";
      // $uploadhtml.= "Stored in: " . $_FILES["file"]["tmp_name"];

         if (file_exists("/var/www/ManagerSystem/upload/" . $_FILES["file"]["name"]))
         {
            $uploadhtml.= $_FILES["file"]["name"] . " already exists. ";
         }
         else
         {
            move_uploaded_file($_FILES["file"]["tmp_name"],
            "/var/www/ManagerSystem/upload/" . $_FILES["file"]["name"]);
            // $uploadhtml.= "Stored in: " . "/var/www/ManagerSystem/upload/" . $_FILES["file"]["name"];
         }
      }
   }
}
elseif ($_POST['uploadKind'] == "url")
{
   exec ("cd /var/www/ManagerSystem/upload && wget ".$_POST['urlFile']);
   if((substr($_POST['urlFile'], -6) == 'libupd') || (substr($_POST['urlFile'], -6) == 'libplg'))
   {
      $urlFile = explode("/", $_POST['urlFile']);
      $uploadhtml.= "File: " . $urlFile[sizeof($urlFile)-1] . "<br />";
   }
   else
   {
      $uploadhtml.= "Invalid file";
   }
}
else
{
   $uploadhtml.= "Invalid action";
}

if(exec("ls /var/www/ManagerSystem/upload/*.lib* | wc -l") == 0)
{
   exec("rm -rf /var/www/ManagerSystem/upload/*");
}
//$uploadhtml.= "<pre>".print_r($_POST, true)."</pre>";
exec('echo "'.$uploadhtml.'" > /var/www/ManagerSystem/upload/log');
/* Redirigir navegador */
header("Location: index.php?section=f_instaler&plugin=".$_POST['returnsto']);
/* Asegúrese de que el código que aparece a continuación no se ejecutará cuando redireccionamos.*/
exit;

?>