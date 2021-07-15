<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\CampaignCreateRequest;
use App\Http\Requests\Campaign\CampaignUpdateRequest;
use App\Models\Campaign;
use App\Models\User;
use App\Traits\HasApiResponse;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class CampaignController extends Controller
{
    use HasApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
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
     * @return JsonResponse
     */
    public function store(CampaignCreateRequest $request): JsonResponse
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
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $campaign = Campaign::query()->findOrFail($id);
        $campaign['participants'] = $campaign->participants()->get();

        return $this->successApiResponse(
            $campaign->toArray(),
            200,
            ' Campaign Found.'
        );
    }


    public function update(CampaignUpdateRequest $request, int $id): JsonResponse
    {
        $campaign = Campaign::query()->findOrFail($id);
        $updated_data = $request->validated();
        $is_updated = $campaign->update($updated_data);

        if(!$is_updated) {
            return $this->errorApiResponse([],422,"Something went wrong");
        }

        return $this->successApiResponse(
            ['campaign' => Campaign::query()
                        ->with('participants')
                        ->findOrFail($id)],
            200,
            'Campaign updated successfully.'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $campaign = Campaign::query()->findOrFail($id);
        $campaign->delete();
        return $this->successApiResponse(
            ['campaign' => $campaign],
            200,
            ' Campaign deleted successfully.'
        );
    }

    public function fetchCampaign( Request $request): JsonResponse
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

        $campaigns =Campaign::query()
            ->select(['campaigns.*','participants.*'])
            ->join('participants', 'campaigns.id','=','participants.campaign_id')
            ->where('campaign_type', '=', (int) $request->input('campaign_type'))
            ->where('is_state_busy', '=', false)
            ->where('campaigns.user_id', '!=', (int) $request->input('user_id'))
            ->where('participants.user_id', '!=', (int) $request->input('user_id'))
//            ->where('participants.user_id', '!=', 'campaigns.user_id')
            ->inRandomOrder()->limit((int)$request->input('no_of_random_record'))
            ->with(['participants'])
            ->get();


        return $this->successApiResponse(
            $campaigns->toArray(),
            200,
            ' Campaign retrieved successfully.'
        );
    }

    public function fetchUserOwnCampaign(Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->errorApiResponse(
                ['errors' => $validator->errors()],
                422,
                'Validations Failed'
            );
        }

        $user = User::query()->findOrFail((int)$request->input('user_id'));

        $campaigns = $user->campaigns()->with(['participants'])->get();


        return $this->successApiResponse(
            $campaigns->toArray(),
            200,
            ' Campaign retrieved successfully.'
        );
    }

    public function fetchUserActionCampaign(Request $request) {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'campaign_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorApiResponse(
                ['errors' => $validator->errors()],
                422,
                'Validations Failed'
            );
        }

        $user = User::query()->findOrFail((int)$request->input('user_id'));

        $campaigns = Campaign::query()
            ->join('participants', 'campaigns.id', '=', 'participants.campaign_id')
            ->where('campaigns.campaign_type', '=', (int)$request->input('campaign_type'))
            ->where('participants.user_id', '=', (int)$request->input('user_id'))
            ->with(['participants'])
            ->get();

        return $this->successApiResponse(
            $campaigns->toArray(),
            200,
            ' Campaign retrieved successfully.'
        );
    }

}
