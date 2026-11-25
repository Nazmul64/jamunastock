<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SiteSetting;
use Illuminate\Http\Request;

class AdminThemeSettingsController extends Controller
{
    /**
     * Display the theme & color customization panel.
     */
    public function index()
    {
        $themeKeys = [
            'theme_navbar_bg'        => '#063c19',
            'theme_navbar_bg_2'      => '#053315',
            'theme_navbar_btn_bg'    => '#d97706',
            'theme_navbar_text'      => '#ffffff',
            'theme_btn_primary'      => '#0c5c2d',
            'theme_btn_accent'       => '#f59e0b',
            'theme_card_header_bg'   => '#064e3b',
            'theme_card_border'      => '#10b981',
            'theme_chat_btn_bg'      => '#0c5c2d',
            'theme_chat_header_bg'   => '#06381a',
            'theme_chat_bubble_bg'   => '#0c5c2d',
            'theme_chat_send_bg'     => '#f59e0b',
            'theme_footer_bg'        => '#052611',
            'theme_footer_text'      => '#cbd5e1',
        ];

        $currentTheme = [];
        foreach ($themeKeys as $key => $default) {
            $val = setting($key);
            $currentTheme[$key] = ($val !== false && !empty($val)) ? $val : $default;
        }

        return view('admin.settings.theme', compact('currentTheme', 'themeKeys'));
    }

    /**
     * Save updated theme colors.
     */
    public function update(Request $request)
    {
        $colorFields = [
            'theme_navbar_bg',
            'theme_navbar_bg_2',
            'theme_navbar_btn_bg',
            'theme_navbar_text',
            'theme_btn_primary',
            'theme_btn_accent',
            'theme_card_header_bg',
            'theme_card_border',
            'theme_chat_btn_bg',
            'theme_chat_header_bg',
            'theme_chat_bubble_bg',
            'theme_chat_send_bg',
            'theme_footer_bg',
            'theme_footer_text',
        ];

        foreach ($colorFields as $field) {
            if ($request->filled($field)) {
                SiteSetting::updateOrCreate(
                    ['name' => $field],
                    ['value' => $request->input($field)]
                );
            }
        }

        return redirect()->back()->with('success', 'ওয়েবসাইটের থিম এবং কালার সেটিং সফলভাবে সংরক্ষিত হয়েছে!');
    }

    /**
     * Reset theme to default colors.
     */
    public function reset()
    {
        $defaultColors = [
            'theme_navbar_bg'        => '#063c19',
            'theme_navbar_bg_2'      => '#053315',
            'theme_navbar_btn_bg'    => '#d97706',
            'theme_navbar_text'      => '#ffffff',
            'theme_btn_primary'      => '#0c5c2d',
            'theme_btn_accent'       => '#f59e0b',
            'theme_card_header_bg'   => '#064e3b',
            'theme_card_border'      => '#10b981',
            'theme_chat_btn_bg'      => '#0c5c2d',
            'theme_chat_header_bg'   => '#06381a',
            'theme_chat_bubble_bg'   => '#0c5c2d',
            'theme_chat_send_bg'     => '#f59e0b',
            'theme_footer_bg'        => '#052611',
            'theme_footer_text'      => '#cbd5e1',
        ];

        foreach ($defaultColors as $key => $val) {
            SiteSetting::updateOrCreate(['name' => $key], ['value' => $val]);
        }

        return redirect()->back()->with('success', 'থিম এবং কালার সফলভাবে ডিফল্ট সেটিংসে রিসেট করা হয়েছে!');
    }
}
