<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Employee;
use App\Models\Employeecounthistory;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Stripe;
use Auth;

class ZoomController extends Controller
{
    public function redirectToZoom()
    {
        $zoomBaseUrl = 'https://zoom.us/oauth/authorize';
        $clientId = config('services.zoom.client_id');
        $redirectUri = config('services.zoom.redirect_uri');
        $state = bin2hex(random_bytes(5));

        $queryParams = [
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'state' => $state,
        ];

        $url = $zoomBaseUrl . '?' . http_build_query($queryParams);

        return redirect()->away($url);
    }

    public function handleZoomCallback(Request $request)
    {
        $user =Auth::user();
    
        $state = $request->input('state');
        // Add your logic to validate state if needed

        $code = $request->input('code');

        $accessToken = $this->getAccessToken($code);
        $zoomuserid = $this->getZoomUserId($accessToken);

       if($accessToken){
        $update = User::find($user->id);
        $update->zoom_access_token = $accessToken;
        $update->zoom_user_id = $zoomuserid;
        $update->save();
       }
        
        // Add your logic to handle the access token
        // For example, you might store it in the database or use it for API requests

        return view('meetingform');
    }

    private function getAccessToken($code)
    {
        
        $tokenUrl = 'https://zoom.us/oauth/token';
        $clientId = config('services.zoom.client_id');
        $clientSecret = config('services.zoom.client_secret');
        $redirectUri = config('services.zoom.redirect_uri');
    
        $response = Http::asForm()->post($tokenUrl, [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'meeting:read meeting:write meeting:write:admin meeting:read:admin',
        ]);
    
        $accessToken = data_get($response->json(), 'access_token');
    
        if (!$accessToken) {
            abort(500, 'Unable to retrieve access token from Zoom API response.');
        }
    
        return $accessToken;
    }
    
    public function createMeeting(Request $request)
    {
        $user = Auth::user();
        $accessToken = $user->zoom_access_token;
    
        $meetingId = $this->createZoomMeeting(
            $accessToken,
            $request->input('topic'),
            $request->input('start_time'),
            $request->input('duration'),
            $request->input('password')
        );
    
        // Add your logic to handle the meeting creation response
        // For example, you might redirect to a confirmation page
    
        $meetings = $this->listMeetings();
       
        return view('zoommeeting', compact('meetings'));
    }
    
    private function createZoomMeeting($accessToken, $topic, $start_time, $duration, $password)
    {
        $user = Auth::user()->id;
        $url = 'https://api.zoom.us/v2/users/me/meetings';
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'topic' => $topic,
            'type' => 2,
            'start_time' => $start_time,
            'duration' => $duration,
            'timezone' => 'UTC',
            'password' => $password,
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'join_before_host' => true,
                'meeting_authentication' => true,
            ],
        ]);
    
        $meetingData = $response->json();
    
        if ($response->successful()) {
            $meetingId = data_get($meetingData, 'id');
            return $meetingId;
        } else {
            $error = data_get($meetingData, 'message', 'Unknown error during Zoom meeting creation.');
            abort(500, $error);
        }
    }
    public function listMeetings()
    {
        // Retrieve the list of meetings from the Zoom API
        $user = Auth::user();
        $accessToken = $user->zoom_access_token;
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->get('https://api.zoom.us/v2/users/me/meetings');
    
        if ($response->successful()) {
            $meetings = $response->json();
            return $meetings;
        } else {
            // Handle API error
            abort(500, 'Error fetching meetings from Zoom API');
        }
    }


    private function getZoomUserId($accessToken)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ])->get('https://api.zoom.us/v2/users/me');

    if ($response->successful()) {
        $userData = $response->json();

        // Check if the "id" key exists in the API response
        if (isset($userData['id'])) {
            return $userData['id'];
        } else {
            // Handle the case where "id" key is not present in the API response
            abort(500, 'Error: Zoom user ID not found in API response.');
        }
    } else {
        // Handle API error
        abort(500, 'Error fetching Zoom user details.');
    }
}
}
