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

namespace App\Models;


use Larapen\Admin\app\Models\Crud;

class PostTypeField extends BaseModel
{
    use Crud;
    
    
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'post_type_field';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	// protected $primaryKey = 'id';
	
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;
	
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'post_type_id',
		'field_id',
		'parent_id',
		'lft',
		'rgt',
		'depth',
	];
	
	/**
	 * The attributes that should be hidden for arrays
	 *
	 * @var array
	 */
	// protected $hidden = [];
	
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	// protected $dates = [];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	protected static function boot()
	{
		parent::boot();
	}
	
	public function getPostTypeHtml()
	{
		$out = '';
		if (!empty($this->post_type)) {
			$currentUrl = preg_replace('#/(search)$#', '', url()->current());
			$editUrl = $currentUrl . '/' . $this->post_type->id . '/edit';
			
			$out .= '<a href="' . $editUrl . '">' . $this->post_type->name . '</a>';
		} else {
			$out .= '--';
		}
		
		return $out;
	}
	
	public function getFieldHtml()
	{
		$out = '';
		if (!empty($this->field)) {
			$currentUrl = preg_replace('#/(search)$#', '', url()->current());
			$editUrl = $currentUrl . '/' . $this->field->id . '/edit';
			
			$out .= '<a href="' . $editUrl . '" style="float:left;">' . $this->field->name . '</a>';
			
			if (in_array($this->field->type, ['select', 'radio', 'checkbox_multiple'])) {
				$optionUrl = admin_url('custom_fields/' . $this->field->id . '/options');
				$out .= ' ';
				$out .= '<span style="float:right;">';
				$out .= '<a class="btn btn-xs btn-danger" href="' . $optionUrl . '"><i class="fa fa-cog"></i> ' . mb_ucfirst(trans('admin::messages.options')) . '</a>';
				$out .= '</span>';
			}
		} else {
			$out .= '--';
		}
		
		return $out;
	}
	
	
	/**
	 * Get Fields details
	 *
	 * @param $PostTypeId
	 * @param null $postId
	 * @param null $languageCode (Required for AJAX Requests in v4.8 and lower)
	 * @return \Illuminate\Support\Collection
	 */
	public static function getFields($PostTypeId, $postId = null, $languageCode = null)
	{
		$fields = [];
		
		// Make sure that the Category nested IDs variables exist
		if (!isset($PostTypeId)) {
			return collect($fields);
		}
		
		// Make sure that the category nested IDs variable are not empty
		if (empty($PostTypeId) && empty($PostTypeId)) {
			return collect($fields);
		}
		
		// Get Post's Custom Fields values
		$postFieldsValues = collect([]);
		if (!empty($postId) && trim($postId) != '') {
			$postFieldsValues = PostValue::where('post_id', $postId)->get();
			$postFieldsValues = self::keyingByFieldId($postFieldsValues);
		}
		
		// Get PostTypeFields fields
		
			$postTypeFields = self::with(['field' => function ($builder) {
				$builder->with(['options']);
			}])->where('post_type_id', $PostTypeId)->orderBy('lft', 'ASC')->get();
		
		if ($postTypeFields->count() > 0) {
			foreach ($postTypeFields as $key => $postTypeField) {
				if (!empty($postTypeField->field)) {
					$fields[$key] = $postTypeField->field;
					
					// Retrieve the Field's Default value
					if ($postFieldsValues->count() > 0) {
						if ($postFieldsValues->has($postTypeField->field->tid)) {
							$postValue = $postFieldsValues->get($postTypeField->field->tid);
							if (isset($postValue->value)) {
								$defaultValue = $postValue->value;
							} else {
								if ($postTypeField->field->options->count() > 0) {
									$selectedOptions = [];
									foreach ($postTypeField->field->options as $option) {
										if (isset($postValue[$option->tid])) {
											$selectedOptions[$option->tid] = $option;
										}
									}
									$defaultValue = $selectedOptions;
								} else {
									$defaultValue = [];
								}
							}
							
							$fields[$key]->default = $defaultValue;
						}
					}
					
				}
			}
		}
		
		return collect($fields);
	}
	
	/**
	 * @param $values
	 * @return \Illuminate\Support\Collection
	 */
	private static function keyingByFieldId($values)
	{
		if (empty($values) || $values->count() <= 0) {
			return $values;
		}
		
		$postValues = [];
		foreach ($values as $value) {
			if (!empty($value->option_id)) {
				$postValues[$value->field_id][$value->option_id] = $value;
			} else {
				$postValues[$value->field_id] = $value;
			}
		}
		
		return collect($postValues);
	}
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	
	public function field()
	{
		return $this->belongsTo(Field::class, 'field_id', 'translation_of')->where('translation_lang', config('app.locale'));
	}
	
	public function post_type()
	{
		return $this->belongsTo(PostType::class, 'post_type_id', 'translation_of')->where('translation_lang', config('app.locale'));
		
	}
	
	
}
