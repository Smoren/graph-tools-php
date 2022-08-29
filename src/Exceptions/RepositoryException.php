<?php

namespace Smoren\GraphTools\Exceptions;

class RepositoryException extends BaseGraphException
{
    public const VERTEX_NOT_FOUND = 1;
    public const CONNECTION_NOT_FOUND = 2;
}