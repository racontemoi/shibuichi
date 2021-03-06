/* -*- coding: utf-8 -*- */
Behaviour.register({
	'input#Form_EditForm_Title': {
		/**
		 * Get the URL segment to suggest a new field
		 */
		onchange: function() {
	                // from http://stackoverflow.com/questions/990904/javascript-remove-accents-in-strings-in-ie6
	                function accentsTidy(s) {
			  var r = s.toLowerCase();
			  r = r.replace(new RegExp("[àáâãäå]", 'g'),"a");
			  r = r.replace(new RegExp("æ", 'g'),"ae");
			  r = r.replace(new RegExp("ç", 'g'),"c");
			  r = r.replace(new RegExp("[èéêë]", 'g'),"e");
			  r = r.replace(new RegExp("[ìíîï]", 'g'),"i");
			  r = r.replace(new RegExp("ñ", 'g'),"n");                            
			  r = r.replace(new RegExp("[òóôõö]", 'g'),"o");
			  r = r.replace(new RegExp("œ", 'g'),"oe");
			  r = r.replace(new RegExp("[ùúûü]", 'g'),"u");
			  r = r.replace(new RegExp("[ýÿ]", 'g'),"y");
			  return r;
			};

			if(this.value.length == 0) return;
			if(!$('Form_EditForm_URLSegment')) return;
			
			var urlSegmentField = $('Form_EditForm_URLSegment');
			var newSuggestion = urlSegmentField.suggestNewValue( accentsTidy( this.value ) );
			var isNew = urlSegmentField.value.indexOf("new") == 0;
			var confirmMessage = ss.i18n.sprintf(
				ss.i18n._t('UPDATEURL.CONFIRM', 'Would you like me to change the URL to:\n\n%s/\n\nClick Ok to change the URL, click Cancel to leave it as:\n\n%s'),
				newSuggestion,
				urlSegmentField.value
			);
			
			if( 
				newSuggestion == urlSegmentField.value 
				|| isNew 
				|| confirm(confirmMessage)
			) {
				urlSegmentField.value = newSuggestion;
			}
			// If you type in Page name, the Navigation Label and Meta Title should automatically update the first time
			// @todo: Change file name from UpdateURL to something more geneneric since we now do more than update the URL.
			if($('Form_EditForm_MetaTitle') && $('Form_EditForm_MenuTitle').value.indexOf("New") == 0 ) {
				$('Form_EditForm_MenuTitle').value = this.value;
			}
			// @todo see if updating this is confusing (Q: why isn't my page title changing? A: Check the Meta-Data tab)
			if($('Form_EditForm_MetaTitle') && $('Form_EditForm_MetaTitle').value.length == 0 ) {
				$('Form_EditForm_MetaTitle').value = this.value;
			}
		}
	}
});
