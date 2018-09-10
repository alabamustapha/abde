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

namespace App\Observer;

use App\Models\Skill;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SkillObserver extends TranslatedModelObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Category $category
     * @return void
     */
    public function deleting($skill)
    {
		parent::deleting($skill);
    
        // If the skill is a parent skill, delete all its children
        if ($skill->parent_id == 0) {
            $subSkills = Skill::where('parent_id', $skill->id)->get();
            if ($subSkills->count() > 0) {
                foreach ($subSkills as $subSkill) {
                    $subSkill->delete();
                }
            }
        }
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  Category $category
     * @return void
     */
    public function saved(Skill $skill)
    {
        // Removing Entries from the Cache
        $this->clearCache($skill);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Category $category
     * @return void
     */
    public function deleted(Skill $skill)
    {
        // Removing Entries from the Cache
        $this->clearCache($skill);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $category
     */
    private function clearCache($skill)
    {
        Cache::flush();
    }
}
