<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailBounceController extends Controller
{
    public function handleBounce(Request $request)
    {
        // Assuming the request contains a JSON payload with bounce information
        $bounceData = $request->all();

        // Log the bounce data for debugging purposes
        Log::info('Email bounce received', $bounceData);

        // Extract email and other relevant information from the bounce data
        $email = $bounceData['email'] ?? null;
        if ($email) {
            // Mark the email as bounced in your database
            \App\Models\User::where('email', $email)->update(['email_bounced' => true]);
        }

        return response()->json(['status' => 'success']);
    }
}
