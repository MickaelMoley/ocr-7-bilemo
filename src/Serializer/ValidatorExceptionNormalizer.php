<?php


namespace App\Serializer;


use App\Exception\FormException;
use JMS\Serializer\Exception\ValidationFailedException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ValidatorExceptionNormalizer implements NormalizerInterface
{
	/**
	 * @param ValidationFailedException $exception
	 * @param null          $format
	 * @param array         $context
	 *
	 * @return array|bool|float|int|string|void
	 */
	public function normalize($exception, $format = null, array $context = [])
	{

		$data   = [];
		$errors = $exception->getConstraintViolationList();


		/** @var ConstraintViolation[] $errors */
		foreach ($errors as $error) {
			$data[] = [
				'message' 		=> $error->getMessage(),
				'propertyPath' 	=> $error->getPropertyPath(),
				'invalidValue' 	=> $error->getInvalidValue()
			];

		}

		return $data;
	}

	/**
	 * @param mixed $data
	 * @param null  $format
	 *
	 * @return bool|void
	 */
	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof ValidationFailedException;
	}
}