<?php
class GenericI18nDateDecorator extends I18nDateDecorator {
        public $numberOfMonths = 12;
        public $numberOfDaysInWeek = 7;

        /**
         * Returns the number of days in the current week, depending
         * on the class locale.
         */
        function I18nDaysInWeek() {
                return $this->numberOfDaysInWeek;
        }

        /**
         * Returns the day of the week, 0-based, depending on the class
         * locale.
         */
        function I18nDayOfWeek() {
                $usWeekDay = $this->DayOfWeek();
                return ($usWeekDay - $this->USWeekDayWeekStartsOn +
                        $this->numberOfDaysInWeek) % $this->numberOfDaysInWeek;
        }

        /**
         * Returns the number of days in the current month, depending
         * on the class locale.
         */
        function I18nDaysInMonth() {
                return $this->DaysInMonth();
        }

        /**
         * Returns the first day of the current month as a date, depending
         * on the class locale.
         */
        function I18nFirstDayOfMonth() {
                return $this->FirstDayOfMonth();
        }

        /**
         * Returns the last day of the current month as a date, depending
         * on the class locale.
         */
        function I18nLastDayOfMonth() {
                return $this->LastDayOfMonth();
        }

        /**
         * Returns the first day of the current week as a date, depending
         * on the class locale.
         */
        function I18nFirstDayOfWeek() {
                return $this->OffsetBy(0, 0, -$this->I18nDayOfWeek());
        }
        
        /**
         * Returns the last day of the current week as a date, depending
         * on the class locale.
         */
        function I18nLastDayOfWeek() {
                return $this->OffsetBy(0, 0,
                                       6 - $this->I18nDayOfWeek());
        }

        function I18nDay() {
                return $this->dayOfWeekNames[$this->I18nDayOfWeek()];
        }

        function I18nYear() {
                return $this->owner->Year();
        }

        function I18nMonth() {
                return $this->owner->Month();
        }

        function I18nDayOfMonth() {
                return $this->owner->DayOfMonth();
        }

        function I18nShortMonth() {
                return $this->shortMonthNames[$this->I18nDayOfMonth()];
        }

        function I18nDayOfYear() {
                return date('z', strtotime($this->owner->RAW()));
        }

        function I18nWeekNumberOfYear() {
                return date('W', strtotime($this->owner->RAW()));
        }

        function I18nNice() {
                return $this->I18nFormat($this->niceFormatString);
        }

        function I18nShort() {
                return $this->I18nFormat($this->niceFormatString);
        }

        function I18nMedium() {
                return $this->I18nFormat($this->shortFormatString);
        }

        function I18nLong() {
                return $this->I18nFormat($this->mediumFormatString);
        }

        function I18nFull() {
                return $this->I18nFormat($this->fullFormatString);
        }

        function I18nFormat($formatString) {
                $elements = array(
                        '%a' => $this->I18nDayOfWeekShortName(),
                        '%A' => $this->I18nDayOfWeekName(),
                        '%d' => substr('00' . $this->I18nDayOfMonth(), -2),
                        '%e' => substr('  ' . $this->I18nDayOfMonth(), -2),
                        '%J' => $this->I18nDayOfYear(), // ext
                        '%j' => substr('000' . $this->I18nDayOfYear(), -3),
                        '%u' => $this->ISO8601DayOfWeek(), // ext
                        '%E' => $this->I18nDayOfMonth(), // ext
                        '%w' => $this->DayOfWeek(),
                        '%v' => $this->I18nDayOfWeek(), // ext
                        '%f' => $this->I18nDayOfMonthOrdinalSuffix(), // ext
                        '%T' => $this->I18nDaysInMonth(), // ext
                        
                        '%U' => strftime('%U', strtotime($this->owner->RAW())),
                        '%V' => strftime('%V', strtotime($this->owner->RAW())),
                        '%W' => strftime('%W', strtotime($this->owner->RAW())),
                        
                        '%b' => $this->I18nMonthShortName(),
                        '%B' => $this->I18nMonthName(),
                        '%h' => $this->I18nMonthShortName(),
                        '%m' => substr('00' . $this->I18nMonth(), -2),
                        '%L' => $this->I18nMonth(), // ext
                        
                        '%C' => substr($this->I18nYear(), 0, 2),
                        '%g' => strftime('%g', strtotime($this->owner->RAW())),
                        '%G' => strftime('%G', strtotime($this->owner->RAW())),
                        '%y' => substr($this->I18nYear(), -2),
                        '%Y' => $this->I18nYear(),
                        
                        // time formats are using
                        // %H %I %l %M %p %P %r %R %S %T %X %z %Z %c
                        
                        '%D' => strftime('%D', strtotime($this->owner->RAW())),
                        '%F' => $this->owner->RAW(),
                        '%s' => strftime('%s', strtotime($this->owner->RAW())),
                        '%x' => null,
                        
                        '%n' => "\n",
                        '%t' => "\t",
                        '%%' => '%');
                foreach ($elements as $key => $value)
                        $formatString = str_replace(
                                $key, $value, $formatString);
                return $formatString;
        }
        
        function I18nMonthName() {
                return $this->monthNames[$this->I18nMonth()];
        }
        
        function I18nMonthShortName() {
                return $this->shortMonthNames[$this->I18nMonth()];
        }
        
        function I18nDayOfWeekName() {
                return $this->dayOfWeekNames[$this->I18nDayOfWeek()];
        }
        function I18nDayOfWeekShortName() {
                return $this->shortDayOfWeekNames[$this->I18nDayOfWeek()];
        }
        function I18nDayOfMonthOrdinalSuffix() {
                return $this->dayOfMonthOrdinalSuffix[$this->I18nDayOfMonth()];
        }
}
?>