<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frontendsetting extends Model
{
    use HasFactory;
    protected $table = 'frontend_setting';
    protected $fillable = [
        'banner_title', 
        'banner_sub_title', 
        'logo_name_thumbnail',
        'logo_name_large',
        'logo_extension',
        'logo_path',
        'banner_name_thumbnail',
        'banner_name_large',
        'banner_extension',
        'banner_path',
        'slogan',
        'cookie_note',
        'cookie_policy',
        'about_us',
        'terms_and_condition',
        'privacy_policy',
        'faq',
        'facebook_link',
        'twitter_link', 
        'linkedin_link',
        'google_link',
        'instagram_link',
        'pinterest_link',
        'default_competition_expiry',
        'hide_show_vendor_contact'
        //'created_by'
    ];
}
