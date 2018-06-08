<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    /**
     *
     * @param  [string] $builder
     * @return [QueryBuilder]
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {

            if(method_exists($this, $filter)) {

                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * [Récupère les paramètres de la requete en fonction des filtres attendus
     * @return [array]
     */
    public function getFilters()
    {
        return $this->request->only($this->filters);
    }


}
