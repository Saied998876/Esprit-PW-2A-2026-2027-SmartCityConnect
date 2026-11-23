<?php

class DonationModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addDonation($donation)
    {
        $sql = 'INSERT INTO donations (campaign_id, donor_name, donor_email, amount_cents, message)
                VALUES (:campaign_id, :donor_name, :donor_email, :amount_cents, :message)';
        $query = $this->db->prepare($sql);
        $query->execute($donation);

        return $this->db->lastInsertId();
    }

    public function getDonation($id)
    {
        $sql = 'SELECT donations.*, campaigns.title AS campaign_title
                FROM donations
                JOIN campaigns ON donations.campaign_id = campaigns.id
                WHERE donations.id = :id';
        $query = $this->db->prepare($sql);
        $query->execute(['id' => $id]);
        $donation = $query->fetch();

        return $donation ? $donation : null;
    }

    public function getRecentDonations()
    {
        $sql = 'SELECT donations.*, campaigns.title AS campaign_title
                FROM donations
                JOIN campaigns ON donations.campaign_id = campaigns.id
                ORDER BY donations.created_at DESC
                LIMIT 20';

        return $this->db->query($sql)->fetchAll();
    }

    public function getTotals()
    {
        $sql = 'SELECT COUNT(*) AS donation_count,
                       COALESCE(SUM(amount_cents), 0) AS raised_cents
                FROM donations';

        return $this->db->query($sql)->fetch();
    }
}
