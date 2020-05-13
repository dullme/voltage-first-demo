<?php
function getSequence($sequence)
{
    if ($sequence) {
        switch ($sequence) {
            case 1 :
                return $sequence . 'st';
                break;
            case 2 :
                return $sequence . 'nd';
                break;
            case 3 :
                return $sequence . 'rd';
                break;
            default :
                return $sequence . 'th';
        }
    }

    return '';
}

function getForeignCurrencyType($type){
    switch ($type) {
        case 1 :
            return 'US';
            break;
        case 2 :
            return 'AUD';
            break;
        default :
            return '';
    }
}

function popover($history){
    $text = '';
    foreach ($history as $key=>$item){
        $text .= ($key+1) . '、' . $history[$key]['estimated'] . ' : ' .  $history[$key]['created_at'] . '<br />';
    }

    return $text;
}
