<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Coupon;
use App\Models\Notice;
use Illuminate\Support\Facades\DB;

class NoticeService
{

    public function __construct()
    {

    }

    public static function markNoticeAsRead($userId, $noticeId) {
        $notice = Notice::findOrFail($noticeId);

        $readmarks = explode(',', $notice->readmarks ?? '');
        if (!in_array($userId, $readmarks)) {
            $readmarks[] = $userId;
            $notice->readmarks = implode(',', array_filter($readmarks));
            $notice->save();
            
        }

        // $readmarks = $notice->readmarks ? json_decode($notice->readmarks, true) : [];
    
        // if (!in_array($userId, $readmarks)) {
        //     $readmarks[] = $userId;
        //     $notice->readmarks = json_encode($readmarks);
        //     $notice->save();
        // }
    }

    public static function getUnreadCount($userId)
    {
        // $unreadCount = Notice::where(function ($query) use ($userId) {
        //     $query->whereNull('readmarks')
        //         ->orWhereRaw("NOT JSON_CONTAINS(readmarks, '\"$userId\"')");
        // })->count();

        // Get the current database connection type (MySQL, PostgreSQL, SQLite)
        $connection = \DB::getConnectionName();

        // $unreadCount = Notice::where(function ($query) use ($userId) {
        //     $query->whereNotNull('readmarks')
        //           ->orWhereRaw("FIND_IN_SET(?, readmarks) = 0", [$userId]);
        // })->count();

        switch ($connection) {
            case 'mysql':
                // MySQL: Use FIND_IN_SET to check if userId is NOT in the readmarks field
                $unreadCount = Notice::whereNotNull('readmarks')
                                     ->whereRaw('FIND_IN_SET(?, readmarks) = 0', [$userId])
                                     ->count();
                break;
    
            case 'pgsql':
                // PostgreSQL: Use STRING_TO_ARRAY and ARRAY_POSITION to check if userId is NOT in the readmarks field
                $unreadCount = Notice::whereNotNull('readmarks')
                                     ->whereRaw("ARRAY_POSITION(STRING_TO_ARRAY(readmarks, ','), ?) IS NULL", [$userId])
                                     ->count();
                break;
    
            case 'sqlite':
                // SQLite: Use LIKE to simulate the FIND_IN_SET logic (handle commas)
                $unreadCount = Notice::whereNotNull('readmarks')
                                     ->whereRaw('readmarks NOT LIKE ? AND readmarks LIKE ?', ['%' . $userId . '%', '%'])
                                     ->count();
                break;
    
            default:
                // Default case for unsupported or unknown databases
                $unreadCount = 0;
                break;
        }

        return $unreadCount;
    }


    public static function getUnreadCountByUserId($userId)
    {
        // Get all notices that the user may have unread (you can filter by other conditions if necessary)
        // $notices = Notice::all();  // You may want to filter this to only relevant notices
        
        $notices = Notice::where('show', 1)->orderBy('created_at', 'DESC')->get();
        
        // Count how many notices the user has not read
        $unreadCount = 0;
        
        foreach ($notices as $notice) {
            // If the user's ID is not in the readmarks list, it's unread for this user
            $readmarks = explode(',', $notice->readmarks ?? '');
            if (!in_array($userId, $readmarks)) {
                $unreadCount++;
            }
        }
        
        return $unreadCount;
    }

}
