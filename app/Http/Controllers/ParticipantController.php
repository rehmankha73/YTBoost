<?php

namespace App\Http\Controllers;

use App\Models\Participants;
use App\Traits\HasApiResponse;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    use HasApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'campaign_id' => 'required|numeric|exists:campaigns,id',
            'user_id' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->errorApiResponse(
                ['errors' => $validator->errors()],
                422,
                'Validation Failed'
            );
        }

//        auth()->user()->participants()->create(['campaign_id' => (int) $request->input('campaign_id')]);
        $participant = new Participants();
        $participant->campaign_id = (int) $request->input('campaign_id');
        $participant->user_id = (int) $request->input('user_id');
        $participant->save();

        return $this->successApiResponse(
            $participant->toArray(),
            200,
            ' Participant created successfully.'
        );


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
