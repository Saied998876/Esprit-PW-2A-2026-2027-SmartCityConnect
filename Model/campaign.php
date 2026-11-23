<?php

class CampaignModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getActiveCampaigns()
    {
        $sql = "SELECT campaigns.*, COALESCE(SUM(donations.amount_cents), 0) AS raised_cents
                FROM campaigns
                LEFT JOIN donations ON campaigns.id = donations.campaign_id
                WHERE campaigns.status = 'active'
                GROUP BY campaigns.id
                ORDER BY campaigns.created_at DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function getCampaign($id)
    {
        $sql = "SELECT campaigns.*, COALESCE(SUM(donations.amount_cents), 0) AS raised_cents
                FROM campaigns
                LEFT JOIN donations ON campaigns.id = donations.campaign_id
                WHERE campaigns.id = :id AND campaigns.status = 'active'
                GROUP BY campaigns.id";
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
        $campaign = $query->fetch();

        return $campaign ? $campaign : null;
    }

    public function getAllCampaigns()
    {
        $sql = "SELECT campaigns.*, COALESCE(SUM(donations.amount_cents), 0) AS raised_cents,
                       COUNT(donations.id) AS donation_count
                FROM campaigns
                LEFT JOIN donations ON campaigns.id = donations.campaign_id
                GROUP BY campaigns.id
                ORDER BY campaigns.created_at DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function saveCampaign($campaign)
    {
        if (empty($campaign['id'])) {
            $sql = 'INSERT INTO campaigns (title, description, category, goal_cents, status)
                    VALUES (:title, :description, :category, :goal_cents, :status)';
        } else {
            $sql = 'UPDATE campaigns
                    SET title = :title, description = :description, category = :category,
                        goal_cents = :goal_cents, status = :status
                    WHERE id = :id';
        }

        $query = $this->db->prepare($sql);
        if (empty($campaign['id'])) {
            unset($campaign['id']);
        }
        $query->execute($campaign);
    }

    public function campaignExists($id)
    {
        $query = $this->db->prepare('SELECT id FROM campaigns WHERE id = :id');
        $query->execute(['id' => $id]);

        return $query->fetch() !== false;
    }

    public function deleteCampaign($id)
    {
        $query = $this->db->prepare('DELETE FROM campaigns WHERE id = :id');
        $query->execute(['id' => $id]);

        return $query->rowCount() > 0;
    }
}
