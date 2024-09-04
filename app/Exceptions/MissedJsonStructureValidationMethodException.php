<?php

namespace App\Exceptions;

use Exception;

class MissedJsonStructureValidationMethodException extends Exception
{
    /**
     * Render the exception as an JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
            ], 404);
        }
    }
}
