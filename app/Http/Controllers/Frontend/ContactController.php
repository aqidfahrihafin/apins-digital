<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Http\Requests\Frontend\NewsletterRequest;
use App\Models\Contacts;
use App\Models\Newsletters;

class ContactController extends Controller
{
    public function submit(ContactRequest $request)
    {
        Contacts::create($request->validated());

        return redirect()->back()->with('success', 'Terima kasih! Pesan Anda telah terkirim.');
    }

    public function subscribe(NewsletterRequest $request)
    {
        Newsletters::subscribe($request->validated('email'));

        return redirect()->back()->with('newsletter_success', 'Terima kasih telah berlangganan newsletter.');
    }
}
