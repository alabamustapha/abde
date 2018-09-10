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

use App\Models\Skill;
use App\Models\Country;
use Larapen\Admin\app\Models\Crud;
use Illuminate\Support\Facades\Request;



class Company extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'companies';
	
	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	
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
		'user_id',
		'name',
		'logo',
		'country_id',
		'city_id',
		'address',
		'phone',
		'fax',
		'email',
		'website',
		'facebook',
		'skills',
		// 'created_at'
	];

	protected $dates = ['created_at'];


	protected $casts = [
        'skills' => 'array',
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
	

	
	
	public function getNameHtml()
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$url = $currentUrl . '/' . $this->id . '/edit';
		
		$out = '<a href="' . $url . '">' . $this->name . '</a>';
		
		return $out;
	}

	// public function getLogoHtml()
	// {
	// 	// Get ad URL
	// 	$url = url(config('app.locale') . '/' . $this->uri);
	// `	
	// 	$style = ' style="width:auto; max-height:90px;"';
	// 	// Get first picture
	// 	if ($this->pictures->count() > 0) {
	// 		foreach ($this->pictures as $picture) {
	// 			$url = localUrl($picture->post->country_code, $this->uri);
	// 			$out = '<img src="' . resize($picture->filename, 'small') . '" data-toggle="tooltip" title="' . $this->title . '"' . $style . '>';
	// 			break;
	// 		}
	// 	} else {
	// 		// Default picture
	// 		$out = '<img src="' . resize(config('larapen.core.picture.default'), 'small') . '" data-toggle="tooltip" title="' . $this->title . '"' . $style . '>';
	// 	}
		
	// 	// Add link to the Ad
	// 	$out = '<a href="' . $url . '" target="_blank">' . $out . '</a>';
		
	// 	return $out;
	// }
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function posts(){
		return $this->hasMany(Post::class, 'company_id');
	}

	public function country(){
		return $this->belongsTo(Country::class, 'country_id');
	}

	public function getSkillAttribute(){
		
		$skills =  Skill::where('parent_id', 0)->pluck('name', 'id')->toArray();
		
		return Skill::find($this->skills)->groupBy(function($item, $key) use ($skills){
			return $skills[$item['parent_id']];
		}, $preserveKeys = true);

	
	}
	
	
}
