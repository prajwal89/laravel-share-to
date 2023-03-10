<?php

namespace Prajwal89\LaravelShareTo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShareToController extends Controller
{
    public function trackAndRedirect(Request $request)
    {
        $payload = $request->payload;
        if (empty($payload)) {
            return redirect('/');
        } else {
            $payloadData = json_decode(base64_decode($payload));
            //track record
            DB::table('track_shares')->insert(
                [
                    'title' => $payloadData->title,
                    'url' => $payloadData->urlToShare,
                    'chanel' => $payloadData->chanel,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            return redirect($payloadData->urlToRedirect);
        }
    }
}
