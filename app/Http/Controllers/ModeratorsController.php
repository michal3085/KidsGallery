<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\PicturesReport;
use Illuminate\Http\Request;

class ModeratorsController extends Controller
{
    public function index()
    {
        $pictures = Picture::where('accept', 1)->latest()->paginate(10);

        return view('moderator.index', compact('pictures'));
    }

    public function blockPicture($id)
    {
        $picture = Picture::where('id', $id)->first();
        $picture->accept = 0;

        if ($picture->save()) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }

    }

    public function unblockPicture($id)
    {
        $picture = Picture::where('id', $id)->first();
        $picture->accept = 1;
        $picture->save();

        return redirect()->back();
    }

    public function showBlocked()
    {
        $pictures = Picture::where('accept', 0)->latest()->paginate(10);

        return view('moderator.blockedpics', compact('pictures'));
    }

    public function showReportedPictures($id = NULL)
    {
        if (!isset($id)) {
            $reports = PicturesReport::with('pictures')->latest()->paginate(20);
        } else {
            $reports = PicturesReport::where('picture_id', $id)->latest()->paginate(20);
        }
        return view('moderator.reportedpics', compact('reports'));
    }

    public function reportDown($id)
    {
        if (PicturesReport::destroy($id)) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }

    }

    public function reportDownAll($id)
    {
        if ( PicturesReport::where('picture_id', $id)->delete() ) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }

    }
}
