<?php 

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process( callable $next )
    {
        try{
            $next();
        }catch( ValidationException $e ) {
            $OldFormData = $_POST;
            $excludedFields = ['password', 'confirm_password'];
            $formattedFormData = array_diff_key(
                $OldFormData,
                array_flip($excludedFields)
            );

            $_SESSION['errors'] = $e->errors;
            $_SESSION['OldFormData'] = $formattedFormData;
            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}