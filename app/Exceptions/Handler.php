<?php

namespace MarketPlex\Exceptions;

use Exception;
use ErrorException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;

use Log;
use PDOException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        $vendor = config('app.vendor');
        if ($exception instanceof PDOException) {
            $errorMessage['DEFAULT'] = 'Something went wrong while connecting database. Please contact your server administrator.';
            $errorMessage['42S22'] = 'Your information contains data that has no property in database. Please contact ' . $vendor . ' for help.';
            $errorMessage['HY000'] = 'Database access denied';
            $errorMessage['23502'] = 'You have skipped providing some data that database schema designed to expect.';
            // $errorCode = !array_has($errorMessage, $exception->getCode()) ? 'DEFAULT' : $exception->getCode();

            // Log::critical('[' . $vendor . '][' . $exception->getMessage() . '] ' . $errorMessage[$errorCode] . '.'  );
            Log::critical('[' . $vendor . '][' . $exception->getMessage() . '] ');
            // flash()->error($errorMessage[$errorCode]);
            flash()->error(config('app.name') . ' says: "Database credentials are denied. Please contact your database administrator"');
            return redirect('/login');
        }
        if ($exception instanceof QueryException) {
            $errorMessage = 'Something went wrong while running database query. Please contact your database server administrator.';
            Log::critical('[' . $vendor . '][' . $exception->getMessage() . "] db query problem or remote database access denied.");
            flash()->error($errorMessage);
            return redirect('/login');
        }

        if ($exception instanceof TokenMismatchException) {
            $errorMessage = 'Something went wrong during your request! Please try again';
            Log::critical('[' . $vendor . '][' . $exception->getMessage() . "] validation error.");
            flash()->error($errorMessage);
            return redirect('/login');
        }
        if($exception instanceof ErrorException)
        {
            $errorMessage = 'Error Occurred.';
            Log::critical('[' . $vendor . '][' . $exception->getMessage() . "] " . $errorMessage . ".");
            flash()->error('problem occure '.$exception->getMessage());
            return redirect()->back();
        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
