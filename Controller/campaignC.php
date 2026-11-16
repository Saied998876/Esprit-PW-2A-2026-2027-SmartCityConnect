<?php

class CampaignController
{
    private $campaignModel;

    public function __construct($campaignModel)
    {
        $this->campaignModel = $campaignModel;
    }

    public function index()
    {
        return $this->campaignModel->getActiveCampaigns();
    }

    public function show($id)
    {
        if ($id < 1) {
            return null;
        }

        return $this->campaignModel->getCampaign($id);
    }
}
