<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Symfony;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AddJsonBodyToRequestListener
{
    /***
     * Processes incoming requests to decode JSON content and modify the request parameters.
     * @param RequestEvent $event
     * @return void
     * @throws \JsonException
     */
	public function onKernelRequest(RequestEvent $event): void
	{
		$request = $event->getRequest();
		$requestContents = $request->getContent();

		if (!empty($requestContents) && $this->containsHeader($request, 'Content-Type', 'application/json')) {
			$jsonData = json_decode($requestContents, true, 512, JSON_THROW_ON_ERROR);
			if (!$jsonData) {
				throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid json data');
			}
			$jsonDataLowerCase = [];
			foreach ($jsonData as $key => $value) {
				$jsonDataLowerCase[preg_replace_callback(
					'/_(.)/',
					static fn ($matches): string => strtoupper((string) $matches[1]),
					(string) $key
				)] = $value;
			}
			$request->request->replace($jsonDataLowerCase);
		}
	}

    /***
     * Checks if a request contains a specific header with a specific value.
     * @param Request $request
     * @param string $name
     * @param string $value
     * @return bool
     */
	private function containsHeader(Request $request, string $name, string $value): bool
	{
		return str_starts_with((string) $request->headers->get($name), $value);
	}
}
