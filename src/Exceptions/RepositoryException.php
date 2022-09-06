<?php

namespace Smoren\GraphTools\Exceptions;

/**
 * Graph repository exception class
 * @author Smoren <ofigate@gmail.com>
 */
class RepositoryException extends BaseGraphException
{
    public const VERTEX_NOT_FOUND = 1;
    public const EDGE_NOT_FOUND = 2;
}
