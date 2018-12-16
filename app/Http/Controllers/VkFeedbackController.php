<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VkFeedbackController extends Controller
{
    public function index(){
        $title = 'Отзывы';
        return view('feedback.feedback',compact('title'));
    }
}
