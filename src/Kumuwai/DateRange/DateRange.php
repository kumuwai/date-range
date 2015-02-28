<?php namespace Kumuwai\DateRange;

use DateTime;
use Carbon\Carbon;


class DateRange
{
	protected $start;
	protected $end;
	protected $config;

	public function __construct($start=Null, $end=Null, $tz=Null, $config=Null)
	{
    	$this->config = $config ?: new Config;
		$this->setDates($start, $end, $tz);
	}

	public function setDates($start=Null, $end=Null, $tz=Null)
	{
        $this->start = new FormattedCarbon($start, $tz, $this->config);
        $this->end = $end ? new FormattedCarbon($end, $tz, $this->config) : $this->start; 
	}

	public function start()
	{
		return $this->start;
	}

	public function end()
	{
		return $this->end;
	}

	public function style($style)
	{
		$output = $this->getOutputForStartOrEndDates($style);
		if ($output !== Null) 
			return $output;

		$output = $this->getOutputForClosure($style);
		if ($output !== Null)
			return $output;

		if ($this->start->eq($this->end)) 
			return $this->getOutputForRange('range-single', $style);

		return $this->getOutputForRange('range', $style);
	}

	private function getOutputForStartOrEndDates($style)
	{
		if (substr($style, 0, 6)=='range_')
			return $this->style(substr($style, 6));

		if ($style == 'start')
			return $this->start;

		if ($style == 'end')
			return $this->end;

		if (substr($style, 0, 6)=='start_')
			return $this->start->style(substr($style, 6));

		if (substr($style, 0, 4)=='end_')
			return $this->end->style(substr($style, 4));
	}

	private function getOutputForClosure($style)
	{
		$closure = $this->config->get("date-range::calculations.$style");
		if ($closure)
			return $closure($this->start, $this->end);
	}

	private function getOutputForRange($section, $style)
	{
		if ( ! isset($this->start->$style))
			return '';

		$format = $this->config->get("date-range::$section.$style");
		if ( ! $format) {
			$format = $this->config->get("date-range::$section.default");
			$format = str_replace('default', $style, $format);
		}

		$start = $this->start;
		$end = $this->end;
		return eval( "return \"{$format}\";" );
	}

	public function __toString()
	{
		return $this->style('default');
	}

	public function __get($style)
	{
		return $this->style($style);
	}

}

