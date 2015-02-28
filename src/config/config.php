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
        'datetime' => 'n/j/Y g:ia',
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
        'full' => 'Y-m-d H:i:s',
        'url' => 'Y-m-d',
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
        'default' => '$start &ndash; $end',
        'title' => 'From $start to $end',
        'url' => 'start={$start}&end={$end}',
    ),
    'range-single' => array(
        'default' => '',
        'title' => 'For $start',
        'url' => 'date={$start}',        
    ),

    /*
    |--------------------------------------------------------------------------
    | Calculations
    |--------------------------------------------------------------------------
    |
    | We can use a closure to calculate other values. 
    |
    | Pass $start and $end or $date values to the closure. 
    |
    */
    'calculations' => array(

        'days' => function($start, $end) { 
            return $end->diffInDays($start); 
        },

        // round months to the nearest two weeks
        'months' => function($start, $end) { 
            return $end->copy()->addDays(14)->diffInMonths($start); 
        },

        'decimal' => function($date) {
            $hours = $date->hour + ($date->minute / 60);
            return round($hours,1);
        },

    ),

);
