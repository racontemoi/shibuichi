<?php
// -------------------------------------------------------------------
// German translation for the Event Calendar
// -------------------------------------------------------------------

i18n::include_locale_file('event_calendar', 'en_US');

global $lang;

if(array_key_exists('de_DE', $lang) && is_array($lang['de_DE'])) {
	$lang['de_DE'] = array_merge($lang['en_US'], $lang['de_DE']);
} else {
	$lang['de_DE'] = $lang['en_US'];
}

/** Date Templating **/

// e.g. 4. Okt. 2009
$lang['de_DE']['Calendar']['OneDay'] = 
	'%{sDayNumShort}. %{sMonShort}. %{sYearFull}';

// e.g. 4. - 6. Okt. 2009
$lang['de_DE']['Calendar']['SameMonthSameYear'] = 
	'%{sDayNumShort}. - %{eDayNumShort}. %{sMonShort}. %{eYearFull}';

// e.g. 4. Okt. - 6. Nov. 2009
$lang['de_DE']['Calendar']['DiffMonthSameYear'] = 
	'%{sDayNumShort}. %{sMonShort}. - %{eDayNumShort}. %{eMonShort}. %{eYearFull}';

// e.g. 30. Dez. 2008 - 2. Jan. 2009
$lang['de_DE']['Calendar']['DiffMonthDiffYear'] = 
	'%{sDayNumShort}. %{sMonShort}. %{sYearFull} - %{eDayNumShort}. %{eMonShort}. %{eYearFull}';


// "Headers" control the display when a date range is given to the calendar through the URL.
$lang['de_DE']['Calendar']['OneDayHeader'] =
	'%{sDayNumShort}. %{sMonFull} %{sYearFull}';

$lang['de_DE']['Calendar']['MonthHeader'] =
	'%{sMonFull} %{sYearFull}';

$lang['de_DE']['Calendar']['YearHeader'] =
	'%{sYearFull}';	
	
/** Language **/


// Output for class or file: Calendar
$lang['de_DE']['Calendar']['NUMBEROFEVENTS'] = 
	'Anzahl anzuzeigender Anlässe (Standard-Ansicht).';
$lang['de_DE']['Calendar']['DEFAULTDATEHEADER'] = 
	'Standard-Titel (wird angezeigt wenn kein Datum selektiert worden ist).';
$lang['de_DE']['Calendar']['NUMBERFUTUREDATES'] = 
	'Maximal anzuzeigende Daten bei sich wiederholenden Anlässen';
$lang['de_DE']['Calendar']['UPCOMINGEVENTSFOR'] = 
	'Kommende Anlässe für %s';
$lang['de_DE']['Calendar']['FILTER'] = 
	'Filtern';

// Output for class or file: CalendarDateTime
$lang['de_DE']['CalendarDateTime']['INVALIDFORMAT'] = 
	'Ungültiges Datums-Format. Gültig sind "dmy" oder "mdy".';

// Output for class or file: CalendarEvent
$lang['de_DE']['CalendarEvent']['REPEATEVENT'] = 
	'Wiederhole diesen Anlass';
$lang['de_DE']['CalendarEvent']['DESCRIBEINTERVAL'] = 
	'Definiere den Zeitraum in dem dieser Anlass wieder auftritt:';
$lang['de_DE']['CalendarEvent']['EVERY'] = 
	'Jede(n) ';
$lang['de_DE']['CalendarEvent']['DAYS'] = 
	' Tag';
$lang['de_DE']['CalendarEvent']['WEEKS'] = 
	' Woche';
$lang['de_DE']['CalendarEvent']['ONFOLLOWINGDAYS'] = 
	'On the following day(s)...';
$lang['de_DE']['CalendarEvent']['MONTHS'] = 
	' Monat';
$lang['de_DE']['CalendarEvent']['ONTHESEDATES'] = 
	'An diesen Daten...';
$lang['de_DE']['CalendarEvent']['ONTHE'] = 
	'Am ...';
$lang['de_DE']['CalendarEvent']['OFTHEMONTH'] = 
	' des Monats.';
$lang['de_DE']['CalendarEvent']['ANYEXCEPTIONS'] = 
	'Gibt es Ausnahmen bei den sich wiederholenden Anlässen? Wenn ja, allfällige Ausnahmen unten eintragen.';
$lang['de_DE']['CalendarEvent']['DATE'] = 
	'Datum';
$lang['de_DE']['CalendarEvent']['RSSFEED'] = 
	'RSS-Feed dieses Kalenders';

// Output for class or file: Calendar.ss
$lang['de_DE']['Calendar.ss']['BROWSECALENDAR'] = 
	'Durchsuche den Kalender';
$lang['de_DE']['Calendar.ss']['USECALENDAR'] = 
	'Kalender benutzen um Anlässe zu finden.';
$lang['de_DE']['Calendar.ss']['SUBSCRIBE'] = 
	'Diesen Kalender abonnieren.';
$lang['de_DE']['Calendar.ss']['ALLDAY'] = 
	'Den ganzen Tag';
$lang['de_DE']['Calendar.ss']['TIME'] = 
	'Zeit';
$lang['de_DE']['Calendar.ss']['MORE'] = 
	'mehr...';
$lang['de_DE']['Calendar.ss']['SEEALSO'] = 
	'Siehe auch';
$lang['de_DE']['Calendar.ss']['ADD'] = 
	'Zu meinem Kalender hinzufügen';
$lang['de_DE']['Calendar.ss']['NOEVENTS'] = 
	'Es wurden keine Anlässe gefunden.';

// Output for class or file: CalendarEvent.ss
$lang['de_DE']['CalendarEvent.ss']['BROWSECALENDAR'] = 
	$lang['de_DE']['Calendar.ss']['BROWSECALENDAR'];
$lang['de_DE']['CalendarEvent.ss']['USECALENDAR'] = 
	$lang['de_DE']['Calendar.ss']['USECALENDAR'];
$lang['de_DE']['CalendarEvent.ss']['FILTERCALENDAR'] = 
	'Kalender filtrieren';
$lang['de_DE']['CalendarEvent.ss']['BACKTO'] = 
	'Zurück zu';
$lang['de_DE']['CalendarEvent.ss']['SUBSCRIBE'] = 
	$lang['de_DE']['Calendar.ss']['SUBSCRIBE'];
$lang['de_DE']['CalendarEvent.ss']['ADDITIONALDATES'] = 
	'Zusätzliche Daten';

$lang['de_DE']['CalendarWidget']['LOCALEFILE'] = 'date_de.js';