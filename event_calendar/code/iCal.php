<?php 
class iCal {  

    private $ics_files = array(); 
     
    function iCal($ics_files) { 
        $this->ics_files = $ics_files;
    } 
     
    function iCalList() { 
            return array_filter($this->ics_files, array($this,"iCalClean")); 
    } 
     
    function iCalClean($file) { 
            return strpos($file, '.ics'); 
    } 
     
    function iCalReader() { 
        $array = $this->iCalList(); 
        foreach ($array as $icalfile) { 
            $iCaltoArray[$icalfile] = $this->iCalDecoder($icalfile); 
        } 
        return $iCaltoArray; 
    } 
     
    function iCalDecoder($file) { 
        $ical = file_get_contents($file); 
        preg_match_all('/(BEGIN:VEVENT.*?END:VEVENT)/si', $ical, $result, PREG_PATTERN_ORDER); 
        for ($i = 0; $i < count($result[0]); $i++) { 
            $tmpbyline = explode("\r\n", $result[0][$i]); 
             
            foreach ($tmpbyline as $item) { 
                $tmpholderarray = explode(":",$item); 
                if (count($tmpholderarray) >1) {  
                    $majorarray[$tmpholderarray[0]] = $tmpholderarray[1]; 
                } 
                 
            } 
            /* 
                lets just finish what we started.. 
            */ 
            /*if (preg_match('/DESCRIPTION:(.*)END:VEVENT/si', $result[0][$i], $regs)) { 
                $majorarray['DESCRIPTION'] = str_replace("  ", " ", str_replace("\r\n", "", $regs[1])); 
            } */ 
            $icalarray[] = $majorarray; 
            unset($majorarray); 
              
              
        } 
        return $icalarray; 
    } 
     
} 