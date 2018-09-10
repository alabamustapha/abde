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

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Traits\TranslatedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Support\Facades\Storage;
use Larapen\Admin\app\Models\Crud;
use Prologue\Alerts\Facades\Alert;

class Skill extends BaseModel
{
	use Crud, Sluggable, SluggableScopeHelpers, TranslatedTrait;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'skills';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	// protected $primaryKey = 'id';
	protected $appends = ['tid'];
	
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
		'parent_id',
		'name',
		'slug',
		'active',
		'lft',
		'rgt',
		'depth',
		'translation_lang',
		'translation_of',
	];
	public $translatable = ['name', 'slug'];
	
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
		
		Skill::observe(SkillObserver::class);
		
		static::addGlobalScope(new ActiveScope());
	}
	
	/**
	 * Return the sluggable configuration array for this model.
	 *
	 * @return array
	 */
	public function sluggable()
	{
		return [
			'slug' => [
				'source' => 'slug_or_name',
			],
		];
	}
	
	public function getNameHtml()
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$url = $currentUrl . '/' . $this->id . '/edit';
		
		$out = '<a href="' . $url . '">' . $this->name . '</a>';
		
		return $out;
	}
	
	public function subSkillsBtn($xPanel = false)
	{
		$out = '';
		
		if ($this->parent_id == 0) {
			$url = admin_url('skills/' . $this->id . '/subskills');
			
			$msg = trans('admin::messages.Subskills of :skill', ['skill' => $this->name]);
			$tooltip = ' data-toggle="tooltip" title="' . $msg . '"';
			
			$out .= '<a class="btn btn-xs btn-default" href="' . $url . '"' . $tooltip . '>';
			$out .= '<i class="fa fa-eye"></i> ';
			$out .= mb_ucfirst(trans('admin::messages.subskills'));
			$out .= '</a>';
		}
		
		return $out;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// public function companies()
	// {
	// 	return $this->hasManyThrough(Company::class, Skill::class, 'parent_id', 'skill_id');
	// }
	
	public function children()
	{
		return $this->hasMany(Skill::class, 'parent_id', 'translation_of')->where('translation_lang', config('app.locale'));
	}
	
	public function lang()
	{
		return $this->hasOne(Skill::class, 'translation_of', 'abbr');
	}
	
	public function parent()
	{
		return $this->belongsTo(Skill::class, 'parent_id', 'translation_of')->where('translation_lang', config('app.locale'));
	}
	
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	
	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/
	// The slug is created automatically from the "title" field if no slug exists.
	public function getSlugOrNameAttribute()
	{
		if ($this->slug != '') {
			return $this->slug;
		}
		return $this->name;
	}
	
	
	
	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
	
	/**
	 * Activate/Deactivate categories with their children if exist
	 *
	 * @param $value
	 */
	public function setActiveAttribute($value)
	{
		$entityId = (isset($this->attributes['id'])) ? $this->attributes['id'] : null;
		
		if (!empty($entityId)) {
			// Activate the entry
			$this->attributes['active'] = $value;
			
			// If the entry is a parent entry, activate its children
			$parentId = (isset($this->attributes['parent_id'])) ? $this->attributes['parent_id'] : null;
			if ($parentId == 0) {
				// Don't select the current parent entry to prevent infinite recursion
				$entries = $this->where('parent_id', $entityId)->get();
				if (!empty($entries)) {
					foreach ($entries as $entry) {
						$entry->active = $value;
						$entry->save();
					}
				}
			}
		} else {
			// Activate the new entries
			$this->attributes['active'] = $value;
		}
	}
}
