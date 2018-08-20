/*
 * LaraClassified - Geo Classified Ads CMS
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

/* Prevent errors, If these variables are missing. */
if (typeof postType === 'undefined') {
	var postType = 0;
}

if (typeof packageIsEnabled === 'undefined') {
	var packageIsEnabled = false;
}
var select2Language = languageCode;

if (typeof langLayout !== 'undefined' && typeof langLayout.select2 !== 'undefined') {
	select2Language = langLayout.select2;
}

$(document).ready(function() {
	/* CSRF Protection */
	var token = $('meta[name="csrf-token"]').attr('content');
	if (token) {
		$.ajaxSetup({
			headers: {'X-CSRF-TOKEN': token},
			async: true,
			cache: false
		});
	}
	
	postType = $('[id^=postTypeId-]:checked').val();
	
	/* On load */
	getCustomFieldsByPostType(siteUrl, languageCode, postType);
	
	/* On category selected */
	$('[id^=postTypeId-]').bind('click, change', function() {
        
        
        postType = $(this).val();
        
		/* Get the category's custom fields */
		getCustomFieldsByPostType(siteUrl, languageCode, postType);
	});
	
});

function initSelect2(selectElementObj, languageCode) {
	selectElementObj.find('.selecter').select2({
		language: select2Language,
		dropdownAutoWidth: 'true',
		minimumResultsForSearch: Infinity
	});
	
	selectElementObj.find('.sselecter').select2({
		language: select2Language,
		dropdownAutoWidth: 'true'
	});
}


/**
 * Get the Custom Fields by Post Type
 *
 * @param siteUrl
 * @param languageCode
 * @param postTypeId
 * @returns {*}
 */
function getCustomFieldsByPostType(siteUrl, languageCode, postTypeId) {
	/* Check undefined variables */
	if (typeof languageCode === 'undefined' || typeof postTypeId === 'undefined') {
		return false;
	}
	
	/* Don't make ajax request if any category has selected. */
	if (postTypeId == 0 || postTypeId == '') {
		return false;
	}
	
	/* Make ajax call */
	$.ajax({
		method: 'POST',
		url: siteUrl + '/ajax/post_type/custom-fields',
		data: {
			'_token': $('input[name=_token]').val(),
			'languageCode': languageCode,
			'postTypeId': postTypeId,
			'errors': errors,
			'oldInput': oldInput,
			'postId': (typeof postId !== 'undefined') ? postId : ''
		}
	}).done(function(obj) {
		/* Load Custom Fields */
		$('#customFields').html(obj.customFields);
		
		console.log(obj.customFields);
		/* Apply Fields Components */
		initSelect2($('#customFields'), languageCode);
	});
	
	return postTypeId;
}