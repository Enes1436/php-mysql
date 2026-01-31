<?php

function fully_divisable($n) {
    if(($n % 2) == 0) {
    return "$n is fully divisable by 2";
}
else{
    return "$n is not fully divisable by 2";
}
}

print_r(fully_divisable(4) ."<br>");
print_r(fully_divisable(7) ."<br>");
print_r(fully_divisable(8) ."<br>");
print_r(fully_divisable(9) ."<br>");
?>