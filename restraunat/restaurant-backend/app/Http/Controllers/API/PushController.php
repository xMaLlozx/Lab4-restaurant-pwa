<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

/**
 * @OA\Tag(name="Push", description="Push-уведомления")
 */
class PushController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/push/subscribe",
     *   tags={"Push"},
     *   summary="Подписаться на push-уведомления",
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"endpoint","keys"},
     *       @OA\Property(property="endpoint", type="string"),
     *       @OA\Property(property="keys", type="object",
     *         @OA\Property(property="p256dh", type="string"),
     *         @OA\Property(property="auth", type="string")
     *       )
     *     )
     *   ),
     *   @OA\Response(response=200, description="Подписка сохранена")
     * )
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth'   => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'endpoint' => $request->endpoint,
                'p256dh'   => $request->keys['p256dh'],
                'auth'     => $request->keys['auth'],
            ]
        );

        return response()->json(['message' => 'Подписка сохранена']);
    }

    /**
     * @OA\Post(
     *   path="/api/push/send",
     *   tags={"Push"},
     *   summary="Отправить push-уведомление",
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"title","body"},
     *       @OA\Property(property="title", type="string", example="Ваш заказ принят!"),
     *       @OA\Property(property="body", type="string", example="Ожидайте доставку через 30 минут")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Уведомление отправлено")
     * )
     */
    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body'  => 'required|string',
        ]);

        $subscription = PushSubscription::where('user_id', $request->user()->id)->first();

        if (!$subscription) {
            return response()->json(['message' => 'Нет активной подписки'], 404);
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject'    => env('APP_URL'),
                'publicKey'  => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);

        $webPush->queueNotification(
            Subscription::create([
                'endpoint'        => $subscription->endpoint,
                'keys'            => [
                    'p256dh' => $subscription->p256dh,
                    'auth'   => $subscription->auth,
                ],
            ]),
            json_encode([
                'title' => $request->title,
                'body'  => $request->body,
                'icon'  => '/icons/icon-192x192.png',
            ])
        );

        foreach ($webPush->flush() as $report) {
            // Если подписка устарела — удаляем
            if ($report->isSubscriptionExpired()) {
                $subscription->delete();
            }
        }

        return response()->json(['message' => 'Уведомление отправлено']);
    }
}
