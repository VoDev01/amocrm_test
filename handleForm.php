<?php

use AmoCRM\Models\LeadModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\RadiobuttonCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\RadiobuttonCustomFieldValueModel;
use AmoCRM\Models\TagModel;

if (isset($_POST))
{

    $accessToken = $_SESSION['accessToken'];
    $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
    $apiClient->setAccessToken($accessToken)->setAccountBaseDomain($accessToken->getValues()['baseDomain']);

    $leadsService = $apiClient->leads();

    $contactCustomFields = new CustomFieldsValuesCollection();
    $leadCustomFields = new CustomFieldsValuesCollection();

    $phoneModel = new MultitextCustomFieldValuesModel();
    $phoneModel->setFieldId(3727);
    $phoneModel->setValues(
        (new MultitextCustomFieldValueCollection())
            ->add((new MultitextCustomFieldValueModel())->setValue($_POST['phone']))
    );

    $commentModel = new MultitextCustomFieldValuesModel();
    $commentModel->setFieldId(5403);
    $commentModel->setValues(
        (new MultitextCustomFieldValueCollection())
            ->add((new MultitextCustomFieldValueModel())->setValue($_POST['comment']))
    );

    $sourceModel = new RadiobuttonCustomFieldValuesModel();
    $sourceModel->setFieldId(5405);
    $sourceModel->setValues(
        (new RadiobuttonCustomFieldValueCollection())
            ->add((new RadiobuttonCustomFieldValueModel())->setValue('Сайт'))
    );

    $contactCustomFields->add($phoneModel);
    $leadCustomFields->add($commentModel);
    $leadCustomFields->add($sourceModel);

    $lead = new LeadModel();
    $lead->setName('Заявка с сайта ' . date('d-m-Y/H:i:s'))
        ->setPrice(50000)
        ->setContacts(
            (new ContactsCollection())
                ->add(
                    (new ContactModel())
                        ->setCustomFieldsValues($contactCustomFields)
                )
        )
        ->setCustomFieldsValues($leadCustomFields)
        ->setTags(
            (new TagsCollection())
                ->add((new TagModel())->setName('Сайт'))
        );
    $leadsService->addOne($lead);
}
