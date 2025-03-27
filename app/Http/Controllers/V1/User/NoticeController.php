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
        $pageSize = $request->input('page_size') ? $request->input('page_size') : 5;
        // $pageSize = 5;

        $model = Notice::where('show', true) -> orderBy('sort', 'ASC');
        
        $total = $model->count();
        
        $res = $model->forPage($current, $pageSize)
            ->get();
        
        return response([
            'data' => $res,
            'total' => $total,
            'current' => $current
        ]);
    }

    public function fetchNoticeList(Request $request)
    {
        // Ensure 'current' and 'pageSize' are integers
        $current = intval($request->input('current', 1));
        $pageSize = intval($request->input('pageSize', 10));
        // $pageSize = 5;
        $userId = $request->user()->id;

        // Get the total count of all notices
        $total = Notice::where('show', true)->count();
        
        $model = Notice::where('show', true)
            -> orderBy('sort', 'ASC')
            ->forPage($current, $pageSize)
            ->get();
        
        // Process each notice to determine if it's read
        $model->transform(function ($notice) use ($userId) {
            $readmarks = explode(',', $notice->readmarks ?? '');
            $notice->is_read = in_array($userId, $readmarks);
            unset($notice->readmarks);
            return $notice;
        });

        return response([
            'data' => $model,
            'total' => $total,
            'current' => $current,
            'pageSize' => $pageSize
        ]);
        
        // return $this->success( [
        //     'data' => $model,
        //     'total' => $total,
        //     'current' => $current,
        //     'pageSize' => $pageSize
        // ]);
    }

    public function markNoticeAsRead(Request $request)  {

        $noticeId = $request->input('noticeId');
        $userId = $request->user()->id;

        $user = User::findOrFail($userId);

        $result = NoticeService::markNoticeAsRead($user->id, $noticeId);

        return response([
            'data' => $result
        ]);
    }
}
