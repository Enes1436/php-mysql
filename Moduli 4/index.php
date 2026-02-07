<?php
$sports =["Football","Basketball","Voleyball","Tenis","Hockey"];
echo $sports[0]."<hr>";

echo end($sports)."<hr>";
echo count($sports)."<hr>";

for($count=0; $count<5; $count++){
    echo $sports[$count]."<hr>";
}


array_push($sports, "Golf");

for($count= 0; $count< 5; $count++){
    echo $sports[$count]."<hr>";
}
array_unshift($sports);

var_dump($sports);

array_pop($sports);

var_dump($sports);

array_push($sports,"golf");

array_shift($sports);

var_dump($sports);


$numbers =[1,2,3,4,5,6,7,8,9];

$mbledhja = array_sum($numbers);
echo $mbledhja."<hr>";
?>