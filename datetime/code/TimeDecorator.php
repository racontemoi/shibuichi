<?php /* -*- coding: utf-8 -*- */
class TimeDecorator extends Extension {
        function Hour() {
                return (int)date('G', strtotime($this->owner->RAW()));
        }
        function Minute() {
                return (int)date('i', strtotime($this->owner->RAW()));
        }
        function Second() {
                return (int)date('s', strtotime($this->owner->RAW()));
        }
        function IsAM()
        {
                return $this->Hour() >= 12;
        }
}
?>