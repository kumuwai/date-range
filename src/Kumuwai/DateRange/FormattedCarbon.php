<?php namespace Kumuwai\DateRange;

use Carbon\Carbon;
use DateTime;


class FormattedCarbon
{
	const NONE = -1;

	public $isNotApplicable = False;
	private $config;
	private $carbon;

    public function __construct($time = null, $tz = null, $config = null)
    {
    	if ( $config )
    		$this->config = $config;
    	else
    		$this->config = new Config;

    	if (is_numeric($time) && $time == self::NONE)
    		return $this->isNotApplicable = True;

        if ($time instanceof FormattedCarbon)
        	return $this->carbon = Carbon::instance($time->getCarbon());

        if ($time instanceof DateTime)
        	return $this->carbon = Carbon::instance($time);

        if (is_numeric($time))
        	return $this->carbon = Carbon::createFromTimestamp($time);

        $this->carbon = Carbon::parse($time, $tz);
	}

	public function none()
	{
		return self::NONE;
	}

	public function isNone()
	{
		return $this->isNotApplicable;
	}

	public function getStyles()
	{
		return $this->config->get('date-range::styles');
	}

	public function getStyle($style)
	{
		return $this->config->get("date-range::styles.$style");
	}

	public function style($name)
	{
		$style = $this->getStyle($name);
		if ($style)
			return $this->carbon->format($style);

		$method = 'to'.ucfirst($name).'String';
		if (method_exists($this->carbon, $method))
			return $this->carbon->$method();
	}

	public function getCarbon()
	{
		return $this->carbon;
	}

	public function toSql()
	{
		return $this->carbon->format('Y-m-d H:i:s');
	}

	public function __get($name)
	{
		if ($this->isNotApplicable)
			return '';  // maybe throw an exception?

		if (isset($this->carbon->$name)) 
			return $this->carbon->$name;

		$formatted = $this->style($name);
		return $formatted ?: '';
	}

	public function __set($name, $value)
	{
		$this->carbon->$name = $value;
	}
	
	public function __call($name, $arguments)
	{
		if ($this->isNotApplicable)
			return '';  // maybe throw an exception?

		foreach($arguments as &$arg) {
			if ($arg instanceof FormattedCarbon) {
				$arg = $arg->getCarbon();
				if (! $arg)
					return '';  // maybe throw an exception?
			}
		}

		$result = call_user_func_array([$this->carbon, $name], $arguments);

		$constructors = ['instance','parse','now','today','tomorrow','yesterday',
			'maxValue','minValue','create','createFromDate','createFromTime','createFromFormat',
			'createFromTimestamp','createFromTimestampUTC','copy'];

		if (in_array($name, $constructors))
			return new static($result);

		return $result;
	}

}
