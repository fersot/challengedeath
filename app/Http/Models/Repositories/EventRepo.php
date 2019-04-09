<?php

namespace App\Http\Models\Repositories;

use App\Http\Models\Entities\Event;

class EventRepo
{

    public function all()
    {
        $Event = Event::all();
        return $Event;
    }

    public function find($id)
    {
        $Event = Event::find($id);
        return $Event;
    }

    public function getByDay($day)
    {
        $Event = Event::where('date',$day)->first();
        return $Event;
    }

    public function store($data)
    {
        $Event = Event::create($data);
        return $Event;
    }

    public function getByDate($date,$id = null)
    {
        if(is_null($id)){
            $Event = Event::where('date', $date)->first();
        }else{
            $Event = Event::where('date', $date)->whereNotIn('id',[$id])->first();
        }

        return $Event;
    }

    public function update($Event, $data)
    {
        $Event->update($data);
        return $Event;
    }
    public function delete($id)
    {
        $Event = Event::find($id);
        $Event->delete();
        return $Event;
    }

}
