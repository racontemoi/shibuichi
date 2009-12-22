<?php // php5

/*

Copyright (c) 2009 Dimas Begunoff, http://www.farinspace.com
Modified for Silverstripe by Denis Rosset

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT XOR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

class GoogleSpreadsheet extends Object
{
	private $client;
	private $spreadsheet;
	private $worksheet = "Sheet1";

	function __construct($user,$pass,$ss=FALSE,$ws=FALSE)
	{
		$this->login($user,$pass);
		if ($ss) $this->useSpreadsheet($ss);
		if ($ws) $this->useWorksheet($ws);
	}

	function useSpreadsheet($ss,$ws=FALSE)
	{
		$this->spreadsheet = $ss;
		if ($ws) $this->useWorksheet($ws);
	}

	function useWorksheet($ws)
	{
		$this->worksheet = $ws;
	}

	function addRow($row)
	{
		if ($this->client instanceof Zend_Gdata_Spreadsheets)
		{
			$ss_id = $this->getSpreadsheetId($this->spreadsheet);
			$ws_id = $this->getWorksheetId($ss_id,$this->worksheet);

			$insert_row = array();
			foreach ($row as $k => $v) $insert_row[$this->cleanKey($k)] = $v;

			$entry = $this->client->insertRow($insert_row,$ss_id,$ws_id);
			if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) return TRUE;
		}

		return FALSE;
	}

	// http://code.google.com/apis/spreadsheets/docs/2.0/reference.html#ListParameters
	function updateRow($row,$search)
	{
		if ($this->client instanceof Zend_Gdata_Spreadsheets AND $search)
		{
			$feed = $this->findRows($search);
			
			if ($feed->entries)
			{
				foreach($feed->entries as $entry) 
				{
					if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry)
					{
						$update_row = array();

						$customRow = $entry->getCustom();
						foreach ($customRow as $customCol) 
						{
							$update_row[$customCol->getColumnName()] = $customCol->getText();
						}
			
						// overwrite with new values
						foreach ($row as $k => $v) 
						{
							$update_row[$this->cleanKey($k)] = $v;
						}

						// update row data, then save
						$entry = $this->client->updateRow($entry,$update_row);
						if ( ! ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry)) return FALSE;
					}
				}

				return TRUE;
			}
		}

		return FALSE;
	}

	// http://code.google.com/apis/spreadsheets/docs/2.0/reference.html#ListParameters
	function getRows($search=FALSE)
	{
		$rows = array();
		
		if ($this->client instanceof Zend_Gdata_Spreadsheets)
		{
			$feed = $this->findRows($search);
			
			if ($feed->entries)
			{
				foreach($feed->entries as $entry) 
				{
					if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry)
					{
						$row = array();
						
						$customRow = $entry->getCustom();
						foreach ($customRow as $customCol) 
						{
							$row[$customCol->getColumnName()] = $customCol->getText();
						}

						$rows[] = $row;
					}
				}
			}
		}

		return $rows;
	}

	// user contribution by dmon (6/10/2009)
	function deleteRow($search)
	{
		if ($this->client instanceof Zend_Gdata_Spreadsheets AND $search)
		{
			$feed = $this->findRows($search);
			
			if ($feed->entries)
			{
				foreach($feed->entries as $entry)
				{
					if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry)
					{
						$this->client->deleteRow($entry);
						
						if ( ! ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry)) return FALSE;
					}
				}

				return TRUE;
			}
		}

		return FALSE;
	}

	private function login($user,$pass)
	{
		// Zend Gdata package required
		// http://framework.zend.com/download/gdata
		
		require_once 'Zend/Loader.php';
		Zend_Loader::loadClass('Zend_Http_Client');
		Zend_Loader::loadClass('Zend_Gdata');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');

		$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
		$http = Zend_Gdata_ClientLogin::getHttpClient($user,$pass,$service);
		$this->client = new Zend_Gdata_Spreadsheets($http);

		if ($this->client instanceof Zend_Gdata_Spreadsheets) return TRUE;

		return FALSE;
	}

	private function findRows($search=FALSE)
	{
		$query = new Zend_Gdata_Spreadsheets_ListQuery();

		$ss_id = $this->getSpreadsheetId($this->spreadsheet);
		$query->setSpreadsheetKey($ss_id);

		$ws_id = $this->getWorksheetId($ss_id,$this->worksheet);
		$query->setWorksheetId($ws_id);

		if ($search) $query->setSpreadsheetQuery($search);

		$feed = $this->client->getListFeed($query);

		return $feed;
	}

	private function getSpreadsheetId($ss=FALSE)
	{
		$feed = $this->client->getSpreadsheetFeed();

		foreach($feed->entries as $entry) 
		{
			if ($entry->title->text == $ss)
			{
				return array_pop(explode("/",$entry->id->text));
			}
		}

		return FALSE;
	}

	private function getWorksheetId($ss_id,$ws=FALSE)
	{
		$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
		$query->setSpreadsheetKey($ss_id);
		$feed = $this->client->getWorksheetFeed($query);

		foreach($feed->entries as $entry) 
		{
			if ($entry->title->text == $ws)
			{
				return array_pop(explode("/",$entry->id->text));
			}
		}

		return FALSE;
	}

	private function cleanKey($k)
	{
		return strtolower(str_replace(array(" ","_",":"),"",$k));
	}
}

?>