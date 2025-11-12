<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactForm;
use App\Models\Subscription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $contacts = ContactForm::paginate(10);
        $subscribers = Subscription::paginate(10);
        return view('dashboard', compact('subscribers', 'contacts'));
    }
}
