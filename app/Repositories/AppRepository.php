<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Interface AppRepository.
 *
 * @package namespace App\Repositories;
 */
abstract class AppRepository extends BaseRepository implements AppInterface
{
    /**
     * Associate model
     *
     * @param $id
     * @param $relation
     * @param $model
     * @return mixed
     */
    public function associate($id, $relation, $model)
    {
        return $this->find($id)->{$relation}()->associate($model)->save();
    }
}
