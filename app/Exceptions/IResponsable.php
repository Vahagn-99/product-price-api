<?php

namespace App\Exceptions;

use Illuminate\Http\Request;

interface IResponsable
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  Request  $request
     * @return \Response
     */
    public function render(Request $request) : \Response;
}