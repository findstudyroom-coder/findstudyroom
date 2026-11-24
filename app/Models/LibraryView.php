<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryView extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'referer',
        'action_type',
    ];

    public function library()
    {
        return $this->belongsTo(Library::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a visitor click or action on a library
     */
    public static function recordVisit($libraryId, $request = null, $actionType = 'view')
    {
        try {
            $request = $request ?: request();
            $userAgent = $request->userAgent() ?? '';
            $ip = $request->ip();
            $sessionId = $request->hasSession() ? $request->session()->getId() : session()->getId();
            $userId = auth()->id() ?? auth('web')->id() ?? null;
            $referer = $request->headers->get('referer') ?? null;

            // Simple device detection
            $device = 'Desktop';
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
                $device = 'Tablet';
            } elseif (preg_match('/(mobile|iphone|ipod|blackberry|opera mini|iemobile|kindle|silk|android)/i', $userAgent)) {
                $device = 'Mobile';
            }

            // Simple browser detection
            $browser = 'Other';
            if (preg_match('/edg/i', $userAgent)) {
                $browser = 'Edge';
            } elseif (preg_match('/chrome|crios/i', $userAgent)) {
                $browser = 'Chrome';
            } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
                $browser = 'Firefox';
            } elseif (preg_match('/safari/i', $userAgent)) {
                $browser = 'Safari';
            } elseif (preg_match('/msie|trident/i', $userAgent)) {
                $browser = 'IE';
            } elseif (preg_match('/opera|opr/i', $userAgent)) {
                $browser = 'Opera';
            }

            // Throttle duplicate page views for identical IP / session / user on the same day for a library
            if ($actionType === 'view') {
                $recent = self::where('library_id', $libraryId)
                    ->where(function($q) use ($ip, $sessionId, $userId) {
                        if ($userId) {
                            $q->where('user_id', $userId);
                        } elseif ($ip) {
                            $q->where('ip_address', $ip)
                              ->orWhere('session_id', $sessionId);
                        } else {
                            $q->where('session_id', $sessionId);
                        }
                    })
                    ->where('action_type', 'view')
                    ->whereDate('created_at', \Carbon\Carbon::today())
                    ->first();

                if ($recent) {
                    return $recent;
                }
            }

            return self::create([
                'library_id'  => $libraryId,
                'user_id'     => $userId,
                'session_id'  => $sessionId,
                'ip_address'  => $ip,
                'user_agent'  => substr($userAgent, 0, 500),
                'device'      => $device,
                'browser'     => $browser,
                'referer'     => $referer ? substr($referer, 0, 500) : null,
                'action_type' => $actionType,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error recording library view: ' . $e->getMessage());
            return null;
        }
    }
}
