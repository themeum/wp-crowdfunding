<?php

if (WPCF_TYPE === 'enterprise'){
    include_once '2checkout-init.php';
}else{
    include_once '2checkout-demo.php';
}