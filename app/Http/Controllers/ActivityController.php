<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class ActivityController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function getDaily(Request $request, $causer_id = 0, $causer_type = '')
	{
		$user = auth()->user();

		if (! $user->can('activity.view'))
		{
			return \Response::noContent(403);
		}

		$date		= $request->input('date') ?? Carbon::now()->toDateTimeString();
		$start_date = Carbon::create($date)->startOfDay()->toDateTimeString();
		$end_date	= Carbon::create($date)->endOfDay()->toDateTimeString();
		$response	= [];
		$where_and	= [
			[ 'log_name', '=', 'default' ]
		];

		if (isset($causer_id) && $causer_id > 0)
		{
			$where_and[] = [ 'causer_id', '=', $causer_id ];
		}

		if (isset($causer_type) && strlen($causer_type) > 0)
		{
			$where_and[] = [ 'causer_type', '=', $causer_type ];
		}

		$activities = Activity::whereBetween('created_at', [ $start_date, $end_date ])
			->where($where_and)
			->get();

		foreach ($activities as $activity)
		{
			$response[] = [
				'message'	=> $activity->description,
				'action'	=> $activity->getExtraProperty('action'),
				'time'		=> $activity->created_at->format('H:i:s'),
			];
		}

		return \Response::json($response);
	}

	public function view()
	{
		$user = auth()->user();

		if (! $user->can('activity.view'))
		{
			return back();
		}

		return view('activity-log');
	}
}
