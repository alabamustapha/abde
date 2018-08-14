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

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Country;
use App\Models\User;
use Jenssegers\Date\Date;
use Larapen\Admin\app\Http\Controllers\PanelController;

class DashboardController extends PanelController
{
	public $data = []; // the information we send to the view
	
	/**
	 * Create a new controller instance.
	 */
	public function __construct()
	{
		$this->middleware('admin');
		
		parent::__construct();
		
		// Get the Mini Stats data
		// Count Ads
		$countActivatedPosts = Post::verified()->count();
		$countUnactivatedPosts = Post::unverified()->count();
		
		// Count Users
		$countActivatedUsers = 0;
		$countUnactivatedUsers = 0;
		$countUsers = 0;
		try {
			$countActivatedUsers = User::doesntHave('permissions')->verified()->count();
			$countUnactivatedUsers = User::doesntHave('permissions')->unverified()->count();
			$countUsers = User::doesntHave('permissions')->count();
		} catch (\Exception $e) {}
		
		// Count activated countries
		$countCountries = Country::where('active', 1)->count();
		
		view()->share('countActivatedPosts', $countActivatedPosts);
		view()->share('countUnactivatedPosts', $countUnactivatedPosts);
		view()->share('countActivatedUsers', $countActivatedUsers);
		view()->share('countUnactivatedUsers', $countUnactivatedUsers);
		view()->share('countUsers', $countUsers);
		view()->share('countCountries', $countCountries);
	}
	
	/**
	 * Show the admin dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboard()
	{
		// Limit latest entries
		$latestEntriesLimit = 5;
		
		// Get latest Ads
		$posts = Post::take($latestEntriesLimit)->orderBy('id', 'DESC')->get();
		$this->data['posts'] = $posts;
		
		// Get latest Users
		$users = User::take($latestEntriesLimit)->orderBy('id', 'DESC')->get();
		$this->data['users'] = $users;
		
		// Get Stats
		$statDayNumber = 30;
		$currentDate = Date::now();
		
		$stats = [];
		for ($i = 1; $i <= $statDayNumber; $i++) {
			$dateObj = ($i == 1) ? $currentDate : $currentDate->subDay();
			$date = $dateObj->toDateString();
			
			// Ads Stats
			$countActivatedPosts = Post::verified()
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$countUnactivatedPosts = Post::unverified()
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$stats['posts'][$i]['y'] = mb_ucfirst($dateObj->formatLocalized('%b %d'));
			$stats['posts'][$i]['activated'] = $countActivatedPosts;
			$stats['posts'][$i]['unactivated'] = $countUnactivatedPosts;
			
			// Users Stats
			$countActivatedUsers = User::doesntHave('permissions')
				->verified()
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$countUnactivatedUsers = User::doesntHave('permissions')
				->unverified()
				->where('created_at', '>=', $date)
				->where('created_at', '<=', $date . ' 23:59:59')
				->count();
			
			$stats['users'][$i]['y'] = mb_ucfirst($dateObj->formatLocalized('%b %d'));
			$stats['users'][$i]['activated'] = $countActivatedUsers;
			$stats['users'][$i]['unactivated'] = $countUnactivatedUsers;
		}
		
		$stats['posts'] = array_reverse($stats['posts'], true);
		$stats['users'] = array_reverse($stats['users'], true);
		
		$this->data['postsStats'] = json_encode(array_values($stats['posts']), JSON_NUMERIC_CHECK);
		$this->data['usersStats'] = json_encode(array_values($stats['users']), JSON_NUMERIC_CHECK);
		
		$this->data['title'] = trans('admin::messages.dashboard');
		
		return view('admin::dashboard', $this->data);
	}
	
	/**
	 * Redirect to the dashboard.
	 *
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function redirect()
	{
		// The '/admin' route is not to be used as a page, because it breaks the menu's active state.
		return redirect(admin_uri('dashboard'));
	}
}
