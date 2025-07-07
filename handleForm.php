<?php

require __DIR__ . '/vendor/autoload.php';

use AmoCRM\Models\TagModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\RadiobuttonCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\RadiobuttonCustomFieldValueCollection;
use Bitrix24\SDK\Core\Credentials\ApplicationProfile;
use Bitrix24\SDK\Services\ServiceBuilder;
use Bitrix24\SDK\Services\ServiceBuilderFactory;
use Symfony\Component\HttpFoundation\Request;

if (!isset($_SESSION))
{
    session_start();
}

if (isset($_POST))
{

    $env = file_get_contents('.env');
    $lines = explode("\n", $env);

    foreach ($lines as $line)
    {
        preg_match("/(?<key>[^#]+)\=(?<value>.+)/", $line, $matches);
        if ($matches['value'] !== null)
        {
            $_ENV[$matches['key']] = trim($matches['value']);
        }
    }

    $amoCrmClientId = $_ENV['AMOCRM_CLIENT_ID'];
    $amoCrmClientSecret = $_ENV['AMOCRM_CLIENT_SECRET'];
    $bitrixClientId = $_ENV['BITRIX_CLIENT_ID'];
    $bitrixClientSecret = $_ENV['BITRIX_CLIENT_SECRET'];
    $redirectUri = $_ENV['REDIRECT_URI'];

    // if (!isset($_GET['code']))
    // {

    //     $state = bin2hex(random_bytes(16));
    //     $_SESSION['state'] = $state;
    //     $apiClient = new \AmoCRM\Client\AmoCRMApiClient($amoCrmClientId, $amoCrmClientSecret, $redirectUri);

    //     $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
    //         'state' => $state,
    //         'mode' => 'post_message', //post_message - редирект произойдет в открытом окне, popup - редирект произойдет в окне родителе
    //     ]);

    //     header('Location: ' . $authorizationUrl);
    //     exit;
    // }


    // if ($_GET['state'] !== $_SESSION['state'] || empty($_GET['state']))
    // {
    //     unset($_SESSION['state']);
    //     header('Location: /');
    //     exit;
    // }

    // $apiClient = new AmoCRMApiClient($amoCrmClientId, $amoCrmClientSecret, $redirectUri);
    
    // var_dump($_GET['code']);

    // $accessToken = $apiClient->getOAuthClient()->setBaseDomain($_ENV['AMOCRM_BASE_DOMAIN'])->getAccessTokenByCode($_GET['code']);
    // $apiClient->setAccessToken($accessToken);

    // $leadsService = $apiClient->leads();

    // $contactCustomFields = new CustomFieldsValuesCollection();
    // $leadCustomFields = new CustomFieldsValuesCollection();

    // $phoneModel = new MultitextCustomFieldValuesModel();
    // $phoneModel->setFieldId(3727);
    // $phoneModel->setValues(
    //     (new MultitextCustomFieldValueCollection())
    //         ->add((new MultitextCustomFieldValueModel())->setEnumId(1775)->setValue($_POST['phone']))
    // );

    // $commentModel = new TextCustomFieldValuesModel();
    // $commentModel->setFieldId(5403);
    // $commentModel->setValues(
    //     (new TextCustomFieldValueCollection())
    //         ->add((new TextCustomFieldValueModel())->setValue($_POST['comment']))
    // );

    // $sourceModel = new RadiobuttonCustomFieldValuesModel();
    // $sourceModel->setFieldId(5405);
    // $sourceModel->setValues(
    //     (new RadiobuttonCustomFieldValueCollection())
    //         ->add((new RadiobuttonCustomFieldValueModel())->setValue('Сайт'))
    // );

    // $contactCustomFields->add($phoneModel);
    // $leadCustomFields->add($commentModel);
    // $leadCustomFields->add($sourceModel);

    // $lead = new LeadModel();
    // $lead->setName('Заявка с сайта ' . date('d-m-Y/H:i:s'))
    //     ->setPrice(50000)
    //     ->setContacts(
    //         (new ContactsCollection())
    //             ->add(
    //                 (new ContactModel())
    //                     ->setCustomFieldsValues($contactCustomFields)
    //             )
    //     )
    //     ->setCustomFieldsValues($leadCustomFields)
    //     ->setTags(
    //         (new TagsCollection())
    //             ->add((new TagModel())->setName('Сайт'))
    //     );
    // $leadsService->addOne($lead);

    $appProfile = ApplicationProfile::initFromArray([
        'BITRIX24_PHP_SDK_APPLICATION_CLIENT_ID' => $bitrixClientId,
        'BITRIX24_PHP_SDK_APPLICATION_CLIENT_SECRET' => $bitrixClientSecret,
        'BITRIX24_PHP_SDK_APPLICATION_SCOPE' => 'crm,user.basic'
    ]);
    $bitrixService = ServiceBuilderFactory::createServiceBuilderFromPlacementRequest(Request::createFromGlobals(), $appProfile);
    var_dump($bitrixService->getCRMScope()->lead()->fields());
    exit;
    $bitrixService->getCRMScope()->lead()->add([
        'TITLE' => 'Заявка с сайта ' . date('d-m-Y/H:i:s'),
        'NAME' => $_POST['name'],
        'COMMENTS' => $_POST['comment'],
    ]);
}
