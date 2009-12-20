<?php /* -*- coding: iso-8859-1 -*- */
class DateDecorator extends Extension {
        /**
         * Checks if the date is in the past.
         */
        function IsPast() {
                $today = date('Y-m-d');
                return $this->owner->RAW() < $today;
        }

        /**
         * Checks if the date is in the past or today.
         */
        function IsPastOrToday() {
                $today = date('Y-m-d');
                return $this->owner->RAW() <= $today;
        }

        /**
         * Checks if the date is in the future or today.
         */
        function IsFutureOrToday() {
                $today = date('Y-m-d');
                return $this->owner->RAW() >= $today;
        }

        /**
         * Checks if the date is in the future.
         */
        function IsFuture() {
                $today = date('Y-m-d');
                return $this->owner->RAW() > $today;
        }

        /**
         * Checks if the date is today
         */
        function IsToday() {
                $today = date('Y-m-d');
                return $this->owner->RAW() == date('Y-m-d');
        }

        /**
         * Checks if the date is first day of its week.
         */
        function IsFirstDayOfWeek() {
                return $this->owner->RAW() == $this->FirstDayOfWeek()->RAW();
        }

        /**
         * Checks if the date is first day of its week.
         */
        function IsLastDayOfWeek() {
                return $this->owner->RAW() == $this->LastDayOfWeek()->RAW();
        }

        /**
         * Checks if the date is not the first day of its week.
         */
        function IsNotFirstDayOfWeek() {
                return $this->owner->RAW() != $this->FirstDayOfWeek()->RAW();
        }

        /**
         * Checks if the date is not the first day of its week.
         */
        function IsNotLastDayOfWeek() {
                return $this->owner->RAW() != $this->LastDayOfWeek()->RAW();
        }

        /**
         * Returns the US day of week as a short name (e.g. Mon).
         */
        function DayOfWeekShortName() {
                return date('w', strtotime($this->owner->RAW()));
        }

        /**
         * Returns the US day of week as an full name (e.g. Monday).
         */
        function DayOfWeekName() {
                return date('l', strtotime($this->owner->RAW()));
        }

        /**
         * Returns the US day of week as an integer.
         */
        function DayOfWeek() {
                return date('w', strtotime($this->owner->RAW()));
        }

        /**
         * Returns the current month.
         */
        function Month() {
                return date('n', strtotime($this->owner->RAW()));
        }


        /**
         * Returns the number of days in current month.
         */
        function DaysInMonth() {
                return date('t', strtotime($this->owner->RAW()));
        }

        /**
         * Returns the ISO-8601 day of week.
         */
        function ISO8601DayOfWeek() {
                return date('N', strtotime($this->owner->RAW()));
        }

        /**
         * Returns the day of year, 0 based.
         */
        function DayOfYear() {
                return date('z', strtotime($this->owner->RAW()));
        }

        /**
         * Returns the first day of the month of the current date.
         */
        function FirstDayOfMonth() {
                $timestamp = mktime(0, 0, 0,
                                    $this->owner->Month(),
                                    1,
                                    $this->owner->Year());
                return DBField::create('Date', date('Y-m-d', $timestamp));
        }

        /**
         * Returns the last day of the month of the current date.
        */
        function LastDayOfMonth() {
                $timestamp = mktime(0, 0, 0,
                                    $this->owner->Month(),
                                    $this->DaysInMonth(),
                                    $this->owner->Year());
                return DBField::create('Date', date('Y-m-d', $timestamp));
        }

        /**
         * Returns the days of the current week as a DataObjectSet of dates.
         */
        function DaysOfWeek() {
                $days = new DataObjectSet();
                
                for($day = $this->FirstDayOfWeek();
                    $day->RAW() != $this->LastDayOfWeek()->RAW();
                    $day = $day->next())
                        $days->push($day);
                return $days;
        }

        /**
         * Returns the first day of the week of the current date.
         */
        function FirstDayOfWeek() {
                return $this->OffsetBy(0, 0, -$this->DayOfWeek());
        }

        /**
         * Returns the last day of the week of the current date.
         */
        function LastDayOfWeek() {
                return $this->OffsetBy(0, 0, 6 - $this->DayOfWeek());
        }

        /**
         * Returns a copy of the current day modified with the given
         * offset.
         */
        function offsetBy($year_offset, $month_offset, $day_offset) {
                $timestamp = mktime(0, 0, 0,
                                    $this->owner->Month() + $month_offset,
                                    $this->owner->DayOfMonth() + $day_offset,
                                    $this->owner->Year() + $year_offset);
                return DBField::create('Date', date('Y-m-d', $timestamp));
        }
        
        /**
         * Replaces the year, the month or the day, and returns a
         * modified copy of the current date.
         */
        function replacedWith($year = null, $month = null, $day = null) {
                $timestamp = mktime(0, 0, 0,
                                    $month ? $month : $this->owner->Month(),
                                    $day ? $day : $this->owner->DayOfMonth(),
                                    $year ? $year : $this->owner->Year());
                return DBField::create('Date', date('Y-m-d', $timestamp));
        }

        /**
         * Returns next day.
         */
        function next() {
                return $this->offsetBy(0, 0, 1);
        }

        /**
         * Returns previous day.
         */
        function prev() {
                return $this->offsetBy(0, 0, -1);
        }
}
?>