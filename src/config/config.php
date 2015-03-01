<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Defaults when there is no date
    |--------------------------------------------------------------------------
    |
    | This is an array of values to return when the developer has explicitly
    | set no date in a field. It signifies that there is no date, or that a
    | date range is open-ended (eg, 1/1/2014 - ???).
    |
    | The developer gets this by using DateRange::NONE as a field value.
    |
    */

    'none' => array(
        'default' => '(n/a)',
        'sql' => Null,
        'full' => Null,
        'url' => Null,
        'calculations' => 0,
    ),


    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    |
    | Return specific php date format strings to use when a given style 
    | is requested.
    |
    | To request a specific style, a developer will use <value>_<style>
    | (eg, start_short for a short format of the start date)
    |
    */
    'styles' => array(
        'default' => 'n/j/Y',

        'title' => 'l, F j, Y',
        'long' => 'D, M j, Y g:ia',
        'full' => 'n/j/Y g:ia',
        // 'time' => 'H:i:s',   // replace Carbon's time definition with ours
        'time' => 'g:ia',

        'short' => 'n/j/Y',
        'tiny' => 'n/j/y',
        'pad' => 'm/d/y',
        'padded' => 'm/d/Y',

        'month_name' => 'F',
        'month_year' => 'F Y',
        'day_detail' => 'l (n/j/Y)',
        'day_only' => 'n/j',

        'sql' => 'Y-m-d',
        'sql_date' => 'Y-m-d 00:00:00',
        'datetime' => 'Y-m-d H:i:s',
        'url' => 'Y-m-d\THisO',
        'timestamp' => 'U',
    ),

    /*
    |--------------------------------------------------------------------------
    | Range Delimiters
    |--------------------------------------------------------------------------
    |
    | Each format can have a range delimiter. This will enter text before,
    | in the middle, and after two dates. In the case the start and end dates
    | are the same, the 'only' value will put data before the resulting date.
    |
    | This can be combined with the format.
    |
    */
    'range' => array(
        'default' => '{$start->default} &ndash; {$end->default}',
        'title' => 'From $start->title to $end->title',
        'url' => 'start={$start->url}&end={$end->url}',
    ),
    'range-single' => array(
        'default' => '$start->default',
        'title' => 'For $start->title',
        'url' => 'date={$start->url}',
    ),

    /*
    |--------------------------------------------------------------------------
    | Calculations
    |--------------------------------------------------------------------------
    |
    | We can use a closure to calculate other values. 
    |
    | Pass $start and $end values to the closure. 
    |
    */
    'calculations' => array(

        'days' => function($start, $end) { 
            return $end->diffInDays($start); 
        },

        // round months to the nearest two weeks
        'months' => function($start, $end) { 
            return $end->copy()->addDays(14)->diffInMonths($start->getCarbon()); 
        },

        'decimal' => function($date) {
            $hours = $date->hour + ($date->minute / 60);
            return round($hours,1);
        },

        'sql_range' => function($start, $end=Null) {
            return [$start->copy()->startOfDay()->format('Y-m-d'),
                $end->copy()->endOfDay()->format('Y-m-d H:i:s')];
        },

        'human' => function($date) {
            if ($date->diff($date->now())->days < 3)
                return $date->diffForHumans(); 

            return $date->format('n/j/Y \a\t g:ia');
        }
    ),

);
