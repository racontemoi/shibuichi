<?php
class I18nDateDecorator extends DateDecorator {
        function I18nIsFirstDayOfWeek() {
                return $this->owner->RAW() ==
                        $this->I18nFirstDayOfWeek()->RAW();
        }

        function I18nIsLastDayOfWeek() {
                return $this->owner->RAW() ==
                        $this->I18nLastDayOfWeek()->RAW();
        }

        function I18nIsNotFirstDayOfWeek() {
                return $this->owner->RAW() !=
                        $this->I18nFirstDayOfWeek()->RAW();
        }

        function I18nIsNotLastDayOfWeek() {
                return $this->owner->RAW() !=
                        $this->I18nLastDayOfWeek()->RAW();
        }

        /**
         * Returns the days of the current week as a DataObjectSet of dates.
         */
        function I18nDaysOfWeek() {
                $days = new DataObjectSet();
                $end = $this->I18nLastDayOfWeek()->next();
                for($day = $this->I18nFirstDayOfWeek();
                    $day->RAW() != $end->RAW();
                    $day = $day->next())
                        $days->push($day);
                return $days;
        }
}
?>