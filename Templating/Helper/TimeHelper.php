<?php

namespace Knp\Bundle\TimeBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use DateTime;
use DateTimeInterface;

class TimeHelper extends Helper
{
    protected $formatter;

    public function __construct(DateTimeFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Returns a single number of years, months, days, hours, minutes or
     * seconds between the specified date times.
     *
     * @param  mixed $since The datetime for which the diff will be calculated
     * @param  mixed $since The datetime from which the diff will be calculated
     *
     * @return string
     */
    public function diff($from, $to = null)
    {
        $from = $this->getDatetimeObject($from);
        $to = $this->getDatetimeObject($to);

        return $this->formatter->formatDiff($from, $to);
    }

    /**
     * Returns a DateTime instance for the given datetime
     *
     * @param  mixed $datetime
     *
     * @return DateTimeInterface
     */
    public function getDatetimeObject($datetime = null)
    {
        if ($datetime instanceof DateTimeInterface) {
            return $datetime;
        }

        if (is_integer($datetime)) {
            $datetime = date('Y-m-d H:i:s', $datetime);
        }

        return new DateTime($datetime);
    }

    /**
     * Return formatted time
     *
     * @param int $time The time in seconds
     * @param int $format The time format (short, full, tiny)
     *
     * @return string
     */
    public function timeFormat($time, $format = null)
    {
        $formats = [
            'short' => 0,
            'full' => 1,
            'tiny' => 2
        ];
        $units = [
            31536000 => ['year', 'year', 'y'],
            2592000 => ['month', 'month', 'm'],
            604800 => ['week', 'week', 'w'],
            86400 => ['day', 'day', 'd'],
            3600 => ['hour', 'hour', 'h'],
            60 => ['min', 'minute', 'm'],
            1 => ['sec', 'second', 's']
        ];
        $parts = [];
        $level = !is_null($format) && isset($formats[$format]) ? $formats[$format] : 0;

        foreach ($units as $unit => $val) {
            if ($time < $unit) continue;
            $suffix = $val[$level];

            $numberOfUnits = (int) floor($time / $unit);
            if ($numberOfUnits > 0) {
                $parts[] = "$numberOfUnits $suffix" . (($level > 1 || !in_array($suffix, ['min', 'sec']) || $numberOfUnits > 1) ? "s" : "");
                $time = $time - $numberOfUnits * $unit;
            }
        }

        return implode(' ', $parts);
    }

    public function getName()
    {
        return 'time';
    }
}
