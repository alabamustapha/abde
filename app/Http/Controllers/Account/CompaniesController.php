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
use App\Models\Skill;
use App\Helpers\Search;
use App\Models\Company;
use App\Models\Country;
use App\Models\Scopes\VerifiedScope;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\AddCompanyRequest;
use Illuminate\Http\Request as HttpRequest;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Http\Requests\UpdateCompanyContactRequest;
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

        $data['countries'] = \DB::table('countries')->select(['id', 'name', 'code'])->get();
        $skill_counts = Skill::count();
        $data['skills'] = Skill::with('children')->where('parent_id', 0)->get();
        
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
        $data['countries'] = \DB::table('countries')->select(['id', 'name', 'code'])->get();
        $skill_counts = Skill::count();
        $data['skills'] = Skill::with('children')->where('parent_id', 0)->get();
        

        // Meta Tags
        MetaTag::set('title', t('Edit company'));
        MetaTag::set('description', t('Edit :company_name', ['company_name' => $company->name]));

        return view('account.edit_company', $data);

    }

    public function updateSkills(HttpRequest $request, Company $company){
        

        if($request->has('skills')){
            $company->skills = $request->skills;
        }else{
            $company->skills = NULL;
        }
        
        $company->save();

        return back();
    }

    public function update(AddCompanyRequest $request, Company $company){
        
        
        $name =  $request->name;
        
        $description =  $request->description;

        $company->name = $name;

        $company->description = $description;

        $company->country_id = $request->country_id;
        $company->city_id    = $request->city_id;
        $company->address    = $request->address;
        $company->phone      = $request->phone;
        $company->profession        = $request->profession;
        $company->email      = $request->email;
        $company->website    = $request->website;
        $company->facebook   = $request->facebook;

        if($request->hasFile('logo') && $request->logo->isValid()){
           
           if(\Storage::exists($company->logo)){
            \Storage::delete($company->logo);
           }
            $path = $request->file('logo')->store('company/'.$request->user()->id);
            $company->logo = $path;
        }

        if($request->has('skills')){
            $company->skills = $request->skills;
        }else{
            $company->skills = NULL;
        }
        
        $company->save();
        
        
        return back();
    }
    
    public function updateContact(UpdateCompanyContactRequest $request, Company $company){
        

        $company->update($request->except(['_token', 'countryCode', 'user_id']));
        
        return back();
    }

    public function addCompany(AddCompanyRequest $request){
        
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

        $company->country_id = $request->country_id;
        $company->city_id    = $request->city_id;
        $company->address    = $request->address;
        $company->phone      = $request->phone;
        $company->profession        = $request->profession;
        $company->email      = $request->email;
        $company->website    = $request->website;
        $company->facebook   = $request->facebook;

        if($request->has('skills')){
            $company->skills = $request->skills;
        }else{
            $company->skills = NULL;
        }
        
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
