<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\User;
use App\Services\NoticeService;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function fetch(Request $request)
    {
        $current = $request->input('current') ? $request->input('current') : 1;
        
        $pageSize = 5;
        
        $model = Notice::where('show', true) -> orderBy('sort', 'ASC');
        
        $total = $model->count();
        
        $res = $model->forPage($current, $pageSize)
            ->get();
        
        return response([
            'data' => $res,
            'total' => $total
        ]);
    }

    public function markNoticeAsRead(Request $request)  {

        $noticeId = $request->input('notice_id');
        $userId = $request->user()->id;

        $user = User::findOrFail($userId);

        NoticeService::markNoticeAsRead($user->id, $noticeId);

        return response([
            'message' => 'Notice marked as read'
        ]);
    }
}
