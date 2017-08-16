<?php
    function readFromUntil($inputString, $fromString, $untilString, $startFromEnd = true) {
        $index = strpos($inputString, $fromString);
        if($index === false) {
            return "";
        }
        if($startFromEnd === true) {
            $index += strlen($fromString);
        }
        $compare = "";
        $returnString = "";
        while($compare != $untilString) {
            $returnString .= $inputString[$index];
            $index++;
            $compare = "";
            for($x = $index; $x < strlen($untilString) + $index; $x++) {
                $compare .= $inputString[$x];
            }
        }
        return $returnString;
    }
?>