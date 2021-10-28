<?php

namespace App\Http\Controllers\Consultant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assistance;
use Auth;

class AssistanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       //
    }

    public function index()
    {
        $data['assistances'] = Auth::user()->assistances()
            ->with('assistanceCategory:assistance_category_id,label')
            ->select('assistance_id', 'label', 'assistance_category_id', 'description',
                'image', 'cost_per_session')
            ->get();

        return view('assistances.index', $data);
    }
}
