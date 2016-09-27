<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use App\Exceptions\GenericException;

use Config;
use Mail;
use Carbon\Carbon;
use Auth;
use Session;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        GenericException::class,
        TokenMismatchException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($e instanceof NotFoundHttpException)
        {
            return parent::report($e);
        }

        if ($e instanceof Exception && Config::get('app.env') == 'production')
        {
            $debug = Config::get('app.debug');
            Config::set('app.debug', true);

            if (ExceptionHandler::isHttpException($e))
            {
                $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::renderHttpException($e), $e);
            }
            else
            {
                $content = ExceptionHandler::toIlluminateResponse(ExceptionHandler::convertExceptionToResponse($e), $e);
            }

            Config::set('app.debug', $debug);

            $error = (!isset($content->original)) ? $e->getMessage() : $content->original;

            $data['exception'] = $e;
            $data['content']   = $error;
            $data['date']      = Carbon::now();
            $data['user']      = Auth::user();

            Mail::send(['html' => 'emails.report'], $data, function ($message) use($error) {
                $message->from(config('mail.sender'), 'Azote.us');
                $message->to('kerubim@azote.us', 'Web Developer');
                $message->subject('Azote.us - PHP Error Report - ' . Carbon::now());
                $message->attachData($error, 'stacktrace.html');
            });
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof GenericException)
        {
            $data   = $e->toArray();
            $status = $e->getStatus();

            return response()->view('errors.generic', $data, $status);
        }

        return parent::render($request, $e);
    }
}
