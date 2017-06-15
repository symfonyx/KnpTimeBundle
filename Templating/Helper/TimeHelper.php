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
     *
     * @return string
     */
    public function timeFormat($time)
    {
        $units = [
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        ];
        $parts = [];

        foreach ($units as $unit => $val) {
            if ($time < $unit) continue;

            $numberOfUnits = (int) floor($time / $unit);
            if ($numberOfUnits > 0) {
                $parts[] = "$numberOfUnits $val" . ($numberOfUnits > 1 ? "s" : "");
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
