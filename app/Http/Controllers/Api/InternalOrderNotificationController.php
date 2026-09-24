<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BackendController;
class InternalOrderNotificationController extends BackendController
{
    public function orderCreated(Request $request)
    {

        try {
            $token = $request->bearerToken();

            $expectedToken = config('services.internal_api.token');

            if (
                empty($token) ||
                empty($expectedToken) ||
                !hash_equals($expectedToken, $token)
            ) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $validated = $request->validate([
                'order_id' => [
                    'required',
                    'integer',
                ],

                'restaurant_id' => [
                    'nullable',
                    'integer',
                ],

                'order_code' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'amount' => [
                    'nullable',
                    'numeric',
                ],

                'user_id' => [
                    'nullable',
                    'integer',
                ],
            ]);

            $notification = AdminOrderNotification::firstOrCreate(
                [
                    'order_id' => $validated['order_id'],
                ],
                [
                    'restaurant_id' => $validated['restaurant_id'] ?? null,
                    'order_code' => $validated['order_code'] ?? null,
                    'title' => 'New Order Received',
                    'message' => !empty($validated['order_code'])
                        ? 'New order ' . $validated['order_code'] . ' has been received.'
                        : 'A new order has been received.',
                    'data' => [
                        'order_id' => $validated['order_id'],
                        'restaurant_id' => $validated['restaurant_id'] ?? null,
                        'amount' => $validated['amount'] ?? null,
                        'user_id' => $validated['user_id'] ?? null,
                    ],
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Order notification created.',
                'data' => [
                    'id' => $notification->id,
                    'order_id' => $notification->order_id,
                    'created' => $notification->wasRecentlyCreated,
                ],
            ]);
        } catch (\Throwable $e) {

            Log::error('Admin Order Notification API Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function latest(Request $request)
    {
        try {

            $afterId = (int) $request->get('after_id', 0);

            $notifications = AdminOrderNotification::query()
                ->where('id', '>', $afterId)
                ->orderBy('id', 'asc')
                ->limit(20)
                ->get();

            $latestId = AdminOrderNotification::max('id') ?? 0;

            return response()->json([
                'status' => true,
                'latest_id' => $latestId,
                'data' => $notifications,
            ]);
        } catch (\Throwable $e) {

            Log::error('Admin Order Notification Latest Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'data' => [],
            ], 500);
        }
    }
}
