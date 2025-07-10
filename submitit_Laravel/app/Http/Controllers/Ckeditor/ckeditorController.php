<?php

namespace App\Http\Controllers\Ckeditor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplates;

class ckeditorController extends Controller
{
    public function updateEmailTemplate(Request $request)
    {
        $editor = EmailTemplates::find($request->id);
        $editor->templateDesign = $request->templateDesign;
        $editor->title = $request->title;
        $editor->update();
        return response()->json("Updated Email Template Successfully");
    }

    public function editEmailTemplate(Request $request)
    {
        $editor = EmailTemplates::find($request->id);
        return response()->json($editor);
    }

    public function getEmailTemplate()
    {
        $emailTemplate = EmailTemplates::all();
        return response()->json($emailTemplate);
    }
}
