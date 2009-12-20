<?php /* -*- coding: utf-8 -*- */
class Localefr_FR_DateDecorator extends GenericI18nDateDecorator {
        public $monthNames = array('', // array is 1-indexed
                                   'janvier',
                                   'février',
                                   'mars',
                                   'avril',
                                   'mai',
                                   'juin',
                                   'juillet',
                                   'août',
                                   'septembre',
                                   'octobre',
                                   'novembre',
                                   'décembre');
        public $numberOfDaysPerWeek = 7;
        public $USWeekDayWeekStartsOn = 1;
        public $niceFormatString = '%d/%m/%Y';
        public $shortFormatString = '%d/%m/%y';
        public $mediumFormatString = '%E %b %y';
        public $longFormatString = '%E %B %Y';
        public $fullFormatString = '%A %E %B %Y';
        public $dayOfMonthOrdinalSuffix = array ('', 'er', // array is 1-indexed
                                                 '', '', '', '', '', '',
                                                 '', '', '', '', '', '',
                                                 '', '', '', '', '', '',
                                                 '', '', '', '', '', '',
                                                 '', '', '', '', '', '');
        public $dayOfWeekNames = array('lundi',
                                       'mardi',
                                       'mercredi',
                                       'jeudi',
                                       'vendredi',
                                       'samedi',
                                       'dimanche');

// CF. LIBC references (we choose avr. instead of avril)
// http://sourceware.org/ml/libc-locales/2008-q1/msg00035.html
        public $shortDayOfWeekNames = array('lun.','mar.','mer.','jeu.','ven.','sam.','dim.');
        public $letterDayOfWeekNames = array('l', 'm', 'm', 'j', 'v', 's', 'd');
        public $shortMonthNames = array('', // array is 1-indexed
                                        'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin',
                                        'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.');
}

class Localefr_FR_TimeDecorator extends GenericI18nTimeDecorator {
        public $usesAMPM = false;
        public $niceFormatString = '%H:%M';
        public $shortFormatString = '%H:%M';
        public $mediumFormatString = '%H:%M:%S';
        public $longFormatString = '%H:%M:%S'; // would need timezone
        public $fullFormatStringOnlyHour = '%hh'; // would need timezone
        public $fullFormatString = '%hh%M'; // would need timezone
        public $fullFormatStringWithOneSecond = '%hh%M et 1 seconde'; // would need timezone
        public $fullFormatStringWithSeconds = '%hh%M et %o secondes'; // would need timezone

        function I18nFull() {
                if($this->owner->Second() == 0 && $this->owner->Minute() == 0)
                        return $this->I18nFormat($this->fullFormatStringOnlyHour);
                switch($this->owner->Second()) {
                        case 0:
                                return $this->I18nFormat($this->fullFormatString);
                        case 1:
                                return $this->I18nFormat($this->fullFormatStringWithOneSecond);
                        default:
                                return $this->I18nFormat($this->fullFormatStringWithSeconds);
                }
        }
}

