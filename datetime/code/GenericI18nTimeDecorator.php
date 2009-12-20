<?php
class GenericI18nTimeDecorator extends I18nTimeDecorator {
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
                        '%H' => substr('00' . $this->Hour(), -2),
                        '%h' => $this->Hour(),
                        '%I' => substr('00' . (($this->Hour() - 1) % 12 + 1), -2),
                        '%l' => substr('  ' . (($this->Hour() - 1) % 12 + 1), -2),
                        '%L' => (($this->Hour() - 1) % 12 + 1), // ext
                        '%M' => substr('00' . $this->Minute(), -2),
                        '%p' => ($this->IsAM() ? 'PM' : 'AM'),
                        '%P' => ($this->IsAM() ? 'pm' : 'am'),
                        '%r' => null,
                        '%R' => null,
                        '%S' => substr('00' . $this->Second(), -2),
                        '%o' => $this->Second(),
                        '%T' => null,
                        '%X' => null,
                        '%z' => null,
                        '%Z' => null);
                foreach ($elements as $key => $value)
                        $formatString = str_replace(
                                $key, $value, $formatString);
                return $formatString;
        }
}
?>