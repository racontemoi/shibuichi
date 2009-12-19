<?php

class Calendar extends Page
{

	static $db = array(
 		'DefaultEventDisplay' => 'Int',
		'DefaultDateHeader' => 'Varchar(50)',
		'OtherDatesCount' => 'Int'
	);
	
	static $has_many = array (
		'Announcements' => 'CalendarDateTime',
		'CalendarEvents' => 'CalendarEvent',
		'Feeds' => 'ICSFeed'
	);
	
	static $allowed_children = array (
		'CalendarEvent'
	);
	
	static $defaults = array (
		'DefaultEventDisplay' => '10',
		'DefaultDateHeader' => 'Upcoming Events',
		'OtherDatesCount' => '3'
	);
	
	static $icon = "event_calendar/images/calendar";
	
	static $language;
	static $timezone;
	static $calScale = "GREGORIAN";
  static $defaultFutureMonths = 6;
  
	protected $event_class = null;
	protected $event_object = null;
	protected $event_datetime_class = null;
	protected $event_datetime_object = null;
	
	protected $start_date;
	protected $end_date;
	
	protected $filter_fields;
	
	// Used to create a fake ID for the recursive events
	static $recurring_event_index = 1;
	
	
	static function set_param($param, $value)
	{
		self::$$param = $value;
	}
	
	
	public function getEventClass() {
		if($this->event_class !== null)
			return $this->event_class;
			
		$class = get_class($this);
		$has_manys = eval("return {$class}::\$has_many;");
		if(is_array($has_manys)) {
			foreach($has_manys as $c) {
				if($c == 'CalendarEvent' || is_subclass_of($c, 'CalendarEvent')) {
					$this->event_class = $c;
					return $c;
				}
			}			
		}
	}
	
	public function getEventObject() {
		if($this->event_object !== null)
			return $this->event_object;

		$c = $this->getEventClass();
		$this->event_object = new $c;
		return $this->event_object;
	}
	
	public function getEventDateTimeClass() {
		if($this->event_datetime_class !== null)
			return $this->event_datetime_class;
		$this->event_datetime_class = $this->getEventObject()->getDateTimeClass();
		return $this->event_datetime_class;
	}
	
	public function getEventDateTimeObject() {
		if($this->event_datetime_object !== null)
			return $this->event_datetime_object;
		
		$c = $this->getEventDateTimeClass();	
		$this->event_datetime_object = new $c; 
		return $this->event_datetime_object;
	}
	
	public function getCMSFields()
	{
		$datetimeObj = $this->getEventDateTimeObject();
		$f = parent::getCMSFields();
		$configuration = _t('Calendar.CONFIGURATION','Configuration');
		$f->addFieldsToTab("Root.Content.$configuration", array(
			new NumericField('DefaultEventDisplay', _t('Calendar.NUMBEROFEVENTS','Number of events to display on default view.')),
			new TextField('DefaultDateHeader', _t('Calendar.DEFAULTDATEHEADER','Default date header (displays when no date range has been selected)')),
			new NumericField('OtherDatesCount', _t('Calendar.NUMBERFUTUREDATES','Number of future dates to show for repeating events'))
		));
		
		$table = $this->getEventDateTimeObject()->getAnnouncementTable($this->ID);
		$announcements = _t('Calendar.Announcements','Announcements');
		$f->addFieldToTab("Root.Content.$announcements", $table);
		
		$obj = class_exists("DataObjectManager") ? "DataObjectManager" : "ComplexTableField";
		$table = Object::create(
			$obj,
			$this,
			'Feeds',
			'ICSFeed',
			array('Title' => 'Title of Feed', 'URL' => 'URL'),
			'getCMSFields_forPopup'
		);
		$table->setAddTitle(_t('Calendar.ICSFEED','ICS Feed'));
		$table->setParentClass("Calendar");
		$feeds = _t('Calendar.FEEDS','Feeds');
		$f->addFieldToTab("Root.Content.$feeds", $table);
		return $f;	
	}
	
	public function getEventJoin()
	{
		//$suffix = Versioned::current_stage() == "Live" ? "_Live" : "";
		$join = "LEFT JOIN `CalendarEvent` ON `CalendarEvent`.ID = `CalendarDateTime`.EventID";
		if(is_subclass_of($this->getEventObject(), "CalendarEvent")) {
			$parents = array_reverse(ClassInfo::ancestry($this->getEventClass()));
			foreach($parents as $class) {
				if(ClassInfo::hasTable($class)) {				
					if($class == "CalendarEvent") break;
						$join .= " LEFT JOIN `$class` ON `$class`.ID = `CalendarEvent`.ID";
				}
			}
		}
		return $join;
	}
	
	public function getDateJoin()
	{
		$join = "LEFT JOIN `CalendarDateTime` ON `CalendarDateTime`.EventID = `CalendarEvent`.ID";
		if(is_subclass_of($this->getEventDateTimeObject(), "CalendarDateTime")) {
		  $parents = array_reverse(ClassInfo::ancestry($this->getEventDateTimeClass()));
		  foreach($parents as $class) {
		    if(ClassInfo::hasTable($class)) {
		      if($class == "CalendarDateTime") break;
		      $join .= " LEFT JOIN `$class` ON `$class`.ID = `CalendarDateTime`.ID";
		    }
		  }
		}
		return $join;
	}
	
	protected function getEventIds()
	{
		$ids = array();
		if($children = $this->Children()) {
			foreach($children as $child)
				$ids[] = $child->ID;

			return $ids;
		}
		else {
			return false;
		}
	}
	
	
	public function newRecursionDateTime($recurring_event_datetime, $start_date)
	{
		$c = $this->getEventDateTimeClass();
		$e = new $c;
		foreach($recurring_event_datetime->db() as $field => $type) {
			$e->$field = $recurring_event_datetime->$field;
		}
		$e->StartDate = $start_date;
		$e->EndDate = $start_date;
		$e->EventID = $recurring_event_datetime->EventID;
		$e->ID = "recurring" . self::$recurring_event_index;
		self::$recurring_event_index++;
		return $e;
	}
	
	protected function getStandardEvents($filter = null)
	{
		if(!$ids = $this->getEventIds()) return false;
		$start = $this->start_date->date();
		$end = $this->end_date->date();
		$where = "
			Recursion != 1 
			AND (
				(StartDate <= '$start' AND EndDate >= '$end') OR
				(StartDate BETWEEN '$start' AND '$end') OR
				(EndDate BETWEEN '$start' AND '$end')
			) 
			AND 
			`CalendarDateTime`.EventID IN (" . implode(',',$ids) . ")
		";
		$where .= $filter !== null ? " AND " . $filter : "";
		return DataObject::get(
			$this->getEventDateTimeClass(),
			$where,
			"StartDate ASC, StartTime ASC, `CalendarDateTime`.EventID ASC",
			$this->getEventJoin()
		);
	}
		

	protected function getRecurringEvents($filter = null)
	{

		$where = "Recursion = 1 AND ParentID = {$this->ID}";
		$where .= $filter !== null ? " AND " . $filter : "";

		return DataObject::get(
			$this->getEventClass(),
			$where,
			"`CalendarDateTime`.StartDate ASC",
      $this->getDateJoin()
		);
		
	}
	
	protected function addRecurringEvents($recurring_events,$all_events)
	{
		$date_counter = $this->start_date;
		foreach($recurring_events as $recurring_event) {
			if($recurring_event_datetime = DataObject::get_one($this->getEventDateTimeClass(),"EventID = {$recurring_event->ID}")) {			
				while($date_counter->get() <= $this->end_date->get()){
					// check the end date
					if($recurring_event_datetime->EndDate) {
						$end = strtotime($recurring_event_datetime->EndDate);
						if($end > 0 && $end <= $date_counter->get())
							break;
					}
					if($recurring_event->recursionHappensOn($date_counter->get())) {
						$e = $this->newRecursionDateTime($recurring_event_datetime, $date_counter->date());
						$all_events->push($e);	
					}
					$date_counter->tomorrow();
				}
				$date_counter->reset();
			}
		}
		
		return $all_events;
	}
	
	public function getNextRecurringEvents($event_obj, $datetime_obj, $limit = null)
	{
		$counter = new sfDate($datetime_obj->StartDate);
		if($event = $datetime_obj->Event()->DateTimes()->First())
			$end_date = strtotime($event->EndDate);
		else
			$end_date = false;
		$counter->tomorrow();
		$dates = new DataObjectSet();
		while($dates->Count() != $this->OtherDatesCount) {
			// check the end date
			if($end_date) {
				if($end_date > 0 && $end_date <= $counter->get())
					break;
			}
			if($event_obj->recursionHappensOn($counter->get()))
				$dates->push($this->newRecursionDateTime($datetime_obj,$counter->date()));
			$counter->tomorrow();
		}
		return $dates;	
	}
	
	protected function importFromFeeds($all_events, $start_date = null, $end_date = null)
	{
		foreach($this->Feeds() as $feed) {
			$parser = new iCal(array($feed->URL));
			$ics_events = $parser->iCalReader();
			if(is_array($ics_events) && is_array($ics_events[$feed->URL])) {
				$dt_start = null;
				$dt_end = null;
				foreach($ics_events[$feed->URL] as $event) {
					if( (!$dt_start && !$dt_end) || (!isset($event[$dt_start]) || (!isset($event[$dt_end]))) ) {
						foreach($event as $k => $v) {
							if(substr($k, 0, 7) == "DTSTART")
								$dt_start = $k;
							if(substr($k, 0, 5) == "DTEND")
								$dt_end = $k;
						}
					}
					if(isset($event[$dt_start]) && isset($event[$dt_end])) {
						list($start_date, $end_date, $start_time, $end_time) = CalendarUtil::date_info_from_ics($event[$dt_start], $event[$dt_end]);
						$t_start = strtotime($start_date);
						$t_end = strtotime($end_date);
						if(
							($t_start >= $this->start_date->get() && $t_end <= $this->end_date->get()) ||
							($t_start >= $this->start_date->get() && $t_start <= $this->end_date->get()) ||
							($t_end <= $this->end_date->get() && $t_end >= $this->start_date->get())
						) {
							$c = $this->getEventDateTimeClass();
							$new_date = new $c();
							$new_date->StartDate = $start_date;
							$new_date->StartTime = $start_time;
							$new_date->EndDate = $end_date;
							$new_date->EndTime = $end_time;
							if(isset($event['DESCRIPTION']) && !empty($event['DESCRIPTION']))
								$new_date->Content = $event['DESCRIPTION'];
							if(isset($event['SUMMARY']) && !empty($event['SUMMARY']))
								$new_date->Title = $event['SUMMARY'];
							$new_date->is_announcement = 1;
							$new_date->CalendarID = $this->ID;
							$new_date->ID = "feed" . self::$recurring_event_index;
							$new_date->Feed = true;
							self::$recurring_event_index++;
							$all_events->push($new_date);
						}
					}
				}
			}
		}
		return $all_events;
	}
  	
  	public function Events($filter = null, $start_date = null, $end_date = null, $default_view = false, $limit = null, $announcement_filter = null)
  	{
		$this->start_date = ($start_date instanceof sfDate) ? $start_date : ($start_date !== null ? new sfDate($start_date) : new sfDate());
		$this->end_date = ($end_date instanceof sfDate) ? $end_date : ($end_date !== null ? new sfDate($end_date) : new sfDate());

		if($end_date instanceof sfDate)
			$this->end_date = $end_date;
		elseif($end_date !== null) 
			$this->end_date = new sfDate($end_date);
		else {
			$this->end_date = new sfDate($this->start_date->addMonth(Calendar::$defaultFutureMonths)->date());
			$default_view = true;
			$this->start_date->reset();
		}
		
		if($events = $this->getStandardEvents($filter))
			$event_list = $events;
		else
			$event_list = new DataObjectSet();
		
		$where = $announcement_filter !== null ? " AND $announcement_filter" : "";
		$start = $this->start_date->date();
		$end = $this->end_date->date();
		if($announcements = DataObject::get(
				$this->getEventDateTimeClass(),
			   "CalendarDateTime.CalendarID={$this->ID}
			      AND (
			         (StartDate <= '$start' AND EndDate >= '$end') OR
			         (StartDate BETWEEN '$start' AND '$end') OR
			         (EndDate BETWEEN '$start' AND '$end')
			      ) AND
			      CalendarDateTime.is_announcement=1
			      $where",				
      			"StartDate ASC"
		)) {

			foreach($announcements as $announcement)
				$event_list->push($announcement);
		}

		
		if($recurring = $this->getRecurringEvents($filter)) {
			$event_list = $this->addRecurringEvents($recurring, $event_list);
		}
		
		if($this->Feeds())
			$event_list = $this->importFromFeeds($event_list);
		
		$e = $event_list->toArray();		
		CalendarUtil::date_sort($e);
		$max = $limit === null ? $this->DefaultEventDisplay : $limit;
		if($default_view && $event_list->Count() > $max) {
			$e = array_slice($e, 0, $max);
		}
		$event_list = new DataObjectSet($e);
		return $event_list;
	}
	
	public function UpcomingEvents($limit = null, $filter = null, $announcement_filter = null) 
	{
		return $this->Events($filter, null, null, true, ($limit === null ? $this->DefaultEventDisplay : $limit), $announcement_filter);
	}

	public function RecentEvents($limit = null, $filter = null, $announcement_filter = null) 
	{
		$start_date = new sfDate();
		$end_date = $start_date;
		$events = $this->Events(
			$filter, 
			$start_date->subtractMonth(Calendar::$defaultFutureMonths), 
			$end_date->yesterday(), 
			true, 
			($limit === null ? $this->DefaultEventDisplay : $limit),
			$announcement_filter
		);
		$events->sort('StartDate','DESC');
		return $events;
	}
	
	public static function is_filtered()
	{
		return isset($_GET['filter']) && $_GET['filter'] == 1;
	}
	
	public static function buildFilterString()
	{
		$filters = "";
		if(Calendar::is_filtered()) {
			$filters .= "filter=1";
			foreach(Calendar::getRawFilters() as $key => $value)
				$filters .= "&amp;$key=$value";
		}
		return $filters;
	}
	
	private static function is_filter_key($key)
	{
		return substr($key, 0, 7) == "filter_";
	}
	
	public static function getRawFilters()
	{
		$filters = array();
		foreach($_GET as $key => $value) {
			if(self::is_filter_key($key) && !empty($value))
				$filters[$key] = $value;
		}
		return $filters;
	}

	public static function getCleanFilters()
	{
		if(self::is_filtered()) {
			$filters = array();
			foreach($_GET as $key => $value) {
				if(self::is_filter_key($key)) {
					$filters[str_replace("filter_","",$key)] = $value;
				}
			}
			return $filters;
		}
		return false;
	}
	
	/**
	 * Swaps out underscores with periods for relational data in the SQL query.
	 *	e.g. "MyEvent_Location" becomes "MyEvent.Location"
	 */
	public static function getFiltersForDB()
	{
		if($filters = self::getCleanFilters()) {
			$for_db = array();
			$event_filters = array();
			$datetime_filters = array();
			foreach($filters as $key => $value) {
				$db_field = str_replace("_",".",$key);
				if(stristr($db_field,".") !== false) {
					$parts = explode(".",$db_field);
					$table = $parts[0];
					$field = $parts[1];
					$db_field = "`".$table."`.".$field;
				}
				else {
					$table = $db_field;
					$db_field = "`".$table."`";
				}
        if($table == "CalendarEvent" || is_subclass_of($table, "CalendarEvent"))
          $event_filters[] = $table;
        else if($table == "CalendarDateTime" || is_subclass_of($table, "CalendarDateTime"))
          $datetime_filters[] = $table;
				$for_db[] = "$db_field = '$value'";
			}
			return array($for_db, $event_filters, $datetime_filters);
		}
		return false;
	}

	
	
	public function getFilterFields() {
		return new CalendarFilterFieldSet();
	}
	
	

	
	
}

class Calendar_Controller extends Page_Controller
{
	protected $view;
	protected $year;
	protected $month;
	protected $day;
	
	protected $start_date;
	protected $end_date;
	
	public $filter_form;
	
	
		
	public function init()
	{
		RSSFeed::linkToFeed($this->Link() . "rss", "RSS Feed of this calendar");
		parent::init();
		$this->parseURL();
		//$p = new ICSReader(Director::baseFolder()."/event_calendar/code/Home.ics");
		//die(print_r($p->getEvents()));
	}

	// TO-DO: Check for invalid dates
	public function parseURL()
	{
		// User has specified a start date. We're not in the default view.
		if(isset($this->urlParams['Action'])) { 
			$this->start_date = new sfDate(CalendarUtil::getDateFromString($this->urlParams['Action']));
			// User has specified an end date.
			if(isset($this->urlParams['ID'])) {
				$this->view = "range";
				$this->end_date = new sfDate(CalendarUtil::getDateFromString(str_replace("-","",$this->urlParams['ID'])));
			}
			// No end date specified. Now we have to make one based on the amount of data given in the start date.
			// e.g. 2008-08 will show the entire month of August, and 2008-08-03 will only show events for one day.
			else {
				switch(strlen(str_replace("-","",$this->urlParams['Action'])))
				{
					case 8:
					$this->view = "day";
					$this->end_date = new sfDate($this->start_date->get()+1);
					break;
					
					case 6:
					$this->view = "month";
					$this->end_date = new sfDate($this->start_date->finalDayOfMonth()->date());
					break;
					
					case 4:
					$this->view = "year";
					$this->end_date = new sfDate($this->start_date->finalDayOfYear()->date());
					break;
					
					default:
					$this->view = "default";
					$this->end_date = new sfDate($this->start_date->addMonth(Calendar::$defaultFutureMonths)->date());
					break;
				}
			}
		}
		// The default "landing page." No dates specified. Just show first X number of events (see Calendar::DefaultEventDisplay)
		// Why 6 months? Because otherwise the loop will just run forever.
		else {
			$this->view = "default";
			$this->start_date = new sfDate(date('Y-m-d'));
			$this->end_date = new sfDate($this->start_date->addMonth(Calendar::$defaultFutureMonths)->date());
		}
		$this->start_date->reset();
	}


	// TO-DO: Account for recurring events.
	public function ics()
	{
		$this->urlParams = Director::urlParams();
		$feed = false;
		$announcement = false;
		if(stristr($this->urlParams['ID'], "feed") !== false) {
			$id = str_replace("feed","",$this->urlParams['ID']);
			$feed = true;		
		}
		else if(stristr($this->urlParams['ID'], "announcement-") !== false) {

			$id = str_replace("announcement-","",$this->urlParams['ID']);
			$announcement = true;
		}
		else {
			$id = $this->urlParams['ID'];
			$announcement = false;
		}
		if(is_numeric($id) && isset($this->urlParams['OtherID'])) {
			if(!$feed) { 
				$event = DataObject::get_by_id($announcement ? $this->getModel()->getEventDateTimeClass() : $this->getModel()->getEventClass(), $id);
				$FILENAME = $announcement ? preg_replace("/[^a-zA-Z0-9s]/", "", $event->Title) : $event->URLSegment;
			}
			else
				$FILENAME = preg_replace("/[^a-zA-Z0-9s]/", "", urldecode($_REQUEST['title']));

			$FILENAME .= ".ics";
			$HOST = $_SERVER['HTTP_HOST'];
			$TIMEZONE = Calendar::$timezone;
			$LANGUAGE = Calendar::$language;
			$CALSCALE = Calendar::$calScale;
			$parts = explode('-',$this->urlParams['OtherID']);
			$START_TIMESTAMP = $parts[0];
			$END_TIMESTAMP = $parts[1];
			if(!$feed)
				$URL = $announcement ? $event->Calendar()->Link() : $event->Link();
			else
				$URL = "";
			$TITLE = $feed ? $_REQUEST['title'] : $event->Title;
			header("Cache-Control: private");
			header("Content-Description: File Transfer");
			header("Content-Type: text/calendar");
			header("Content-Transfer-Encoding: binary");
	  		if(stristr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
 				header("Content-disposition: filename=".$FILENAME."; attachment;");
 			else
 				header("Content-disposition: attachment; filename=".$FILENAME);
			
			// pull out the html comments
			return trim(strip_tags($this->customise(array(
				'HOST' => $HOST,
				'LANGUAGE' => $LANGUAGE,
				'TIMEZONE' => $TIMEZONE,
				'CALSCALE' => $CALSCALE,
				'START_TIMESTAMP' => $START_TIMESTAMP,
				'END_TIMESTAMP' => $END_TIMESTAMP,
				'URL' => $URL,
				'TITLE' => $TITLE
			))->renderWith(array('ics'))));
		}
		else {
			Director::redirectBack();
		}
	}
	
	
	public function RSSLink()
	{
		return $this->Link('rss');
	}
	
	public function rss() 
	{
		$events = $this->getModel()->UpcomingEvents(null,$this->DefaultEventDisplay);
		foreach($events as $event) {
			$event->Title = strip_tags($event->_Dates()) . " : " . $event->EventTitle();
			$event->Description = $event->EventContent();
		}
		$rss = new RSSFeed($events, $this->Link(), sprintf(_t("Calendar.UPCOMINGEVENTSFOR","Upcoming Events for %s"),$this->Title), "", "Title", "Description");

		if(is_int($rss->lastModified)) {
			HTTP::register_modification_timestamp($rss->lastModified);
			header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $rss->lastModified) . ' GMT');
		}
		if(!empty($rss->etag)) {
			HTTP::register_etag($rss->etag);
		}
		$xml = str_replace('&nbsp;', '&#160;', $rss->renderWith('RSSFeed'));
		$xml = preg_replace('/<!--(.|\s)*?-->/', '', $xml);
		$xml = trim($xml);
		HTTP::add_cache_headers();
		header("Content-type: text/xml");
		echo $xml;
	}
	
	public function import()
	{
    if(isset($this->urlParams['ID']))
      $file = Director::baseFolder()."/event_calendar/import/".$this->urlParams['ID'].".ics";
      if(file_exists($file)) {
  			$parser = new iCal(array($file));
  			$ics_events = $parser->iCalReader();
  			if(is_array($ics_events) && is_array($ics_events[$file])) {
  				$dt_start = null;
  				$dt_end = null;
  				$i=1;
  				foreach($ics_events[$file] as $event) {
  					if( (!$dt_start && !$dt_end) || (!isset($event[$dt_start]) || (!isset($event[$dt_end]))) ) {
  						foreach($event as $k => $v) {
  							if(substr($k, 0, 7) == "DTSTART")
  								$dt_start = $k;
  							if(substr($k, 0, 5) == "DTEND")
  								$dt_end = $k;
  						}
  					}
  					if(isset($event[$dt_start]) && isset($event[$dt_end])) {
  						list($start_date, $end_date, $start_time, $end_time) = CalendarUtil::date_info_from_ics($event[$dt_start], $event[$dt_end]);
							$c = $this->getModel()->getEventDateTimeClass();
							$new_date = new $c();
							$new_date->StartDate = $start_date;
							$new_date->StartTime = $start_time;
							$new_date->EndDate = $end_date;
							$new_date->EndTime = $end_time;
							if(isset($event['DESCRIPTION']) && !empty($event['DESCRIPTION']))
								$new_date->Content = $event['DESCRIPTION'];
							if(isset($event['SUMMARY']) && !empty($event['SUMMARY']))
								$new_date->Title = $event['SUMMARY'];
							$new_date->is_announcement = 1;
							$new_date->CalendarID = $this->ID;
							$new_date->write();
							echo sprintf("<p style='color:green;'>Event <em>%s</em> imported successfully, and was assigned ID %d</p>",$new_date->Title, $new_date->ID);
  					}
  					else
            	echo sprintf("<p style='color:red;'>Event #%d could not be imported.</p>",$i);  					 
  				  $i++;	   					
  				}
  		  }
  		  die();        
      }
      else
        die("The file $file could not be found.");      
	}
	
	public function doCalendarFilter($data,$form)
	{
		$link = $this->Link() . $data['StartYear'] . "-" . $data['StartMonth'] . "-" . $data['StartDay'] . "/" . $data['EndYear'] . "-" . $data['EndMonth'] . "-" . $data['EndDay'];
		if(isset($data['filter'])) {
			$link .= "?filter=1";
			foreach($data['filter'] as $key => $value) {
				if(!empty($value))
					$link .= "&filter_" . $key . "=" . urlencode($value);
			}
		}
							
		Director::redirect($link);
	}
		
	public function DateHeader()
	{
		switch($this->view)
		{
			case "day":
				return CalendarUtil::localize($this->start_date->get(), null, CalendarUtil::ONE_DAY_HEADER);
			break;
			
			case "month":
				return CalendarUtil::localize($this->start_date->get(), null, CalendarUtil::MONTH_HEADER);
			break;
			
			case "year":
				return CalendarUtil::localize($this->start_date->get(), null, CalendarUtil::YEAR_HEADER);
			break;
			
			case "range":
				list($strStartDate,$strEndDate) = CalendarUtil::getDateString($this->start_date->date(),$this->end_date->date());
				return $strStartDate.$strEndDate;
			break;
			
			default: 
				return $this->DefaultDateHeader;
			break;
		}
	 }
	 
	public function getModel() 
	{
		$model_class = str_replace("_Controller", "", get_class($this));
		return DataObject::get_by_id($model_class,$this->ID);	
	}
	
	
	public function Events($filter = null, $announcement_filter = null)
	{
		if(list($db_clauses,$event_filters,$datetime_filters) = Calendar::getFiltersForDB()) {
			$filter = (sizeof($db_clauses > 1)) ? implode(" AND ", $db_clauses) : $db_clauses;
      if(!empty($datetime_filters))
        $announcement_filter = sizeof($datetime_filters) > 1 ? implode(" AND ", $datetime_filters) : $datetime_filters;
		}
		return $this->getModel()->Events($filter, $this->start_date, $this->end_date, ($this->view == "default"), null, $announcement_filter);
	}
		
	public function CalendarWidget()
	{
		return new CalendarWidget($this, $this->start_date, $this->end_date, ($this->view == "default"));
	}
	
	public function MonthNavigator()
	{
		return new MonthNavigator($this, $this->start_date, $this->end_date);
	}

	public function LiveCalendarWidget()
	{
		return new LiveCalendarWidget($this, $this->start_date, $this->end_date, ($this->view == "default"));
	}


	public function CalendarFilterForm()
	{
		$start_date = $this->start_date;
		if($this->end_date === null || !$this->end_date instanceof sfDate || $this->view == "default")
			$end_date = $start_date;
		else
			$end_date = $this->end_date;

		$form = new Form(
			$this,
			'CalendarFilterForm',
			$this->getModel()->getFilterFields(),
			new FieldSet(
				new FormAction('doCalendarFilter',_t('Calendar.FILTER','Filter'))
			)
		);

		$form_data = array (
			'StartMonth' => $start_date->format('m'),
			'StartDay' => $start_date->format('d'),
			'StartYear' => $start_date->format('Y'),
			'EndMonth' => $end_date->format('m'),
			'EndDay' => $end_date->format('d'),
			'EndYear' => $end_date->format('Y')
		);

		if($filters = Calendar::getCleanFilters()) {
			foreach($filters as $key => $value) {
				$form_data["filter[".$key."]"] = $value;
			}
		}
		$form->loadDataFrom($form_data);
		$form->unsetValidator();
		
		return $form;

	}
	
	
	
	
	
	
}

?>