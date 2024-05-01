<?php

namespace StaffCollab\HebrewDate;

use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Collection;
use Zman\Exceptions\InvalidDateException;
use Zman\Zman;

class HebrewDate extends Zman
{
    public function addHebrewMonths(int $month = 1): self
    {
        while ($month) {
            $this->jewishMonth < 13 ? $this->jewishMonth++ : ($this->jewishYear++ && $this->jewishMonth = 1);

            if ($this->jewishMonth == 6 && ! $this->isJewishLeapYear()) {
                $this->jewishMonth++;
            }

            $month--;
        }

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function subHebrewMonths(int $month = 1): self
    {
        while ($month) {
            $this->jewishMonth > 1 ? $this->jewishMonth-- : ($this->jewishYear-- && $this->jewishMonth = 13);

            if ($this->jewishMonth == 6 && ! $this->isJewishLeapYear()) {
                $this->jewishMonth--;
            }

            $month--;
        }

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function addHebrewYears(int $year = 1): self
    {
        $this->jewishYear += $year;

        if($this->jewishMonth == 6 && ! $this->isJewishLeapYear()) {
            $this->jewishMonth++;
        }

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function subHebrewYears(int $year = 1): self
    {
        $this->jewishYear -= $year;

        if($this->jewishMonth == 6 && ! $this->isJewishLeapYear()) {
            $this->jewishMonth++;
        }

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function startOfHebrewMonth(): self
    {
        $this->jewishDay = 1;

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function endOfHebrewMonth(): self
    {
        $this->startOfHebrewMonth()->addHebrewMonths()->subDay();

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function startOfHebrewYear(): self
    {
        $this->jewishMonth = 1;
        $this->jewishDay = 1;

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function endOfHebrewYear(): self
    {
        $this->startOfHebrewYear()->addHebrewYears()->subDay();

        return self::createFromJewishDate($this->jewishYear, $this->jewishMonth, $this->jewishDay);
    }

    public function getDaysPeriod(): Collection
    {
        $days = CarbonPeriodImmutable::create(
            $this->startOfMonth(),
            $this->endOfMonth()
        )->days();

        return collect($days);
    }

    public function getMonthsPeriod(): Collection
    {
        $months = CarbonPeriodImmutable::create(
            $this->startOfYear(),
            $this->endOfYear()
        )->months();

        return collect($months);
    }

    public function getYearsPeriod(): Collection
    {
        $years = CarbonPeriodImmutable::create(
            $this->subYears(10),
            $this->addYears(10)
        )->years();

        return collect($years);
    }

    public function getHebrewDaysPeriod(): Collection
    {
        $days = CarbonPeriodImmutable::create(
            $this->startOfHebrewMonth(),
            $this->endOfHebrewMonth()
        )->days();

        return collect($days);
    }

    public function getHebrewMonthsPeriod($lang = 'hebrew'): Collection
    {
        $months = collect();

        $this->startOfHebrewYear();

        for ($i = 0; $i < 13; $i++) {
            $months->push($lang == 'hebrew' ? $this->copy()->jewishMonthNameHebrew : $this->copy()->jewishMonthName);
            $this->addHebrewMonths();
        }

        return $months;
    }

    public function getHebrewYearsPeriod($lang = 'hebrew'): Collection
    {
        $years = collect();

        $this->subHebrewYears(10);

        for ($i = 0; $i < 20; $i++) {
            $years->push($lang == 'hebrew' ? $this->copy()->jewishYearHebrew : $this->copy()->jewishYear);
            $this->addHebrewYears();
        }

        return $years;
    }

    public static function createFromJewishDate($year, $month, $day): self
    {
        if ($month === 6 && ! isJewishLeapYear($year)) {
            throw new InvalidDateException("{$year} is not a leap year.");
        }

        return HebrewDate::parse(jdtogregorian(jewishtojd($month, $day, $year)));
    }
}
