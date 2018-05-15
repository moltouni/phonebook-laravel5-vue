<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ContactRepository;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Http\Requests\ContactDestroyRequest;
use App\Criteria\BelongsToCurrentUserCriteria;
use Illuminate\Support\Facades\Storage;

/**
 * Class ContactController.
 *
 * @package namespace App\Http\Controllers;
 */
class ContactController extends Controller
{
    /**
     * @var ContactRepository
     */
    protected $repository;

    /**
     * ContactController constructor.
     *
     * @param ContactRepository $repository
     */
    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * User contacts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->repository->pushCriteria( app('Prettus\Repository\Criteria\RequestCriteria') );
        $this->repository->pushCriteria( BelongsToCurrentUserCriteria::class );

        $contacts = $this->repository->orderBy('first_name')->all();

        return response()->json([
            'data' => $contacts,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ContactCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function store(ContactCreateRequest $request)
    {

        $data = $request->all();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = Storage::url($request->avatar->store('avatar'));
        }
        
        $contact = $this->repository->create($data);
        
        $this->repository->associate(
            $contact->id,
            'user',
            Auth::user()
        );

        $contact = $this->repository->find($contact->id);

        $response = [
            'message' => 'Contact created.',
            'data'    => $contact->toArray(),
        ];

        return response()->json($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->repository->pushCriteria( BelongsToCurrentUserCriteria::class );
        $contact = $this->repository->find($id);

        return response()->json([
            'data' => $contact,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContactUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     */
    public function update(ContactUpdateRequest $request, $id)
    {

        $data = $request->all();
        
        if ($request->hasFile('avatar')) {
            $data['avatar'] = Storage::url($request->avatar->store('avatar'));
        }

        $contact = $this->repository->update($data, $id);

        $response = [
            'message' => 'Contact updated.',
            'data'    => $contact->toArray(),
        ];

        return response()->json($response);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactDestroyRequest $request, $id)
    {
        $deleted = $this->repository->delete($id);

        return response()->json([
            'message' => 'Contact deleted.',
            'deleted' => $deleted,
        ]);

    }
}
