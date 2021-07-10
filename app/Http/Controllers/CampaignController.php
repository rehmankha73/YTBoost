<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\CampaignCreateRequest;
use App\Http\Requests\Campaign\CampaignUpdateRequest;
use App\Models\Campaign;
use App\Traits\HasApiResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    use HasApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $campaigns= Campaign::query()
            ->with('participants')
            ->latest()
            ->get();

        return $this->successApiResponse(
            $campaigns->toArray(),
            200,
            'All campaigns retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Campaign\CampaignCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CampaignCreateRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated_data = $request->validated();
        $campaign = Campaign::query()->create($validated_data);
        $campaign['participants'] = $campaign->participants()->get();
        return $this->successApiResponse(
            $campaign->toArray(),
            200,
            'New Campaign created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $campaign = Campaign::query()->findOrFail($id);
        $campaign['participants'] = $campaign->participants()->get();

        return $this->successApiResponse(
            $campaign->toArray(),
            200,
            ' Campaign Found.'
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Campaign\CampaignUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CampaignUpdateRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        $campaign = Campaign::query()->findOrFail($id);
        $updated_data = $request->validated();
        $is_updated = $campaign->update($updated_data);

        if(!$is_updated) {
            return $this->errorApiResponse([],422,"Something went wrong");
        }

        return $this->successApiResponse(
            ['campaign' => auth()->user()->campaigns()->findOrFail($id)],
            200,
            'Campaign updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $campaign = Campaign::query()->findOrFail($id);
        $campaign->delete();
        return $this->successApiResponse(
            ['campaign' => $campaign],
            200,
            ' Campaign deleted successfully.'
        );
    }

    public function fetchUserActionCampaign(Request $request) {
        $user = auth()->user();

        $campaigns = DB::table('campaigns')
            ->join('participants', 'campaigns.id', '=', 'participants.user_id')
            ->where('campaign_type', '=', 2)
            ->where('participants.user_id', '=', auth()->id())
            ->where('campaigns.user_id', '!=', auth()->id())
            ->get();


        return $this->successApiResponse(
          $campaigns->toArray(),
            200,
            ' Campaign retrieved successfully.'
        );
    }

    public function fetchCampaign( Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'campaign_type' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'no_of_random_record' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorApiResponse(
                ['errors' => $validator->errors()],
                422,
                'Validation Failed'
            );
        }
        $campaigns = DB::table('campaigns')
            ->where('campaign_type', '=', (int) $request->input('campaign_type'))
            ->where('is_state_busy', '=', false)
            ->where('campaigns.user_id', '!=', (int) $request->input('user_id'))
            ->inRandomOrder()->limit((int)$request->input('no_of_random_record'))
            ->get();

        return $this->successApiResponse(
            $campaigns->toArray(),
            200,
            ' Campaign retrieved successfully.'
        );
    }
}
