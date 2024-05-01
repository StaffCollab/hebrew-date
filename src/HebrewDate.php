<?php

namespace StaffCollab\HebrewDate;

use Zman\Zman;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriodImmutable;
use Illuminate\Support\Collection;

class HebrewDate extends Zman
{
    public function addHebrewMonths(int $month = 1): self
    {
        $this->isJewishLeapYear() ? $months = 13 : $months = 12;

        while ($month) {
            $this->jewishMonth < $months ? $this->jewishMonth++ : ($this->jewishYear++ && $this->jewishMonth = 1);
            $month--;
        }

        return $this;
    }

    public function subHebrewMonths(int $month = 1): self
    {
        $this->isJewishLeapYear() ? $months = 13 : $months = 12;

        while ($month) {
            $this->jewishMonth > 1 ? $this->jewishMonth-- : ($this->jewishYear-- && $this->jewishMonth = $months);
            $month--;
        }

        return $this;
    }

    public function addHebrewYears(int $year = 1): self
    {
        $this->jewishYear += $year;
        return $this;
    }

    public function subHebrewYears(int $year = 1): self
    {
        $this->jewishYear -= $year;
        return $this;
    }

    public function startOfHebrewMonth(): self
    {
        $this->jewishDay = 1;
        return $this;
    }

    public function endOfHebrewMonth(): self
    {
        $this->startOfHebrewMonth()->addHebrewMonths()->subDay();
        return $this;
    }

    public function startOfHebrewYear(): self
    {
        $this->jewishMonth = 1;
        $this->jewishDay = 1;
        return $this;
    }

    public function endOfHebrewYear(): self
    {
        $this->startOfHebrewYear()->addHebrewYears()->subDay();
        return $this;
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
        $count = $this->isJewishLeapYear() ? 13 : 12;

        for ($i = 0; $i < $count; $i++) {
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
}