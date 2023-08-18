/**
 * @copyright	Copyright (C) 2013-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Slider CK
 * @license		GNU/GPL
 * */
 
function ckBeforeImportFromFolder() {
	CKBox.open({style: {padding: '10px'}, fullscreen: false, size: {x: '500px', y: '200px'}, handler: 'inline', content: 'ckfolderimportpopup'});
}

function ckImportFromFolder(clearallimgs) {

	if (! clearallimgs) clearallimgs = false;
	if (clearallimgs) jQuery('#ckslideslist').find('.ckslide').remove();

	var myurl = "index.php?option=com_sliderck&task=ajax.importFromFolder&plugin=folder&" + SLIDERCK.TOKEN;
	jQuery.ajax({
		type: "POST",
		url: myurl,
		dataType: "json",
		data: {
			folder: jQuery('#jform_params_fromfoldername').val()
		}
	}).done(function(imgs) {//
		for (i=0; i<imgs.length; i++) {
			var slide = [];
			slide['imgname'] = imgs[i];
			slide['imgthumb'] = SLIDERCK.URIROOT+imgs[i];
			ckAddSlide(slide);
		}
		CKBox.close();
	}).fail(function() {
		alert('Failed');
	});
}

function showfolderslistck(button) {
	jQuery('#cklistfolders').show();
}

function selectfolderck(folder, field) {
	jQuery('#' + field).val(jQuery(folder).attr('data-foldername'));
	jQuery('#cklistfolders').hide();
}
