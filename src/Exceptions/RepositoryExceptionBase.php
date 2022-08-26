<?php

namespace Smoren\GraphTools\Exceptions;

class RepositoryExceptionBase extends BaseGraphException
{
    public const VERTEX_NOT_FOUND = 1;
    public const CONNECTION_NOT_FOUND = 2;
}
