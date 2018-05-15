<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\PhoneRepository;
use App\Http\Requests\PhoneCreateRequest;
use App\Http\Requests\PhoneUpdateRequest;
use App\Http\Requests\PhoneDestroyRequest;
use App\Http\Requests\PhoneIndexRequest;

/**
 * Class PhoneController.
 *
 * @package namespace App\Http\Controllers;
 */
class PhoneController extends Controller
{
    /**
     * @var PhoneRepository
     */
    protected $repository;

    /**
     * PhoneController constructor.
     *
     * @param PhoneRepository $repository
     */
    public function __construct(PhoneRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PhoneIndexRequest $request, $contact)
    {

        $phones = $this->repository->findByField('contact_id', $contact);

        return response()->json([
            'data' => $phones,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PhoneCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function store(PhoneCreateRequest $request, $contact)
    {

        $this->repository->deleteWhere([
            'contact_id' => $contact
        ]);
            
        $data = $request->get('phones');
        foreach ($data as $phone) {
            $phone = $this->repository->create($phone);
            $this->repository->associate($phone->id, 'contact', $contact);
        }

        $phones = $this->repository->findByField('contact_id', $contact);
        $response = [
            'message' => 'Phones synced.',
            'data'    => $phones,
        ];

        return response()->json($response);

    }

}
