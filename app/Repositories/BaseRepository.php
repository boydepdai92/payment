<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\RepositoryInterface;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Repository\Eloquent\BaseRepository as L5Repository;

/**
 * Class BaseRepository
 * @property Model|Builder $model
 * @package App\Repositories
 */
abstract class BaseRepository extends L5Repository implements RepositoryInterface
{
	/**
	 * @param array $conditions
	 * @param array $columns
	 * @return mixed
	 * @throws RepositoryException
	 */
	public function findWhereForUpdate(array $conditions, $columns = ['*'])
    {
        $this->applyConditions($conditions);

	    $results = $this->model->lockForUpdate()->first($columns);

        $this->resetModel();

        return $this->parserResult($results);
    }

    public function findWhereFirst(array $conditions, $columns = ['*'])
    {
	    $this->applyConditions($conditions);

	    return $this->first($columns);
    }

	/**
	 * @param CriteriaInterface $criteria
	 * @param int $limit
	 * @param array $columns
	 * @return mixed
	 * @throws RepositoryException
	 */
	public function criteriaPaginate(CriteriaInterface $criteria, int $limit = 15, array $columns = ['*'])
    {
	    $this->pushCriteria($criteria);

	    return $this->paginate($limit, $columns);
    }

    public function saveMany(array $data): bool
    {
        return $this->model->insert($data);
    }

    public function upsert(array $records): bool
    {
        $columnsString = $valuesString = $updateString = '';
        $params = [];
        $size   = count($records);

        for ($i = 0; $i < $size; $i++) {
            $row = (array) $records[$i];
            if ($i == 0) {
                foreach ($row as $key => $value) {
                    $columnsString .= "$key,";
                    $updateString .= "$key=VALUES($key),";
                }
                $columnsString = rtrim($columnsString, ',');
                $updateString = rtrim($updateString, ',');
            } else {
                $valuesString .= ',';
            }

            $valuesString .= '(';

            foreach ($row as $value) {
                $valuesString .= '?,';
                if (empty($value)) {
                    $value = '';
                }
                $params[] = $value;
            }

            $valuesString = rtrim($valuesString, ',');
            $valuesString .= ')';
        }

        $query = "INSERT INTO {$this->model->getTable()} ({$columnsString}) VALUES $valuesString ON duplicate KEY UPDATE $updateString";

        return $this->model->getConnection()->statement($query, $params);
    }

    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                if (strtoupper($condition) == 'IN') {
                    $this->model = $this->model->whereIn($field, $val);
                } else if (strtoupper($condition) == 'NOT_IN') {
                    $this->model = $this->model->whereNotIn($field, $val);
                } else {
                    $this->model = $this->model->where($field, $condition, $val);
                }
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }
}
