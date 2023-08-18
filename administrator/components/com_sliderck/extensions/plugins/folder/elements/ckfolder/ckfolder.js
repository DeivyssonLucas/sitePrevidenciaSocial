/**
 * @copyright	Copyright (C) 2016 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

function ckGetFolder(id, path) {
	document.getElementById(id).value = path;
	CKBox.close();
}

function ckMakeImagesSortable() {
	jQuery('#labelseditor').sortable({
		items: '> div:not(.ckheader)',
		handle: '.ckmove',
		update: function( event, ui ){
		}
	});
}

function ckEditLabelsFile() {
	var path = jQuery('#jform_params_autoloadfoldername').val();
	myurl = SLIDERCK.URIBASE + '/index.php?option=com_sliderck&task=ajax.loadLabelsFile&plugin=folder&' + SLIDERCK.TOKEN;
	jQuery.ajax({
			type: "POST",
			url: myurl,
			data: {
				path: path
			},
		}).done(function(code) {
			jQuery('#jform_params_autoloadfoldernamelabelseditor').empty().append(code);
			CKBox.open({
				handler: 'inline',
				content: 'jform_params_autoloadfoldernamelabelseditor',
				style: {padding: '10px'},
				footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveLabelsToFile()">'+Joomla.JText._('CK_SAVE_CLOSE')+'</a>'
			});
			CKApi.Tooltip('#jform_params_autoloadfoldernamelabelseditor .cktip', {zindex: '100030', placement: 'right'});
			ckMakeImagesSortable();
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
}

function ckSaveLabelsToFile() {
	var path = jQuery('#jform_params_autoloadfoldername').val();
	var labels = '';
	
	jQuery('#labelseditor .ckrow').each(function() {
		var title = jQuery(this).find('.labeltitle').val();
		var desc = jQuery(this).find('.labeldesc').val();
		var link = jQuery(this).find('.labellink').val();

		labels += jQuery(this).find('.ckfile').attr('data-image')
			+ '|' + title
			+ '|' + desc
			+ '|' + link;
		labels += "\r\n";
	});
	myurl = SLIDERCK.URIBASE + '/index.php?option=com_sliderck&task=ajax.writeLabelsFile&plugin=folder&' + SLIDERCK.TOKEN;
	jQuery.ajax({
			type: "POST",
			url: myurl,
			data: {
				path: path,
				labels: labels
			},
		}).done(function(code) {
			if (code != '1') {
				alert('Error when writing the file labels.txt in the folder ' + path + ', please edit it manually.');
			}
			CKBox.close();
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
}