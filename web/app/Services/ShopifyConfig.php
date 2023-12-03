<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services;

use Illuminate\Support\Env;
use SimpleXMLElement;
use Spatie\ArrayToXml\ArrayToXml;
use Yosymfony\Toml\Toml;

class ShopifyConfig
{
    private const SHOPIFY_KEY_PREFIX = 'shopify::';
    protected static array $mapping = [
        'SHOPIFY_API_KEY' => 'client_id',
        'SCOPES' => 'access_scopes/scopes',
        'SHOPIFY_SHOP' => 'build/dev_store_url'
    ];

    public static function load()
    {
        self::addAll();
        self::addSpecifics();
    }

    protected static function addAll(): void
    {
        foreach (self::shopifyAppConfig() as $k => $v) {
            $v = is_array($v) ? json_encode($v) : $v;
            Env::getRepository()->set(self::SHOPIFY_KEY_PREFIX . $k, $v);
        }
    }

    protected static function shopifyAppConfig(): mixed
    {
        return Toml::ParseFile(str_replace('web', 'shopify.app.toml', base_path()));
    }

    protected static function addSpecifics(): void
    {
        $xml = new SimpleXMLElement(ArrayToXml::convert(self::shopifyAppConfig()));
        foreach (self::$mapping as $k => $v) {
            $configValue = $xml->xpath('/root/' . $v);
            Env::getRepository()->set($k, reset($configValue)->__toString());
        }
    }
}
