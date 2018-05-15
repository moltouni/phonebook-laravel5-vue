<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AppInterface.
 *
 * @package namespace App\Repositories;
 */
interface AppInterface extends RepositoryInterface
{
    /**
     * Sync relations
     *
     * @param $id
     * @param $relation
     * @param $model
     * @return mixed
     */
    public function associate($id, $relation, $model);
}
