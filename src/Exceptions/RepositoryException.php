<?php

namespace Smoren\GraphTools\Exceptions;

use Smoren\ExtendedExceptions\BaseException;

class RepositoryException extends BaseException
{
    public const VERTEX_NOT_FOUND = 1;
    public const CONNECTION_NOT_FOUND = 2;
}
