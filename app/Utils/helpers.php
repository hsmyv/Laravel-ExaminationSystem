<?php

function setActiveHeader($header, $output = "active")
{
    return request()->routeIs($header) == $header ? $output : '';
}

?>
