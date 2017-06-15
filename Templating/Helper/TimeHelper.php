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
            'short' => [
                ['year','years'], ['month','months'], ['week','weeks'], ['day','days'], ['hour','hours'], ['min'], ['sec']
            ],
            'full' => [
                ['year','years'], ['month','months'], ['week','weeks'], ['day','days'], ['hour','hours'], ['minute','minutes'], ['second','seconds']
            ],
            'tiny' => [
                ['y'], ['m'], ['w'], ['d'], ['h'], ['m'], ['s']
            ]
        ];
        $units = [
            31536000, // year
            2592000,  // month
            604800,   // week
            86400,    // day
            3600,     // hour
            60,       // minute
            1         // second
        ];
        $parts = [];
        $format = !is_null($format) && isset($formats[$format]) ? $format : 'short';

        foreach ($units as $index => $unit) {
            if ($time < $unit) continue;

            $numberOfUnits = (int) floor($time / $unit);
            if ($numberOfUnits > 0) {
                $suffix = ($numberOfUnits > 1 && isset($formats[$format][$index][1])) ? $formats[$format][$index][1] : $formats[$format][$index][0];
                $parts[] = $format == 'tiny' ? "$numberOfUnits$suffix" : "$numberOfUnits $suffix";
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
