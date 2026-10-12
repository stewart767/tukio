<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, $locale)
    {
        // Validate locale (only allow 'en' or 'sw')
        if (!in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }
        
        // Store locale in session
        Session::put('locale', $locale);
        
        // Redirect back to the previous page or home
        return redirect()->back();
    }
}
