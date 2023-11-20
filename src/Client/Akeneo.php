<?php

namespace JustBetter\AkeneoClient\Client;

use Akeneo\Pim\ApiClient\AkeneoPimClientInterface;
use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogApiInterface;
use Akeneo\Pim\ApiClient\Api\AppCatalog\AppCatalogProductApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetCategoryApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetApiInterface as AssetManagerApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetAttributeOptionApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetManager\AssetMediaFileApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetReferenceFileApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetTagApiInterface;
use Akeneo\Pim\ApiClient\Api\AssetVariationFileApiInterface;
use Akeneo\Pim\ApiClient\Api\AssociationTypeApiInterface;
use Akeneo\Pim\ApiClient\Api\AttributeApiInterface;
use Akeneo\Pim\ApiClient\Api\AttributeGroupApiInterface;
use Akeneo\Pim\ApiClient\Api\AttributeOptionApiInterface;
use Akeneo\Pim\ApiClient\Api\CategoryApiInterface;
use Akeneo\Pim\ApiClient\Api\ChannelApiInterface;
use Akeneo\Pim\ApiClient\Api\CurrencyApiInterface;
use Akeneo\Pim\ApiClient\Api\FamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\FamilyVariantApiInterface;
use Akeneo\Pim\ApiClient\Api\LocaleApiInterface;
use Akeneo\Pim\ApiClient\Api\MeasureFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\MeasurementFamilyApiInterface;
use Akeneo\Pim\ApiClient\Api\MediaFileApiInterface;
use Akeneo\Pim\ApiClient\Api\Operation\DownloadableResourceInterface;
use Akeneo\Pim\ApiClient\Api\ProductApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductDraftApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductDraftUuidApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductModelDraftApiInterface;
use Akeneo\Pim\ApiClient\Api\ProductUuidApiInterface;
use Akeneo\Pim\ApiClient\Api\PublishedProductApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityAttributeOptionApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityMediaFileApiInterface;
use Akeneo\Pim\ApiClient\Api\ReferenceEntityRecordApiInterface;
use Illuminate\Support\Facades\Http;
use JustBetter\AkeneoClient\Exceptions\AkeneoException;
use SensitiveParameter;

/**
 * @method ?string getToken()
 * @method ?string getRefreshToken()
 * @method ProductApiInterface getProductApi()
 * @method CategoryApiInterface getCategoryApi()
 * @method DownloadableResourceInterface getCategoryMediaFileApi()
 * @method AttributeApiInterface getAttributeApi()
 * @method AttributeOptionApiInterface getAttributeOptionApi()
 * @method AttributeGroupApiInterface getAttributeGroupApi()
 * @method FamilyApiInterface getFamilyApi()
 * @method MediaFileApiInterface getProductMediaFileApi()
 * @method LocaleApiInterface getLocaleApi()
 * @method ChannelApiInterface getChannelApi()
 * @method CurrencyApiInterface getCurrencyApi()
 * @method MeasureFamilyApiInterface getMeasureFamilyApi()
 * @method MeasurementFamilyApiInterface getMeasurementFamilyApi()
 * @method AssociationTypeApiInterface getAssociationTypeApi()
 * @method FamilyVariantApiInterface getFamilyVariantApi()
 * @method ProductModelApiInterface getProductModelApi()
 * @method PublishedProductApiInterface getPublishedProductApi()
 * @method ProductModelDraftApiInterface getProductModelDraftApi()
 * @method ProductDraftApiInterface getProductDraftApi()
 * @method AssetApiInterface getAssetApi()
 * @method AssetCategoryApiInterface getAssetCategoryApi()
 * @method AssetTagApiInterface getAssetTagApi()
 * @method AssetReferenceFileApiInterface getAssetReferenceFileApi()
 * @method AssetVariationFileApiInterface getAssetVariationFileApi()
 * @method ReferenceEntityRecordApiInterface getReferenceEntityRecordApi()
 * @method ReferenceEntityMediaFileApiInterface getReferenceEntityMediaFileApi()
 * @method ReferenceEntityAttributeApiInterface getReferenceEntityAttributeApi()
 * @method ReferenceEntityAttributeOptionApiInterface getReferenceEntityAttributeOptionApi()
 * @method ReferenceEntityApiInterface getReferenceEntityApi()
 * @method AssetManagerApiInterface getAssetManagerApi()
 * @method AssetFamilyApiInterface getAssetFamilyApi()
 * @method AssetAttributeApiInterface getAssetAttributeApi()
 * @method AssetAttributeOptionApiInterface getAssetAttributeOptionApi()
 * @method AssetMediaFileApiInterface getAssetMediaFileApi()
 * @method ProductUuidApiInterface getProductUuidApi()
 * @method ProductDraftUuidApiInterface getProductDraftUuidApi()
 * @method AppCatalogApiInterface getAppCatalogApi()
 * @method AppCatalogProductApiInterface getAppCatalogProductApi()
 */
class Akeneo
{
    protected AkeneoPimClientInterface $client;

    public function __construct(
        string $url,
        string $clientId,
        #[SensitiveParameter] string $secret,
        string $username,
        #[SensitiveParameter] string $password
    ) {
        $clientBuilder = new ClientBuilder($url);

        $this->client = $clientBuilder->buildAuthenticatedByPassword($clientId, $secret, $username, $password);
    }

    public function __call(string $method, array $args): mixed
    {
        $callable = [$this->client, $method];

        if (! is_callable($callable)) {
            throw new AkeneoException('Method "'.$method.'" is not callable');
        }

        return call_user_func_array($callable, $args);
    }

    public static function fake(): void
    {
        config()->set('akeneo', [
            'url' => 'akeneo',
            'client_id' => '::client-id::',
            'secret' => '::secret::',
            'username' => '::username::',
            'password' => '::password::',
            'webhook_secret' => '::webhook-secret::',
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);

        Http::fake([
            'akeneo/api/oauth/v1/token' => Http::response([
                'access_token' => '::access-token::',
                'expires_in' => 3600,
                'token_type' => 'bearer',
                'scope' => null,
                'refresh_token' => '::refresh-token::',
            ]),
        ]);
    }
}
