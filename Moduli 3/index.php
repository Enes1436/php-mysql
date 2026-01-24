<?php

$var=10;

if($var>0){
    echo" $var greater than 5 <hr>" ;
}
$age= 14;

if($age>18 ){
echo"You can vote";
}else{
    echo "You cant vote <hr>";
}

$a = 50;
$b = 10;

if($a==$b){
    echo "a is equals b <hr>";
}else if($a>$b){
    echo"a is greater then b <hr>";
}else{
    echo "a is less than b <hr>";
}

switch($age){
    case($age>=0 && $age<18):
        echo "You are a minor <hr>";
        break;

         case($age>=18 && $age<25):
        echo "You are a young adult <hr>";
        break;

         case($age>25 && $age<65):
        echo "You are middle age <hr>";
        break;

     case($age>=65):
        echo "You are a senior <hr>";
        break;

        default:
        echo"Invalid value <hr>";
       break;
}

$number =1;

while($number <=10){
    echo"Number is $number <hr>"; 
    $number++ ;
}

$z=1;

do{
    echo "The number is $z <hr>";
    $z++;
}while($z<=5);

?>