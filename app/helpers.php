<?php
function getSequence($sequence)
{
    if ($sequence != '' || $sequence !== null) {
        switch ($sequence) {
            case 0 :
                return 'Golden Row';
                break;
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

function getWarning($first, $second){
    $status = 0;

    if($first == null){
        return $status;
    }

    $first = \Carbon\Carbon::parse($first);

    if($second == null){

        if(\Carbon\Carbon::now()->toDateString() >= $first->toDateString()){
            $status = 2;//标记红色
        }else if(\Carbon\Carbon::now()->toDateString() >= $first->subDays(2)->toDateString()){
            $status = 1;//标记黄色
        }
    }

    return $status;
}
