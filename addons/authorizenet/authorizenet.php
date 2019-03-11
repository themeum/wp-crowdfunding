<?php

if (WPNEO_CROWDFUNDING_TYPE === 'enterprise'){
    include_once 'authorizenet-base.php';
}else{
    include_once 'authorizenet-demo.php';
}
