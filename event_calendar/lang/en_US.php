<?php
// -------------------------------------------------------------------
// This file was automatically generated by the LangBuilder.php script
// Date of creation: 2009/04/13 18:57:21
// -------------------------------------------------------------------
global $lang;


/** Date Templating **/

// e.g. Oct 4th, 2009
$lang['en_US']['Calendar']['OneDay'] = 
	'%{sMonShort}. %{sDayNumShort}, %{sYearFull}';

// e.g. Oct 4th - 6th, 2009
$lang['en_US']['Calendar']['SameMonthSameYear'] = 
	'%{sMonShort}. %{sDayNumShort} - %{eDayNumShort}, %{eYearFull}';

// e.g. Oct 4th - Nov. 6th, 2009
$lang['en_US']['Calendar']['DiffMonthSameYear'] = 
	'%{sMonShort}. %{sDayNumShort} - %{eMonShort}. %{eDayNumShort}, %{eYearFull}';

// e.g. Dec 30th, 2008 - Jan 2nd, 2009
$lang['en_US']['Calendar']['DiffMonthDiffYear'] = 
	'%{sMonShort}. %{sDayNumShort}, %{sYearFull} - %{eMonShort} %{eDayNumShort}, %{eYearFull}';


// "Headers" control the display when a date range is given to the calendar through the URL.
$lang['en_US']['Calendar']['OneDayHeader'] =
	'%{sMonFull} %{sDayNumShort}%{sDaySuffix}, %{sYearFull}';

$lang['en_US']['Calendar']['MonthHeader'] =
	'%{sMonFull}, %{sYearFull}';

$lang['en_US']['Calendar']['YearHeader'] =
	'%{sYearFull}';	
	
/** Language **/


// Output for class or file: Calendar
$lang['en_US']['Calendar']['NUMBEROFEVENTS'] = 
	'Number of events to display on default view.';
$lang['en_US']['Calendar']['DEFAULTDATEHEADER'] = 
	'Default date header (displays when no date range has been selected)';
$lang['en_US']['Calendar']['NUMBERFUTUREDATES'] = 
	'Number of future dates to show for repeating events';
$lang['en_US']['Calendar']['UPCOMINGEVENTSFOR'] = 
	'Upcoming Events for %s';
$lang['en_US']['Calendar']['FILTER'] = 
	'Filter';

// Output for class or file: CalendarDateTime
$lang['en_US']['CalendarDateTime']['INVALIDFORMAT'] = 
	'Invalid date format. Must be either "dmy" or "mdy"';

// Output for class or file: CalendarEvent
$lang['en_US']['CalendarEvent']['REPEATEVENT'] = 
	'Repeat this event';
$lang['en_US']['CalendarEvent']['DESCRIBEINTERVAL'] = 
	'Describe the interval at which this event recurs.';
$lang['en_US']['CalendarEvent']['EVERY'] = 
	'Every ';
$lang['en_US']['CalendarEvent']['DAYS'] = 
	' day(s)';
$lang['en_US']['CalendarEvent']['WEEKS'] = 
	' weeks';
$lang['en_US']['CalendarEvent']['ONFOLLOWINGDAYS'] = 
	'On the following day(s)...';
$lang['en_US']['CalendarEvent']['MONTHS'] = 
	' month(s)';
$lang['en_US']['CalendarEvent']['ONTHESEDATES'] = 
	'On these date(s)...';
$lang['en_US']['CalendarEvent']['ONTHE'] = 
	'On the...';
$lang['en_US']['CalendarEvent']['OFTHEMONTH'] = 
	' of the month.';
$lang['en_US']['CalendarEvent']['ANYEXCEPTIONS'] = 
	'Any exceptions to this pattern? Add the dates below.';
$lang['en_US']['CalendarEvent']['DATE'] = 
	'Date';
$lang['en_US']['CalendarEvent']['RSSFEED'] = 
	'RSS Feed of this calendar';

// Output for class or file: Calendar.ss
$lang['en_US']['Calendar.ss']['BROWSECALENDAR'] = 
	'Browse the Calendar';
$lang['en_US']['Calendar.ss']['USECALENDAR'] = 
	'Use the calendar below to navigate dates';
$lang['en_US']['Calendar.ss']['SUBSCRIBE'] = 
	'Subscribe to the Calendar';
$lang['en_US']['Calendar.ss']['ALLDAY'] = 
	'All Day';
$lang['en_US']['Calendar.ss']['TIME'] = 
	'Time';
$lang['en_US']['Calendar.ss']['MORE'] = 
	'more...';
$lang['en_US']['Calendar.ss']['SEEALSO'] = 
	'See also';
$lang['en_US']['Calendar.ss']['ADD'] = 
	'Add to Calendar';
$lang['en_US']['Calendar.ss']['NOEVENTS'] = 
	'There are no events';

// Output for class or file: CalendarEvent.ss
$lang['en_US']['CalendarEvent.ss']['BROWSECALENDAR'] = 
	'Browse the Calendar';
$lang['en_US']['CalendarEvent.ss']['USECALENDAR'] = 
	'Use the calendar below to navigate dates';
$lang['en_US']['CalendarEvent.ss']['FILTERCALENDAR'] = 
	'Filter calendar';
$lang['en_US']['CalendarEvent.ss']['BACKTO'] = 
	'Back to';
$lang['en_US']['CalendarEvent.ss']['SUBSCRIBE'] = 
	'Subscribe to the Calendar';
$lang['en_US']['CalendarEvent.ss']['ADDITIONALDATES'] = 
	'Additional Dates';

$lang['en_US']['CalendarWidget']['LOCALEFILE'] = 'date_en.js';

?>