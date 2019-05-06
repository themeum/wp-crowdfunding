<?php

if (WPCF_TYPE === 'enterprise'){
    include_once 'authorizenet-base.php';
}else{
    include_once 'authorizenet-demo.php';
}
