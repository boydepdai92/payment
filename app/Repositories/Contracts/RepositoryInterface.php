<?php

namespace App\Repositories\Contracts;

use Prettus\Repository\Contracts\CriteriaInterface;

interface RepositoryInterface extends \Prettus\Repository\Contracts\RepositoryInterface
{
    public function findWhereForUpdate(array $conditions, $columns = ['*']);

    public function findWhereFirst(array $conditions, $columns = ['*']);

	public function criteriaPaginate(CriteriaInterface $criteria, int $limit = 15, array $columns = ['*']);

    public function saveMany(array $data): bool;

    public function upsert(array $records): bool;
}
