<?php

namespace App\Http\Controllers\Api;

use App\Http\Models\Repositories\EventRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EventController extends BaseController
{

    private $EventRepo;

    public function __construct(EventRepo $EventRepo)
    {
        $this->EventRepo = $EventRepo;
    }

    public function all()
    {
        try {
            $users = $this->EventRepo->all();
            $response = [
                'status' => 'OK',
                'code' => 200,
                'message' => __('Correctly Obtained Data'),
                'data' => $users,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            $response = [
                'status' => 'FAILED',
                'code' => 500,
                'message' => __('An internal error has occurred') . '.',
            ];
            return response()->json($response, 500);
        }
    }

    public function getByDay($day)
    {
        try {
            $hours = [
              '9',
              '10',
              '11',
              '12',
              '13',
              '14',
              '15',
              '16',
              '17',
              '18',
            ];
            $events = [];
            $day = Carbon::parse($day)->startOfDay();
            foreach ($hours as $hour){
                $day->hour((int)$hour);
                $d = $day;
                $d->toDateTimeString();
                $event = $this->EventRepo->getByDay($d);
                $events[$hour] = $event;
            }

            $response = [
                'status' => 'OK',
                'code' => 200,
                'message' => __('Correctly Obtained Data'),
                'data' => $events,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            $response = [
                'status' => 'FAILED',
                'code' => 500,
                'message' => __('An internal error has occurred') . '.',
            ];
            return response()->json($response, 500);
        }
    }


    public function read($id)
    {
        try {
            $user = $this->EventRepo->find($id);
            $response = [
                'status' => 'OK',
                'code' => 200,
                'message' => __('Correctly Obtained Data'),
                'data' => $user,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'status' => 'FAILED',
                'code' => 500,
                'message' => __('An internal error has occurred') . '.',
            ];
            return response()->json($response, 500);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => 'FAILED',
                'code' => 400,
                'message' => __('Incorrect Params'),
                'data' => $validator->errors()->getMessages(),
            ];
            return response()->json($response);
        }
        try {
            $data = [
                'name' => $request->get('name'),
                'date' =>Carbon::parse($request->get('date'))->toDateTimeString(),
                'email' => $request->get('email'),
            ];
            $event = $this->EventRepo->getByDate($data['date']);
            if (!is_null($event)) {
                $response = [
                    'status' => 'FAILED',
                    'code' => 500,
                    'message' => __('this time is busy, choose another') . '.',

                ];
                return response()->json($response, 500);
            }
            $event = $this->EventRepo->store($data);
            $response = [
                'status' => 'OK',
                'code' => 200,
                'message' => __('Citation Registered Succesfully'),
                'data' => $event,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            $response = [
                'status' => 'FAILED',
                'code' => 500,
                'message' => __('An internal error has occurred') . '.',

            ];
            return response()->json($response, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $event = $this->EventRepo->find($id);
            $data = $request->all();
            $event_db = $this->EventRepo->getByDate($data['date'], $id);
            if (!is_null($event_db)) {
                $response = [
                    'status' => 'FAILED',
                    'code' => 500,
                    'message' => __('this time is busy, choose another') . '.',

                ];
                return response()->json($response, 500);
            }
            $event = $this->EventRepo->update($event, $data);
            $response = [
                'status' => 'OK',
                'code' => 200,
                'message' => __('Citation Updated Successfully'),
                'data' => $event,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'status' => 'FAILED',
                'code' => 500,
                'message' => 'An internal error has occurred',
            ];
            return response()->json($response, 500);
        }
    }

    public function delete($id)
    {
        try {
            $event = $this->EventRepo->delete($id);
            $response = [
                'status' => 'OK',
                'code' => 200,
                'message' => __('Citation Deleted Succesfully'),
                'data' => $event,
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            $response = [
                'status' => 'FAILED',
                'code' => 500,
                'message' => __('An internal error has occurred') . '.',

            ];
            return response()->json($response, 500);
        }
    }
}
