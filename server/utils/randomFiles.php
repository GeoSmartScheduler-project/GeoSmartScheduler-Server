<?php

function generate_file($file_name, $size_in_bytes)
{
   $data = str_repeat(rand(0,9), $size_in_bytes);
   file_put_contents($file_name, $data); //writes $data in a file   
}