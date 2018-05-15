<?php

namespace App\Repositories;

use App\Entities\Contact;
use App\Repositories\ContactRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ContactRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ContactRepositoryEloquent extends AppRepository implements ContactRepository
{

    protected $fieldSearchable = [
        'favorite',
        'first_name' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Contact::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
