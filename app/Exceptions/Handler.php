<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        
        if ($exception instanceof ModelNotFoundException) {
            
            $path = explode('/', $request->path());
            $isAjax = !empty($path[0]) && $path[0]== 'v1' ? true : false;
                
            if ($isAjax) {
                return response()->json([
                    'status' => 0,
                    'result' => new \stdClass(),
                    'message' => 'Not Found',
                ], 404);
            }
        }


        if (str_contains($exception->getMessage(), 'unserialize()')) {
            /*$cookie1 = \Cookie::forget('browserId');
            $cookie2 = \Cookie::forget('previousUrl');
            $cookie3 = \Cookie::forget('XSRF-TOKEN');
            $cookie4 = \Cookie::forget('shopzz_session');
            $cookie5 = \Cookie::forget('webp');

            return redirect()->to('/')
                ->withCookie($cookie1)
                ->withCookie($cookie2)
                ->withCookie($cookie3)
                ->withCookie($cookie4)
                ->withCookie($cookie5);*/

                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        setcookie($name, '', time()-1000);
                        setcookie($name, '', time()-1000, '/');
                    }
                    return redirect()->to('/');
                }
        }
         
       /* $currentTheme = $request->is('admin/*') ? 'admin' : config('app.themename');
        */
        if($exception instanceof NotFoundHttpException)
        { 
            return response()->view('errors.404', [], 404);
        }
       if($exception instanceof HttpException)
        {   
            return response()->view('errors.500', [], 500);
        }
        if($exception instanceof HttpExceptionInterface)
        {   
            return response()->view('errors.500', [], 500);
        }
        
        return parent::render($request, $exception);
    }

    
    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        $path = explode('/', $request->path());
        $isAjax = !empty($path[0]) && $path[0]== 'api' ? true : false;
        
        if ($e->response) {
            return $e->response;
        }
        
        return ($request->expectsJson() || $isAjax)
        ? $this->invalidJson($request, $e)
        : $this->invalid($request, $e);
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }*/

    protected function unauthenticated($request, AuthenticationException $exception)
    {

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $guard = array_get($exception->guards(), 0);

        switch ($guard) {
            case 'admin': //depends which guard you failed to auth
                $login = 'admin';
                break;
            case 'advertiser':
                $login = 'advertiser';
                break;
            default:
                $login = '/';
                break;
        }

        return redirect()->guest($login);
        
    }
    
    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        $errors = collect($exception->errors())->first();
        
        $message = '';
        
        if(!empty($errors[0]))
        {
            $message = $errors[0];
        }
        return response()->json([
            'status' => 0,
            'result' => new \stdClass(),
            'message' => $message,
        ], 200);
    }
}
