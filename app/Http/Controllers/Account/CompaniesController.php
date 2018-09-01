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

use Carbon\Carbon;
use App\Helpers\Arr;
use App\Models\Post;
// use App\Models\Post;
use App\Helpers\Search;
use App\Models\Company;
use App\Models\Scopes\VerifiedScope;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\AddCompanyRequest;
use Illuminate\Http\Request as HttpRequest;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Http\Controllers\Search\Traits\PreSearchTrait;

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
    
    public function edit(Company $company)
    {
        $data = [];
        $data['user_id'] = auth()->user()->id;
        $data['type'] = 'my-companies';
        $data['company'] = $company;
        
        // Meta Tags
        MetaTag::set('title', t('Add company'));
        MetaTag::set('description', t('Add company on :app_name', ['app_name' => config('settings.app.app_name')]));

        return view('account.edit_company', $data);

    }

    public function update(AddCompanyRequest $request, Company $company){
        
        $name =  $request->name;
        
        $description =  $request->description;

        $company->name = $name;

        $company->description = $description;

        if($request->hasFile('logo') && $request->logo->isValid()){
           
           if(\Storage::exists($company->logo)){
            \Storage::delete($company->logo);
           }
            $path = $request->file('logo')->store('company/'.$request->user()->id);
            $company->logo = $path;
        }

        
        $company->save();
        return back();
    }

    public function addCompany(AddCompanyRequest $request){
        
        $path = '';
        
        dd($request->all());
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
    public function destroy(HttpRequest $request, Company $company)
    {

        if(\Storage::exists($company->logo)){
            \Storage::delete($company->logo);
        }

        $posts = Post::where('company_id',  $company->id)->update(['company_id' => null]);
        

        $company->delete();

        return back();
        
    }
}
