<?php

namespace StackoverflowApiClient\Response\Visitor;

/**
 * @author Nádasdi Attila
 * @since 2016.11.13.
 */
interface VisitableInterface
{
    /**
     * @param VisitorInterface $visitor
     */
    public function accept(VisitorInterface $visitor);
}
