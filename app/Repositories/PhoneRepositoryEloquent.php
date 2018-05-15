<?php

namespace App\Repositories;

use App\Entities\Phone;
use App\Repositories\PhoneRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class PhoneRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PhoneRepositoryEloquent extends AppRepository implements PhoneRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Phone::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
