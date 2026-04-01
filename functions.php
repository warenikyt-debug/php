<?php

function add($a, $b) {
    return $a + $b;
}

function subtract($a, $b) {
    return $a - $b;
}

function multiply($a, $b) {
    return $a * $b;
}

function divide($a, $b) {
    return $a / $b;
}

function ackermann($n, $m) {
    if ($n == 0) {
        return $m + 1;
    } elseif ($n != 0 && $m == 0) {
        return ackermann($n - 1, 1);
    } else {
        return ackermann($n - 1, ackermann($n, $m - 1));
    }
}

function combinations($n, $m) {
    if ($m == 0 || $m == $n) {
        return 1;
    } else {
        return combinations($n - 1, $m) + combinations($n - 1, $m - 1);
    }
}

function a_function($n) {
    if ($n == 1) {
        return 1;
    } else {
        return a_function((int)($n / 2)) + 1;
    }
}

function logarithm($base, $arg) {
    return log($arg, $base);
}

function derivative($x, $n) {
    return $n * pow($x, $n - 1);
}

?>
