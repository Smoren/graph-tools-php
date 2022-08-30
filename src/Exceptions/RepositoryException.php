<?php

namespace Smoren\GraphTools\Exceptions;

/**
 * Graph repository exception class
 * @author <ofigate@gmail.com> Smoren
 */
class RepositoryException extends BaseGraphException
{
    public const VERTEX_NOT_FOUND = 1;
    public const EDGE_NOT_FOUND = 2;
}
