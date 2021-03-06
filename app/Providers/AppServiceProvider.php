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

namespace App\Providers;

use App\Helpers\DBTool;
use App\Models\Setting;
use App\Models\Language;
use Jenssegers\Date\Date;
use App\Models\Permission;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	private $cacheExpiration = 1440; // Cache for 1 day (60 * 24)
	
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Paginator::useBootstrapThree();
		
		try {
			// Specified key was too long error
			Schema::defaultStringLength(191);
		} catch (\Exception $e) {
			//...
		}
		
		// Create the local storage symbolic link
		$this->checkAndCreateStorageSymlink();
		
		// Setup ACL system
		$this->setupAclSystem();
		
		// Force HTTPS protocol
		$this->forceHttps();
		
		// Create setting config var for the default language
		$this->getDefaultLanguage();
		
		// Create config vars from settings table
		$this->createConfigVars();
		
		// Update the config vars
		$this->setConfigVars();
		
		// Check the Multi-Countries feature
		// To prevent the Locale (Language Abbr) & the Country Code conflict,
		// Don't hive the Default Locale in URL
		if (config('settings.seo.multi_countries_urls')) {
			Config::set('laravellocalization.hideDefaultLocaleInURL', false);
		}
		
		// Create the MySQL Distance Calculation function, If doesn't exist
		if (!DBTool::checkIfMySQLFunctionExists(config('larapen.core.distanceCalculationFormula'))) {
			$res = DBTool::createMySQLDistanceCalculationFunction(config('larapen.core.distanceCalculationFormula'));
		}
		
		// Date default encoding & translation
		// The translation option is overwritten when applying the front-end settings
		if (config('settings.app.date_force_utf8')) {
			Date::setUTF8(true);
		}
		Date::setLocale(config('appLang.abbr', 'en'));
		setlocale(LC_ALL, config('appLang.locale', 'en_US'));
	}
	
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
	
	/**
	 * Check the local storage symbolic link and Create it if does not exist.
	 */
	private function checkAndCreateStorageSymlink()
	{
		$symlink = public_path('storage');
		
		// symlink("c:\\xampp\htdocs\abde\storage\app\public", "c:\\xampp\htdocs\abde\storage\");
		
		// dd(is_link($symlink));
		try {
			if (!is_link($symlink)) {
				// $exitCode = Artisan::call('storage:link');
				symlink('../storage/app/public', './public');
			}
		} catch (\Exception $e) {
			$errorUrl = 'http://support.bedigit.com/help-center/articles/71/images-dont-appear-in-my-website';
			$message = ($e->getMessage() != '') ? $e->getMessage() : 'symlink() has been disabled on your server';
			$message = $message . ' - Please <a href="' . $errorUrl . '" target="_blank">see this article</a> for more information.';
			
			// flash($message)->error();
		}
	}
	
	/**
	 * Force HTTPS protocol
	 */
	private function forceHttps()
	{
		if (config('larapen.core.forceHttps') == true) {
			URL::forceScheme('https');
		}
	}
	
	/**
	 * Create setting config var for the default language
	 */
	private function getDefaultLanguage()
	{
		try {
			// Get the DB default language
			$defaultLang = Cache::remember('language.default', $this->cacheExpiration, function () {
				$defaultLang = Language::where('default', 1)->first();
				
				return $defaultLang;
			});
			
			if (!empty($defaultLang)) {
				// Create DB default language settings
				Config::set('appLang', $defaultLang->toArray());
				
				// Set dates default locale
				Date::setLocale(config('appLang.abbr'));
				setlocale(LC_ALL, config('appLang.locale'));
			} else {
				Config::set('appLang.abbr', config('app.locale'));
			}
		} catch (\Exception $e) {
			Config::set('appLang.abbr', config('app.locale'));
		}
	}
	
	/**
	 * Create config vars from settings table
	 */
	private function createConfigVars()
	{
		// Get some default values
		Config::set('settings.app.purchase_code', config('larapen.core.purchaseCode'));
		Config::set('settings.app.default_date_format', config('larapen.core.defaultDateFormat'));
		Config::set('settings.app.default_datetime_format', config('larapen.core.defaultDatetimeFormat'));
		
		// Check DB connection and catch it
		try {
			// Get all settings from the database
			$settings = Cache::remember('settings.active', $this->cacheExpiration, function () {
				$settings = Setting::where('active', 1)->get();
				
				return $settings;
			});
			
			// Bind all settings to the Laravel config, so you can call them like
			if ($settings->count() > 0) {
				foreach ($settings as $setting) {
					if (count($setting->value) > 0) {
						foreach ($setting->value as $subKey => $value) {
							if (!empty($value)) {
								Config::set('settings.' . $setting->key . '.' . $subKey, $value);
							}
						}
					}
				}
			}
		} catch (\Exception $e) {
			Config::set('settings.error', true);
			Config::set('settings.app.logo', config('larapen.core.logo'));
		}
	}
	
	/**
	 * Update the config vars
	 */
	private function setConfigVars()
	{
		// App
		Config::set('app.name', config('settings.app.app_name'));
		Config::set('app.timezone', config('settings.app.default_timezone', config('app.timezone')));
		// reCAPTCHA
		Config::set('recaptcha.public_key', env('RECAPTCHA_PUBLIC_KEY', config('settings.security.recaptcha_public_key')));
		Config::set('recaptcha.private_key', env('RECAPTCHA_PRIVATE_KEY', config('settings.security.recaptcha_private_key')));
		// Mail
		Config::set('mail.driver', env('MAIL_DRIVER', config('settings.mail.driver')));
		Config::set('mail.host', env('MAIL_HOST', config('settings.mail.host')));
		Config::set('mail.port', env('MAIL_PORT', config('settings.mail.port')));
		Config::set('mail.encryption', env('MAIL_ENCRYPTION', config('settings.mail.encryption')));
		Config::set('mail.username', env('MAIL_USERNAME', config('settings.mail.username')));
		Config::set('mail.password', env('MAIL_PASSWORD', config('settings.mail.password')));
		Config::set('mail.from.address', env('MAIL_FROM_ADDRESS', config('settings.mail.email_sender')));
		Config::set('mail.from.name', env('MAIL_FROM_NAME', config('settings.app.app_name')));
		// Mailgun
		Config::set('services.mailgun.domain', env('MAILGUN_DOMAIN', config('settings.mail.mailgun_domain')));
		Config::set('services.mailgun.secret', env('MAILGUN_SECRET', config('settings.mail.mailgun_secret')));
		// Mandrill
		Config::set('services.mandrill.secret', env('MANDRILL_SECRET', config('settings.mail.mandrill_secret')));
		// Amazon SES
		Config::set('services.ses.key', env('SES_KEY', config('settings.mail.ses_key')));
		Config::set('services.ses.secret', env('SES_SECRET', config('settings.mail.ses_secret')));
		Config::set('services.ses.region', env('SES_REGION', config('settings.mail.ses_region')));
		// Sparkpost
		Config::set('services.sparkpost.secret', env('SPARKPOST_SECRET', config('settings.mail.sparkpost_secret')));
		// Facebook
		Config::set('services.facebook.client_id', env('FACEBOOK_CLIENT_ID', config('settings.social_auth.facebook_client_id')));
		Config::set('services.facebook.client_secret', env('FACEBOOK_CLIENT_SECRET', config('settings.social_auth.facebook_client_secret')));
		// Google
		Config::set('services.google.client_id', env('GOOGLE_CLIENT_ID', config('settings.social_auth.google_client_id')));
		Config::set('services.google.client_secret', env('GOOGLE_CLIENT_SECRET', config('settings.social_auth.google_client_secret')));
		Config::set('services.googlemaps.key', env('GOOGLE_MAPS_API_KEY', config('settings.other.googlemaps_key')));
		// Meta-tags
		Config::set('meta-tags.title', config('settings.app.slogan'));
		Config::set('meta-tags.open_graph.site_name', config('settings.app.app_name'));
		Config::set('meta-tags.twitter.creator', config('settings.seo.twitter_username'));
		Config::set('meta-tags.twitter.site', config('settings.seo.twitter_username'));
		// Cookie Consent
		Config::set('cookie-consent.enabled', env('COOKIE_CONSENT_ENABLED', config('settings.other.cookie_consent_enabled')));
		
		// Admin panel
		Config::set('larapen.admin.skin', config('settings.style.admin_skin'));
		Config::set('larapen.admin.default_date_format', config('settings.app.default_date_format'));
		Config::set('larapen.admin.default_datetime_format', config('settings.app.default_datetime_format'));
		if (str_contains(config('settings.show_powered_by'), 'fa')) {
			Config::set('larapen.admin.show_powered_by', str_contains(config('settings.footer.show_powered_by'), 'fa-check-square-o') ? 1 : 0);
		} else {
			Config::set('larapen.admin.show_powered_by', config('settings.footer.show_powered_by'));
		}
	}
	
	/**
	 * Setup ACL system
	 * Check & Migrate Old admin authentication to ACL system
	 */
	private function setupAclSystem()
	{
		if (isFromAdminPanel()) {
			// Check & Fix the default Permissions
			if (!Permission::checkDefaultPermissions()) {
				Permission::resetDefaultPermissions();
			}
		}
	}
}
