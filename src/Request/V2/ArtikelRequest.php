<?php
/**
 * @author  OptiWise Technologies B.V. <info@optiwise.nl>
 * @project SnelstartApiPHP
 */

namespace SnelstartPHP\Request\V2;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\UuidInterface;
use SnelstartPHP\Model\V2\Relatie;
use SnelstartPHP\Request\BaseRequest;
use SnelstartPHP\Request\ODataRequestData;

final class ArtikelRequest extends BaseRequest
{
    public static function findAll(ODataRequestData $ODataRequestData, ?Relatie $relatie = null, ?int $aantal = null): RequestInterface
    {
        return new Request("GET", "artikelen?" . $ODataRequestData->getHttpCompatibleQueryString() . '&' . static::getQueryString($relatie, $aantal));
    }

    public static function find(UuidInterface $id, ODataRequestData $ODataRequestData, ?Relatie $relatie = null, ?int $aantal = null): RequestInterface
    {
        return new Request("GET", sprintf("artikelen/%s/?%s", $id->toString(), $ODataRequestData->getHttpCompatibleQueryString() . '&' . static::getQueryString($relatie, $aantal)));
    }

    public static function getCustomFields(UuidInterface $id)
    {
        return new Request("GET", sprintf("artikelen/%s/customFields", $id->toString()));
    }

    private static function getQueryString(?Relatie $relatie = null, ?int $aantal = null): string
    {
        return \http_build_query(array_filter([
            "relatieId" =>  $relatie ? $relatie->getId()->toString() : null,
            "aantal"    =>  $aantal,
        ], static function($value) {
            return $value !== null;
        }), null, "&", \PHP_QUERY_RFC3986);
    }
}