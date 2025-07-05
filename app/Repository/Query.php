<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Database\Eloquent\{
    Builder,
    Collection,
    Model,
    ModelNotFoundException,
};

class Query
{
    /**
     * Constructor Query
     *
     * @param string $model_class_name
     */
    public function __construct(protected string $model_class_name) {
        //
    }

    /**
     * Получить Builder объект модели.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder() : Builder
    {
        return $this->model_class_name::query();
    }

    /**
     * Получение среза данных.
     *
     * @param int $limit
     * @param int $offset
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilderDataSlice(int $limit, int $offset) : Builder
    {
        return $this->builder()
            ->limit($limit)
            ->offset($offset);
    }

    /**
     * Получить все записи.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(array $columns = ['*']) : Collection
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        if (! empty($where)) {
            return $this->builder()
                ->where($where)
                ->get($columns);
        } else {
            return $this->builder()
                ->get($columns);
        }
    }

    /**
     * Получить все записи по условию.
     *
     * @param array $where
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllBy(array $where, array $columns = ['*']) : Collection
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        return $this->builder()
            ->where($where)
            ->get($columns);
    }

    /**
     * Получить записи по условию.
     *
     * @param string $column
     * @param array $in
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWhereIn(string $column, array $in, array $columns = ['*']) : Collection
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        return $this->builder()
            ->whereIn($column, $in)
            ->get($columns);
    }

    /**
     * Получить записи по первичному ключу.
     * Если ничего не будет найдено, то будет выброшено исключение.
     *
     * @param mixed $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function get(mixed $id, array $columns = ['*']) : Model
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        try {
            return $this->builder()
                ->findOrFail($id, $columns);
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException($this->getRepositoryName()." - id $id not found");
        }
    }

    /**
     * Получить записи по первичному ключу.
     *
     * @param mixed $id
     * @param array $columns
     * @param bool $with_trashed
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(mixed $id, array $columns = ['*'], bool $with_trashed = false) : ?Model
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        $query = $this->builder();

        if ($with_trashed) {
            $query->withTrashed();
        }

        return $query->find($id, $columns);
    }

    /**
     * Поиск записи по условиям.
     *
     * @param array $where
     * @param array $columns
     * @param string $where_boolean
     * @param bool $with_trashed
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBy(
        array $where,
        array $columns = ['*'],
        string $where_boolean = 'and',
        bool $with_trashed = false
    ) : ?Model
    {
        if (empty($columns)) {
            $columns = ['*'];
        }

        $query = $this->builder();

        if (! empty($with_trashed)) {
            $query->withTrashed();
        }

        return $query->where($where, $where_boolean)
            ->first($columns);
    }

    /**
     * Получить количество записей в таблице.
     *
     * @return int
     */
    public function count() : int
    {
        return $this->builder()
            ->count();
    }

    /**
     * Получить количество записей по условию.
     *
     * @param array $where
     * @return int
     */
    public function countBy(array $where) : int
    {
        return $this->builder()
            ->where($where)
            ->count();
    }

    //****************************************************************
    //************************** Support *****************************
    //****************************************************************

    /**
     * Получить название репозитория
     *
     * @return string
     */
    protected function getRepositoryName() : string
    {
        return class_basename(get_class($this));
    }
}
