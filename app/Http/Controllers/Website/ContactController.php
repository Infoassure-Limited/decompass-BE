<?php

namespace App\Http\Controllers\Website;

use App\Mail\ConsultationFormMessage;
use App\Models\Website\ContactForm;
use Illuminate\Http\Request;
use App\Mail\ContactFormMessage;
use App\Mail\TrainingFormMessage;
use App\Models\Website\ConsultationForm;
use App\Models\Website\Subscription;
use App\Models\Website\TrainingForm;
use App\Rules\ReCaptcha;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //
    public function submitContactForm(Request $request)
    {
        $request->validate([
            'company_email' => 'required|email:rfc,dns',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        $company_email = $request->company_email;
        $full_name = $request->first_name . ' ' . $request->last_name;
        if (isset($request->subscribe) && $request->subscribe == '1') {
            Subscription::updateOrCreate(['email' => $company_email], ['email' => $company_email, 'name' => $full_name]);
        }
        $data = request()->all();
        ContactForm::updateOrCreate(['company_email' => $company_email], $data);
        // Mail::to('info@decompass.com')->send(new ContactFormMessage($request));
        return redirect()->back()->with('status', 'Your message was sent. Thank you for contacting us');
    }
    public function submitConsultationForm(Request $request)
    {
        $request->validate([
            'company_email' => 'required|email:rfc,dns',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);
        $company_email = $request->company_email;
        $full_name = $request->first_name . ' ' . $request->last_name;
        $data = request()->all();
        ConsultationForm::updateOrCreate(['company_email' => $company_email], $data);
        // Mail::to('info@decompass.com')->send(new ConsultationFormMessage($request));
        return redirect()->back()->with('status', 'Your schedule was sent. Thank you for contacting us');
    }

    public function submitTrainingForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
            'name' => 'required',
            'course_of_interest' => 'required',
            'phone_no' => 'required',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);

        $data = request()->all();
        $data['course_of_interest'] = implode(', ', $request->course_of_interest);
        TrainingForm::create($data);
        // Mail::to('info@decompass.com')->send(new TrainingFormMessage($request));
        return redirect()->back()->with('status', 'Form Submitted Successfully.');
    }
    public function subscribeToNewsletter(Request $request)
    {
        $email = $request->email;
        Subscription::updateOrCreate(['email' => $email], ['email' => $email]);
        return redirect()->back()->with('status', 'Thank you for subscribing');
    }
}
