<?php

namespace App\Service;

use DateTime;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
//use Doctrine\Bundle\FixturesBundle\Fixture;

class DateTimeGenerator
{
    private $Year = 2020;
    private $month = 2;
    private $day = 1;
    private $nbDay = 10;
    private $nbYear = 1;
    private $manager;
    private $route;
    //$date = new DateTime($Year . '-' . $month . '-' . $day . ' 00:00:00');

    /*public function __construct(EntityManagerInterface $manager, RequestStack $request)
    {
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager      = $manager;
        //$this->twig         = $twig;
        //$this->templatePath = $templatePath;
    }*/

    public function getArrayDateTime(): array
    {
        //$date = new DateTime('2020-02-01 00:00:00');
        $date = new DateTime($this->Year . '-' . $this->month . '-' . $this->day . ' 00:00:00');
        $date->format('Y-m-d H:i:s');
        $date_array = [];
        for ($a = 1; $a <= $this->nbYear; $a++) {
            for ($j = 1; $j <= $this->nbDay; $j++) {
                for ($h = 0; $h < 24; $h++) {
                    for ($m = 1; $m <= 4; $m++) {
                        $date->add(new DateInterval('P0DT0H15M0S'))
                            ->format('Y-m-d H:i:s');
                        $date_array[] = $date;
                    }
                }
            }
        }

        return $date_array;
    }

    /**
     * Get the value of month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set the value of month
     *
     * @return  self
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get the value of Year
     */
    public function getYear()
    {
        return $this->Year;
    }

    /**
     * Set the value of Year
     *
     * @return  self
     */
    public function setYear($Year)
    {
        $this->Year = $Year;

        return $this;
    }

    /**
     * Get the value of day
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set the value of day
     *
     * @return  self
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get the value of nbDay
     */
    public function getNbDay()
    {
        return $this->nbDay;
    }

    /**
     * Set the value of nbDay
     *
     * @return  self
     */
    public function setNbDay($nbDay)
    {
        $this->nbDay = $nbDay;

        return $this;
    }

    /**
     * Get the value of nbYear
     */
    public function getNbYear()
    {
        return $this->nbYear;
    }

    /**
     * Set the value of nbYear
     *
     * @return  self
     */
    public function setNbYear($nbYear)
    {
        $this->nbYear = $nbYear;

        return $this;
    }
}
