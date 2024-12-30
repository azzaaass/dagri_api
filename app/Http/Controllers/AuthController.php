<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {
        $client = new Client();

        try {
            $response = $client->post('https://gateway.telkomuniversity.ac.id/issueauth', [
                'form_params' => [
                    'username' => $request['username'],
                    'password' => $request['password'],
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            if (isset($body['token'])) {
                $token = $body['token'];

                $userProfile = $client->get('https://gateway.telkomuniversity.ac.id/issueprofile', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token
                    ],
                ]);

                $userProfileBody = json_decode($userProfile->getBody(), true);
                $user = null;
                // dd($userProfileBody);
                if (isset($userProfileBody['fullname'], $userProfileBody['user'], $userProfileBody['email'], $userProfileBody['photo'])) {
                    $user = User::updateOrCreate(
                        [
                            'email' => $userProfileBody['email'],
                        ],
                        [
                            'fullname' => $userProfileBody['fullname'],
                            'username' => $userProfileBody['user'],
                            'photo' => $userProfileBody['photo'],
                            'token' => $token,
                        ]
                    );
                }

                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                    'profile' => [
                        'fullname' => $userProfileBody['fullname'],
                        'username' => $userProfileBody['user'],
                        'email' => $userProfileBody['email'],
                        'photo' => $userProfileBody['photo'],
                    ]
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Token not found in response',
                ], 400);
            }

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $message = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();

            return response()->json([
                'message' => 'Login failed',
                'error' => $message,
            ], $statusCode);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
