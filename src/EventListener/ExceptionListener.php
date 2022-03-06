<?php

namespace App\EventListener;

use App\Factory\NormalizerFactory;
use App\Http\ApiResponse;
use JMS\Serializer\Exception\ValidationFailedException;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ExceptionListener
{

	/**
	 * @var NormalizerFactory
	 */
	private $normalizerFactory;

	/**
	 * ExceptionListener constructor.
	 *
	 * @param NormalizerFactory $normalizerFactory
	 */
	public function __construct(NormalizerFactory $normalizerFactory)
	{
		$this->normalizerFactory = $normalizerFactory;
	}

	/**
	 * @param ExceptionEvent $event
	 */
	public function onKernelException(ExceptionEvent $event)
	{

		$exception = $event->getThrowable();
		$request   = $event->getRequest();


		$response = $this->createApiResponse($exception);
		$event->setResponse($response);
	}

	/**
	 * Creates the ApiResponse from any Exception
	 *
	 * @param \Throwable $exception
	 *
	 * @return ApiResponse
	 */
	private function createApiResponse(\Throwable $exception): ApiResponse
	{
		$normalizer = $this->normalizerFactory->getNormalizer($exception);
		$statusCode = Response::HTTP_BAD_REQUEST;


		if($exception instanceof  HttpExceptionInterface)
		{
			$statusCode = $exception->getStatusCode();
		}
		if($exception instanceof ValidationFailedException)
		{
			$statusCode = Response::HTTP_BAD_REQUEST;
		}



		try {
			$errors = $normalizer ? $normalizer->normalize($exception) : [];

			if($exception->getPrevious() instanceof OutOfRangeCurrentPageException){
				$errors = [];
				$errors['message'] = $exception->getPrevious()->getMessage();
			}

			if($exception instanceof \LogicException){
				$errors = [];
			}

		} catch (\Exception $e) {
			$errors = [];
		} catch (ExceptionInterface $e) {
		}

		return new ApiResponse($exception->getMessage(), null, $errors, $statusCode);
	}
}