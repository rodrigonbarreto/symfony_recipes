<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HypermidiaResponse
{
    /**
     * @var bool
     */
    private $success;

    /**
     * @var mixed
     */
    private $data;
    /**
     * @var int
     */
    private $status;

    public function __construct($data, bool $success = true, int $status = Response::HTTP_OK)
    {
        $this->success = $success;
        $this->status = $status;
        $this->data = $data;
    }

    public static function fromError(\Throwable $error)
    {

        $statusCode = method_exists($error, 'getStatusCode' )
            ? $error->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;
        return new self(['mensagem' => $error->getMessage()], false, $statusCode, null);
    }

    public function getResponse(): JsonResponse
    {
        $data = get_object_vars($this);

        return new JsonResponse($data, $this->status);
    }
}
