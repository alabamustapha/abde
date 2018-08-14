<?php
/**
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

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Post\Traits\CustomFieldTrait;
use App\Models\Category;
use App\Http\Controllers\FrontController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class PostTypeController extends FrontController
{
	use CustomFieldTrait;

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCustomFields(Request $request)
	{
		$languageCode = $request->input('languageCode');
		$postTypeId = $request->input('postTypeId');
		$postId = $request->input('postId');
		
		// Custom Fields vars
		$errors = stripslashes($request->input('errors'));
		$errors = collect(json_decode($errors, true));
		$oldInput = stripslashes($request->input('oldInput'));
		$oldInput = json_decode($oldInput, true);
		
		
		// Get the Category's Custom Fields buffer
		$customFields = $this->getPostTypeFieldsBuffer($postTypeId, $languageCode, $errors, $oldInput, $postId);
		
		// Get Result's Data
		$data = [
			'customFields' => $customFields,
		];
		
		return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
	}
}
