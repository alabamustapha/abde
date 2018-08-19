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

namespace App\Http\Controllers\Account;

use App\Helpers\Arr;
use App\Helpers\Search;
use App\Http\Controllers\Search\Traits\PreSearchTrait;
// use App\Models\Post;
use App\Models\Company;
use App\Models\Scopes\VerifiedScope;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CompaniesController extends AccountBaseController
{
    use PreSearchTrait;

    private $perPage = 12;

    public function __construct()
    {
        parent::__construct();

        $this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
    }

    /**
     * @param $pagePath
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getPage()
    {
        $data = [];
        $data['companies'] = $this->companies;
        $data['type'] = 'my-companies';
        
        // Meta Tags
        MetaTag::set('title', t('My companies'));
        MetaTag::set('description', t('My companies on :app_name', ['app_name' => config('settings.app.app_name')]));

        return view('account.companies', $data);

    }
    
    public function create()
    {
        $data = [];
        $data['user_id'] = auth()->user()->id;
        $data['type'] = 'my-companies';
        
        // Meta Tags
        MetaTag::set('title', t('Add company'));
        MetaTag::set('description', t('Add company on :app_name', ['app_name' => config('settings.app.app_name')]));

        return view('account.create_company', $data);

    }

    public function addCompany(HttpRequest $request){
        
        $path = '';
        
        $company = Company::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->user()->id
        ]);

        
        if($request->hasFile('logo') && $request->logo->isValid()){
            $path = $request->file('logo')->store('company/'.$request->user()->id);
        }

        $company->logo = $path;

        $company->save();

        return back()->with('message', 'company added');
    }

   
	/**
	 * @param $pagePath
	 * @param null $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
    public function destroy($pagePath, $id = null)
    {
        // Get Entries ID
        $ids = [];
        if (request()->filled('entries')) {
            $ids = request()->input('entries');
        } else {
            if (!is_numeric($id) && $id <= 0) {
                $ids = [];
            } else {
                $ids[] = $id;
            }
        }

        // Delete
        $nb = 0;
        if ($pagePath == 'favourite') {
            $savedPosts = SavedPost::where('user_id', auth()->user()->id)->whereIn('post_id', $ids);
            if ($savedPosts->count() > 0) {
                $nb = $savedPosts->delete();
            }
        } elseif ($pagePath == 'saved-search') {
            $nb = SavedSearch::destroy($ids);
        } else {
            foreach ($ids as $item) {
                $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->find($item);
                if (!empty($post)) {
                    $tmpPost = Arr::toObject($post->toArray());

                    // Delete Entry
                    $nb = $post->delete();

                    // Send an Email confirmation
					if (!empty($tmpPost->email)) {
						try {
							Mail::send(new PostDeleted($tmpPost));
						} catch (\Exception $e) {
							flash($e->getMessage())->error();
						}
					}
                }
            }
        }

        // Confirmation
        if ($nb == 0) {
            flash(t("No deletion is done. Please try again."))->error();
        } else {
            $count = count($ids);
            if ($count > 1) {
                $message = t("x :entities has been deleted successfully.", ['entities' => t('ads'), 'count' => $count]);
            } else {
                $message = t("1 :entity has been deleted successfully.", ['entity' => t('ad')]);
            }
            flash($message)->success();
        }

        return redirect(config('app.locale') . '/account/' . $pagePath);
    }
}
