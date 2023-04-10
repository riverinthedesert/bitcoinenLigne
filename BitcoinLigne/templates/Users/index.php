<?php
session_start();
if(isset($_SESSION['connect'])){
    echo 'Vous êtes connecté en tant que : ';
    echo $_SESSION['login'];
}else{
    echo "";
}

?>
