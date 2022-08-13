<?php


namespace Modules\Shared\Interfaces;

use Modules\Shared\Libraries\UrlAggregation;

interface UrlAggregationInterface
{

    //--------------------------------------------------------------------

    /**
     * get key
     *
     * @param array $dataMap
     *
     */

    public function dataMap(array $dataMap): void;


    //--------------------------------------------------------------------

    /**
     * Get value.
     *
     *
     * @param string $key
     * @param string $value
     * @param string $function
     * @param string $sign
     * @param string|null $joinWith
     * @return string
     */
    public function encodeQueryParam(string $key, string $value, string $function, string $sign, string $joinWith = ''): string;

    //--------------------------------------------------------------------


    /**
     * Get value.
     *
     *
     *
     * @return UrlAggregation
     *
     */
    public function decodeQueryParam(): UrlAggregation;


    /**
     * Get value.
     * @param string $append
     *
     */
    public function setTableName(string $append): UrlAggregation;

    /**
     * Get value.
     * @return int
     */
    public function getForeignKey(): int;

    /**
     * Get value.
     * @param array|null $defaultPipeLine
     * @return array
     */
    public function getPipeLine(?array $defaultPipeLine = null): array;
    /**
     * Get value.
     */
    public function clearPipeLine(): void;
}
