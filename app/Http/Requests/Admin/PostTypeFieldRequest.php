<?php
/**
 * LaraClassified - Geo Classified Ads Software
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

namespace App\Http\Requests\Admin;

class PostTypeFieldRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
    
        if ($this->segment(2) == 'post_type') {
			$rules['field_id'] = 'required|not_in:0';
			$rules['field_id'] .= '|unique_ccf:post_type_id,' . $this->post_type_id;
			$rules['field_id'] .= '|unique_ccf_parent:post_type_id,' . $this->post_type_id;
			// $rules['field_id'] .= '|unique_ccf_children:category_id,' . $this->category_id;
        }
    
        if ($this->segment(2) == 'field') {
			$rules['post_type_id'] = 'required|not_in:0';
			$rules['post_type_id'] .= '|unique_ccf:field_id,' . $this->field_id;
			$rules['post_type_id'] .= '|unique_ccf_parent:field_id,' . $this->field_id;
			$rules['post_type_id'] .= '|unique_ccf_children:field_id,' . $this->field_id;
        }
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        $messages = [
            'post_type_id.required' => trans('admin::messages.The :field is required.', ['field' => trans('admin::messages.category')]),
            'post_type_id.not_in'   => trans('admin::messages.The :field is required. And cannot be 0.', ['field' => trans('admin::messages.category')]),
            'field_id.required'    => trans('admin::messages.The :field is required.', ['field' => trans('admin::messages.custom field')]),
            'field_id.not_in'      => trans('admin::messages.The :field is required. And cannot be 0.', ['field' => trans('admin::messages.custom field')]),
        ];
		
		$messages['category_id.unique_ccf'] = trans('admin::messages.The :field_1 have this :field_2 assigned already.', [
			'field_1' => trans('admin::messages.post_type'),
			'field_2' => trans('admin::messages.custom field'),
		]);
		$messages['category_id.unique_ccf_parent'] = trans('admin::messages.The parent :field_1 of the :field_1 have this :field_2 assigned already.', [
			'field_1' => trans('admin::messages.post_type_id'),
			'field_2' => trans('admin::messages.custom field'),
		]);
		// $messages['category_id.unique_ccf_children'] = trans('admin::messages.A child :field_1 of the :field_1 have this :field_2 assigned already.', [
		// 	'field_1' => trans('admin::messages.post_type_id'),
		// 	'field_2' => trans('admin::messages.custom field'),
		// ]);
		
		$messages['field_id.unique_ccf'] = trans('admin::messages.The :field_1 is already assign to this :field_2.', [
			'field_1' => trans('admin::messages.custom field'),
			'field_2' => trans('admin::messages.post_type_id'),
		]);
		$messages['field_id.unique_ccf_parent'] = trans('admin::messages.The :field_1 is already assign to the parent :field_2 of this :field_2.', [
			'field_1' => trans('admin::messages.custom field'),
			'field_2' => trans('admin::messages.post_type_id'),
		]);
		$messages['field_id.unique_ccf_children'] = trans('admin::messages.The :field_1 is already assign to one :field_2 of this :field_2.', [
			'field_1' => trans('admin::messages.custom field'),
			'field_2' => trans('admin::messages.post_type_id'),
		]);
        
        return $messages;
    }
}
